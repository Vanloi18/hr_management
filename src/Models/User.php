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

    public function update($id, $data)
    {
        $params = [
            'full_name' => $data['full_name'],
            'email' => $data['email'],
            'role' => $data['role'],
            'id' => $id
        ];

        if (!empty($data['password'])) {
            $params['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
            $sql = "UPDATE users SET full_name = :full_name, email = :email, role = :role, password = :password WHERE id = :id";
        } else {
            $sql = "UPDATE users SET full_name = :full_name, email = :email, role = :role WHERE id = :id";
        }

        return $this->db->query($sql, $params);
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