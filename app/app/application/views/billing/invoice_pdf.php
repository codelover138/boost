<?php
$payment = isset($payment) ? (array)$payment : array();
$organisation = isset($organisation) ? (array)$organisation : array();
$plan = isset($plan) ? (array)$plan : array();

if (!function_exists('billing_invoice_datetime')) {
    function billing_invoice_datetime($value)
    {
        if (empty($value)) {
            return 'N/A';
        }

        $timestamp = strtotime($value);
        if (!$timestamp) {
            return $value;
        }

        return date('M j, Y g:ia', $timestamp);
    }
}
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payment Invoice</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #173044;
            font-size: 12px;
            line-height: 1.6;
        }
        .invoice-shell {
            border: 1px solid #d8e4ee;
            border-radius: 18px;
            overflow: hidden;
        }
        .invoice-hero {
            padding: 28px 32px;
            background: linear-gradient(135deg, #102033 0%, #1c4459 100%);
            color: #fff;
        }
        .invoice-eyebrow {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
            opacity: .72;
            margin-bottom: 10px;
        }
        .invoice-title {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }
        .invoice-subtitle {
            margin: 10px 0 0;
            color: rgba(255,255,255,.8);
            font-size: 13px;
        }
        .invoice-body {
            padding: 28px 32px 20px;
            background: #fff;
        }
        .invoice-grid {
            width: 100%;
            margin-bottom: 22px;
        }
        .invoice-grid td {
            width: 50%;
            vertical-align: top;
            padding: 0 0 18px;
        }
        .label {
            display: block;
            margin-bottom: 5px;
            color: #6d8092;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
        }
        .value {
            color: #102033;
            font-size: 16px;
            font-weight: 700;
        }
        .value-small {
            color: #415668;
            font-size: 12px;
            font-weight: 400;
        }
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
        }
        .summary-table th,
        .summary-table td {
            border: 1px solid #e3edf5;
            padding: 12px 14px;
            text-align: left;
        }
        .summary-table th {
            background: #f5f8fb;
            color: #627588;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
        }
        .invoice-total {
            margin-top: 22px;
            padding: 16px 18px;
            border-radius: 14px;
            background: #f3f8f4;
            border: 1px solid #d9eadf;
        }
        .invoice-total strong {
            display: block;
            margin-top: 4px;
            color: #0f5132;
            font-size: 24px;
        }
        .invoice-footer {
            margin-top: 24px;
            color: #6d8092;
            font-size: 11px;
        }
    </style>
</head>
<body>
    <div class="invoice-shell">
        <div class="invoice-hero">
            <div class="invoice-eyebrow">Boost Accounting</div>
            <h1 class="invoice-title">Payment Invoice</h1>
            <p class="invoice-subtitle">Subscription payment confirmation for <?php echo htmlspecialchars(isset($organisation['company_name']) && $organisation['company_name'] !== '' ? $organisation['company_name'] : $organisation['account_name']); ?></p>
        </div>

        <div class="invoice-body">
            <table class="invoice-grid">
                <tr>
                    <td>
                        <span class="label">Invoice Reference</span>
                        <div class="value"><?php echo htmlspecialchars(isset($payment['merchant_reference']) ? $payment['merchant_reference'] : 'N/A'); ?></div>
                    </td>
                    <td>
                        <span class="label">Payment Status</span>
                        <div class="value"><?php echo htmlspecialchars(isset($payment['payment_status']) ? ucfirst($payment['payment_status']) : 'N/A'); ?></div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span class="label">Billed To</span>
                        <div class="value"><?php echo htmlspecialchars(isset($organisation['company_name']) && $organisation['company_name'] !== '' ? $organisation['company_name'] : $organisation['account_name']); ?></div>
                        <div class="value-small"><?php echo htmlspecialchars(isset($organisation['email']) ? $organisation['email'] : ''); ?></div>
                    </td>
                    <td>
                        <span class="label">Invoice Date</span>
                        <div class="value"><?php echo billing_invoice_datetime(isset($payment['date_created']) ? $payment['date_created'] : null); ?></div>
                    </td>
                </tr>
            </table>

            <table class="summary-table">
                <thead>
                    <tr>
                        <th>Description</th>
                        <th>Billing Period</th>
                        <th>Gross Amount</th>
                        <th>Net Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <?php echo htmlspecialchars(isset($payment['item_name']) ? $payment['item_name'] : (isset($plan['name']) ? $plan['name'] : 'Subscription Payment')); ?>
                            <div class="value-small"><?php echo htmlspecialchars(isset($plan['description']) ? $plan['description'] : 'Boost subscription access'); ?></div>
                        </td>
                        <td>
                            <?php echo billing_invoice_datetime(isset($payment['billing_date_from']) ? $payment['billing_date_from'] : null); ?><br>
                            to<br>
                            <?php echo billing_invoice_datetime(isset($payment['billing_date_to']) ? $payment['billing_date_to'] : null); ?>
                        </td>
                        <td>R<?php echo number_format((float)(isset($payment['amount_gross']) ? $payment['amount_gross'] : 0), 2); ?></td>
                        <td>R<?php echo number_format((float)(isset($payment['amount_net']) && $payment['amount_net'] !== null ? $payment['amount_net'] : (isset($payment['amount_gross']) ? $payment['amount_gross'] : 0)), 2); ?></td>
                    </tr>
                </tbody>
            </table>

            <div class="invoice-total">
                Total Paid
                <strong>R<?php echo number_format((float)(isset($payment['amount_gross']) ? $payment['amount_gross'] : 0), 2); ?></strong>
            </div>

            <div class="invoice-footer">
                Generated by Boost Accounting. This document serves as a subscription payment invoice for your records.
            </div>
        </div>
    </div>
</body>
</html>
