<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'third_party/PHPExcel/Classes/PHPExcel.php';

class Excel {

    public function generate_excel($data) {
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $sheet = $objPHPExcel->getActiveSheet();
        $sheet->setTitle('Business Report');

        $currency_symbol = $data['currency']['currency_symbol'];

        // Set Header Row
        $sheet->setCellValue('A1', 'Report Period');
        $col = 'B';
        foreach ($data['get_month_year_revenue'] as $revenue) {
            $sheet->setCellValue($col . '1', ucwords($revenue['period']));
            $col++;
        }
        $sheet->setCellValue($col . '1', 'Total');

        // Style the Header Row
        $sheet->getStyle('A1:' . $col . '1')->getFont()->setBold(true);

        // Revenue Row
        $row = 2;
        $sheet->setCellValue('A' . $row, 'Revenue');
        $col = 'B';
        $total_revenue = 0;
        foreach ($data['get_month_year_revenue'] as $revenue) {
            $total_revenue += $revenue['net_revenue'];
            $sheet->setCellValue($col . $row,  '');
            $col++;
        }
        $sheet->setCellValue($col . $row,  '');

        // Client Revenue Rows
        if (!empty($data['get_month_year_client_revenue'])) {
            foreach ($data['get_month_year_client_revenue'] as $client) {
                $row++;
                $sheet->setCellValue('A' . $row, '  ' . $client['client_name']); // Indent client name
                $col = 'B';
                $client_total = 0;
                foreach ($client['data'] as $val) {
                    $client_total += $val['net_revenue'];
                    $sheet->setCellValue($col . $row,number_format($val['net_revenue']));
                    $col++;
                }
                $sheet->setCellValue($col . $row, number_format($client_total));
            }
        }
        
        $row++;
        $sheet->setCellValue('A' . $row, 'Total');
        $col = 'B';
        $total_revenue = 0;
        foreach ($data['get_month_year_revenue'] as $revenue) {
            $total_revenue += $revenue['net_revenue'];
            $sheet->setCellValue($col . $row,  number_format($revenue['net_revenue']));
            $col++;
        }
        $sheet->setCellValue($col . $row,  number_format($total_revenue));

        // Add a Horizontal Divider After Revenue Section
        $row++;
        $sheet->setCellValue('A' . $row, '--------------------------------------');
        $row++;

        // Expense Row
        $sheet->setCellValue('A' . $row, 'Expense');
        $col = 'B';
        $sheet->getStyle('A1:' . $col . '1')->getFont()->setBold(true);
        $total_expense = 0;
        foreach ($data['get_month_year_expense'] as $expense) {
            $total_expense += $expense['net_expense'];
            $sheet->setCellValue($col . $row,  '');
            $col++;
        }
        $sheet->setCellValue($col . $row, '');

        // Category Expense Rows
        if (!empty($data['get_month_year_client_expense'])) {
            foreach ($data['get_month_year_client_expense'] as $category) {
                $row++;
                $sheet->setCellValue('A' . $row, '  ' . $category['category_name']); // Indent category name
                $col = 'B';
                $category_total = 0;
                foreach ($category as $key=>$val) {
                     if($category['category_name'] !== $category[$key]) $category_total += $category[$key];
                    $category_vale = (($category['category_name'] !== $category[$key]) ? $category[$key] :0);
                    $sheet->setCellValue($col . $row, number_format($category_vale));
                    if( $category['category_name'] !== $category[$key]) $col++;
                    
                }
                $sheet->setCellValue($col . $row,  number_format($category_total));
            }
        }
        
        // Expense total Row
        $row++;
        $sheet->setCellValue('A' . $row, 'Total');
        $col = 'B';
        $sheet->getStyle('A1:' . $col . '1')->getFont()->setBold(true);
        $total_expense = 0;
        foreach ($data['get_month_year_expense'] as $expense) {
            $total_expense += $expense['net_expense'];
            $sheet->setCellValue($col . $row,  number_format($expense['net_expense']));
            $col++;
        }
        $sheet->setCellValue($col . $row, number_format($total_expense));


        // Add a Horizontal Divider After Expense Section
        $row++;
        $sheet->setCellValue('A' . $row, '--------------------------------------');
        $row++;

        // Profit Row
        $sheet->setCellValue('A' . $row, 'Profit');
        $col = 'B';
        $expense_lookup = array_column($data['get_month_year_expense'], 'net_expense', 'period');
        $differences = [];
        $total_revenue = 0;
        $total_expense = 0;

        foreach ($data['get_month_year_revenue'] as $revenue) {
            $period = $revenue['period'];
            $net_revenue = $revenue['net_revenue'];
            $total_revenue += $net_revenue;
            $net_expense = $expense_lookup[$period] ?? 0;
            $total_expense += $net_expense;
            $differences[] = $net_revenue - $net_expense;
            $sheet->setCellValue($col . $row,number_format($net_revenue - $net_expense));
            $col++;
        }
        $profit = $total_revenue - $total_expense;
        $sheet->setCellValue($col . $row, number_format($profit));

        // Style the Headers and Sections
        $sheet->getStyle('A1')->getFont()->setBold(true);
        $sheet->getStyle('A2')->getFont()->setBold(true);
        $sheet->getStyle('A' . ($row))->getFont()->setBold(true);

        // Auto size columns
        foreach (range('A', $col) as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Output the Excel file
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="business_report.xls"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }
}
