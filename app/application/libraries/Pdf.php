<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . '/third_party/vendor/autoload.php';

class Pdf {
    public function create($html, $filename = 'business_report.pdf') {
        $mpdf = new \Mpdf\Mpdf();

        // Write the HTML to the PDF
        $mpdf->WriteHTML($html);

        // Output the PDF as a download
        $mpdf->Output($filename, 'D'); // 'D' forces download
    }
}
