<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Payfast_service
{
    protected $CI;
    protected $config = array();

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->config = array(
            'merchant_id' => (string)$this->CI->config->item('payfast_merchant_id'),
            'merchant_key' => (string)$this->CI->config->item('payfast_merchant_key'),
            'passphrase' => (string)$this->CI->config->item('payfast_passphrase'),
            'test_mode' => (bool)$this->CI->config->item('payfast_test_mode'),
            'plans' => (array)$this->CI->config->item('payfast_plans'),
            'plan' => (array)$this->CI->config->item('payfast_plan'),
            'valid_hosts' => (array)$this->CI->config->item('payfast_itn_valid_hosts')
        );
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

        $fields = array(
            'merchant_id' => $this->config['merchant_id'],
            'merchant_key' => $this->config['merchant_key'],
            'return_url' => $base_app_url . '/billing/complete/success/' . $payment->id,
            'cancel_url' => $base_app_url . '/billing/complete/cancel/' . $payment->id,
            'notify_url' => rtrim($this->CI->config->item('api_url'), '/') . '/billing/itn',
            'name_first' => $first_name,
            'name_last' => $last_name,
            'email_address' => $organisation->email,
            'm_payment_id' => $payment->id,
            'amount' => number_format((float)$payment->amount_gross, 2, '.', ''),
            'item_name' => $payment->item_name,
            'item_description' => !empty($plan['description']) ? $plan['description'] : '',
            'custom_str1' => $payment->merchant_reference,
            'custom_str2' => $payment->account_name
        );

        if ($this->config['test_mode']) {
            $fields['email_confirmation'] = 1;
            if ($this->CI->config->item('payfast_debug_email')) {
                $fields['confirmation_address'] = $this->CI->config->item('payfast_debug_email');
            }
        }

        $fields['signature'] = $this->generate_signature($fields);

        return $fields;
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
