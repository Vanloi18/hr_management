<?php

namespace App\Models;

use App\Core\Model;

class Department extends Model
{
    public function all()
    {
        return $this->db->query("SELECT * FROM departments ORDER BY name ASC")->fetchAll();
    }

    public function find($id)
    {
        return $this->db->query("SELECT * FROM departments WHERE id = :id", ['id' => $id])->fetch();
    }

    public function findByName($name)
    {
        return $this->db->query("SELECT id FROM departments WHERE name = :name", ['name' => $name])->fetch();
    }

    public function findByNameAndNotId($name, $id)
    {
        return $this->db->query(
            "SELECT id FROM departments WHERE name = :name AND id != :id",
            ['name' => $name, 'id' => $id]
        )->fetch();
    }

    public function create($name)
    {
        return $this->db->query("INSERT INTO departments (name, created_at) VALUES (:name, NOW())", ['name' => $name]);
    }

    public function update($id, $name)
    {
        return $this->db->query("UPDATE departments SET name = :name WHERE id = :id", ['name' => $name, 'id' => $id]);
    }

    public function delete($id)
    {
        return $this->db->query("DELETE FROM departments WHERE id = :id", ['id' => $id]);
    }

    public function getPaginated($keyword = '', $limit = 10, $offset = 0)
    {
        $sql = "SELECT d.*, 
                       (SELECT COUNT(*) FROM employees e WHERE e.department_id = d.id) as employee_count
                FROM departments d 
                WHERE 1=1";
        
        $params = [];

        if (!empty($keyword)) {
            $sql .= " AND d.name LIKE ?";
            $params[] = "%$keyword%";
        }

        $sql .= " ORDER BY d.id DESC LIMIT " . (int)$limit . " OFFSET " . (int)$offset;

        return $this->db->query($sql, $params)->fetchAll();
    }

    public function countAll($keyword = '')
    {
        $sql = "SELECT COUNT(*) as total FROM departments WHERE 1=1";
        $params = [];

        if (!empty($keyword)) {
            $sql .= " AND name LIKE ?";
            $params[] = "%$keyword%";
        }

        $result = $this->db->query($sql, $params)->fetch();
        return $result ? (int)$result['total'] : 0;
    }

    /**
     * 🔥 HÀM MỚI: Lấy dữ liệu Export (Không phân trang)
     */
    public function getAllForExport($keyword = '')
    {
        $sql = "SELECT d.*, 
                       (SELECT COUNT(*) FROM employees e WHERE e.department_id = d.id) as employee_count
                FROM departments d 
                WHERE 1=1";
        
        $params = [];

        if (!empty($keyword)) {
            $sql .= " AND d.name LIKE ?";
            $params[] = "%$keyword%";
        }

        $sql .= " ORDER BY d.id DESC"; // Lấy hết

        return $this->db->query($sql, $params)->fetchAll();
    }

    public function hasDependencies($id)
{
    // 1. Kiểm tra bảng nhân viên
    $sqlEmp = "SELECT COUNT(*) as total FROM employees WHERE department_id = :id";
    $countEmp = $this->db->query($sqlEmp, ['id' => $id])->fetch()['total'] ?? 0;

    if ($countEmp > 0) {
        return "Không thể xóa! Đang có $countEmp nhân viên thuộc phòng ban này.";
    }


    return false; 
}
}