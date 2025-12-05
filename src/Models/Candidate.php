<?php

namespace App\Models;

use App\Core\Model;

class Candidate extends Model
{
    /**
     * Lấy danh sách ứng viên: Phân trang + Tìm kiếm + Filter
     */
    public function getPaginated($keyword = '', $status = '', $position_id = '', $limit = 10, $offset = 0)
    {
        // JOIN bảng positions để lấy tên vị trí
        $sql = "SELECT c.*, p.title as position_title 
                FROM candidates c 
                LEFT JOIN positions p ON c.position_id = p.id 
                WHERE 1=1";
        
        $params = [];

        // 1. Tìm kiếm (Tên, Email, SĐT)
        if (!empty($keyword)) {
            $sql .= " AND (c.full_name LIKE ? OR c.email LIKE ? OR c.phone LIKE ?)";
            $keywordParam = "%$keyword%";
            $params[] = $keywordParam;
            $params[] = $keywordParam;
            $params[] = $keywordParam;
        }

        // 2. Lọc theo Trạng thái
        if (!empty($status)) {
            $sql .= " AND c.status = ?";
            $params[] = $status;
        }

        // 3. Lọc theo Vị trí ứng tuyển
        if (!empty($position_id)) {
            $sql .= " AND c.position_id = ?";
            $params[] = $position_id;
        }

        $sql .= " ORDER BY c.applied_at DESC LIMIT " . (int)$limit . " OFFSET " . (int)$offset;

        return $this->db->query($sql, $params)->fetchAll();
    }

    /**
     * Đếm tổng số bản ghi (Hàm này thay thế hoàn toàn hàm countAll cũ)
     */
    public function countAll($keyword = '', $status = '', $position_id = '')
    {
        $sql = "SELECT COUNT(*) as total FROM candidates c WHERE 1=1";
        $params = [];

        if (!empty($keyword)) {
            $sql .= " AND (c.full_name LIKE ? OR c.email LIKE ? OR c.phone LIKE ?)";
            $keywordParam = "%$keyword%";
            $params[] = $keywordParam;
            $params[] = $keywordParam;
            $params[] = $keywordParam;
        }

        if (!empty($status)) {
            $sql .= " AND c.status = ?";
            $params[] = $status;
        }

        if (!empty($position_id)) {
            $sql .= " AND c.position_id = ?";
            $params[] = $position_id;
        }

        $result = $this->db->query($sql, $params)->fetch();
        return $result ? (int)$result['total'] : 0;
    }
    
    /**
     * Helper: Lấy danh sách Vị trí tuyển dụng để nạp vào Dropdown lọc
     */
    public function getPositionsList() {
        return $this->db->query("SELECT id, title FROM positions ORDER BY title ASC")->fetchAll();
    }

    // --- CÁC HÀM CŨ (CREATE, FIND, UPDATE, DELETE) ---
    // Bạn giữ lại các hàm CRUD cũ của bạn ở dưới đây. 
    // Nếu bạn chưa có, tôi sẽ thêm các hàm cơ bản để code không bị lỗi khi gọi create/delete
    
    public function create($data)
{
    $sql = "INSERT INTO candidates 
    (position_id, full_name, email, phone, cv_file_path, status, notes, applied_at) 
    VALUES 
    (:position_id, :full_name, :email, :phone, :cv_file_path, :status, :notes, NOW())";

    return $this->db->query($sql, $data);
}


    public function find($id)
    {
        // Lấy thông tin chi tiết kèm tên vị trí
        $sql = "SELECT c.*, p.title as position_title 
                FROM candidates c 
                LEFT JOIN positions p ON c.position_id = p.id 
                WHERE c.id = :id";
        return $this->db->query($sql, ['id' => $id])->fetch();
    }

    public function update($id, $data)
    {
        // Logic update tùy thuộc vào form của bạn, đây là ví dụ cập nhật trạng thái
        $sql = "UPDATE candidates SET status = :status WHERE id = :id";
        return $this->db->query($sql, ['status' => $data['status'], 'id' => $id]);
    }

    public function delete($id)
    {
        return $this->db->query("DELETE FROM candidates WHERE id = :id", ['id' => $id]);
    }

    /**
     * Lấy toàn bộ danh sách ứng viên theo bộ lọc (Dùng cho Export)
     */
    public function getAllForExport($keyword = '', $status = '', $position_id = '')
    {
        // Copy logic JOIN từ getPaginated để lấy tên vị trí
        $sql = "SELECT c.*, p.title as position_title 
                FROM candidates c 
                LEFT JOIN positions p ON c.position_id = p.id 
                WHERE 1=1";
        
        $params = [];

        // 1. Tìm kiếm
        if (!empty($keyword)) {
            $sql .= " AND (c.full_name LIKE ? OR c.email LIKE ? OR c.phone LIKE ?)";
            $keywordParam = "%$keyword%";
            $params[] = $keywordParam;
            $params[] = $keywordParam;
            $params[] = $keywordParam;
        }

        // 2. Lọc theo Trạng thái
        if (!empty($status)) {
            $sql .= " AND c.status = ?";
            $params[] = $status;
        }

        // 3. Lọc theo Vị trí
        if (!empty($position_id)) {
            $sql .= " AND c.position_id = ?";
            $params[] = $position_id;
        }

        $sql .= " ORDER BY c.id DESC"; // Lấy HẾT, không LIMIT

        return $this->db->query($sql, $params)->fetchAll();
    }
}