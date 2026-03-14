<?php
$billing_data = isset($billing['data']) ? $billing['data'] : array();
$plan = isset($billing_data['plan']) ? $billing_data['plan'] : array();
$subscription = isset($billing_data['subscription']) ? $billing_data['subscription'] : array();
$history = isset($billing_data['history']) ? $billing_data['history'] : array();
$status = isset($subscription['status']) ? ucfirst($subscription['status']) : 'Unknown';
?>

<style>
.billing-card {
    background: #fff;
    border: 1px solid #e6ebf1;
    border-radius: 12px;
    padding: 24px;
    margin-bottom: 24px;
    box-shadow: 0 3px 18px rgba(0, 0, 0, 0.05);
}

.billing-kpis {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 16px;
    margin-bottom: 24px;
}

.billing-kpi {
    background: #f7f9fc;
    border-radius: 10px;
    padding: 18px;
}

.billing-kpi .label {
    display: block;
    color: #6d7b8a;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: .04em;
    margin-bottom: 6px;
}

.billing-kpi .value {
    color: #203040;
    font-size: 20px;
    font-weight: 700;
}

.billing-history-table {
    width: 100%;
    border-collapse: collapse;
}

.billing-history-table th,
.billing-history-table td {
    border-bottom: 1px solid #edf2f7;
    padding: 12px 10px;
    text-align: left;
    font-size: 13px;
}

.billing-history-table th {
    color: #607080;
    font-weight: 700;
}

.billing-status-pill {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
}

.billing-status-pill.active,
.billing-status-pill.complete {
    background: #d9f7e8;
    color: #167a49;
}

.billing-status-pill.trial,
.billing-status-pill.pending {
    background: #fff4cc;
    color: #9a6b00;
}

.billing-status-pill.failed,
.billing-status-pill.cancelled,
.billing-status-pill.invalid,
.billing-status-pill.past_due {
    background: #ffe1e1;
    color: #b42318;
}
</style>

<?php if (!empty($flash_message)) : ?>
<div class="alert alert-<?php echo !empty($flash_type) ? $flash_type : 'info'; ?>">
    <?php echo $flash_message; ?>
</div>
<?php endif; ?>

<?php if (isset($billing['message']) && is_array($billing['message']) && !empty($billing['message'])) : ?>
<div class="alert alert-warning">
    <?php echo implode(' ', $billing['message']); ?>
</div>
<?php endif; ?>

<div class="billing-card">
    <h3 style="margin-top:0;"><?php echo isset($plan['name']) ? $plan['name'] : 'Subscription Plan'; ?></h3>
    <p style="color:#607080; margin-bottom:20px;">
        <?php echo isset($plan['description']) ? $plan['description'] : 'Pay securely with PayFast and restore access immediately after payment confirmation.'; ?>
    </p>

    <div class="billing-kpis">
        <div class="billing-kpi">
            <span class="label">Plan Price</span>
            <span class="value">R <?php echo isset($plan['amount']) ? $plan['amount'] : '0.00'; ?></span>
        </div>
        <div class="billing-kpi">
            <span class="label">Subscription Status</span>
            <span class="value"><?php echo $status; ?></span>
        </div>
        <div class="billing-kpi">
            <span class="label">Paid Until</span>
            <span
                class="value"><?php echo !empty($subscription['paid_until']) ? $subscription['paid_until'] : 'Not paid yet'; ?></span>
        </div>
        <div class="billing-kpi">
            <span class="label">Trial Ends</span>
            <span
                class="value"><?php echo !empty($subscription['trial_ends_at']) ? $subscription['trial_ends_at'] : 'N/A'; ?></span>
        </div>
    </div>

    <a class="btn btn-success btn-lg" href="<?php echo base_url('billing/pay'); ?>">Pay With PayFast</a>
</div>

<div class="billing-card">
    <h3 style="margin-top:0;">Payment History</h3>
    <?php if (!empty($history)) : ?>
    <table class="billing-history-table">
        <thead>
            <tr>
                <th>Reference</th>
                <th>Status</th>
                <th>Amount</th>
                <th>Billing Until</th>
                <th>Created</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($history as $payment) : ?>
            <?php $payment = (array)$payment; ?>
            <tr>
                <td><?php echo htmlspecialchars($payment['merchant_reference']); ?></td>
                <td>
                    <span class="billing-status-pill <?php echo strtolower($payment['payment_status']); ?>">
                        <?php echo htmlspecialchars($payment['payment_status']); ?>
                    </span>
                </td>
                <td>R<?php echo number_format((float)$payment['amount_gross'], 2); ?></td>
                <td><?php echo !empty($payment['billing_date_to']) ? htmlspecialchars($payment['billing_date_to']) : '—'; ?>
                </td>
                <td><?php echo !empty($payment['date_created']) ? htmlspecialchars($payment['date_created']) : '—'; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php else : ?>
    <p style="margin-bottom:0; color:#607080;">No subscription payments have been recorded yet.</p>
    <?php endif; ?>
</div>