<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Payfast_service
{
    protected $CI;
    protected $config = array();

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->library('payment_settings');
        $settings = $this->CI->payment_settings->get();
        $this->config = array(
            'merchant_id' => (string)$settings['merchant_id'],
            'merchant_key' => (string)$settings['merchant_key'],
            'passphrase' => (string)$settings['passphrase'],
            'test_mode' => (bool)$settings['test_mode'],
            'debug_email' => (string)$settings['debug_email'],
            'plans' => (array)$settings['plans'],
            'plan' => isset($settings['default_plan_code'], $settings['plans'][$settings['default_plan_code']])
                ? (array)$settings['plans'][$settings['default_plan_code']]
                : array(),
            'valid_hosts' => (array)$settings['itn_valid_hosts']
        );
    }

    public function get_setting($key, $default = null)
    {
        return array_key_exists($key, $this->config) ? $this->config[$key] : $default;
    }

    public function get_plans()
    {
        $plans = array();

        if (!empty($this->config['plans'])) {
            foreach ($this->config['plans'] as $plan_code => $plan) {
                if (!is_array($plan)) {
                    continue;
                }

                $plans[] = $this->normalise_plan($plan, is_string($plan_code) ? $plan_code : null);
            }
        }

        if (empty($plans) && !empty($this->config['plan'])) {
            $plans[] = $this->normalise_plan($this->config['plan']);
        }

        return $plans;
    }

    public function get_plan($code = null)
    {
        $plans = $this->get_plans();

        if ($code !== null && $code !== '') {
            foreach ($plans as $plan) {
                if (isset($plan['code']) && $plan['code'] === $code) {
                    return $plan;
                }
            }
        }

        if (!empty($plans)) {
            return $plans[0];
        }

        return $this->normalise_plan(array());
    }

    public function has_plan($code)
    {
        if ($code === null || $code === '') {
            return false;
        }

        foreach ($this->get_plans() as $plan) {
            if (isset($plan['code']) && $plan['code'] === $code) {
                return true;
            }
        }

        return false;
    }

    public function get_payfast_url()
    {
        if ($this->config['test_mode']) {
            return 'https://sandbox.payfast.co.za/eng/process';
        }

        return 'https://www.payfast.co.za/eng/process';
    }

    public function generate_signature($data)
    {
        if (isset($data['signature'])) {
            unset($data['signature']);
        }

        $pairs = array();
        foreach ($data as $key => $value) {
            if ($value === null || $value === '') {
                continue;
            }

            $pairs[] = $key . '=' . urlencode(trim((string)$value));
        }

        if ($this->config['passphrase'] !== '') {
            $pairs[] = 'passphrase=' . urlencode($this->config['passphrase']);
        }

        return md5(implode('&', $pairs));
    }

    public function build_payment_fields($payment, $organisation, $user, $plan = array())
    {
        $full_name = trim((string)$user->first_name . ' ' . (string)$user->last_name);
        $names = preg_split('/\s+/', trim($full_name));
        $first_name = !empty($names[0]) ? $names[0] : $organisation->company_name;
        $last_name = count($names) > 1 ? implode(' ', array_slice($names, 1)) : 'Account';

        $base_app_url = rtrim(get_protocol() . $payment->account_name . '.' . $this->CI->config->item('domain'), '/');

        // Determine recurring billing frequency from plan cycle_days
        $cycle_days = !empty($plan['cycle_days']) ? (int)$plan['cycle_days'] : 30;
        $payfast_frequency = $this->get_payfast_frequency($cycle_days);

        // billing_date: when PayFast should begin the recurring charges after the initial payment
        $billing_date = !empty($payment->billing_date_to)
            ? date('Y-m-d', strtotime($payment->billing_date_to))
            : date('Y-m-d', strtotime('+' . $cycle_days . ' days'));

        $fields = array(
            'merchant_id'      => $this->config['merchant_id'],
            'merchant_key'     => $this->config['merchant_key'],
            'return_url'       => $base_app_url . '/billing/complete/success/' . $payment->id,
            'cancel_url'       => $base_app_url . '/billing/complete/cancel/' . $payment->id,
            'notify_url'       => rtrim($this->CI->config->item('api_url'), '/') . '/billing/itn',
            'name_first'       => $first_name,
            'name_last'        => $last_name,
            'email_address'    => $organisation->email,
            'm_payment_id'     => $payment->id,
            'amount'           => number_format((float)$payment->amount_gross, 2, '.', ''),
            'item_name'        => $payment->item_name,
            'item_description' => !empty($plan['description']) ? $plan['description'] : '',
            'custom_str1'      => $payment->merchant_reference,
            'custom_str2'      => $payment->account_name,
            // PayFast recurring subscription fields
            'subscription_type' => 1,
            'billing_date'      => $billing_date,
            'recurring_amount'  => number_format((float)$payment->amount_gross, 2, '.', ''),
            'frequency'         => $payfast_frequency,
            'cycles'            => 0
        );

        if ($this->config['test_mode']) {
            $fields['email_confirmation'] = 1;
            if (!empty($this->config['debug_email'])) {
                $fields['confirmation_address'] = $this->config['debug_email'];
            }
        }

        $fields['signature'] = $this->generate_signature($fields);

        return $fields;
    }

    /**
     * Map plan cycle_days to PayFast frequency value.
     * 1=Daily, 2=Weekly, 3=Monthly, 4=Quarterly, 5=Biannual, 6=Annual
     */
    public function get_payfast_frequency($cycle_days)
    {
        $cycle_days = (int)$cycle_days;

        if ($cycle_days <= 1) {
            return 1; // Daily
        }
        if ($cycle_days <= 7) {
            return 2; // Weekly
        }
        if ($cycle_days <= 31) {
            return 3; // Monthly
        }
        if ($cycle_days <= 100) {
            return 4; // Quarterly
        }
        if ($cycle_days <= 200) {
            return 5; // Biannual
        }

        return 6; // Annual
    }

    protected function normalise_plan($plan, $fallback_code = null)
    {
        $code = isset($plan['code']) && $plan['code'] !== '' ? $plan['code'] : $fallback_code;
        $cycle_days = isset($plan['cycle_days']) ? (int)$plan['cycle_days'] : 30;

        return array(
            'code' => $code ? $code : 'boost-plan',
            'name' => isset($plan['name']) ? $plan['name'] : 'Boost Subscription',
            'description' => isset($plan['description']) ? $plan['description'] : 'Boost subscription access',
            'amount' => number_format((float)(isset($plan['amount']) ? $plan['amount'] : 0), 2, '.', ''),
            'currency' => isset($plan['currency']) ? $plan['currency'] : 'ZAR',
            'cycle_days' => $cycle_days,
            'billing_cycle_label' => $cycle_days >= 365 ? 'Yearly' : 'Monthly',
            'test_mode' => $this->config['test_mode']
        );
    }

    public function validate_signature($data)
    {
        if (!isset($data['signature'])) {
            return false;
        }

        return hash_equals($this->generate_signature($data), $data['signature']);
    }

    public function validate_source_ip($ip_address)
    {
        if (empty($ip_address)) {
            return false;
        }

        $valid_ips = array();
        foreach ($this->config['valid_hosts'] as $host) {
            $resolved = gethostbynamel($host);
            if (is_array($resolved)) {
                $valid_ips = array_merge($valid_ips, $resolved);
            }
        }

        $valid_ips = array_unique($valid_ips);

        if (empty($valid_ips)) {
            return false;
        }

        return in_array($ip_address, $valid_ips, true);
    }

    public function confirm_itn($data)
    {
        $payload = http_build_query($data);
        $url = $this->config['test_mode']
            ? 'https://sandbox.payfast.co.za/eng/query/validate'
            : 'https://www.payfast.co.za/eng/query/validate';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/x-www-form-urlencoded',
            'Content-Length: ' . strlen($payload)
        ));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);

        $response = curl_exec($ch);
        $curl_error = curl_error($ch);
        curl_close($ch);

        if ($response === false) {
            return array(
                'bool' => false,
                'message' => $curl_error !== '' ? $curl_error : 'Unable to confirm PayFast ITN'
            );
        }

        return array(
            'bool' => stripos(trim($response), 'VALID') === 0,
            'message' => trim($response)
        );
    }

    public function normalise_payment_status($status)
    {
        $status = strtoupper(trim((string)$status));

        switch ($status) {
            case 'COMPLETE':
                return 'complete';
            case 'CANCELLED':
                return 'cancelled';
            case 'FAILED':
                return 'failed';
            default:
                return 'pending';
        }
    }
}
