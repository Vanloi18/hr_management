<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Report; // <-- Import Model

class StatisticsController extends Controller
{
    public function index()
    {
        $this->checkAuthentication();

        $reportModel = new Report(); 

        // === 1. LẤY DỮ LIỆU TUYỂN DỤNG (CŨ) ===
        $totalOpenPositions = $reportModel->getTotalOpenPositions();
        $totalCandidates = $reportModel->getTotalCandidates();
        $totalRecruiters = $reportModel->getTotalRecruiters();
        $cvsByStatusRaw = $reportModel->getCvsByStatus();
        $positionsByFieldRaw = $reportModel->getPositionsByField();

        $cvStatusLabels = array_column($cvsByStatusRaw, 'status');
        $cvStatusData = array_column($cvsByStatusRaw, 'count');
        $cvStatusLabels = array_map('ucfirst', $cvStatusLabels);
        
        $posFieldLabels = array_column($positionsByFieldRaw, 'field_name');
        $posFieldData = array_column($positionsByFieldRaw, 'count');
        
        // === 2. LẤY DỮ LIỆU NHÂN SỰ (MỚI) ===
        $totalActiveEmployees = $reportModel->getTotalActiveEmployees();
        $employeesByDeptRaw = $reportModel->getEmployeesByDepartment();
        
        $empDeptLabels = array_column($employeesByDeptRaw, 'department_name');
        $empDeptData = array_column($employeesByDeptRaw, 'count');
        

        // === 3. CHUẨN BỊ DATA CHO VIEW ===
        $data = [
            'title' => 'Thống kê - Báo cáo',
            
            'totalOpenPositions' => $totalOpenPositions,
            'totalCandidates' => $totalCandidates,
            'totalRecruiters' => $totalRecruiters,
            'totalActiveEmployees' => $totalActiveEmployees, 

            'cvStatusLabels' => $cvStatusLabels,
            'cvStatusData' => $cvStatusData,
            'posFieldLabels' => $posFieldLabels,
            'posFieldData' => $posFieldData,
            
            'empDeptLabels' => $empDeptLabels,
            'empDeptData' => $empDeptData,

            'cvsByStatus' => $cvsByStatusRaw,
            'positionsByField' => $positionsByFieldRaw 
        ];

        // 🔥 LOGIC PJAX MỚI
        if (isAjaxRequest()) {
            return partial('statistics/index', $data);
        }
        
        return view('statistics/index', $data);
    }
}