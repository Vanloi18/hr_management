<?php

namespace App\Models;

use App\Core\Model;

class Report extends Model
{
    public function getTotalOpenPositions()
    {
        return $this->db->query("SELECT COUNT(*) as count FROM positions WHERE status = 'open'")->fetch()['count'];
    }

    public function getTotalCandidates()
    {
        return $this->db->query("SELECT COUNT(*) as count FROM candidates")->fetch()['count'];
    }

    public function getTotalRecruiters()
    {
        return $this->db->query("SELECT COUNT(*) as count FROM recruiters")->fetch()['count'];
    }

    public function getCvsByStatus()
    {
        return $this->db->query("SELECT status, COUNT(*) as count FROM candidates GROUP BY status")->fetchAll();
    }

    public function getPositionsByField()
    {
        return $this->db->query("
            SELECT f.field_name, COUNT(p.id) as count
            FROM positions p
            JOIN fields f ON p.field_id = f.id
            GROUP BY f.field_name
            ORDER BY count DESC
        ")->fetchAll();
    }

    /**
     * LẤY KPI MỚI: Đếm tổng số nhân viên đang "active"
     */
    public function getTotalActiveEmployees()
    {
        return $this->db->query("SELECT COUNT(*) as count FROM employees WHERE status = 'active'")->fetch()['count'];
    }

    /**
     * LẤY DATA MỚI: Đếm nhân viên theo từng phòng ban
     */
    public function getEmployeesByDepartment()
    {
        // Dùng LEFT JOIN phòng trường hợp NV chưa có phòng ban (NULL)
        // Dùng COALESCE để đổi tên phòng ban NULL thành 'Chưa phân loại'
        $sql = "
            SELECT 
                COALESCE(d.name, 'Chưa phân loại') AS department_name, 
                COUNT(e.id) as count
            FROM 
                employees AS e
            LEFT JOIN 
                departments AS d ON e.department_id = d.id
            GROUP BY 
                department_name
            ORDER BY
                count DESC
        ";
        return $this->db->query($sql)->fetchAll();
    }
}