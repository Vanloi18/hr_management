<?php
namespace App\Core;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ExcelExporter
{
    /**     * Xuất dữ liệu ra file Excel
     * @param array $data Dữ liệu (Mảng 2 chiều)
     * @param array $headers Tiêu đề cột (Mảng 1 chiều)
     * @param string $fileName Tên file (không cần đuôi .xlsx)
     * @param string $sheetTitle Tên Sheet
     */
    public static function export(array $data, array $headers, string $fileName, string $sheetTitle = 'Data')
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle($sheetTitle);

        // 1. Set Header
        $sheet->fromArray([$headers], NULL, 'A1');

        // 2. Set Data
        if (!empty($data)) {
            $sheet->fromArray($data, NULL, 'A2');
        }

        // 3. Styling
        $lastRow = count($data) + 1;
        $lastColChar = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($headers));
        
        $styleRange = "A1:{$lastColChar}{$lastRow}";
        
        // Border
        $sheet->getStyle($styleRange)->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
        ]);

        // Header Style
        $sheet->getStyle("A1:{$lastColChar}1")->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '0d6efd']], // Màu xanh Primary
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
        ]);

        // Auto Size Columns
        foreach (range(1, count($headers)) as $col) {
            $sheet->getColumnDimensionByColumn($col)->setAutoSize(true);
        }

        // 4. Output
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $fileName . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}