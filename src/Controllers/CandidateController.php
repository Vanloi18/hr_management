<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Candidate; 
use App\Models\Position; 
use App\Models\Employee;
use App\Models\Department;
use App\Core\Validator; 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Mpdf\Mpdf;

class CandidateController extends Controller
{
    private const CV_UPLOAD_DIR = BASE_PATH . 'public/uploads/cvs/';
    private const ALLOWED_EXTENSIONS = ['pdf', 'doc', 'docx'];
    private const MAX_FILE_SIZE = 5 * 1024 * 1024; 

    protected $candidateModel;
    protected $positionModel;

    public function __construct()
    {
        parent::__construct();
        $this->candidateModel = new Candidate();
        $this->positionModel = new Position();
    }


    // [Hàm INDEX]
    public function index()
    {
        $this->checkAuthentication();

        // 1. Lấy tham số filter từ URL
        $keyword     = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
        $status      = isset($_GET['status']) ? trim($_GET['status']) : '';
        $position_id = isset($_GET['position_id']) ? trim($_GET['position_id']) : '';
        $page        = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) $page = 1;

        $limit  = 10;
        $offset = ($page - 1) * $limit;

        // 2. Lấy dữ liệu
        $candidates = $this->candidateModel->getPaginated($keyword, $status, $position_id, $limit, $offset);
        $totalRecords = $this->candidateModel->countAll($keyword, $status, $position_id);
        $totalPages = ceil($totalRecords / $limit);
        
        // Lấy list vị trí tuyển dụng cho dropdown filter
        $positionsList = $this->candidateModel->getPositionsList();

        // 3. Đóng gói dữ liệu
        $data = [
            'title'         => 'Quản lý Ứng viên',
            'candidates'    => $candidates,
            'positionsList' => $positionsList,
            'currentPage'   => $page,
            'totalPages'    => $totalPages,
            'totalRecords'  => $totalRecords,
            'keyword'       => $keyword,
            'status'        => $status,
            'position_id'   => $position_id
        ];

        // 4. Xử lý AJAX
        if (isAjaxRequest()) {
            return partial('candidates/index', $data);
        }

        return view('candidates/index', $data);
    }

    // [Hàm CREATE]
    public function create()
    {
        $this->checkAuthentication();
        // Giả định Position Model có hàm allOpenForDropdown
        $positions = $this->positionModel->allOpenForDropdown();
        $selected_position_id = $_GET['position_id'] ?? null;
        
        $data = [
            'title' => 'Thêm Ứng viên mới',
            'positions' => $positions,
            'selected_position_id' => $selected_position_id
        ];

        if (isAjaxRequest()) {
            return partial('candidates/create', $data);
        }
        return view('candidates/create', $data);
    }

    // [Hàm STORE]
    public function store()
    {
        $this->checkAuthentication();
        
        // 1. Validate TEXT
        $validator = new Validator();
        $rules = [
            'position_id' => 'required|numeric',
            'full_name' => 'required|min:3',
            'email' => 'required|email|unique:candidates',
            'phone' => 'required|min:8',
        ];

        if (!$validator->validate($_POST, $rules)) {
            // Validator đã tự động flash lỗi và redirect
            return; 
        }

        // 2. Xử lý FILES (CV là bắt buộc khi TẠO MỚI)
        $file_info = $_FILES['cv_file'] ?? null;
        if (empty($file_info) || $file_info['error'] === UPLOAD_ERR_NO_FILE) {
             flash('error', 'File CV là bắt buộc khi tạo mới hồ sơ.');
             // Dùng $_POST để giữ lại giá trị cũ trong form
             $_SESSION['_flash']['old'] = $_POST;
             redirect('/candidates/create');
        }

        $data = $validator->validatedData();
        $data['status'] = e($_POST['status'] ?? 'applied'); // Mặc định là applied (đã nộp) hoặc pending
        $data['notes'] = e($_POST['notes'] ?? null);
        $data['applied_at'] = date('Y-m-d H:i:s'); 
        $data['interview_date'] = null;
        $data['interview_location'] = null;

        try {
            $data['cv_file_path'] = $this->handleFileUpload($file_info);
        } catch (\Exception $e) {
             flash('error', 'Lỗi File CV: ' . $e->getMessage());
             $_SESSION['_flash']['old'] = $_POST;
             redirect('/candidates/create');
        }

        // 3. Lưu CSDL
        $this->candidateModel->create($data);

        flash('success', 'Thêm ứng viên thành công!');
        redirect('/candidates');
    }

    // [Hàm EDIT]
    public function edit()
    {
        $this->checkAuthentication();
        $id = $_GET['id'] ?? null;
        if (!$id) redirect('/candidates');

        $candidate = $this->candidateModel->find($id);
        if (!$candidate) {
            flash('error', 'Không tìm thấy ứng viên.');
            redirect('/candidates');
        }
        
        $positions = $this->positionModel->allOpenForDropdown();
        
        $data = [
            'title' => 'Chỉnh sửa Ứng viên',
            'candidate' => $candidate,
            'positions' => $positions
        ];

        if (isAjaxRequest()) {
            return partial('candidates/edit', $data);
        }
        return view('candidates/edit', $data);
    }

    
    public function update()
{
    $this->checkAuthentication();
    $id = $_POST['id'] ?? null;
    if (!$id) redirect('/candidates');

    $oldCandidate = $this->candidateModel->find($id);
    if (!$oldCandidate) redirect('/candidates');

    // 1. Validate TEXT
    $validator = new Validator();
    $rules = [
        'position_id' => 'required|numeric',
        'full_name'   => 'required|min:3',
        'email'       => 'required|email|unique:candidates,' . $id,
        'phone'       => 'required|min:8',
    ];

    // Nếu chuyển sang trạng thái phỏng vấn => bắt buộc nhập lịch
    if (($_POST['status'] ?? null) === 'interviewing') {
        $rules['interview_date']     = 'required';
        $rules['interview_location'] = 'required';
    }

    // Validator tự flash lỗi -> redirect quay lại EDIT
    if (!$validator->validate($_POST, $rules)) {
        redirect('/candidates/edit?id=' . $id);
        return;
    }

    // 2. Lấy dữ liệu và Xử lý FILES
    $data = $validator->validatedData();
    $data['status'] = e($_POST['status'] ?? 'pending');
    $data['notes']  = e($_POST['notes'] ?? null);

    // Files
    $file_info = $_FILES['cv_file'] ?? null;
    $data['cv_file_path'] = $oldCandidate['cv_file_path']; // Giữ file mặc định

    $data['interview_date'] = $data['status'] === 'interviewing' ? $_POST['interview_date'] : null;
    $data['interview_location'] = $data['status'] === 'interviewing' ? $_POST['interview_location'] : null;
    $this->candidateModel->update($id, $data);

    // 1. Gửi email nếu chuyển sang phỏng vấn
        if ($data['status'] === 'interviewing' && $oldCandidate['status'] !== 'interviewing') {
            $updatedCandidate = $this->candidateModel->find($id);
            $this->sendInterviewEmail($updatedCandidate);
        }

        // [MỚI] 2. Gửi email thông báo kết quả (Trúng tuyển hoặc Từ chối)
        if (in_array($data['status'], ['hired', 'rejected']) && $data['status'] !== $oldCandidate['status']) {
            $updatedCandidate = $this->candidateModel->find($id); // Lấy lại thông tin đầy đủ (kèm tên vị trí)
            $this->sendResultEmail($updatedCandidate, $data['status']);
        }

        // 3. Tạo nhân viên nếu chuyển sang Hired
        if ($data['status'] === 'hired' && $oldCandidate['status'] !== 'hired') {
            $this->promoteToEmployee($data, $oldCandidate['cv_file_path']);
            return; 
        }

    if ($data['status'] === 'hired' && $oldCandidate['status'] !== 'hired') {

        $employeeModel = new Employee();

        // Lấy tên vị trí
        $position = $this->positionModel->find($data['position_id']);
        $jobTitle = $position['title'] ?? 'Chưa xác định';

        $deptModel = new Department();
        $allDepts = $deptModel->all(); 
        $deptId = !empty($allDepts) ? $allDepts[0]['id'] : null;

        $employeeData = [
            'full_name'      => $data['full_name'],
            'email'          => $data['email'],
            'phone'          => $data['phone'],
            'job_title'      => $jobTitle,
            'department_id'  => $deptId,
            'start_date'     => date('Y-m-d'),
            'status'         => 'probation', 
            'photo_path'     => null,
            'contract_path'  => $oldCandidate['cv_file_path'], 
        ];

        $employeeModel->create($employeeData);

        flash('success', 'Tuyệt vời! Đã tuyển dụng và tạo hồ sơ nhân viên thành công.');
        redirect('/employees');
        return;
    }

    flash('success', 'Cập nhật ứng viên thành công!');
    redirect('/candidates');
}


    // [Hàm DESTROY]
    public function destroy()
    {
        $this->checkAuthentication();
        try {
            $id = $_POST['id'] ?? null;
            if (!$id) {
                throw new \Exception('Thiếu ID của Ứng viên.');
            }

            $candidate = $this->candidateModel->find($id);

            $this->candidateModel->delete($id);
            if ($candidate && $candidate['cv_file_path']) {
                $filePath = self::CV_UPLOAD_DIR . basename($candidate['cv_file_path']);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }

            $_SESSION['success'] = 'Đã xóa ứng viên và file CV liên quan thành công.';
            header('Location: ' . BASE_URL . '/candidates');
            exit();

        } catch (\Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
            header('Location: ' . BASE_URL . '/candidates');
            exit();
        }
    }

    // --- HÀM HELPER PRIVATE ---
    private function handleFileUpload($fileInfo)
    {
        // 1. Kiểm tra lỗi upload cơ bản
        if ($fileInfo['error'] !== UPLOAD_ERR_OK) {
            throw new \Exception('File upload bị lỗi.');
        }

        // 2. Kiểm tra kích thước và định dạng
        if ($fileInfo['size'] > self::MAX_FILE_SIZE) {
            throw new \Exception('File quá lớn. Tối đa 5MB.');
        }

        $extension = strtolower(pathinfo($fileInfo['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, self::ALLOWED_EXTENSIONS)) {
            throw new \Exception('Chỉ chấp nhận file định dạng: ' . implode(', ', self::ALLOWED_EXTENSIONS));
        }

        // 3. Tạo tên file duy nhất và đường dẫn
        $fileName = uniqid() . '-' . basename($fileInfo['name']);
        $destination = self::CV_UPLOAD_DIR . $fileName;
        $relativePath = 'uploads/cvs/' . $fileName; 

        // 4. Tạo thư mục nếu chưa tồn tại
        if (!is_dir(self::CV_UPLOAD_DIR)) {
            mkdir(self::CV_UPLOAD_DIR, 0777, true);
        }

        // 5. Di chuyển file
        if (move_uploaded_file($fileInfo['tmp_name'], $destination)) {
            return $relativePath;
        } else {
            throw new \Exception('Không thể di chuyển file đã upload.');
        }
    }

    private function sendInterviewEmail($candidate) 
    {
        $mail = new PHPMailer(true);

        try {
            // Cấu hình Server SMTP
            $mail->isSMTP();
            $mail->Host       = SMTP_HOST;
            $mail->SMTPAuth   = true;
            $mail->Username   = SMTP_USERNAME; 
            $mail->Password   = SMTP_PASSWORD;
            $mail->SMTPSecure = SMTP_SECURE;
            $mail->Port       = SMTP_PORT;
            
            // Cài đặt người gửi và người nhận
            $mail->setFrom(SMTP_USERNAME, MAIL_FROM_NAME);
            $mail->addAddress($candidate['email'], $candidate['full_name']); // Thêm người nhận

            // Cài đặt Nội dung Email
            $mail->isHTML(true); 
            $mail->CharSet = 'UTF-8';
            $mail->Subject = '[HR System] Thư mời phỏng vấn - ' . $candidate['position_title'];

            $interviewTime = date('H:i, d/m/Y', strtotime($candidate['interview_date']));
            
            // Nội dung HTML (Bạn có thể làm đẹp thêm bằng CSS)
            $mail->Body = "
                <p>Chào bạn <b>{$candidate['full_name']}</b>,</p>
                <p>Chúng tôi xin chúc mừng và thông báo bạn đã vượt qua vòng hồ sơ và được mời tham gia phỏng vấn cho vị trí <b>{$candidate['position_title']}</b> tại công ty chúng tôi.</p>
                <hr>
                <p><b>THÔNG TIN PHỎNG VẤN:</b></p>
                <ul>
                    <li><strong>Thời gian:</strong> {$interviewTime}</li>
                    <li><strong>Địa điểm/Hình thức:</strong> {$candidate['interview_location']}</li>
                    <li><strong>Liên hệ:</strong> ".MAIL_FROM_NAME."</li>
                </ul>
                <p>Vui lòng xác nhận tham gia phỏng vấn qua email này. Chúc bạn thành công!</p>
                <p style='font-size: 0.8rem; color: #999;'>Trân trọng,<br>Bộ phận Tuyển dụng</p>
            ";

            $mail->send();
            error_log('Email phỏng vấn đã được gửi thành công tới: ' . $candidate['email']);

        } catch (Exception $e) {
            // Xử lý lỗi: Ghi log lỗi vào file
            error_log("LỖI GỬI EMAIL tới {$candidate['email']}: {$mail->ErrorInfo}");
            // Bạn có thể dùng flash('error', 'Lỗi gửi email. Vui lòng kiểm tra log.') nếu cần báo lỗi cho HR
        }
    }

    /**
     * Xuất Excel danh sách Ứng viên
     */
    public function exportExcel()
    {
        $this->checkAuthentication();

        // 1. Lấy tham số filter
        $keyword     = $_GET['keyword'] ?? '';
        $status      = $_GET['status'] ?? '';
        $position_id = $_GET['position_id'] ?? '';

        // 2. Lấy dữ liệu
        $candidates = $this->candidateModel->getAllForExport($keyword, $status, $position_id);

        // 3. Khởi tạo Excel
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Danh sách Ứng viên');

        // Header
        $headers = ['ID', 'Họ tên', 'Email', 'SĐT', 'Vị trí ứng tuyển', 'Ngày nộp', 'Trạng thái'];
        $sheet->fromArray([$headers], NULL, 'A1');

        // Data
        $rows = [];
        foreach ($candidates as $cand) {
            // Mapping trạng thái sang tiếng Việt
            $statusText = match($cand['status']) {
                'applied'      => 'Đã nộp hồ sơ',
                'interviewing' => 'Đang phỏng vấn',
                'hired'        => 'Đã tuyển',
                'rejected'     => 'Từ chối',
                default        => $cand['status']
            };

            $rows[] = [
                $cand['id'],
                $cand['full_name'],
                $cand['email'],
                $cand['phone'],
                $cand['position_title'] ?? 'N/A',
                date('d/m/Y H:i', strtotime($cand['applied_at'])),
                $statusText
            ];
        }

        if (!empty($rows)) {
            $sheet->fromArray($rows, NULL, 'A2');
        }

        // Style
        $lastRow = count($rows) + 1;
        $sheet->getStyle("A1:G{$lastRow}")->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
        ]);
        $sheet->getStyle('A1:G1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '198754']], // Màu xanh success
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
        ]);
        foreach (range('A', 'G') as $col) $sheet->getColumnDimension($col)->setAutoSize(true);

        // Output
        $fileName = 'DS_UngVien_' . date('dmY_Hi') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    /**
     * Xuất PDF danh sách Ứng viên
     */
    public function exportPDF()
    {
        $this->checkAuthentication();

        $keyword     = $_GET['keyword'] ?? '';
        $status      = $_GET['status'] ?? '';
        $position_id = $_GET['position_id'] ?? '';

        $candidates = $this->candidateModel->getAllForExport($keyword, $status, $position_id);

        // HTML Template
        $html = '
        <html>
        <head>
            <style>
                body { font-family: DejaVu Sans, sans-serif; font-size: 11px; }
                h2 { text-align: center; color: #198754; margin-bottom: 20px; }
                table { width: 100%; border-collapse: collapse; margin-top: 10px; }
                th { background-color: #198754; color: white; padding: 8px; border: 1px solid #333; font-weight: bold; }
                td { padding: 8px; border: 1px solid #444; text-align: center; }
                .text-left { text-align: left; }
            </style>
        </head>
        <body>
            <h2>DANH SÁCH ỨNG VIÊN</h2>
            <p style="text-align:center">Ngày xuất: ' . date('d/m/Y H:i') . '</p>
            <table>
                <thead>
                    <tr>
                        <th width="5%">ID</th>
                        <th width="20%">Họ tên</th>
                        <th width="20%">Liên hệ</th>
                        <th width="20%">Vị trí</th>
                        <th width="15%">Ngày nộp</th>
                        <th width="20%">Trạng thái</th>
                    </tr>
                </thead>
                <tbody>';
        
        foreach ($candidates as $cand) {
            $statusText = match($cand['status']) {
                'applied'      => 'Đã nộp hồ sơ',
                'interviewing' => 'Đang phỏng vấn',
                'hired'        => 'Đã tuyển',
                'rejected'     => 'Từ chối',
                default        => $cand['status']
            };
            
            $contactInfo = $cand['email'] . '<br>' . $cand['phone'];

            $html .= '<tr>
                <td>' . $cand['id'] . '</td>
                <td class="text-left">' . $cand['full_name'] . '</td>
                <td class="text-left">' . $contactInfo . '</td>
                <td>' . ($cand['position_title'] ?? '-') . '</td>
                <td>' . date('d/m/Y', strtotime($cand['applied_at'])) . '</td>
                <td>' . $statusText . '</td>
            </tr>';
        }

        $html .= '</tbody></table></body></html>';

        try {
            $mpdf = new Mpdf(['mode' => 'utf-8', 'format' => 'A4-L']);
            $mpdf->WriteHTML($html);
            $mpdf->Output('DS_UngVien_' . date('dmY') . '.pdf', 'D');
        } catch (\Exception $e) {
            echo "Lỗi xuất PDF: " . $e->getMessage();
        }
        exit;
    }
    // --- HÀM GỬI EMAIL KẾT QUẢ (MỚI) ---
    private function sendResultEmail($candidate, $status)
    {
        $mail = new PHPMailer(true);

        try {
            // Cấu hình Server (Giống hàm sendInterviewEmail)
            $mail->isSMTP();
            $mail->Host       = SMTP_HOST;
            $mail->SMTPAuth   = true;
            $mail->Username   = SMTP_USERNAME;
            $mail->Password   = SMTP_PASSWORD;
            $mail->SMTPSecure = SMTP_SECURE;
            $mail->Port       = SMTP_PORT;

            // Người gửi - Người nhận
            $mail->setFrom(SMTP_USERNAME, MAIL_FROM_NAME);
            $mail->addAddress($candidate['email'], $candidate['full_name']);

            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';

            // Xử lý nội dung thư theo trạng thái
            if ($status === 'hired') {
                // --- KỊCH BẢN TRÚNG TUYỂN ---
                $mail->Subject = '[HR System] Chúc mừng! Thư mời nhận việc - ' . ($candidate['position_title'] ?? 'Vị trí mới');
                $mail->Body = "
                    <p>Chào bạn <b>{$candidate['full_name']}</b>,</p>
                    <p>Chúc mừng bạn! Chúng tôi rất vui mừng thông báo rằng bạn đã trúng tuyển vị trí <b>" . ($candidate['position_title'] ?? 'N/A') . "</b> tại công ty.</p>
                    <p>Bộ phận Nhân sự đã tạo hồ sơ nhân viên cho bạn và sẽ liên hệ sớm nhất để hướng dẫn các thủ tục nhận việc.</p>
                    <p>Chào mừng bạn gia nhập đội ngũ của chúng tôi!</p>
                    <hr>
                    <p style='color:#666; font-size:12px'>Trân trọng,<br>Bộ phận Tuyển dụng</p>
                ";
            } elseif ($status === 'rejected') {
                // --- KỊCH BẢN TỪ CHỐI ---
                $mail->Subject = '[HR System] Thông báo kết quả tuyển dụng - ' . ($candidate['position_title'] ?? '');
                $mail->Body = "
                    <p>Chào bạn <b>{$candidate['full_name']}</b>,</p>
                    <p>Cảm ơn bạn đã dành thời gian quan tâm và ứng tuyển vào vị trí <b>" . ($candidate['position_title'] ?? 'N/A') . "</b>.</p>
                    <p>Sau khi xem xét kỹ lưỡng hồ sơ và kết quả phỏng vấn, chúng tôi rất tiếc phải thông báo rằng hiện tại hồ sơ của bạn chưa phù hợp với yêu cầu của vị trí này.</p>
                    <p>Chúng tôi sẽ lưu hồ sơ của bạn trong cơ sở dữ liệu và sẽ liên hệ lại nếu có vị trí phù hợp hơn trong tương lai.</p>
                    <p>Chúc bạn sớm tìm được công việc như ý!</p>
                    <hr>
                    <p style='color:#666; font-size:12px'>Trân trọng,<br>Bộ phận Tuyển dụng</p>
                ";
            }

            $mail->send();

        } catch (Exception $e) {
            // Chỉ ghi log lỗi, không làm sập web
            error_log("Lỗi gửi email kết quả ({$status}) tới {$candidate['email']}: {$mail->ErrorInfo}");
        }
    }
    // --- HÀM HỖ TRỢ TUYỂN DỤNG (ĐÃ FIX LỖI DUPLICATE EMAIL) ---
    private function promoteToEmployee($candidateData, $cvPath)
    {
        $employeeModel = new Employee();
        
        // 1. Tìm phòng ban mặc định
        $deptModel = new Department();
        $allDepts = $deptModel->all(); 
        $deptId = !empty($allDepts) ? $allDepts[0]['id'] : null;

        if (!$deptId) {
            $deptModel->create(['name' => 'Phòng Nhân Sự (Auto)', 'description' => 'Tự động tạo']);
            $newDepts = $deptModel->all();
            $deptId = $newDepts[0]['id'];
        }

        // 2. Lấy tên vị trí công việc
        $position = $this->positionModel->find($candidateData['position_id']);
        $jobTitle = $position['title'] ?? 'Staff';

        // 3. Chuẩn bị dữ liệu nhân viên mới
        $empData = [
            'full_name'     => $candidateData['full_name'],
            'email'         => $candidateData['email'],
            'phone'         => $candidateData['phone'],
            'job_title'     => $jobTitle,
            'department_id' => $deptId,
            'start_date'    => date('Y-m-d'),
            'status'        => 'probation',
            'photo_path'    => null,
            'contract_path' => $cvPath
        ];

        // 4. Tạo nhân viên (Có xử lý lỗi trùng lặp)
        try {
            $employeeModel->create($empData);
            flash('success', 'Tuyệt vời! Đã tuyển dụng và tạo hồ sơ nhân viên thành công.');
        } catch (\PDOException $e) {
            // Mã lỗi 23000 là lỗi vi phạm ràng buộc (Duplicate entry)
            if ($e->getCode() == '23000') {
                flash('warning', 'Ứng viên đã được chuyển sang trạng thái "Đã tuyển", nhưng hồ sơ nhân viên đã tồn tại từ trước (Email trùng lặp).');
            } else {
                // Nếu là lỗi khác thì vẫn báo lỗi như thường
                throw $e; 
            }
        }
        
        redirect('/employees');
        exit;
    }
}