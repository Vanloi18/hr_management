<?php

namespace App\Models;

use App\Core\Model;

class Setting extends Model
{
    /**
     * Lấy tất cả cài đặt và gom thành mảng assoc [key => value]
     * Ví dụ: ['company_name' => 'ABC', 'company_email' => '...']
     */
    public function getAllSettings()
    {
        $rows = $this->db->query("SELECT setting_key, setting_value FROM settings")->fetchAll();
        
        $settings = [];
        foreach ($rows as $row) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }
        
        return $settings;
    }

    /**
     * Lấy giá trị của 1 key cụ thể
     */
    public function getValue($key)
    {
        $result = $this->db->query("SELECT setting_value FROM settings WHERE setting_key = :key", ['key' => $key])->fetch();
        return $result ? $result['setting_value'] : null;
    }

    /**
     * Cập nhật hoặc Thêm mới cài đặt
     */
    public function updateSetting($key, $value)
    {
        // Kiểm tra xem key đã tồn tại chưa
        $exists = $this->db->query("SELECT id FROM settings WHERE setting_key = :key", ['key' => $key])->fetch();

        if ($exists) {
            $sql = "UPDATE settings SET setting_value = :value WHERE setting_key = :key";
        } else {
            $sql = "INSERT INTO settings (setting_key, setting_value) VALUES (:key, :value)";
        }

        return $this->db->query($sql, ['key' => $key, 'value' => $value]);
    }
}