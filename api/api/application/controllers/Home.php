<?php
class Home extends CI_Controller
{
    public function index()
    {
        $this->load->library('resources/login');

        $post = array(
            'email'=>'pride@sointeractive.co.za',
            'password'=>'123456'
        );

        $table_description = $this->generic_model->describe('boost_estimates');

        //var_dump($table_description);

        $required_fields = $this->regular->get_required_fields('boost_estimates');

        //var_dump($required_fields);

        //$read = $this->login->get_members('1');

        //var_dump($read);

        //$validate = $this->login->validate($post);

        //var_dump($this->session->userdata());

        //var_dump($read[3]->items);

        $data_arr = array();

        $data_arr['client_id'] = 1;
        $data_arr['currency_id'] = 1;
        $data_arr['date'] = '29 Jun 2015';
        $data_arr['due_date'] = '05 Jul 2015';
        $data_arr['discount_percentage'] = 10;
        $data_arr['reference'] = 1;

        $data_arr['items'][0]['item_name'] = 'the first item';
        $data_arr['items'][0]['description'] = 'the first item\'s description';
        $data_arr['items'][0]['tax'] = 5;
        $data_arr['items'][0]['quantity'] = 1;
        $data_arr['items'][0]['rate'] = '1000';

        $data_arr['items'][1]['item_name'] = 'the second item';
        $data_arr['items'][1]['description'] = 'the item one\'s amount';
        $data_arr['items'][1]['tax'] = 5;
        $data_arr['items'][1]['quantity'] = 1;
        $data_arr['items'][1]['rate'] = '1000-00';

        //$date = '29 Jun 2015';
        //var_dump(date('Y-m-d H:i:s', strtotime($date)));


        //var_dump($this->invoices_model->create($data_arr));

        $this->load->library('finance');
        //$result = $this->finance->calculate_amounts($data_arr);

        //var_dump($result);

        //$this->calculate_amounts($data_arr);

        $data = array(
            'main_content'=>'home',
            'page_title'=>'Home'
        );
        $this->load->view('templates/default', $data);
    }

    public function calculate_amounts($statement)
    {
        $all_taxes_raw = $this->generic_model->read(array('table'=>'boost_taxes'));
        $all_taxes = array();

        foreach($all_taxes_raw as $value) :
            $all_taxes[$value->id] = $value;
        endforeach;

        //var_dump($statement);

        $sub_total = 0;
        $vat_amount = 0;

        $amounts = array();

        foreach($statement['items'] as $item)
        {
            $tax_rate = $all_taxes[$item['tax']]->percentage;
            $rate = $item['rate'];
            $quantity = $item['quantity'];

            $amount_excl = $rate * $quantity; # save to item
            $tax_charged = ($rate * $quantity) * ($tax_rate / 100);
            $amount_incl = ($rate * $quantity) + $tax_charged;

            $vat_amount += $tax_charged;


            var_dump('Tax rate: '.$tax_rate);
            var_dump('Tax charged: '.$tax_charged);
            var_dump('Item Amount excl. tax: '.$amount_excl);
            var_dump('Item Amount incl. tax: '.$amount_incl);

            $sub_total += $amount_excl;
        }

        $overall_amount = $sub_total + $vat_amount;

        if(isset($statement['discount_percentage'])) :
            $discount_percentage = $statement['discount_percentage'];
            $amounts['discount_percentage'] = $discount_percentage;

            $discount_price = $overall_amount * ($discount_percentage / 100);
            var_dump('Discount Price: '.$discount_price);

            $overall_amount -= $discount_price;
        endif;

        var_dump('Sub Total: '.$sub_total);
        var_dump('Vat Amount: '.$vat_amount);
        var_dump('Overall Amount: '.$overall_amount);

        $amounts['sub_total'] = $sub_total;
        $amounts['vat_amount'] = $vat_amount;
        $amounts['overall_amount'] = $overall_amount;

        var_dump($amounts);
    }
}