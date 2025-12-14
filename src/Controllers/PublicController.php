<?php
// src/Controllers/PublicController.php

namespace App\Controllers;

// Nếu chưa định nghĩa BASE_PATH thì load config (dành cho trường hợp gọi trực tiếp)
if (!defined('BASE_PATH')) {
    require_once dirname(__DIR__, 2) . '/config.php';
}

use App\Core\Controller;
use App\Core\Database;
use App\Models\Candidate;
// Không cần use App\Models\Position hay Field vì ta sẽ query trực tiếp để linh hoạt

class PublicController extends Controller
{
    public function index() 
    {
        $db = Database::getInstance();
        $conn = $db->connection;

        // 1. LẤY PARAMETERS
        $keyword = $_GET['keyword'] ?? '';
        $field_id = $_GET['field_id'] ?? '';
        $location = $_GET['location'] ?? '';
        $job_type = $_GET['job_type'] ?? '';
        
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $limit = 6; 
        $offset = ($page - 1) * $limit;

        // 2. XÂY DỰNG QUERY CƠ BẢN (SỬA LẠI ĐỂ JOIN TRƯỚC WHERE)
        // Lưu ý: Đưa cả recruiters vào đây để dùng chung cho cả đếm và lấy dữ liệu
        $sqlBase = "FROM positions p 
                    LEFT JOIN fields f ON p.field_id = f.id 
                    LEFT JOIN recruiters r ON p.recruiter_id = r.id 
                    WHERE p.status = 'open'";
        
        $params = [];

        if (!empty($keyword)) {
            $sqlBase .= " AND (p.title LIKE ? OR p.description LIKE ?)";
            $params[] = "%$keyword%";
            $params[] = "%$keyword%";
        }

        if (!empty($field_id)) {
            $sqlBase .= " AND p.field_id = ?";
            $params[] = $field_id;
        }

        if (!empty($location)) {
            $sqlBase .= " AND p.location = ?";
            $params[] = $location;
        }

        if (!empty($job_type)) {
            $sqlBase .= " AND p.job_type = ?";
            $params[] = $job_type;
        }

        // 3. ĐẾM TỔNG SỐ
        $countSql = "SELECT COUNT(*) as total $sqlBase";
        $stmtCount = $conn->prepare($countSql);
        $stmtCount->execute($params);
        $totalRecords = $stmtCount->fetch(\PDO::FETCH_ASSOC)['total'];
        $totalPages = ceil($totalRecords / $limit);

        // 4. LẤY DỮ LIỆU (Giờ chỉ cần SELECT các cột, không cần JOIN lại nữa)
        $sql = "SELECT p.*, 
                       COALESCE(f.field_name, f.field_name, 'General') as field_name,
                       COALESCE(r.company_name, 'Công ty Tuyển dụng') as company_name 
                $sqlBase 
                ORDER BY p.id DESC 
                LIMIT $limit OFFSET $offset";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        $jobs = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        // 5. DATA CHO DROPDOWN
        $fields = $conn->query("SELECT * FROM fields ORDER BY id ASC")->fetchAll(\PDO::FETCH_ASSOC);
        $locations = $conn->query("SELECT DISTINCT location FROM positions WHERE status='open' AND location IS NOT NULL ORDER BY location ASC")->fetchAll(\PDO::FETCH_COLUMN);

        // 6. GỌI VIEW
        require BASE_PATH . 'views/public/career_list.php';
    }

    /**
     * Hiển thị chi tiết việc làm
     */
    public function detail()
    {
        $id = $_GET['id'] ?? 0;
        $db = Database::getInstance();
        $conn = $db->connection;
        
        // Query lấy chi tiết
        $stmt = $conn->prepare("SELECT p.*, COALESCE(f.field_name, f.field_name, 'General') as field_name 
                                FROM positions p 
                                LEFT JOIN fields f ON p.field_id = f.id 
                                WHERE p.id = ? AND p.status = 'open'");
        $stmt->execute([$id]);
        $job = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$job) {
            echo "Việc làm không tồn tại hoặc đã đóng.";
            return;
        }

        // Tăng lượt xem (Giả lập, hoặc thực hiện update DB nếu có cột views)
        // $conn->query("UPDATE positions SET views = views + 1 WHERE id = $id");

        require BASE_PATH . 'views/public/job_detail.php';
    }

    /**
     * Xử lý nộp hồ sơ
     */
    public function apply()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Xử lý upload file
            $cvPath = '';
            if (isset($_FILES['resume']) && $_FILES['resume']['error'] == 0) {
                $uploadDir = BASE_PATH . 'public/uploads/cvs/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
                
                $fileName = uniqid() . '-' . basename($_FILES['resume']['name']);
                $cvPath = 'uploads/cvs/' . $fileName;
                move_uploaded_file($_FILES['resume']['tmp_name'], $uploadDir . $fileName);
            }

            // Lưu vào Database
            $candidateModel = new Candidate();
            $data = [
                'full_name' => $_POST['full_name'],
                'email' => $_POST['email'],
                'phone' => $_POST['phone'],
                'position_id' => $_POST['position_id'],
                'cv_file_path' => $cvPath,
                'status' => 'pending', // Trạng thái mặc định
                'applied_at' => date('Y-m-d H:i:s')
            ];
            
            $candidateModel->create($data);
            
            // Thông báo và chuyển hướng
            echo "<script>alert('Nộp hồ sơ thành công!'); window.location.href='" . BASE_URL . "/careers';</script>";
        }
    }
}
?>