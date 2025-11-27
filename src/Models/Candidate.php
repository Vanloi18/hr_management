<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class Candidate extends Model
{
    /**
     * Đếm tổng số CV (có tìm kiếm)
     */
    public function countAll($search)
    {
        $sqlWhere = "";
        $params = [];
        if ($search) {
            $sqlWhere = " WHERE c.full_name LIKE :search ";
            $params['search'] = "%{$search}%";
        }

        return $this->db->query("SELECT COUNT(*) as count FROM candidates c" . $sqlWhere, $params)->fetch()['count'];
    }

    /**
     * Lấy CV (có tìm kiếm và phân trang)
     */
    public function allWithDetails($search, $limit, $offset)
    {
        $sqlWhere = "";
        $params = [];
        if ($search) {
            $sqlWhere = " WHERE c.full_name LIKE :search ";
            $params['search'] = "%{$search}%";
        }

        $sql = "
            SELECT 
                c.id, c.full_name, c.email, c.phone, c.status, c.applied_at,
                c.cv_file_path, p.title AS position_title
            FROM candidates AS c
            JOIN positions AS p ON c.position_id = p.id
            {$sqlWhere}
            ORDER BY c.applied_at DESC
            LIMIT :limit OFFSET :offset
        ";
        
        $stmt = $this->db->connection->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function find($id)
    {
        return $this->db->query("SELECT * FROM candidates WHERE id = :id", ['id' => $id])->fetch();
    }

    public function findByEmailAndNotId($email, $id = null)
    {
        $sql = "SELECT id FROM candidates WHERE email = :email";
        $params = ['email' => $email];
        if ($id) {
            $sql .= " AND id != :id";
            $params['id'] = $id;
        }
        return $this->db->query($sql, $params)->fetch();
    }

    public function create($data)
    {
        return $this->db->query(
            "INSERT INTO candidates (position_id, full_name, email, phone, status, notes, cv_file_path) 
             VALUES (:pid, :name, :email, :phone, :status, :notes, :path)",
            [
                'pid' => $data['position_id'],
                'name' => $data['full_name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'status' => $data['status'],
                'notes' => $data['notes'],
                'path' => $data['cv_file_path']
            ]
        );
    }

    public function update($id, $data)
    {
        return $this->db->query(
            "UPDATE candidates SET 
                position_id = :pid, full_name = :name, email = :email, 
                phone = :phone, status = :status, notes = :notes, cv_file_path = :path
             WHERE id = :id",
            [
                'pid' => $data['position_id'],
                'name' => $data['full_name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'status' => $data['status'],
                'notes' => $data['notes'],
                'path' => $data['cv_file_path'],
                'id' => $id
            ]
        );
    }

    public function delete($id)
    {
        return $this->db->query("DELETE FROM candidates WHERE id = :id", ['id' => $id]);
    }
}