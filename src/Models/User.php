<?php

namespace App\Models;

use App\Core\Model;

class User extends Model
{
    public function all()
    {
        return $this->db->query("SELECT id, full_name, email, role, created_at FROM users ORDER BY created_at DESC")->fetchAll();
    }

    public function find($id)
    {
        return $this->db->query("SELECT id, full_name, email, role FROM users WHERE id = :id", ['id' => $id])->fetch();
    }

    public function findByEmail($email)
    {
        return $this->db->query("SELECT * FROM users WHERE email = :email", ['email' => $email])->fetch();
    }

    public function findByEmailAndNotId($email, $id)
    {
        return $this->db->query(
            "SELECT id FROM users WHERE email = :email AND id != :id",
            ['email' => $email, 'id' => $id]
        )->fetch();
    }

    public function create($data)
    {
        // Code cũ của bạn có thể đang dùng bindParam rời rạc
        // Hãy đổi sang dạng mảng dynamic này cho tiện:
        
        $sql = "INSERT INTO users (full_name, email, password, role, status, created_at) 
                VALUES (:full_name, :email, :password, :role, :status, NOW())";
        
        // Đảm bảo data có đủ key, nếu thiếu thì gán mặc định
        $params = [
            'full_name' => $data['full_name'],
            'email'     => $data['email'],
            'password'  => $data['password'],
            'role'      => $data['role'],
            'status'    => $data['status'] ?? 1
        ];

        return $this->db->query($sql, $params);
    }

    public function update($id, $data)
    {
        // 1. Nếu không có dữ liệu gì để sửa thì return luôn
        if (empty($data)) {
            return true; 
        }

        // 2. Tạo câu query động: UPDATE users SET col1=:col1, col2=:col2 WHERE id=:id
        $fields = [];
        foreach ($data as $key => $value) {
            // Chỉ thêm các trường có trong mảng $data
            $fields[] = "$key = :$key";
        }

        $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE id = :id";
        
        // 3. Thêm ID vào mảng data để bind vào tham số :id trong câu SQL
        $data['id'] = $id; 
        
        // 4. Thực thi
        return $this->db->query($sql, $data);
    }

    public function delete($id)
    {
        return $this->db->query("DELETE FROM users WHERE id = :id", ['id' => $id]);
    }

    public function getPaginated($keyword = '', $role = '', $limit = 10, $offset = 0)
    {
        $sql = "SELECT * FROM users WHERE 1=1";
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

        $sql .= " ORDER BY id DESC LIMIT " . (int)$limit . " OFFSET " . (int)$offset;

        return $this->db->query($sql, $params)->fetchAll();
    }

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