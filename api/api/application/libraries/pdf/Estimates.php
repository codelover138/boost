<?php

class Estimates
{
    public $table_prefix;

    public function __construct($params = null)
    {
        $this->CI =& get_instance();
        $this->params = $params;

        $headers = $this->CI->regular->get_request_headers();
        $this->CI->load->library('db/switcher', array('account_name' => $headers['Account-Name']));
        $this->CI->switcher->account_db();

        $this->CI->load->model('generic_model');
        $this->table_prefix = $this->CI->config->item('db_table_prefix');
        $this->activities_params = array('table' => $this->table_prefix . 'activites', 'entity' => 'activity');
        $this->CI->load->library('regular');
        $this->CI->load->library('pdf/tcpdf/tcpdf');

        if (isset($this->params['model'])) :
            $this->CI->load->model($this->params['model']);
            $this->model = $this->params['model'];
        else :
            $this->model = 'generic_model';
        endif;
    }

    public function generate_pdf($ids, $output = 'F', $base_url = '', $echo_json = true)
    {
        $return = array('status' => 'ERROR');

        if (is_null($ids)) :
            $return['message'] = 'no estimate id(s) given';
            $return['input'] = $this->CI->regular->decode();
            $this->CI->regular->respond($return);
            return;
        endif;

        # create a PDF object
        $this->CI->load->library('pdf/mytcpdf');

        $this->CI->tcpdf->SetPrintHeader(false);
        $this->CI->tcpdf->SetPrintFooter(false);

        // set document (meta) information
        $this->CI->tcpdf->SetCreator(PDF_CREATOR);
        $this->CI->tcpdf->SetAuthor('BOOST ACCOUNTING');
        $this->CI->tcpdf->SetTitle('estimate');
        //$this->CI->tcpdf->SetSubject('TCPDF Tutorial');
        //$this->CI->tcpdf->SetKeywords('TCPDF, PDF, example, tutorial');

        $estimate_number = '';
        $original_ids = $ids;
        if (!is_array($ids)) :
            $ids = array(array('id' => $ids));
        endif;

        foreach ($ids as $entity) {
            $id = $entity['id'];

            $estimate_data_ = $this->estimate_data($id);

            if (!empty($estimate_data_)) {
                # get estimate and company data:
                $other_db_data = $estimate_data_[$id];
                $company_data = $this->company_data();

                $estimate_number .= '_' . $other_db_data->estimate_number;

                # add a page
                $this->CI->tcpdf->AddPage();

                $text_color = '#6d90a9';
                $text_color2 = '#35a2ef';
                $text_color3 = '#adc0cf';

                $header_image = <<<EOD
            <img src="$company_data->logo">
EOD;
                $this->CI->mytcpdf->write_htmlcell(array('html' => $header_image, 'align' => 'L', 'x' => 10, 'y' => 25, 'w' => 45));

                # estimate title / number
                $estimate_title = <<<EOD
            <div style="color:$text_color2;">
            <h3 style="font-weight:normal; line-height: normal;">
                <span style="color:$text_color;">ESTIMATE</span> #$other_db_data->estimate_number
            </h3>
            </div>
EOD;
                $this->CI->mytcpdf->write_htmlcell(array('html' => $estimate_title, 'align' => 'R'));

                # company info
                $company_info = <<<EOD
            <div style="font-size:8; font-weight:normal; color:$text_color;">
            <strong>$company_data->company_name</strong> <br>
            $company_data->address_line_1 <br>
            $company_data->address_line_2 <br>
            $company_data->city, $company_data->region_state, $company_data->zip <br>
            $company_data->country
            </div>
EOD;
                $this->CI->mytcpdf->write_htmlcell(array('html' => $company_info, 'align' => 'R', 'y' => 25));

                # estimate info
                $estimate_info = <<<EOD
            <div style="font-size:8; font-weight:normal; color:$text_color;">
            <strong>estimate date:</strong> $other_db_data->date <br>
            <strong>Due date:</strong> $other_db_data->due_date <br>
            <strong>Ref No:</strong> $other_db_data->reference<br>
            <strong>Amount due:</strong> $other_db_data->currency_symbol $other_db_data->total_amount <br>
            </div>
EOD;
                $this->CI->mytcpdf->write_htmlcell(array('html' => $estimate_info, 'align' => 'R', 'y' => 50));

                # contact info
                $client_org = $other_db_data->contact->organisation;
                $client_addr = $other_db_data->contact->address;
                $contact_info = <<<EOD
            <div style="font-size:8; font-weight:normal; color:$text_color;">
            <strong>Bill to:</strong> <br>
            $client_org <br>
            $client_addr
            </div>
EOD;
                $this->CI->mytcpdf->write_htmlcell(array('html' => $contact_info, 'align' => 'L', 'x' => 12, 'y' => 55, 'ln' => 1));

                $estimate_items = '';
                foreach ($other_db_data->items as $key => $item) {
                    $estimate_items .= '
                <tr>
                    <td><strong>' . $item->item_name . '</strong> <br><span style="color:#adc0cf;">' . $item->description . '</span></td>
                    <td style="color:' . $text_color3 . ';">' . $item->quantity . '</td>
                    <td><strong>' . $item->rate . '</strong></td>
                    <td><strong>' . $item->total_amount . '</strong></td>
                </tr>';
                }

                $actual_estimate = <<<EOD
            <style>
                div {
                    padding-top: 5px;
                }
                table {
                    width:100%;
                    font-size:8px;
                    font-weight:normal;
                    color:$text_color;
                    border-spacing: 0 10px;
                }
            </style>

            <table style="border-top:1px solid #e8e8e8; border-bottom:1px solid #e8e8e8;">
                <tr>
                    <th style="width:60%;">Item:</th>
                    <th style="width:13.33%;">Qty:</th>
                    <th style="width:13.33%;">Rate:</th>
                    <th style="width:13.33%;">Total ($other_db_data->currency_short_code):</th>
                </tr>


                <div style="border-top:1px solid #e8e8e8;"></div>

                $estimate_items

                <div style="border-bottom:1px solid #e8e8e8;"></div>
                <tr>
                    <td style="width:65%;">&nbsp;</td>
                    <td style="width:70%;">
                        <table align="right" style="width:50%; font-weight:bold; text-align:right; border-bottom:1px solid #e8e8e8;">
                            <tr>
                                <td>Subtotal:</td>
                                <td style="color:$text_color3;">$other_db_data->sub_total</td>
                            </tr>
                            <tr>
                                <td>Discount</td>
                                <td style="color:$text_color3;">$other_db_data->discount_total</td>
                            </tr>
                            <tr>
                                <td>Vat Amount:</td>
                                <td style="color:$text_color3;">$other_db_data->vat_amount</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="width:65%;">&nbsp;</td>
                    <td style="width:50%;">
                        <table align="right" style="width:65%; font-weight:bold; margin-left:300px; text-align:right;">
                            <tr style="font-size:10px; font-weight:normal; line-height: normal;">
                                <td><h3 style="font-weight:normal">Total:</h3></td>
                                <td style="color:$text_color2;"><h3 style="font-weight:normal">$other_db_data->currency_symbol $other_db_data->total_amount</h3></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

            <table style="width:100%; border-top:1px solid #e8e8e8;">
                <tr>
                    <td style="width:50%;">Terms (or Banking Details): </td>
                    <td style="width:50%;">Closing Note:</td>
                </tr>

                <tr style="font-size:6px; font-weight:normal; line-height: normal;">
                    <td style="width:50%;">$other_db_data->terms</td>
                    <td style="width:50%;">$other_db_data->closing_note</td>
                </tr>
            </table>

EOD;
                $this->CI->mytcpdf->write_htmlcell(array('html' => $actual_estimate, 'x' => 12, 'y' => 80, 'ln' => 1));
            }
        }
        /*END OF estimateS LOOP*/

        # set PDF font
        $this->CI->tcpdf->SetFont(PDF_FONT_NAME_MAIN, '', 10);

        //$file_path = FCPATH . 'downloads/pdf/' . md5('estimate' . $estimate_number) . '.pdf';
        //$file_path = FCPATH . 'assets/pdf/' . md5('estimate' . $estimate_number) . '.pdf';
        if(is_array($original_ids))
        {
            $file_path = FCPATH . 'assets/pdf/' .'bulk_estimates_' . create_slug(current_datetime()) . '.pdf';
        }
        else
        {
            $file_path = FCPATH . 'assets/pdf/' . $estimate_number . '_' . create_slug(current_datetime()) . '.pdf';
        }

        if (!is_dir(FCPATH . 'assets/pdf')) {
            mkdir(FCPATH . 'assets/pdf');
        }

        if (ob_get_contents()) ob_clean();
        # Close and output PDF document
        $this->CI->tcpdf->Output($file_path, $output);

        if (file_exists($file_path)) {
            $return['status'] = 'OK';

            //$return['download'] = base_url('api/download/' . basename($file_path));
            $return['download'] = $base_url . 'download/' . basename($file_path);
            if(!$echo_json) $return['file_path'] = $file_path;
            $return['message'] = 'estimate pdf(s) ready for download';
        } else {
            $return['message'] = 'could not export estimate pdf(s).';
        }

        if ($echo_json) :
            $this->CI->regular->respond($return);
        else :
            return $return;
        endif;
    }

    public function estimate_data($estimate_id = null)
    {
        $params = array(
            'table' => $this->table_prefix . 'estimates',
            'entity' => 'estimate',
            'items_table' => $this->table_prefix . 'estimate_items'
        );

        $this->CI->load->model('template_model');

        $result = $this->CI->template_model->read($params, $estimate_id);

        return $result;
    }

    public function company_data()
    {
        $params = array(
            'table' => $this->table_prefix . 'organisations o',
            'entity' => 'organisation',
            'main_id_field' => 'o.id',
            'fields' => 'o.*, c.country'
        );

        $joins = array();
        $joins[0]['table1'] = $this->table_prefix . 'countries c';
        $joins[0]['table2'] = 'c.id = o.country_id AND c.active = 1';
        $joins[0]['type'] = 'left';


        $params['join'] = $joins;

        $logo_params = array(
            'table' => $this->table_prefix . 'theme_settings ts',
            'entity' => 'logo',
            'fields' => 'ts.image_string "logo"'
        );

        $logo = $this->CI->generic_model->read($logo_params, null, 'single');

        $result = $this->CI->generic_model->read($params, null, 'single');

        if (!empty($logo)) :
            $result->logo = $logo->logo;
        else :
            $result->logo = '';
        endif;

        return $result;
    }
}
