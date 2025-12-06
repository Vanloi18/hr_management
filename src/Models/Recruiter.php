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
    public function getPaginated($keyword = '', $limit = 10, $offset = 0)
    {
        // Câu lệnh SQL lấy dữ liệu từ bảng recruiters
        $sql = "SELECT * FROM recruiters WHERE 1=1";
        $params = [];

        if (!empty($keyword)) {
            // SỬA LỖI Ở ĐÂY: dùng contact_person thay vì contact_name
            $sql .= " AND (company_name LIKE ? OR contact_person LIKE ? OR email LIKE ?)";
            $keywordParam = "%$keyword%";
            $params[] = $keywordParam;
            $params[] = $keywordParam;
            $params[] = $keywordParam;
        }

        $sql .= " ORDER BY id DESC LIMIT " . (int)$limit . " OFFSET " . (int)$offset;

        return $this->db->query($sql, $params)->fetchAll();
    }

    /**
     * Đếm tổng số bản ghi để tính trang
     */
    public function countAll($keyword = '')
    {
        $sql = "SELECT COUNT(*) as total FROM recruiters WHERE 1=1";
        $params = [];

        if (!empty($keyword)) {
            // SỬA LỖI Ở ĐÂY: dùng contact_person
            $sql .= " AND (company_name LIKE ? OR contact_person LIKE ? OR email LIKE ?)";
            $keywordParam = "%$keyword%";
            $params[] = $keywordParam;
            $params[] = $keywordParam;
            $params[] = $keywordParam;
        }

        $result = $this->db->query($sql, $params)->fetch();
        return $result ? (int)$result['total'] : 0;
    }
    public function delete($id)
    {
        return $this->db->query("DELETE FROM recruiters WHERE id = :id", ['id' => $id]);
    }

    // Thêm vào class Recruiter
    public function getAllForExport($keyword = '')
    {
        $sql = "SELECT * FROM recruiters WHERE 1=1";
        $params = [];

        if (!empty($keyword)) {
            $sql .= " AND (company_name LIKE ? OR email LIKE ? OR contact_person LIKE ?)";
            $keywordParam = "%$keyword%";
            $params[] = $keywordParam;
            $params[] = $keywordParam;
            $params[] = $keywordParam;
        }

        $sql .= " ORDER BY id DESC"; // Lấy tất cả
        return $this->db->query($sql, $params)->fetchAll();
    }
}