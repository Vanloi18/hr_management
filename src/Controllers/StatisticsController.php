<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Report;
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
     * Hiển thị Dashboard Thống kê
     */
    public function index()
    {
        $this->checkAuthentication();

        $reportModel = new Report();

        // 1. Lấy dữ liệu
        $totalOpenPositions = $reportModel->getTotalOpenPositions();
        $totalCandidates = $reportModel->getTotalCandidates();
        $totalRecruiters = $reportModel->getTotalRecruiters();
        $totalActiveEmployees = $reportModel->getTotalActiveEmployees(); 
        
        $cvsByStatusRaw = $reportModel->getCvsByStatus();
        $positionsByFieldRaw = $reportModel->getPositionsByField();
        $employeesByDeptRaw = $reportModel->getEmployeesByDepartment();

        // Xử lý dữ liệu biểu đồ
        $cvStatusLabels = array_map('ucfirst', array_column($cvsByStatusRaw, 'status'));
        $cvStatusData = array_column($cvsByStatusRaw, 'count');
        
        $posFieldLabels = array_column($positionsByFieldRaw, 'field_name');
        $posFieldData = array_column($positionsByFieldRaw, 'count');
        
        $empDeptLabels = array_column($employeesByDeptRaw, 'department_name');
        $empDeptData = array_column($employeesByDeptRaw, 'count');
        
        // 2. Chuẩn bị Data
        $data = [
            'title' => 'Báo cáo Thống kê',
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

        if (isAjaxRequest()) {
            return partial('statistics/index', $data);
        }
        
        return view('statistics/index', $data);
    }

    /**
     * Xuất Excel (Số liệu chi tiết)
     */
    public function exportExcel()
    {
        $this->checkAuthentication();
        
        $reportModel = new Report();
        $data = $reportModel->getEmployeesByDepartment();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Thống kê nhân sự');

        // Header
        $headers = ['STT', 'Tên phòng ban', 'Số lượng nhân sự'];
        $sheet->fromArray([$headers], NULL, 'A1');

        // Data
        $rows = [];
        $stt = 1;
        $total = 0;
        foreach ($data as $item) {
            $rows[] = [$stt++, $item['department_name'], $item['count']];
            $total += $item['count'];
        }
        $rows[] = ['', 'TỔNG CỘNG', $total];

        $sheet->fromArray($rows, NULL, 'A2');

        // Style
        $lastRow = count($rows) + 1;
        $sheet->getStyle("A1:C{$lastRow}")->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
        ]);
        $sheet->getStyle('A1:C1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '0d6efd']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
        ]);
        $sheet->getStyle("A{$lastRow}:C{$lastRow}")->getFont()->setBold(true);
        foreach (range('A', 'C') as $col) $sheet->getColumnDimension($col)->setAutoSize(true);

        // Output
        $fileName = 'Bao_cao_nhan_su_' . date('dmY_Hi') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    /**
     * Xuất PDF (Kèm Biểu đồ từ Client gửi lên)
     */
    public function exportPDF()
    {
        $this->checkAuthentication();

        $reportModel = new Report();
        $deptStats = $reportModel->getEmployeesByDepartment();
        $openPositions = $reportModel->getTotalOpenPositions();
        $activeEmp = $reportModel->getTotalActiveEmployees();
        $totalCandidates = $reportModel->getTotalCandidates();

        // Nhận ảnh Chart từ POST (Base64 String)
        $chartCV = $_POST['chart_cv'] ?? '';
        $chartEmp = $_POST['chart_emp'] ?? '';
        $chartPos = $_POST['chart_pos'] ?? '';

        // HTML Template
        $html = '
        <html>
        <head>
            <style>
                body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #333; }
                .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #0d6efd; padding-bottom: 10px; }
                .header h2 { margin: 0; color: #0d6efd; text-transform: uppercase; }
                .meta { text-align: center; color: #666; font-style: italic; font-size: 10px; margin-top: 5px; }
                
                .summary-box { 
                    border: 1px solid #ddd; 
                    padding: 15px; 
                    margin-bottom: 20px; 
                    background-color: #f8f9fa; 
                    border-radius: 5px;
                }
                .summary-item { font-size: 14px; margin-bottom: 5px; }
                
                .chart-section { text-align: center; margin-bottom: 30px; page-break-inside: avoid; }
                .chart-title { font-weight: bold; margin-bottom: 10px; color: #555; font-size: 14px; }
                .chart-img { max-width: 100%; height: auto; border: 1px solid #eee; }

                table { width: 100%; border-collapse: collapse; margin-top: 10px; }
                th { background-color: #0d6efd; color: white; padding: 10px; border: 1px solid #333; font-size: 12px; }
                td { padding: 8px; border: 1px solid #444; text-align: center; font-size: 12px; }
                .text-left { text-align: left; }
                .total-row { font-weight: bold; background-color: #e9ecef; }
            </style>
        </head>
        <body>
            <div class="header">
                <h2>Báo Cáo Tổng Hợp Hoạt Động</h2>
                <div class="meta">Ngày xuất: ' . date('d/m/Y H:i') . ' | Người xuất: ' . ($_SESSION['user']['full_name'] ?? 'Admin') . '</div>
            </div>

            <div class="summary-box">
                <div class="summary-item"><strong>• Nhân sự đang hoạt động:</strong> ' . $activeEmp . ' người</div>
                <div class="summary-item"><strong>• Vị trí đang tuyển:</strong> ' . $openPositions . ' vị trí</div>
                <div class="summary-item"><strong>• Tổng hồ sơ ứng viên:</strong> ' . $totalCandidates . ' hồ sơ</div>
            </div>

            ';
            
            if ($chartEmp) {
                $html .= '
                <div class="chart-section">
                    <div class="chart-title">Biểu đồ 1: Phân bổ Nhân sự theo Phòng ban</div>
                    <img src="' . $chartEmp . '" class="chart-img" style="width: 500px;" />
                </div>';
            }

            if ($chartCV) {
                $html .= '
                <div class="chart-section">
                    <div class="chart-title">Biểu đồ 2: Tỷ lệ Hồ sơ Ứng viên</div>
                    <img src="' . $chartCV . '" class="chart-img" style="width: 500px;" />
                </div>';
            }

            $html .= '
            <h3>Chi tiết thống kê nhân sự</h3>
            <table>
                <thead>
                    <tr>
                        <th width="10%">STT</th>
                        <th width="60%">Phòng ban</th>
                        <th width="30%">Số lượng</th>
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

        // Xuất PDF
        try {
            $mpdf = new Mpdf(['mode' => 'utf-8', 'format' => 'A4']);
            // Cho phép load ảnh base64 lớn
            $mpdf->showImageErrors = true; 
            $mpdf->WriteHTML($html);
            $mpdf->Output('Bao_cao_thong_ke_' . date('dmY') . '.pdf', 'D');
        } catch (\Exception $e) {
            echo "Lỗi xuất PDF: " . $e->getMessage();
        }
        exit;
    }
}