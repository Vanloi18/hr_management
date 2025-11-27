<?php

namespace App\Models;

use App\Core\Model;

class Field extends Model
{
    public function all()
    {
        return $this->db->query("SELECT * FROM fields ORDER BY field_name ASC")->fetchAll();
    }
    
    // Dùng cho dropdown
    public function allForDropdown()
    {
        return $this->db->query("SELECT id, field_name FROM fields ORDER BY field_name ASC")->fetchAll();
    }

    public function find($id)
    {
        return $this->db->query("SELECT * FROM fields WHERE id = :id", ['id' => $id])->fetch();
    }

    public function findByName($name)
    {
        return $this->db->query("SELECT id FROM fields WHERE field_name = :name", ['name' => $name])->fetch();
    }

    public function findByNameAndNotId($name, $id)
    {
        return $this->db->query(
            "SELECT id FROM fields WHERE field_name = :name AND id != :id",
            ['name' => $name, 'id' => $id]
        )->fetch();
    }

    public function create($data)
    {
        return $this->db->query(
            "INSERT INTO fields (field_name, description) VALUES (:name, :desc)",
            ['name' => $data['field_name'], 'desc' => $data['description']]
        );
    }

    public function update($id, $data)
    {
        return $this->db->query(
            "UPDATE fields SET field_name = :name, description = :desc WHERE id = :id",
            ['name' => $data['field_name'], 'desc' => $data['description'], 'id' => $id]
        );
    }

    public function delete($id)
    {
        return $this->db->query("DELETE FROM fields WHERE id = :id", ['id' => $id]);
    }

    public function getPaginated($keyword = '', $limit = 10, $offset = 0)
    {
        // Subquery để đếm số tin tuyển dụng thuộc lĩnh vực này
        // Giả định bảng tin tuyển dụng là 'positions' và khóa ngoại là 'field_id'
        $sql = "SELECT f.*, 
                       (SELECT COUNT(*) FROM positions p WHERE p.field_id = f.id) as position_count
                FROM fields f 
                WHERE 1=1";
        
        $params = [];

        // Tìm kiếm theo tên lĩnh vực hoặc mô tả
        if (!empty($keyword)) {
            $sql .= " AND (f.field_name LIKE ? OR f.description LIKE ?)";
            $keywordParam = "%$keyword%";
            $params[] = $keywordParam;
            $params[] = $keywordParam;
        }

        $sql .= " ORDER BY f.id DESC LIMIT " . (int)$limit . " OFFSET " . (int)$offset;

        return $this->db->query($sql, $params)->fetchAll();
    }

    /**
     * Đếm tổng số lĩnh vực
     */
    public function countAll($keyword = '')
    {
        $sql = "SELECT COUNT(*) as total FROM fields WHERE 1=1";
        $params = [];

        if (!empty($keyword)) {
            $sql .= " AND (field_name LIKE ? OR description LIKE ?)";
            $keywordParam = "%$keyword%";
            $params[] = $keywordParam;
            $params[] = $keywordParam;
        }

        $result = $this->db->query($sql, $params)->fetch();
        return $result ? (int)$result['total'] : 0;
    }
}