<?php

namespace App\Models;

use App\Core\Model;

class Employee extends Model
{
    public function allWithDetails()
    {
        $sql = "
            SELECT 
                e.*, 
                d.name AS department_name 
            FROM 
                employees AS e
            LEFT JOIN 
                departments AS d ON e.department_id = d.id
            ORDER BY 
                e.full_name ASC
        ";
        return $this->db->query($sql)->fetchAll();
    }

    public function find($id)
    {
        return $this->db->query("SELECT * FROM employees WHERE id = :id", ['id' => $id])->fetch();
    }

    public function findByEmail($email)
    {
        return $this->db->query("SELECT id FROM employees WHERE email = :email", ['email' => $email])->fetch();
    }

    /**
     * Kiểm tra email trùng lặp (dùng khi cập nhật, loại trừ chính NV này)
     */
    public function findByEmailAndNotId($email, $id)
    {
        return $this->db->query(
            "SELECT id FROM employees WHERE email = :email AND id != :id",
            ['email' => $email, 'id' => $id]
        )->fetch();
    }

    public function create($data)
{
    $sql = "INSERT INTO employees 
            (full_name, email, phone, job_title, department_id, start_date, status, photo_path, contract_path) 
            VALUES 
            (:full_name, :email, :phone, :job_title, :department_id, :start_date, :status, :photo_path, :contract_path)";
    
    // Đảm bảo array $data có đủ key cho các cột:
    $params = [
        'full_name' => $data['full_name'],
        'email' => $data['email'],
        'phone' => $data['phone'],
        'job_title' => $data['job_title'],
        'department_id' => $data['department_id'],
        'start_date' => $data['start_date'],
        'status' => $data['status'] ?? 'active',
        'photo_path' => $data['photo_path'] ?? null,
        'contract_path' => $data['contract_path'] ?? null,
    ];
    
    return $this->db->query($sql, $params);
}

    public function update($id, $data)
    {
        return $this->db->query(
            "UPDATE employees SET 
                department_id = :dep_id, full_name = :name, email = :email, phone = :phone, 
                job_title = :job, start_date = :start, status = :status, 
                photo_path = :photo, contract_path = :contract
             WHERE id = :id",
            [
                'dep_id' => $data['department_id'],
                'name' => $data['full_name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'job' => $data['job_title'],
                'start' => $data['start_date'],
                'status' => $data['status'],
                'photo' => $data['photo_path'],
                'contract' => $data['contract_path'],
                'id' => $id
            ]
        );
    }

    public function delete($id)
    {
        return $this->db->query("DELETE FROM employees WHERE id = :id", ['id' => $id]);
    }

    /**
     * Lấy danh sách nhân viên: Có Phân trang + Tìm kiếm + Lọc Phòng ban
     */
    public function getPaginated($keyword = '', $status = '', $department_id = '', $limit = 10, $offset = 0)
    {
        // JOIN bảng departments để lấy tên phòng ban
        $sql = "SELECT e.*, d.name as department_name 
                FROM employees e 
                LEFT JOIN departments d ON e.department_id = d.id 
                WHERE 1=1";
        
        $params = [];

        // 1. Tìm kiếm (Tên, Email, Mã NV)
        if (!empty($keyword)) {
            $sql .= " AND (e.full_name LIKE ? OR e.email LIKE ? OR e.id LIKE ?)";
            $keywordParam = "%$keyword%";
            $params[] = $keywordParam;
            $params[] = $keywordParam;
            $params[] = $keywordParam;
        }

        // 2. Lọc theo Trạng thái
        if (!empty($status)) {
            $sql .= " AND e.status = ?";
            $params[] = $status;
        }

        // 3. Lọc theo Phòng ban
        if (!empty($department_id)) {
            $sql .= " AND e.department_id = ?";
            $params[] = $department_id;
        }

        $sql .= " ORDER BY e.id DESC LIMIT " . (int)$limit . " OFFSET " . (int)$offset;

        return $this->db->query($sql, $params)->fetchAll();
    }

    public function countAll($keyword = '', $status = '', $department_id = '')
    {
        $sql = "SELECT COUNT(*) as total FROM employees e WHERE 1=1";
        $params = [];

        if (!empty($keyword)) {
            $sql .= " AND (e.full_name LIKE ? OR e.email LIKE ? OR e.id LIKE ?)";
            $keywordParam = "%$keyword%";
            $params[] = $keywordParam;
            $params[] = $keywordParam;
            $params[] = $keywordParam;
        }

        if (!empty($status)) {
            $sql .= " AND e.status = ?";
            $params[] = $status;
        }

        if (!empty($department_id)) {
            $sql .= " AND e.department_id = ?";
            $params[] = $department_id;
        }

        $result = $this->db->query($sql, $params)->fetch();
        return $result ? (int)$result['total'] : 0;
    }

    public function getByDepartmentId($departmentId)
{
    // Lấy id, full_name, created_at theo yêu cầu
    $sql = "SELECT id, full_name, created_at FROM employees WHERE department_id = :department_id ORDER BY full_name ASC";
    return $this->db->query($sql, ['department_id' => $departmentId])->fetchAll();
}

    public function getDepartments() {
        return $this->db->query("SELECT id, name FROM departments ORDER BY name ASC")->fetchAll();
    }

    /**
     * Lấy toàn bộ danh sách nhân viên theo bộ lọc (Dùng cho Export)
     */
    public function getAllForExport($keyword = '', $status = '', $department_id = '')
    {
        $sql = "SELECT e.*, d.name as department_name 
                FROM employees e 
                LEFT JOIN departments d ON e.department_id = d.id 
                WHERE 1=1";
        
        $params = [];

        if (!empty($keyword)) {
            $sql .= " AND (e.full_name LIKE ? OR e.email LIKE ? OR e.id LIKE ?)";
            $keywordParam = "%$keyword%";
            $params[] = $keywordParam;
            $params[] = $keywordParam;
            $params[] = $keywordParam;
        }

        if (!empty($status)) {
            $sql .= " AND e.status = ?";
            $params[] = $status;
        }

        if (!empty($department_id)) {
            $sql .= " AND e.department_id = ?";
            $params[] = $department_id;
        }

        $sql .= " ORDER BY e.id DESC"; // Không có LIMIT/OFFSET

        return $this->db->query($sql, $params)->fetchAll();
    }
}