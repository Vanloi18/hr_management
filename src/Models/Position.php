<?php

namespace App\Models;

use App\Core\Model;

class Position extends Model
{
    /**
     * Lấy tất cả vị trí (kèm chi tiết) cho trang index
     */
    public function allWithDetails()
    {
        $sql = "
            SELECT 
                p.id, p.title, p.status, p.created_at,
                r.company_name,
                f.field_name,
                u.full_name AS created_by_name
            FROM positions AS p
            JOIN recruiters AS r ON p.recruiter_id = r.id
            JOIN fields AS f ON p.field_id = f.id
            LEFT JOIN users AS u ON p.created_by_user_id = u.id
            ORDER BY p.created_at DESC
        ";
        return $this->db->query($sql)->fetchAll();
    }

    /**
     * Lấy danh sách cho dropdown (chỉ vị trí đang 'open')
     */
    public function allOpenForDropdown()
    {
        return $this->db->query("SELECT id, title FROM positions WHERE status = 'open' ORDER BY title ASC")->fetchAll();
    }

    public function find($id)
    {
        return $this->db->query("SELECT * FROM positions WHERE id = :id", ['id' => $id])->fetch();
    }

    public function create($data)
    {
        // [QUAN TRỌNG] Bổ sung salary_range và location vào câu lệnh INSERT
        $sql = "INSERT INTO positions 
                (title, recruiter_id, field_id, description, requirements, salary_range, location, status, created_at) 
                VALUES 
                (:title, :recruiter_id, :field_id, :description, :requirements, :salary_range, :location, :status, NOW())";

        $params = [
            'title'        => $data['title'],
            'recruiter_id' => $data['recruiter_id'],
            'field_id'     => $data['field_id'],
            'description'  => $data['description'],
            'requirements' => $data['requirements'],
            'salary_range' => $data['salary_range'] ?? 'Thỏa thuận', // Giá trị mặc định nếu null
            'location'     => $data['location'] ?? 'Toàn quốc',     // Giá trị mặc định nếu null
            'status'       => $data['status'] ?? 'open',
        ];

        return $this->db->query($sql, $params);
    }

    public function update($id, $data)
    {
        // [QUAN TRỌNG] Bổ sung salary_range và location vào câu lệnh UPDATE
        $sql = "UPDATE positions SET 
                title        = :title, 
                recruiter_id = :recruiter_id, 
                field_id     = :field_id, 
                description  = :description, 
                requirements = :requirements,
                salary_range = :salary_range, 
                location     = :location,
                status       = :status
                WHERE id = :id";
        
        $params = [
            'id'           => $id,
            'title'        => $data['title'],
            'recruiter_id' => $data['recruiter_id'],
            'field_id'     => $data['field_id'],
            'description'  => $data['description'],
            'requirements' => $data['requirements'],
            'salary_range' => $data['salary_range'],
            'location'     => $data['location'],
            'status'       => $data['status']
        ];

        return $this->db->query($sql, $params);
    }

    public function delete($id)
    {
        return $this->db->query("DELETE FROM positions WHERE id = :id", ['id' => $id]);
    }


    public function getPaginated($keyword = '', $status = '', $recruiter_id = '', $limit = 10, $offset = 0)
    {
        // [QUAN TRỌNG] Phải SELECT p.* để lấy hết cột (bao gồm salary_range, location)
        $sql = "SELECT p.*, 
                       r.company_name, 
                       f.field_name
                FROM positions p
                LEFT JOIN recruiters r ON p.recruiter_id = r.id
                LEFT JOIN fields f ON p.field_id = f.id
                WHERE 1=1";
        
        $params = [];

        // 1. Tìm kiếm
        if (!empty($keyword)) {
            $sql .= " AND (p.title LIKE ? OR r.company_name LIKE ?)";
            $keywordParam = "%$keyword%";
            $params[] = $keywordParam;
            $params[] = $keywordParam;
        }

        // 2. Filter Trạng thái
        if (!empty($status)) {
            $sql .= " AND p.status = ?";
            $params[] = $status;
        }

        // 3. Filter Công ty
        if (!empty($recruiter_id)) {
            $sql .= " AND p.recruiter_id = ?";
            $params[] = $recruiter_id;
        }

        $sql .= " ORDER BY p.created_at DESC LIMIT " . (int)$limit . " OFFSET " . (int)$offset;

        return $this->db->query($sql, $params)->fetchAll();
    }

    /**
     * Đếm tổng số bản ghi
     */
    public function countAll($keyword = '', $status = '', $recruiter_id = '')
    {
        $sql = "SELECT COUNT(*) as total 
                FROM positions p 
                LEFT JOIN recruiters r ON p.recruiter_id = r.id
                WHERE 1=1";
        $params = [];

        if (!empty($keyword)) {
            $sql .= " AND (p.title LIKE ? OR r.company_name LIKE ?)";
            $keywordParam = "%$keyword%";
            $params[] = $keywordParam;
            $params[] = $keywordParam;
        }

        if (!empty($status)) {
            $sql .= " AND p.status = ?";
            $params[] = $status;
        }

        if (!empty($recruiter_id)) {
            $sql .= " AND p.recruiter_id = ?";
            $params[] = $recruiter_id;
        }

        $result = $this->db->query($sql, $params)->fetch();
        return $result ? (int)$result['total'] : 0;
    }

    // Helper: Lấy danh sách công ty để nạp vào Dropdown lọc
    public function getRecruitersList() {
        return $this->db->query("SELECT id, company_name FROM recruiters ORDER BY company_name ASC")->fetchAll();
    }
    /**
     * Lấy toàn bộ dữ liệu để xuất file (JOIN với Recruiters và Fields)
     */
    public function getAllForExport($keyword = '', $status = '', $recruiter_id = '')
    {
        $sql = "SELECT p.*, 
                       r.company_name, 
                       f.field_name,
                       u.full_name as created_by_name
                FROM positions p
                LEFT JOIN recruiters r ON p.recruiter_id = r.id
                LEFT JOIN fields f ON p.field_id = f.id
                LEFT JOIN users u ON p.created_by_user_id = u.id
                WHERE 1=1";
        
        $params = [];

        if (!empty($keyword)) {
            $sql .= " AND (p.title LIKE ? OR r.company_name LIKE ?)";
            $keywordParam = "%$keyword%";
            $params[] = $keywordParam;
            $params[] = $keywordParam;
        }

        if (!empty($status)) {
            $sql .= " AND p.status = ?";
            $params[] = $status;
        }

        if (!empty($recruiter_id)) {
            $sql .= " AND p.recruiter_id = ?";
            $params[] = $recruiter_id;
        }

        $sql .= " ORDER BY p.id DESC";

        return $this->db->query($sql, $params)->fetchAll();
    }
}