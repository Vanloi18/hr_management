<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Department;
use App\Core\Validator; 
use App\Models\Employee;
// Thư viện Export
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Mpdf\Mpdf;

class DepartmentController extends Controller
{
    protected $departmentModel;
    protected $employeeModel;

    public function __construct()
    {
        parent::__construct();
        $this->departmentModel = new Department();
        $this->employeeModel = new Employee();
    }

    public function index()
    {
        $this->checkAuthentication();

        $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
        $page    = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) $page = 1;

        $limit  = 10;
        $offset = ($page - 1) * $limit;

        $departments = $this->departmentModel->getPaginated($keyword, $limit, $offset);
        $totalRecords = $this->departmentModel->countAll($keyword);
        $totalPages = ceil($totalRecords / $limit);

        $data = [
            'title'        => 'Quản lý Phòng ban',
            'departments'  => $departments,
            'currentPage'  => $page,
            'totalPages'   => $totalPages,
            'totalRecords' => $totalRecords,
            'keyword'      => $keyword
        ];

        if (isAjaxRequest()) {
            return partial('departments/index', $data);
        }
        return view('departments/index', $data);
    }

    public function create()
    {
        $this->checkAuthentication();
        return view('departments/create', ['title' => 'Thêm Phòng ban']);
    }

    public function store()
    {
        $this->checkAuthentication();
        $name = trim($_POST['name'] ?? '');

        if (empty($name)) {
            flash('error', 'Tên phòng ban không được để trống.');
            redirect('/departments/create');
        }

        if ($this->departmentModel->findByName($name)) {
            flash('error', 'Tên phòng ban đã tồn tại.');
            redirect('/departments/create');
        }

        $this->departmentModel->create($name);
        flash('success', 'Thêm phòng ban thành công.');
        redirect('/departments');
    }

    public function edit()
    {
        $this->checkAuthentication();
        $id = $_GET['id'] ?? null;
        if (!$id) redirect('/departments');

        $department = $this->departmentModel->find($id);
        if (!$department) redirect('/departments');

        return view('departments/edit', [
            'title' => 'Chỉnh sửa Phòng ban',
            'department' => $department
        ]);
    }

    public function update()
    {
        $this->checkAuthentication();
        $id = $_POST['id'] ?? null;
        $name = trim($_POST['name'] ?? '');

        if (!$id || empty($name)) {
            flash('error', 'Dữ liệu không hợp lệ.');
            redirect('/departments');
        }

        $exists = $this->departmentModel->findByNameAndNotId($name, $id);
        if ($exists) {
            flash('error', 'Tên phòng ban đã tồn tại.');
            redirect("/departments/edit?id=$id");
        }

        $this->departmentModel->update($id, $name);
        flash('success', 'Cập nhật thành công.');
        redirect('/departments');
    }

    public function destroy()
    {
        $this->requireAdmin();
        header('Content-Type: application/json');

        try {
            $id = $_POST['id'] ?? null;
            if (!$id) throw new \Exception('Thiếu ID.');

            $this->departmentModel->delete($id); 

            echo json_encode(['success' => true, 'message' => 'Đã xóa phòng ban.']);
            exit();

        } catch (\Exception $e) {
            // Xử lý lỗi khóa ngoại (nếu có nhân viên trong phòng)
            if ($e->getCode() === '23000') {
                echo json_encode(['success' => false, 'message' => 'Không thể xóa: Vẫn còn nhân viên trong phòng ban này.']);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
            exit();
        }
    }

    public function apiGetEmployees()
    {
        $this->checkAuthentication(); 
        header('Content-Type: application/json');
        
        $departmentId = $_GET['id'] ?? null;

        if (!$departmentId) {
            echo json_encode(['error' => 'Thiếu ID.']);
            exit;
        }

        try {
            $employees = $this->employeeModel->getByDepartmentId($departmentId);
            $department = $this->departmentModel->find($departmentId);
            
            echo json_encode([
                'success' => true,
                'department_name' => $department['name'] ?? 'Không rõ',
                'employees' => $employees
            ]);
        } catch (\Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    /**
     * Xuất Excel
     */
    public function exportExcel()
    {
        $this->checkAuthentication();
        $keyword = $_GET['keyword'] ?? '';
        $departments = $this->departmentModel->getAllForExport($keyword);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Danh sách Phòng ban');

        $headers = ['ID', 'Tên phòng ban', 'Số lượng nhân sự', 'Ngày tạo'];
        $sheet->fromArray([$headers], NULL, 'A1');

        $rows = [];
        foreach ($departments as $dept) {
            $rows[] = [
                $dept['id'],
                $dept['name'],
                $dept['employee_count'],
                date('d/m/Y', strtotime($dept['created_at']))
            ];
        }

        if (!empty($rows)) $sheet->fromArray($rows, NULL, 'A2');

        $lastRow = count($rows) + 1;
        $sheet->getStyle("A1:D{$lastRow}")->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
        ]);
        $sheet->getStyle('A1:D1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '0d6efd']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
        ]);
        foreach (range('A', 'D') as $col) $sheet->getColumnDimension($col)->setAutoSize(true);

        $fileName = 'DS_PhongBan_' . date('dmY') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    /**
     * Xuất PDF
     */
    public function exportPDF()
    {
        $this->checkAuthentication();
        $keyword = $_GET['keyword'] ?? '';
        $departments = $this->departmentModel->getAllForExport($keyword);

        $html = '
        <html>
        <head>
            <style>
                body { font-family: DejaVu Sans, sans-serif; font-size: 11px; }
                h2 { text-align: center; color: #0d6efd; margin-bottom: 20px; }
                table { width: 100%; border-collapse: collapse; margin-top: 10px; }
                th { background-color: #0d6efd; color: white; padding: 8px; border: 1px solid #333; }
                td { padding: 8px; border: 1px solid #444; text-align: center; }
                .text-left { text-align: left; }
            </style>
        </head>
        <body>
            <h2>DANH SÁCH PHÒNG BAN</h2>
            <p style="text-align:center">Ngày xuất: ' . date('d/m/Y H:i') . '</p>
            <table>
                <thead>
                    <tr>
                        <th width="10%">ID</th>
                        <th width="50%">Tên phòng ban</th>
                        <th width="20%">Nhân sự</th>
                        <th width="20%">Ngày tạo</th>
                    </tr>
                </thead>
                <tbody>';
        
        foreach ($departments as $dept) {
            $html .= '<tr>
                <td>' . $dept['id'] . '</td>
                <td class="text-left">' . $dept['name'] . '</td>
                <td>' . $dept['employee_count'] . '</td>
                <td>' . date('d/m/Y', strtotime($dept['created_at'])) . '</td>
            </tr>';
        }

        $html .= '</tbody></table></body></html>';

        try {
            $mpdf = new Mpdf(['mode' => 'utf-8', 'format' => 'A4']);
            $mpdf->WriteHTML($html);
            $mpdf->Output('DS_PhongBan_' . date('dmY') . '.pdf', 'D');
        } catch (\Exception $e) {
            echo "Lỗi xuất PDF: " . $e->getMessage();
        }
        exit;
    }
}