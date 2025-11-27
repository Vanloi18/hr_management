<?php

namespace App\Models;

use App\Core\Model;

class User extends Model
{
    /**
     * Lấy tất cả user
     */
    public function all()
    {
        return $this->db->query("SELECT id, full_name, email, role, created_at FROM users ORDER BY created_at DESC")->fetchAll();
    }

    /**
     * Tìm user bằng ID
     */
    public function find($id)
    {
        return $this->db->query("SELECT id, full_name, email, role FROM users WHERE id = :id", ['id' => $id])->fetch();
    }

    /**
     * Tìm user bằng Email
     */
    public function findByEmail($email)
    {
        return $this->db->query("SELECT * FROM users WHERE email = :email", ['email' => $email])->fetch();
    }

    /**
     * Kiểm tra email trùng lặp (khi cập nhật)
     */
    public function findByEmailAndNotId($email, $id)
    {
        return $this->db->query(
            "SELECT id FROM users WHERE email = :email AND id != :id",
            ['email' => $email, 'id' => $id]
        )->fetch();
    }

    /**
     * Tạo user mới
     */
    public function create($data)
    {
        $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);
        
        return $this->db->query(
            "INSERT INTO users (full_name, email, password, role) VALUES (:full_name, :email, :password, :role)",
            [
                'full_name' => $data['full_name'],
                'email' => $data['email'],
                'password' => $hashedPassword,
                'role' => $data['role']
            ]
        );
    }

    /**
     * Cập nhật user
     */
    public function update($id, $data)
    {
        $params = [
            'full_name' => $data['full_name'],
            'email' => $data['email'],
            'role' => $data['role'],
            'id' => $id
        ];

        // CHỈ cập nhật mật khẩu NẾU người dùng nhập mật khẩu mới
        if (!empty($data['password'])) {
            $params['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
            $sql = "UPDATE users SET full_name = :full_name, email = :email, role = :role, password = :password WHERE id = :id";
        } else {
            $sql = "UPDATE users SET full_name = :full_name, email = :email, role = :role WHERE id = :id";
        }

        return $this->db->query($sql, $params);
    }

    /**
     * Xóa user
     */
    public function delete($id)
    {
        return $this->db->query("DELETE FROM users WHERE id = :id", ['id' => $id]);
    }

    /**
     * Lấy danh sách User có phân trang và tìm kiếm
     */
    public function getPaginated($keyword = '', $role = '', $limit = 10, $offset = 0)
    {
        $sql = "SELECT * FROM users WHERE 1=1";
        $params = [];

        // 1. Xử lý tìm kiếm
        if (!empty($keyword)) {
            $sql .= " AND (full_name LIKE ? OR email LIKE ?)";
            $params[] = "%$keyword%";
            $params[] = "%$keyword%";
        }

        // 2. Xử lý lọc role
        if (!empty($role)) {
            $sql .= " AND role = ?";
            $params[] = $role;
        }

        // 3. Sắp xếp và Phân trang
        // Lưu ý: LIMIT và OFFSET được nối chuỗi trực tiếp sau khi ép kiểu (int)
        // để tránh lỗi PDO bind param dạng string.
        $sql .= " ORDER BY id DESC LIMIT " . (int)$limit . " OFFSET " . (int)$offset;

        return $this->db->query($sql, $params)->fetchAll();
    }

    /**
     * Đếm tổng số bản ghi (để tính toán số trang)
     */
    public function countAll($keyword = '', $role = '')
    {
        $sql = "SELECT COUNT(*) as total FROM users WHERE 1=1";
        $params = [];

        if (!empty($keyword)) {
            $sql .= " AND (full_name LIKE ? OR email LIKE ?)";
            $params[] = "%$keyword%";
            $params[] = "%$keyword%";
        }

        if (!empty($role)) {
            $sql .= " AND role = ?";
            $params[] = $role;
        }

        $result = $this->db->query($sql, $params)->fetch();
        return $result ? (int)$result['total'] : 0;
    }
}