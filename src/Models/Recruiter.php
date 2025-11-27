<?php

namespace App\Models;

use App\Core\Model;

class Recruiter extends Model
{
    public function all()
    {
        return $this->db->query("SELECT * FROM recruiters ORDER BY company_name ASC")->fetchAll();
    }

    // Dùng cho dropdown
    public function allForDropdown()
    {
        return $this->db->query("SELECT id, company_name FROM recruiters ORDER BY company_name ASC")->fetchAll();
    }

    public function find($id)
    {
        return $this->db->query("SELECT * FROM recruiters WHERE id = :id", ['id' => $id])->fetch();
    }

    public function create($data)
    {
        return $this->db->query(
            "INSERT INTO recruiters (company_name, contact_person, email, phone, address) 
             VALUES (:company_name, :contact_person, :email, :phone, :address)",
            [
                'company_name' => $data['company_name'],
                'contact_person' => $data['contact_person'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'address' => $data['address']
            ]
        );
    }

    public function update($id, $data)
    {
        return $this->db->query(
            "UPDATE recruiters SET 
                company_name = :company_name, 
                contact_person = :contact_person, 
                email = :email, 
                phone = :phone, 
                address = :address
             WHERE id = :id",
            [
                'company_name' => $data['company_name'],
                'contact_person' => $data['contact_person'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'address' => $data['address'],
                'id' => $id
            ]
        );
    }

    public function delete($id)
    {
        return $this->db->query("DELETE FROM recruiters WHERE id = :id", ['id' => $id]);
    }
}