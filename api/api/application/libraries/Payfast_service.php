<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Payfast_service
{
    protected $signature_field_order = array(
        'merchant_id',
        'merchant_key',
        'return_url',
        'cancel_url',
        'notify_url',
        'name_first',
        'name_last',
        'email_address',
        'cell_number',
        'm_payment_id',
        'amount',
        'item_name',
        'item_description',
        'custom_int1',
        'custom_int2',
        'custom_int3',
        'custom_int4',
        'custom_int5',
        'custom_str1',
        'custom_str2',
        'custom_str3',
        'custom_str4',
        'custom_str5',
        'email_confirmation',
        'confirmation_address',
        'payment_method',
        'subscription_type',
        'billing_date',
        'recurring_amount',
        'frequency',
        'cycles'
    );

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

    public function generate_signature($data, $sort_fields = true, $skip_empty = true)
    {
        $data = $this->normalise_signature_data($data, $skip_empty);
        if ($sort_fields) {
            $data = $this->sort_signature_data($data);
        }
        $pairs = array();
        foreach ($data as $key => $value) {
            $pairs[] = $key . '=' . urlencode($value);
        }

        $passphrase = trim($this->config['passphrase']);
        if ($passphrase !== '') {
            $pairs[] = 'passphrase=' . urlencode($passphrase);
        }

        $signature_string = implode('&', $pairs);
        log_message('error', 'PAYFAST_SIGNATURE_STRING: ' . $signature_string);

        return md5($signature_string);
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
            : date('Y-m-d', strtotime($this->calculate_period_end(date('Y-m-d H:i:s'), $plan)));

        $fields = array(
            'merchant_id'      => $this->config['merchant_id'],
            'merchant_key'     => $this->config['merchant_key'],
            'return_url'       => $base_app_url . '/billing/complete/success/' . $payment->id,
            'cancel_url'       => $base_app_url . '/billing/complete/cancel/' . $payment->id,
            'notify_url'       => rtrim($this->CI->config->item('api_url'), '/') . '/billing/itn',
            'name_first'       => $this->sanitise_payfast_text($first_name, 100),
            'name_last'        => $this->sanitise_payfast_text($last_name, 100),
            'email_address'    => $organisation->email,
            'm_payment_id'     => $payment->id,
            'amount'           => number_format((float)$payment->amount_gross, 2, '.', ''),
            'item_name'        => $this->sanitise_payfast_text($payment->item_name, 100),
            'item_description' => !empty($plan['description'])
                ? $this->sanitise_payfast_text(trim(preg_replace('/\s+/', ' ', $plan['description'])), 255)
                : '',
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

        $fields = $this->normalise_signature_data($fields);
        $fields = $this->sort_signature_data($fields);
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

    public function calculate_period_end($start_datetime, $plan = array())
    {
        $start_value = trim((string)$start_datetime);
        $start = $start_value !== '' ? new DateTime($start_value) : new DateTime();
        $frequency = $this->get_payfast_frequency(!empty($plan['cycle_days']) ? (int)$plan['cycle_days'] : 30);

        switch ($frequency) {
            case 1:
                return $this->shift_datetime($start, '+1 day');
            case 2:
                return $this->shift_datetime($start, '+1 week');
            case 3:
                return $this->add_calendar_months($start, 1);
            case 4:
                return $this->add_calendar_months($start, 3);
            case 5:
                return $this->add_calendar_months($start, 6);
            case 6:
            default:
                return $this->add_calendar_years($start, 1);
        }
    }

    protected function shift_datetime(DateTime $start, $modifier)
    {
        $end = clone $start;
        $end->modify($modifier);

        return $end->format('Y-m-d H:i:s');
    }

    protected function add_calendar_months(DateTime $start, $months)
    {
        $year = (int)$start->format('Y');
        $month = (int)$start->format('n');
        $day = (int)$start->format('j');
        $hour = (int)$start->format('H');
        $minute = (int)$start->format('i');
        $second = (int)$start->format('s');

        $target_month = $month + (int)$months;
        $target_year = $year + (int)floor(($target_month - 1) / 12);
        $target_month = (($target_month - 1) % 12) + 1;

        $last_day = cal_days_in_month(CAL_GREGORIAN, $target_month, $target_year);
        $target_day = min($day, $last_day);

        $end = clone $start;
        $end->setDate($target_year, $target_month, $target_day);
        $end->setTime($hour, $minute, $second);

        return $end->format('Y-m-d H:i:s');
    }

    protected function add_calendar_years(DateTime $start, $years)
    {
        $target_year = (int)$start->format('Y') + (int)$years;
        $month = (int)$start->format('n');
        $day = (int)$start->format('j');
        $hour = (int)$start->format('H');
        $minute = (int)$start->format('i');
        $second = (int)$start->format('s');

        $last_day = cal_days_in_month(CAL_GREGORIAN, $month, $target_year);
        $target_day = min($day, $last_day);

        $end = clone $start;
        $end->setDate($target_year, $month, $target_day);
        $end->setTime($hour, $minute, $second);

        return $end->format('Y-m-d H:i:s');
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

    public function validate_signature($data, $raw_body = null)
    {
        if (!isset($data['signature'])) {
            return false;
        }

        $submitted_signature = strtolower(trim((string)$data['signature']));
        $candidates = array();

        $raw_signature = $this->generate_signature_from_raw_body($raw_body);
        if ($raw_signature !== null) {
            $candidates[] = $raw_signature;
        }

        // ITN callbacks can differ from checkout signing in both field order and whether
        // empty custom fields were present in the raw body, so validate against both forms.
        $candidates[] = $this->generate_signature($data, false, true);
        $candidates[] = $this->generate_signature($data, false, false);

        foreach (array_unique($candidates) as $candidate) {
            if ($candidate !== '' && hash_equals($candidate, $submitted_signature)) {
                return true;
            }
        }

        log_message('error', 'PAYFAST_ITN_SIGNATURE_DEBUG ' . json_encode(array(
            'submitted_signature' => $submitted_signature,
            'raw_body' => $raw_body,
            'candidates' => array_values(array_unique($candidates))
        )));

        return false;
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

    public function cancel_subscription($token)
    {
        $token = trim((string)$token);
        if ($token === '') {
            return array(
                'bool' => false,
                'message' => 'Missing PayFast subscription token'
            );
        }

        $cancel_result = $this->request_subscription_api('PUT', $token . '/cancel', array(
            'token' => $token
        ));
        $decoded = isset($cancel_result['decoded']) ? $cancel_result['decoded'] : null;
        $is_success = !empty($cancel_result['bool']);

        $status_result = $this->request_subscription_api('GET', $token, array(
            'token' => $token
        ));
        $remote_status = $this->extract_subscription_status(isset($status_result['decoded']) ? $status_result['decoded'] : null);
        if ($remote_status !== null) {
            $is_success = in_array($remote_status, array('cancelled', 'cancel', 'inactive', 'paused'), true);
        }

        log_message('error', 'PAYFAST_CANCEL_SUBSCRIPTION ' . json_encode(array(
            'token' => $token,
            'cancel_result' => $cancel_result,
            'status_result' => $status_result,
            'remote_status' => $remote_status
        )));

        return array(
            'bool' => $is_success,
            'message' => $is_success ? 'Subscription cancelled at PayFast' : $this->build_subscription_api_error($cancel_result, $status_result),
            'status_code' => isset($cancel_result['status_code']) ? $cancel_result['status_code'] : 0,
            'response' => array(
                'cancel' => $decoded !== null ? $decoded : (isset($cancel_result['response']) ? $cancel_result['response'] : null),
                'status' => isset($status_result['decoded']) ? $status_result['decoded'] : (isset($status_result['response']) ? $status_result['response'] : null),
                'remote_status' => $remote_status
            )
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

    protected function normalise_signature_data($data, $skip_empty = true)
    {
        if (!is_array($data)) {
            return array();
        }

        if (isset($data['signature'])) {
            unset($data['signature']);
        }

        $normalised = array();
        foreach ($data as $key => $value) {
            if (!is_scalar($value) && $value !== null) {
                continue;
            }

            $value = trim((string)$value);
            if ($skip_empty && $value === '') {
                continue;
            }

            $normalised[$key] = $value;
        }

        return $normalised;
    }

    protected function sort_signature_data($data)
    {
        if (empty($data)) {
            return array();
        }

        $order_lookup = array_flip($this->signature_field_order);
        uksort($data, function ($left, $right) use ($order_lookup) {
            $left_index = array_key_exists($left, $order_lookup) ? $order_lookup[$left] : PHP_INT_MAX;
            $right_index = array_key_exists($right, $order_lookup) ? $order_lookup[$right] : PHP_INT_MAX;

            if ($left_index === $right_index) {
                return strcmp($left, $right);
            }

            return $left_index < $right_index ? -1 : 1;
        });

        return $data;
    }

    protected function generate_signature_from_raw_body($raw_body)
    {
        $raw_body = trim((string)$raw_body);
        if ($raw_body === '') {
            return null;
        }

        $segments = preg_split('/&/', $raw_body);
        $pairs = array();

        foreach ($segments as $segment) {
            if ($segment === '') {
                continue;
            }

            $parts = explode('=', $segment, 2);
            $key = isset($parts[0]) ? trim((string)$parts[0]) : '';
            if ($key === '' || $key === 'signature') {
                continue;
            }

            $pairs[] = $segment;
        }

        if (empty($pairs)) {
            return null;
        }

        $passphrase = trim($this->config['passphrase']);
        if ($passphrase !== '') {
            $pairs[] = 'passphrase=' . urlencode($passphrase);
        }

        $signature_string = implode('&', $pairs);
        log_message('error', 'PAYFAST_RAW_ITN_SIGNATURE_STRING: ' . $signature_string);

        return md5($signature_string);
    }

    protected function generate_api_signature($fields)
    {
        ksort($fields);

        $pairs = array();
        foreach ($fields as $key => $value) {
            $pairs[] = $key . '=' . urlencode(trim((string)$value));
        }

        $passphrase = trim($this->config['passphrase']);
        if ($passphrase !== '') {
            $pairs[] = 'passphrase=' . urlencode($passphrase);
        }

        $signature_string = implode('&', $pairs);
        log_message('error', 'PAYFAST_API_SIGNATURE_STRING: ' . $signature_string);

        return md5($signature_string);
    }

    protected function request_subscription_api($method, $path, $body = null)
    {
        $timestamp = date('c');
        $version = 'v1';
        $body = is_array($body) ? $body : array();
        $headers_for_signature = array(
            'merchant-id' => trim((string)$this->config['merchant_id']),
            'timestamp' => $timestamp,
            'version' => $version
        );
        if (isset($body['token']) && trim((string)$body['token']) !== '') {
            $headers_for_signature['token'] = trim((string)$body['token']);
        }

        $signature = $this->generate_api_signature($headers_for_signature);
        $url = 'https://api.payfast.co.za/subscriptions/' . ltrim((string)$path, '/');
        if ($this->config['test_mode']) {
            $separator = strpos($url, '?') === false ? '?' : '&';
            $url .= $separator . 'testing=true';
        }

        $headers = array(
            'merchant-id: ' . $headers_for_signature['merchant-id'],
            'version: ' . $version,
            'timestamp: ' . $timestamp,
            'signature: ' . $signature,
            'Accept: application/json',
            'Content-Type: application/x-www-form-urlencoded',
            'User-Agent: BoostAccounting/PayFast'
        );

        $payload = null;
        if (strtoupper((string)$method) !== 'GET' && !empty($body)) {
            ksort($body);
            $payload = http_build_query($body, '', '&');
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper((string)$method));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        if ($payload !== null && $payload !== '') {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        }

        $response = curl_exec($ch);
        $curl_error = curl_error($ch);
        $status_code = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($response === false) {
            return array(
                'bool' => false,
                'message' => $curl_error !== '' ? $curl_error : 'Unable to contact PayFast subscription API',
                'status_code' => $status_code,
                'response' => null,
                'decoded' => null
            );
        }

        $decoded = json_decode($response, true);
        $is_success = $status_code >= 200 && $status_code < 300;
        if (is_array($decoded) && isset($decoded['response']) && is_bool($decoded['response'])) {
            $is_success = $is_success && $decoded['response'];
        }

        return array(
            'bool' => $is_success,
            'message' => $is_success ? 'OK' : trim((string)$response),
            'status_code' => $status_code,
            'response' => $response,
            'decoded' => $decoded
        );
    }

    protected function extract_subscription_status($response)
    {
        if (!is_array($response)) {
            return null;
        }

        $candidates = array();
        if (isset($response['status'])) {
            $candidates[] = $response['status'];
        }
        if (isset($response['data']) && is_array($response['data']) && isset($response['data']['status'])) {
            $candidates[] = $response['data']['status'];
        }
        if (isset($response['response']) && is_array($response['response']) && isset($response['response']['status'])) {
            $candidates[] = $response['response']['status'];
        }

        foreach ($candidates as $candidate) {
            $candidate = strtolower(trim((string)$candidate));
            if ($candidate !== '') {
                return $candidate;
            }
        }

        return null;
    }

    protected function build_subscription_api_error($cancel_result, $status_result)
    {
        if (is_array($cancel_result) && !empty($cancel_result['message']) && $cancel_result['message'] !== 'OK') {
            return (string)$cancel_result['message'];
        }

        if (is_array($status_result) && !empty($status_result['message']) && $status_result['message'] !== 'OK') {
            return 'PayFast cancellation could not be verified: ' . (string)$status_result['message'];
        }

        return 'PayFast cancellation could not be verified.';
    }

    protected function sanitise_payfast_text($value, $max_length = 255)
    {
        $value = trim((string)$value);
        if ($value === '') {
            return '';
        }

        $value = str_replace(array("\r\n", "\r", "\n"), ' ', $value);
        $value = str_replace(array('·', '•', '–', '—'), array('-', '-', '-', '-'), $value);

        if (function_exists('iconv')) {
            $converted = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $value);
            if ($converted !== false) {
                $value = $converted;
            }
        }

        $value = preg_replace('/[^A-Za-z0-9 \-_\.\,\:\@\(\)\/]/', '', $value);
        $value = preg_replace('/\s+/', ' ', $value);

        if ($max_length > 0 && strlen($value) > $max_length) {
            $value = substr($value, 0, $max_length);
        }

        return trim($value);
    }
}
