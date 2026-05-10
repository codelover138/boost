<?php
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
// Add a page
$pdf->AddPage();
$html = "<h1>Test Page</h1>";
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output('example_001.pdf', 'I');