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
        return $this->db->query("INSERT INTO departments (name) VALUES (:name)", ['name' => $name]);
    }

    public function update($id, $name)
    {
        return $this->db->query("UPDATE departments SET name = :name WHERE id = :id", ['name' => $name, 'id' => $id]);
    }

    public function delete($id)
    {
        return $this->db->query("DELETE FROM departments WHERE id = :id", ['id' => $id]);
    }
}