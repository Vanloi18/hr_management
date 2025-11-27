<?php

namespace App\Core;

/**
 * Lớp Model "cha"
 * Cung cấp kết nối CSDL ($this->db) cho tất cả các Model con
 */
abstract class Model
{
    /** @var Database $db */
    protected Database $db;

    public function __construct()
    {
        // Tự động lấy thể hiện (instance) CSDL
        $this->db = Database::getInstance();
    }
}