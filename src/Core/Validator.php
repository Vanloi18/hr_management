<?php

namespace App\Core;

use App\Core\Database;

/**
 * Lớp Validator (Bộ kiểm tra lỗi)
 * * Xử lý kiểm tra dữ liệu (validation) cho form, 
 * tự động flash lỗi và redirect nếu thất bại.
 */
class Validator
{
    /** @var Database $db */
    protected $db;

    /** @var array $errors */
    protected $errors = [];

    /** @var array $validatedData */
    protected $validatedData = [];

    /** @var array $data */
    protected $data = [];

    public function __construct()
    {
        // Validator cần CSDL để kiểm tra quy tắc 'unique'
        $this->db = Database::getInstance();
    }

    /**
     * Phương thức "thần thánh" - chạy toàn bộ kiểm tra
     *
     * @param array $data Dữ liệu (thường là $_POST)
     * @param array $allRules Các quy tắc (ví dụ: ['email' => 'required|email'])
     * @return bool True nếu thành công, False nếu thất bại (và tự redirect)
     */
    public function validate(array $data, array $allRules)
    {
        // Reset
        $this->errors = [];
        $this->validatedData = [];
        $this->data = $data;

        // 1. Lặp qua từng TRƯỜNG (field)
        foreach ($allRules as $field => $rulesString) {
            $value = $data[$field] ?? null;
            $rules = explode('|', $rulesString);

            // 2. Logic "Tùy chọn" (Optional):
            // Nếu một trường KHÔNG "required" VÀ nó rỗng,
            // chúng ta không cần kiểm tra các quy tắc khác (như min, max, email)
            if (!in_array('required', $rules) && (empty($value) || (is_string($value) && trim($value) === ''))) {
                $this->validatedData[$field] = $value; // Dữ liệu sạch (vì nó rỗng và được phép)
                continue; // Đi đến trường tiếp theo
            }

            // 3. Lặp qua từng QUY TẮC (rule) của trường đó
            $fieldPassed = true;
            foreach ($rules as $rule) {
                // Tách quy tắc (ví dụ: 'min:3')
                $param = null;
                if (strpos($rule, ':') !== false) {
                    list($rule, $param) = explode(':', $rule, 2);
                }

                $methodName = 'validate_' . $rule;

                // Kiểm tra xem chúng ta có code cho quy tắc này không
                if (!method_exists($this, $methodName)) {
                    // (Trong dự án thực tế, nên ném ra Exception)
                    continue; 
                }

                // 4. CHẠY QUY TẮC
                // Nếu quy tắc trả về false (thất bại), dừng kiểm tra
                if ($this->{$methodName}($field, $value, $param) === false) {
                    $fieldPassed = false;
                    break; // Dừng lặp (các quy tắc khác của trường này)
                }
            }

            // 5. Nếu trường này vượt qua TẤT CẢ quy tắc
            if ($fieldPassed) {
                $this->validatedData[$field] = $value;
            }
        }

        // 6. KIỂM TRA KẾT QUẢ CUỐI CÙNG
        if (!empty($this->errors)) {
            $this->fail(); // Tự động flash & redirect
            return false;
        }

        return true; // Thành công!
    }

    /**
     * Lấy mảng lỗi
     */
    public function errors()
    {
        return $this->errors;
    }

    /**
     * Lấy mảng dữ liệu "sạch" (chỉ chứa các trường đã qua kiểm tra)
     */
    public function validatedData()
    {
        return $this->validatedData;
    }

    /**
     * Kiểm tra xem đây có phải là một request AJAX không
     */
    protected function isAjaxRequest()
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) 
               && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }

    protected function fail()
    {
        // Gửi lỗi sang cho View
        $_SESSION['_flash']['errors'] = $this->errors;
        // Gửi dữ liệu cũ sang cho View
        $_SESSION['_flash']['old'] = $this->data;
        
        // 🔥 SỬA LẠI DÒNG NÀY:
        // (Xóa $this->)
        if (isAjaxRequest()) {
            return; // Nếu LÀ AJAX, thì DỪNG LẠI
        }
        
        // Nếu không phải AJAX, redirect như cũ
        redirect($_SERVER['HTTP_REFERER'] ?? '/');
    }

    /**
     * Thêm một thông báo lỗi vào mảng errors
     */
    protected function addError($field, $rule, $message)
    {
        // $this->errors['email'] = "Email là bắt buộc."
        $this->errors[$field] = $message;
    }

    /*
    |--------------------------------------------------------------------------
    | CÁC HÀM QUY TẮC (RULE METHODS)
    |--------------------------------------------------------------------------
    | (Trả về true nếu Pass, false nếu Fail)
    */

    /**
     * Quy tắc: Bắt buộc
     */
    protected function validate_required($field, $value, $param)
    {
        if (empty($value) || (is_string($value) && trim($value) === '')) {
            $this->addError($field, 'required', "Trường này là bắt buộc.");
            return false;
        }
        return true;
    }

    /**
     * Quy tắc: Email
     */
    protected function validate_email($field, $value, $param)
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->addError($field, 'email', "Trường này phải là một email hợp lệ.");
            return false;
        }
        return true;
    }

    /**
     * Quy tắc: Độ dài tối thiểu
     * (ví dụ: 'min:6')
     */
    protected function validate_min($field, $value, $param)
    {
        if (strlen(trim($value)) < $param) {
            $this->addError($field, 'min', "Trường này phải có ít nhất {$param} ký tự.");
            return false;
        }
        return true;
    }

    /**
     * Quy tắc: Khớp với một trường khác
     * (ví dụ: 'matches:password')
     */
    protected function validate_matches($field, $value, $param)
    {
        // $param là tên của trường kia (ví dụ: 'password')
        $otherValue = $this->data[$param] ?? null;

        if ($value !== $otherValue) {
            $this->addError($field, 'matches', "Trường này không khớp với trường {$param}.");
            return false;
        }
        return true;
    }

    /**
     * Quy tắc: Độc nhất trong CSDL
     * (ví dụ: 'unique:users' hoặc 'unique:users,10')
     */
    protected function validate_unique($field, $value, $param)
    {
        // $param có dạng: table[,exceptId]
        // Ví dụ 1 (Create): 'unique:users' (Chỉ có table)
        // Ví dụ 2 (Update): 'unique:users,15' (Table 'users', ngoại trừ ID 15)
        
        $exceptId = null;
        $table = $param;

        if (strpos($param, ',') !== false) {
            list($table, $exceptId) = explode(',', $param, 2);
        }

        // Dùng ` (dấu huyền) để bảo vệ tên bảng/cột
        $sql = "SELECT id FROM `{$table}` WHERE `{$field}` = :value";
        $params = ['value' => $value];

        if ($exceptId) {
            $sql .= " AND id != :id";
            $params['id'] = $exceptId;
        }
        
        $result = $this->db->query($sql, $params)->fetch();

        if ($result) {
            $this->addError($field, 'unique', "Giá trị '{$value}' đã được sử dụng.");
            return false;
        }
        return true;
    }
}