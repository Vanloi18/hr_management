<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Report; // <-- Import Model cũ của bạn

// Import thư viện Export
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Mpdf\Mpdf;

class StatisticsController extends Controller
{
    /**
     * Hiển thị Dashboard Thống kê (Giữ nguyên logic của bạn)
     */
    public function index()
    {
        $this->checkAuthentication();

        $reportModel = new Report();

        // === 1. LẤY DỮ LIỆU TUYỂN DỤNG ===
        $totalOpenPositions = $reportModel->getTotalOpenPositions();
        $totalCandidates = $reportModel->getTotalCandidates();
        $totalRecruiters = $reportModel->getTotalRecruiters();
        $cvsByStatusRaw = $reportModel->getCvsByStatus();
        $positionsByFieldRaw = $reportModel->getPositionsByField();

        // Xử lý dữ liệu biểu đồ CV
        $cvStatusLabels = array_column($cvsByStatusRaw, 'status');
        $cvStatusData = array_column($cvsByStatusRaw, 'count');
        $cvStatusLabels = array_map('ucfirst', $cvStatusLabels);
        
        // Xử lý dữ liệu biểu đồ Vị trí
        $posFieldLabels = array_column($positionsByFieldRaw, 'field_name');
        $posFieldData = array_column($positionsByFieldRaw, 'count');
        
        // === 2. LẤY DỮ LIỆU NHÂN SỰ ===
        $totalActiveEmployees = $reportModel->getTotalActiveEmployees();
        $employeesByDeptRaw = $reportModel->getEmployeesByDepartment();
        
        // Xử lý dữ liệu biểu đồ Nhân sự
        $empDeptLabels = array_column($employeesByDeptRaw, 'department_name');
        $empDeptData = array_column($employeesByDeptRaw, 'count');
        
        // === 3. CHUẨN BỊ DATA CHO VIEW ===
        $data = [
            'title' => 'Nhóm 2 - Lớp 3',
            
            'totalOpenPositions' => $totalOpenPositions,
            'totalCandidates' => $totalCandidates,
            'totalRecruiters' => $totalRecruiters,
            'totalActiveEmployees' => $totalActiveEmployees, 

            'cvStatusLabels' => $cvStatusLabels,
            'cvStatusData' => $cvStatusData,
            'posFieldLabels' => $posFieldLabels,
            'posFieldData' => $posFieldData,
            
            'empDeptLabels' => $empDeptLabels,
            'empDeptData' => $empDeptData,

            'cvsByStatus' => $cvsByStatusRaw,
            'positionsByField' => $positionsByFieldRaw 
        ];

        // LOGIC PJAX
        if (isAjaxRequest()) {
            return partial('statistics/index', $data);
        }
        
        return view('statistics/index', $data);
    }

    /**
     * Chức năng: Xuất Excel Báo cáo Nhân sự theo Phòng ban
     */
    public function exportExcel()
    {
        $this->checkAuthentication();
        
        // 1. Lấy dữ liệu từ Model Report
        $reportModel = new Report();
        $data = $reportModel->getEmployeesByDepartment(); // Tận dụng method có sẵn

        // 2. Khởi tạo Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Thống kê nhân sự');

        // 3. Tạo Header
        $headers = ['STT', 'Tên phòng ban', 'Số lượng nhân sự'];
        $sheet->fromArray([$headers], NULL, 'A1');

        // 4. Đổ dữ liệu
        $rows = [];
        $stt = 1;
        $total = 0;
        foreach ($data as $item) {
            $rows[] = [
                $stt++,
                $item['department_name'], // Key khớp với getEmployeesByDepartment()
                $item['count']
            ];
            $total += $item['count'];
        }
        
        // Dòng tổng cộng
        $rows[] = ['', 'TỔNG CỘNG', $total];

        $sheet->fromArray($rows, NULL, 'A2');

        // 5. Format giao diện
        $lastRow = count($rows) + 1;
        
        // Kẻ bảng
        $sheet->getStyle("A1:C{$lastRow}")->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
        ]);

        // Style Header
        $sheet->getStyle('A1:C1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '0d6efd']], // Màu xanh bootstrap
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
        ]);

        // Style dòng tổng cộng
        $sheet->getStyle("A{$lastRow}:C{$lastRow}")->getFont()->setBold(true);

        // Auto width
        foreach (range('A', 'C') as $col) $sheet->getColumnDimension($col)->setAutoSize(true);

        // 6. Xuất file
        $fileName = 'Bao_cao_nhan_su_' . date('dmY_Hi') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    /**
     * Chức năng: Xuất PDF Báo cáo
     */
    public function exportPDF()
    {
        $this->checkAuthentication();

        // 1. Lấy dữ liệu
        $reportModel = new Report();
        $deptStats = $reportModel->getEmployeesByDepartment();
        $openPositions = $reportModel->getTotalOpenPositions();
        $activeEmp = $reportModel->getTotalActiveEmployees();

        // 2. Tạo HTML Template
        $html = '
        <html>
        <head>
            <style>
                body { font-family: DejaVu Sans, sans-serif; font-size: 13px; }
                .header { text-align: center; margin-bottom: 20px; }
                .header h2 { margin: 0; color: #2c3e50; }
                .meta { text-align: center; color: #666; font-style: italic; font-size: 11px; }
                
                .summary-box { border: 1px solid #ddd; padding: 10px; margin-bottom: 20px; background: #f9f9f9; }
                
                table { width: 100%; border-collapse: collapse; margin-top: 10px; }
                th { background-color: #0d6efd; color: white; padding: 10px; border: 1px solid #000; }
                td { padding: 8px; border: 1px solid #444; text-align: center; }
                .text-left { text-align: left; }
                .total-row { font-weight: bold; background-color: #eee; }
            </style>
        </head>
        <body>
            <div class="header">
                <h2>BÁO CÁO THỐNG KÊ TỔNG HỢP</h2>
                <div class="meta">Ngày xuất: ' . date('d/m/Y H:i') . ' | Người xuất: ' . ($_SESSION['user']['full_name'] ?? 'Admin') . '</div>
            </div>

            <div class="summary-box">
                <strong>Tổng quan nhanh:</strong><br>
                - Nhân sự đang hoạt động: <b>' . $activeEmp . '</b><br>
                - Vị trí đang tuyển: <b>' . $openPositions . '</b>
            </div>

            <h3>Chi tiết nhân sự theo phòng ban</h3>
            <table>
                <thead>
                    <tr>
                        <th width="10%">STT</th>
                        <th width="60%">Phòng ban</th>
                        <th width="30%">Số lượng nhân viên</th>
                    </tr>
                </thead>
                <tbody>';
        
        $stt = 1;
        $total = 0;
        foreach ($deptStats as $item) {
            $html .= '<tr>
                <td>' . $stt++ . '</td>
                <td class="text-left">' . $item['department_name'] . '</td>
                <td>' . $item['count'] . '</td>
            </tr>';
            $total += $item['count'];
        }

        $html .= '<tr class="total-row">
                <td colspan="2">TỔNG CỘNG</td>
                <td>' . $total . '</td>
            </tr>';

        $html .= '</tbody></table></body></html>';

        // 3. Xuất PDF
        try {
            $mpdf = new Mpdf(['mode' => 'utf-8', 'format' => 'A4']);
            $mpdf->WriteHTML($html);
            $mpdf->Output('Bao_cao_nhan_su_' . date('dmY') . '.pdf', 'D');
        } catch (\Exception $e) {
            echo "Lỗi xuất PDF: " . $e->getMessage();
        }
        exit;
    }
}