<?php

namespace App\Core;

use PDO; // Import lớp PDO từ "global namespace"

/**
 * Lớp Database (Singleton Pattern)
 * Quản lý kết nối PDO duy nhất
 */
class Database
{
    /** @var PDO|null $connection */
    public ?PDO $connection = null;
    
    /** @var Database|null $instance */
    private static ?Database $instance = null;

    /**
     * Hàm private __construct() ngăn việc "new Database()" từ bên ngoài.
     */
    private function __construct()
    {
        // Tạo chuỗi DSN (Data Source Name)
        $dsn = 'mysql:' . http_build_query([
            'host' => DB_HOST,
            'port' => DB_PORT,
            'dbname' => DB_NAME,
            'charset' => DB_CHARSET
        ], '', ';');

        // Cấu hình PDO
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Ném Exception khi có lỗi SQL
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Trả về dạng mảng associative
            PDO::ATTR_EMULATE_PREPARES => false, // Dùng "native" prepared statements
        ];

        try {
            $this->connection = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (\PDOException $e) {
            // Dừng ứng dụng nếu không thể kết nối CSDL
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    /**
     * Phương thức "static" để lấy thể hiện (instance) duy nhất của lớp.
     */
    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Hàm "query" tiện ích (helper)
     * Dùng để chạy truy vấn CÓ sử dụng Prepared Statements.
     *
     * @param string $query
     * @param array $params
     * @return \PDOStatement
     */
    public function query(string $query, array $params = []): \PDOStatement
    {
        $stmt = $this->connection->prepare($query);
        $stmt->execute($params);
        return $stmt;
    }
}