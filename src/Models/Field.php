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
}