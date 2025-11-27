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
        return $this->db->query(
            "INSERT INTO positions (title, recruiter_id, field_id, description, requirements, status, created_by_user_id) 
             VALUES (:title, :recruiter_id, :field_id, :description, :requirements, :status, :created_by_user_id)",
            [
                'title' => $data['title'],
                'recruiter_id' => $data['recruiter_id'],
                'field_id' => $data['field_id'],
                'description' => $data['description'],
                'requirements' => $data['requirements'],
                'status' => $data['status'],
                'created_by_user_id' => $data['created_by_user_id']
            ]
        );
    }

    public function update($id, $data)
    {
        return $this->db->query(
            "UPDATE positions SET 
                title = :title, 
                recruiter_id = :recruiter_id, 
                field_id = :field_id, 
                description = :description, 
                requirements = :requirements, 
                status = :status 
             WHERE id = :id",
            [
                'title' => $data['title'],
                'recruiter_id' => $data['recruiter_id'],
                'field_id' => $data['field_id'],
                'description' => $data['description'],
                'requirements' => $data['requirements'],
                'status' => $data['status'],
                'id' => $id
            ]
        );
    }

    public function delete($id)
    {
        return $this->db->query("DELETE FROM positions WHERE id = :id", ['id' => $id]);
    }
}