<?php

//require_once('api/application/libraries/pdf/tcpdf/tcpdf.php');

class Mytcpdf
{
    public function __construct($params = null)
    {
        $this->CI =& get_instance();
        $this->CI->load->library('pdf/tcpdf/tcpdf');
    }

    public function Header()
    {
        $this->CI->tcpdf->setJPEGQuality(90);
        //$this->CI->tcpdf->Image('api/resources/images/temp_logo.png', 15, 30, 40, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
    }

    public function Footer()
    {
        $this->CI->tcpdf->SetY(-15);
        $this->CI->tcpdf->SetFont(PDF_FONT_NAME_MAIN, 'I', 8);
        //$this->CI->tcpdf->Cell(0, 10, 'footer text', 0, false, 'C');
    }

    public function CreateTextBox($textval, $x = 0, $y, $width = 0, $height = 10, $fontsize = 10, $fontstyle = '', $align = 'L')
    {
        $this->CI->tcpdf->SetXY($x + 15, $y); // 20 = margin left
        $this->CI->tcpdf->SetFont(PDF_FONT_NAME_MAIN, $fontstyle, $fontsize);
        $this->CI->tcpdf->Cell($width, $height, $textval, 0, false, $align);
    }

    public function AddTextBox($params)
    {
        $settings = array();

        $settings['x'] = isset($params['x']) ? $settings['x'] = $params['x'] : $settings['x'] = 0;
        $settings['y'] = isset($params['y']) ? $settings['y'] = $params['y'] : $settings['y'] = 0;

        $settings['width'] = isset($params['width']) ? $settings['width'] = $params['width'] : $settings['width'] = 0;
        $settings['height'] = isset($params['height']) ? $settings['height'] = $params['height'] : $settings['height'] = 0;

        $settings['fontsize'] = isset($params['fontsize']) ? $settings['fontsize'] = $params['fontsize'] : $settings['fontsize'] = 10;
        $settings['fontstyle'] = isset($params['fontstyle']) ? $settings['fontstyle'] = $params['fontstyle'] : $settings['fontstyle'] = '';

        $settings['align'] = isset($params['align']) ? $settings['align'] = $params['align'] : $settings['align'] = 'L';

        $this->CI->tcpdf->SetXY($settings['x'] + 15, $settings['y']); // 15 = margin left
        $this->CI->tcpdf->SetFont(PDF_FONT_NAME_MAIN, $settings['fontstyle'], $settings['fontsize']);
        $this->CI->tcpdf->Cell($settings['width'], $settings['height'], $params['text'], 0, false, $settings['align']);
    }

    public function write_htmlcell($params)
    {
        $settings['w'] = isset($params['w']) ? $settings['w'] = $params['w'] : $settings['w'] = 0;
        $settings['h'] = isset($params['h']) ? $settings['h'] = $params['h'] : $settings['h'] = 0;
        $settings['x'] = isset($params['x']) ? $settings['x'] = $params['x'] : $settings['x'] = 0;
        $settings['y'] = isset($params['y']) ? $settings['y'] = $params['y'] : $settings['y'] = 0;
        $settings['html'] = isset($params['html']) ? $settings['html'] = $params['html'] : $settings['html'] = '';
        $settings['border'] = isset($params['border']) ? $settings['border'] = $params['border'] : $settings['border'] = 0;
        $settings['ln'] = isset($params['ln']) ? $settings['ln'] = $params['ln'] : $settings['ln'] = 0;
        $settings['fill'] = isset($params['fill']) ? $settings['fill'] = $params['fill'] : $settings['fill'] = false;
        $settings['reseth'] = isset($params['reseth']) ? $settings['reseth'] = $params['reseth'] : $settings['reseth'] = true;
        $settings['align'] = isset($params['align']) ? $settings['align'] = $params['align'] : $settings['align'] = '';
        $settings['autopadding'] = isset($params['autopadding']) ? $settings['autopadding'] = $params['autopadding'] : $settings['autopadding'] = true;

        $this->CI->tcpdf->writeHTMLCell($settings['w'], $settings['h'], $settings['x'], $settings['y'], $settings['html'], $settings['border'], $settings['ln'], $settings['fill'], $settings['reseth'], $settings['align'], $settings['autopadding']);
    }
}