<?php
class Contacts
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
            $return['message'] = 'no contact id(s) given';
            $this->CI->regular->respond($return);
            return;
        endif;

        $this->CI->load->library('pdf/mytcpdf');

        $this->CI->tcpdf->SetPrintHeader(false);
        $this->CI->tcpdf->SetPrintFooter(false);

        $this->CI->tcpdf->SetCreator(PDF_CREATOR);
        $this->CI->tcpdf->SetAuthor('BOOST ACCOUNTING');
        $this->CI->tcpdf->SetTitle('Contacts');

        $original_ids = $ids;

        if (!is_array($ids)) :
            $ids = array(array('id' => $ids));
        endif;

        $this->CI->tcpdf->AddPage();

        $text_color = '#6d90a9';
        $text_color2 = '#35a2ef';

        $company_data = $this->company_data();

        $header_image = <<<EOD
    <img src="$company_data->logo">
EOD;
        $this->CI->mytcpdf->write_htmlcell(array('html' => $header_image, 'align' => 'L', 'x' => 10, 'y' => 15, 'w' => 45));

        $title_html = <<<EOD
    <div style="color:$text_color2;">
    <h3 style="font-weight:normal; line-height: normal;">
        <span style="color:$text_color;">CONTACTS</span>
    </h3>
    </div>
EOD;
        $this->CI->mytcpdf->write_htmlcell(array('html' => $title_html, 'align' => 'R'));

        $rows = '';
        foreach ($ids as $entity) {
            $id = $entity['id'];
            $contact = $this->contact_data($id);

            if (!empty($contact)) :
                $rows .= '
            <tr>
                <td>' . htmlspecialchars($contact->organisation) . '</td>
                <td>' . ucwords(htmlspecialchars($contact->contact_type)) . '</td>
                <td>' . htmlspecialchars($contact->first_name . ' ' . $contact->last_name) . '</td>
                <td>' . htmlspecialchars($contact->email) . '</td>
                <td>' . htmlspecialchars($contact->land_line) . '</td>
            </tr>';
            endif;
        }

        $table_html = <<<EOD
    <style>
        table { width:100%; font-size:8px; font-weight:normal; color:$text_color; border-spacing: 0 4px; }
        th { font-weight:bold; border-bottom:1px solid #e8e8e8; }
        td { padding:3px 2px; border-bottom:1px solid #f0f0f0; }
    </style>
    <table>
        <tr>
            <th>Organisation</th>
            <th>Type</th>
            <th>Contact</th>
            <th>Email</th>
            <th>Phone</th>
        </tr>
        $rows
    </table>
EOD;
        $this->CI->mytcpdf->write_htmlcell(array('html' => $table_html, 'x' => 10, 'y' => 40, 'ln' => 1));

        $this->CI->tcpdf->SetFont(PDF_FONT_NAME_MAIN, '', 10);

        if (is_array($original_ids)) {
            $file_path = FCPATH . 'assets/pdf/contacts_' . create_slug(current_datetime()) . '.pdf';
        } else {
            $file_path = FCPATH . 'assets/pdf/contact_' . $original_ids . '_' . create_slug(current_datetime()) . '.pdf';
        }

        if (!is_dir(FCPATH . 'assets/pdf')) {
            mkdir(FCPATH . 'assets/pdf');
        }

        if (ob_get_contents()) ob_clean();
        $this->CI->tcpdf->Output($file_path, $output);

        if (file_exists($file_path)) {
            $return['status'] = 'OK';
            $return['download'] = $base_url . 'download/' . basename($file_path);
            if (!$echo_json) $return['file_path'] = $file_path;
            $return['message'] = 'contact pdf(s) ready for download';
        } else {
            $return['message'] = 'could not export contact pdf(s).';
        }

        if ($echo_json) :
            $this->CI->regular->respond($return);
        else :
            return $return;
        endif;
    }

    public function contact_data($contact_id = null)
    {
        $params = array(
            'table' => $this->table_prefix . 'contacts c',
            'entity' => 'contact',
            'main_id_field' => 'c.id',
            'fields' => 'c.*, ct.type "contact_type"'
        );

        $joins = array();
        $joins[0]['table1'] = 'boost_contact_types ct';
        $joins[0]['table2'] = 'ct.id = c.contact_type_id';
        $params['join'] = $joins;

        $result = $this->CI->generic_model->read($params, $contact_id, 'single');

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
