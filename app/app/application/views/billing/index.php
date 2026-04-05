<?php
$billing_data = isset($billing['data']) ? $billing['data'] : array();
$plan = isset($billing_data['plan']) ? $billing_data['plan'] : array();
$latest_payment = isset($billing_data['latest_payment']) ? $billing_data['latest_payment'] : array();
$plans = isset($billing_data['plans']) && is_array($billing_data['plans']) && !empty($billing_data['plans'])
    ? array_values($billing_data['plans'])
    : (!empty($plan) ? array($plan) : array());
$subscription = isset($billing_data['subscription']) ? $billing_data['subscription'] : array();
$history = isset($billing_data['history']) ? $billing_data['history'] : array();
$status_key = isset($subscription['status']) ? strtolower($subscription['status']) : 'unknown';
$status = ucfirst(str_replace('_', ' ', $status_key));
$current_plan = isset($subscription['current_plan']) && is_array($subscription['current_plan']) ? $subscription['current_plan'] : array();
$current_plan_code = isset($subscription['current_plan_code']) ? $subscription['current_plan_code'] : '';
$pending_plan = isset($subscription['pending_plan']) && is_array($subscription['pending_plan']) ? $subscription['pending_plan'] : array();
$pending_plan_code = isset($subscription['pending_plan_code']) ? $subscription['pending_plan_code'] : '';
$featured_plan = !empty($current_plan) ? $current_plan : (!empty($plan) ? $plan : (!empty($plans) ? $plans[0] : array()));
$can_pay = isset($subscription['can_pay']) ? (bool)$subscription['can_pay'] : true;
$can_cancel = isset($subscription['can_cancel']) ? (bool)$subscription['can_cancel'] : false;
$can_change_plan = isset($subscription['can_change_plan']) ? (bool)$subscription['can_change_plan'] : false;
$has_paid_access = isset($subscription['has_paid_access']) ? (bool)$subscription['has_paid_access'] : false;
$cancel_at_period_end = isset($subscription['cancel_at_period_end']) ? (bool)$subscription['cancel_at_period_end'] : false;
$plan_change_effective_at = !empty($subscription['plan_change_effective_at']) ? $subscription['plan_change_effective_at'] : null;
$grace_period_ends_at = !empty($subscription['grace_period_ends_at']) ? $subscription['grace_period_ends_at'] : 'N/A';
$access_message = !empty($subscription['access_message'])
    ? $subscription['access_message']
    : ($can_pay ? 'Your subscription can be renewed now.' : 'Your subscription is currently active.');
$is_test_mode = !empty($featured_plan['test_mode']);
$latest_payment = is_array($latest_payment) ? $latest_payment : (array)$latest_payment;
$can_simulate_recurring = $is_test_mode && !empty($latest_payment) && isset($latest_payment['payment_status']) && strtolower($latest_payment['payment_status']) === 'complete';

$status_title = 'Subscription In Good Standing';
if ($status_key === 'trial') {
    $status_title = 'Trial Access In Progress';
} elseif ($status_key === 'grace_period') {
    $status_title = 'Grace Period Active';
} elseif ($status_key === 'cancellation_scheduled') {
    $status_title = 'Cancellation Scheduled';
} elseif ($can_pay) {
    $status_title = 'Renewal Ready';
}

$status_tone = ($can_pay || $cancel_at_period_end) ? 'attention' : 'healthy';

if (!function_exists('billing_display_datetime')) {
    function billing_display_datetime($value)
    {
        if (empty($value) || $value === 'N/A') {
            return 'N/A';
        }

        $timestamp = strtotime($value);
        if (!$timestamp) {
            return $value;
        }

        return date('M j, g:ia', $timestamp);
    }
}

if (!function_exists('billing_cycle_label')) {
    function billing_cycle_label($plan_option)
    {
        if (!empty($plan_option['billing_cycle_label'])) {
            return $plan_option['billing_cycle_label'];
        }

        $cycle_days = isset($plan_option['cycle_days']) ? (int)$plan_option['cycle_days'] : 30;
        return $cycle_days >= 365 ? 'Yearly' : 'Monthly';
    }
}
?>

<style>
.billing-shell {
    position: relative;
    padding: 10px 0 28px;
}

.billing-shell:before {
    content: "";
    position: absolute;
    top: 0;
    right: 0;
    left: 0;
    height: 320px;
    border-radius: 28px;
    background:
        radial-gradient(circle at top right, rgba(55, 156, 114, 0.16), transparent 22%),
        radial-gradient(circle at left top, rgba(17, 35, 58, 0.10), transparent 28%),
        linear-gradient(135deg, #f2f7f3 0%, #eef4fb 46%, #f9fbff 100%);
    pointer-events: none;
}

.billing-section {
    position: relative;
    z-index: 1;
    margin-bottom: 24px;
}

.billing-panel {
    background: #fff;
    border: 1px solid #dce6ef;
    border-radius: 24px;
    box-shadow: 0 18px 40px rgba(15, 23, 42, 0.08);
}

.billing-hero {
    display: grid;
    grid-template-columns: minmax(0, 1fr);
    gap: 0;
    align-items: stretch;
}

.billing-hero-main {
    overflow: hidden;
    padding: 34px 36px 30px;
    background:
        radial-gradient(circle at top right, rgba(113, 210, 171, 0.18), transparent 26%),
        radial-gradient(circle at left center, rgba(255, 255, 255, 0.05), transparent 30%),
        linear-gradient(135deg, #13273d 0%, #18344c 48%, #1d5b53 100%);
    color: #fff;
}

.billing-hero-top {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 18px;
}

.billing-hero-content {
    max-width: 840px;
}

.billing-hero-heading {
    display: flex;
    align-items: center;
    gap: 18px;
}

.billing-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 14px;
    border-radius: 999px;
    background: rgba(255, 255, 255, 0.10);
    color: #edf9f3;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .08em;
    text-transform: uppercase;
}

.billing-eyebrow:before {
    content: "";
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: currentColor;
}

.billing-hero-title {
    margin: 0;
    color: #fff;
    font-size: 34px;
    line-height: 1.12;
    font-weight: 700;
    letter-spacing: -.02em;
}

.billing-hero-copy {
    max-width: 700px;
    margin: 12px 0 0;
    color: rgba(236, 244, 252, 0.82);
    font-size: 15px;
    line-height: 1.7;
}

.billing-hero-status {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-top: 2px;
    white-space: nowrap;
    box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.08);
}

.billing-hero-meta {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 14px;
    margin-top: 26px;
}

.billing-hero-stat {
    padding: 18px 20px;
    border: 1px solid rgba(255, 255, 255, 0.12);
    border-radius: 18px;
    background: rgba(255, 255, 255, 0.08);
    box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.04);
    backdrop-filter: blur(10px);
}

.billing-hero-stat-label {
    display: block;
    margin-bottom: 8px;
    color: rgba(235, 244, 255, 0.66);
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .08em;
    text-transform: uppercase;
}

.billing-hero-stat-value {
    color: #fff;
    font-size: 18px;
    font-weight: 700;
    line-height: 1.35;
}

.billing-status-pill {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 8px 13px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .05em;
    text-transform: uppercase;
}

.billing-status-pill:before {
    content: "";
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: currentColor;
}

.billing-status-pill.active,
.billing-status-pill.complete {
    background: #daf6e7;
    color: #14784a;
}

.billing-status-pill.trial,
.billing-status-pill.pending,
.billing-status-pill.grace_period,
.billing-status-pill.cancellation_scheduled {
    background: #fff4cf;
    color: #9a6900;
}

.billing-status-pill.failed,
.billing-status-pill.cancelled,
.billing-status-pill.invalid,
.billing-status-pill.past_due,
.billing-status-pill.expired {
    background: #ffe2e2;
    color: #b42318;
}

.billing-card {
    padding: 28px 30px;
}

.billing-card-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 16px;
    margin-bottom: 20px;
}

.billing-card-title {
    margin: 0;
    color: #102033;
    font-size: 25px;
    font-weight: 700;
}

.billing-card-copy {
    margin: 7px 0 0;
    max-width: 760px;
    color: #66798b;
    font-size: 14px;
    line-height: 1.75;
}

.billing-inline-alert {
    padding: 15px 17px;
    border-radius: 16px;
    border: 1px solid #e4edf5;
    background: #f6f9fc;
    color: #425567;
    font-size: 14px;
    line-height: 1.65;
}

.billing-inline-alert.healthy {
    background: #eef8f1;
    border-color: #d6ebdd;
    color: #1f6a46;
}

.billing-inline-alert.attention {
    background: #fff8e1;
    border-color: #f2e3af;
    color: #8b6400;
}

.billing-plan-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 18px;
}

.billing-plan-option {
    position: relative;
    display: flex;
    flex-direction: column;
    min-height: 100%;
    padding: 24px;
    border: 1px solid #dde7f0;
    border-radius: 22px;
    background: linear-gradient(180deg, #ffffff, #f7fbff);
    box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.88);
}

.billing-plan-option.featured {
    border-color: #b9dec6;
    background:
        radial-gradient(circle at top right, rgba(76, 201, 138, 0.14), transparent 26%),
        linear-gradient(180deg, #fbfffc, #f1fbf4);
    box-shadow: 0 18px 32px rgba(20, 120, 74, 0.10);
}

.billing-plan-top {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 14px;
    margin-bottom: 14px;
}

.billing-plan-badge {
    display: inline-block;
    align-self: flex-start;
    padding: 6px 10px;
    border-radius: 999px;
    background: #eaf1f7;
    color: #5f7385;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .06em;
    text-transform: uppercase;
}

.billing-plan-option.featured .billing-plan-badge {
    background: #dbf4e4;
    color: #177245;
}

.billing-plan-cycle {
    color: #718596;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: .08em;
    text-transform: uppercase;
}

.billing-plan-option h4 {
    margin: 0 0 6px;
    color: #102033;
    font-size: 24px;
    font-weight: 700;
}

.billing-plan-price {
    margin: 0 0 12px;
    color: #102033;
    font-size: 38px;
    font-weight: 700;
    line-height: 1;
}

.billing-plan-price small {
    font-size: 14px;
    color: #728392;
    font-weight: 600;
}

.billing-plan-description {
    color: #5d7184;
    min-height: 52px;
    margin-bottom: 18px;
    line-height: 1.75;
}

.billing-plan-feature {
    margin-bottom: 8px;
    color: #506478;
    font-size: 13px;
    line-height: 1.65;
}

.billing-plan-feature strong {
    color: #102033;
}

.billing-plan-form {
    margin-top: auto;
    padding-top: 14px;
}

.billing-plan-button {
    width: 100%;
    padding: 14px 18px;
    border: none;
    border-radius: 14px;
    background: linear-gradient(180deg, #a9d879 0%, #9fd16f 100%);
    color: #fff;
    font-size: 14px;
    font-weight: 700;
    text-shadow: 0 1px 0 rgba(0, 0, 0, 0.08);
    box-shadow: 0 14px 26px rgba(128, 176, 79, 0.24);
}

.billing-plan-button[disabled] {
    opacity: .72;
    cursor: not-allowed;
    box-shadow: none;
}

.billing-plan-note {
    margin: 12px 0 0;
    color: #6b7f90;
    font-size: 12px;
    line-height: 1.6;
}

.billing-management-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 18px;
}

.billing-management-card {
    padding: 22px 24px;
    border: 1px solid #dde7f0;
    border-radius: 20px;
    background: linear-gradient(180deg, #fff, #f8fbfe);
}

.billing-management-title {
    margin: 0 0 8px;
    color: #102033;
    font-size: 19px;
    font-weight: 700;
}

.billing-management-copy {
    margin: 0 0 16px;
    color: #607486;
    font-size: 14px;
    line-height: 1.7;
}

.billing-management-button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 180px;
    padding: 12px 16px;
    border: 1px solid #d7e2eb;
    border-radius: 14px;
    background: #fff;
    color: #16324a;
    font-size: 14px;
    font-weight: 700;
}

.billing-management-button.danger {
    border-color: #f0c8c8;
    background: #fff5f5;
    color: #b42318;
}

.billing-management-button[disabled] {
    opacity: .7;
    cursor: not-allowed;
}

.billing-history-shell {
    border: 1px solid #e5edf4;
    border-radius: 18px;
    overflow: hidden;
    background: #fff;
}

.billing-history-table {
    width: 100%;
    border-collapse: collapse;
}

.billing-history-table th,
.billing-history-table td {
    padding: 16px 18px;
    text-align: left;
    border-bottom: 1px solid #edf2f7;
    white-space: nowrap;
}

.billing-history-table th {
    background: linear-gradient(180deg, #f8fafc, #f3f7fb);
    color: #6a7f91;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .06em;
    text-transform: uppercase;
}

.billing-history-table tbody tr:hover {
    background: #fbfdff;
}

.billing-history-ref {
    color: #183753;
    font-weight: 700;
}

.billing-history-actions {
    text-align: right;
}

.billing-history-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 12px;
    border: 1px solid #d5e2ec;
    border-radius: 999px;
    background: #fff;
    color: #21415d;
    font-size: 12px;
    font-weight: 700;
    text-decoration: none;
}

.billing-history-link:hover,
.billing-history-link:focus {
    text-decoration: none;
    color: #102033;
    border-color: #b7cede;
    background: #f8fbfe;
}

.billing-empty {
    padding: 20px;
}

.billing-amount {
    color: #102033;
    font-weight: 700;
}

@media (max-width: 900px) {
    .billing-hero-top {
        display: block;
    }

    .billing-hero-heading {
        display: block;
    }

    .billing-eyebrow {
        margin-bottom: 14px;
    }

    .billing-hero-status {
        margin-top: 16px;
    }

    .billing-hero-meta {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 767px) {
    .billing-shell:before {
        height: 260px;
        border-radius: 20px;
    }

    .billing-hero-main,
    .billing-card {
        padding: 22px 20px;
    }

    .billing-hero-title {
        font-size: 29px;
    }

    .billing-card-header,
    .billing-plan-top {
        display: block;
    }

    .billing-plan-cycle {
        margin-top: 10px;
    }

    .billing-history-table th,
    .billing-history-table td {
        padding: 14px 12px;
        font-size: 12px;
    }
}
</style>

<?php if (!empty($flash_message)) : ?>
<div class="alert alert-<?php echo !empty($flash_type) ? $flash_type : 'info'; ?>">
    <?php echo $flash_message; ?>
</div>
<?php endif; ?>

<?php if ($cancel_at_period_end) : ?>
<div class="alert alert-warning">
    <strong>Cancellation scheduled.</strong>
    Your subscription will not renew. You keep full access until
    <strong><?php echo htmlspecialchars(billing_display_datetime(isset($subscription['paid_until']) ? $subscription['paid_until'] : null)); ?></strong>,
    after which your workspace will be deactivated.
</div>
<?php elseif ($status_key === 'cancelled') : ?>
<div class="alert alert-danger">
    <strong>Subscription cancelled.</strong>
    Your workspace no longer has an active subscription. Renew above to restore access.
</div>
<?php endif; ?>

<?php if (isset($billing['message']) && is_array($billing['message']) && !empty($billing['message'])) : ?>
<div class="alert alert-warning">
    <?php echo implode(' ', $billing['message']); ?>
</div>
<?php endif; ?>

<div class="billing-shell">
    <div class="billing-section billing-hero">
        <div class="billing-panel billing-hero-main">
            <div class="billing-hero-top">
                <div class="billing-hero-content">
                    <div class="billing-hero-heading">
                        <span class="billing-eyebrow">Workspace Billing</span>
                        <h1 class="billing-hero-title">
                            <?php echo isset($featured_plan['name']) ? htmlspecialchars($featured_plan['name']) : 'Boost Subscription'; ?>
                        </h1>
                    </div>
                    <p class="billing-hero-copy">
                        <?php echo isset($featured_plan['description']) ? htmlspecialchars($featured_plan['description']) : 'Manage your subscription, keep access uninterrupted, and renew securely through PayFast when billing opens again.'; ?>
                    </p>
                </div>

                <span class="billing-status-pill billing-hero-status <?php echo htmlspecialchars($status_key); ?>">
                    <?php echo htmlspecialchars($status); ?>
                </span>
            </div>

            <div class="billing-hero-meta">
                <div class="billing-hero-stat">
                    <span class="billing-hero-stat-label">Current Price</span>
                    <span
                        class="billing-hero-stat-value">R<?php echo isset($featured_plan['amount']) ? htmlspecialchars($featured_plan['amount']) : '0.00'; ?></span>
                </div>
                <div class="billing-hero-stat">
                    <span class="billing-hero-stat-label">Paid Until</span>
                    <span
                        class="billing-hero-stat-value"><?php echo !empty($subscription['paid_until']) ? billing_display_datetime($subscription['paid_until']) : 'Not paid yet'; ?></span>
                </div>
                <div class="billing-hero-stat">
                    <span class="billing-hero-stat-label">Grace Ends</span>
                    <span
                        class="billing-hero-stat-value"><?php echo htmlspecialchars(billing_display_datetime($grace_period_ends_at)); ?></span>
                </div>
            </div>
        </div>
    </div>

    <div class="billing-section billing-panel billing-card">
        <div class="billing-card-header">
            <div>
                <h3 class="billing-card-title">Choose Your Plan</h3>
                <p class="billing-card-copy">Select the billing cycle that matches your workspace. Renewal becomes
                    available automatically once the current paid term has ended.</p>
            </div>
        </div>

        <div class="billing-inline-alert <?php echo $status_tone; ?>" style="margin-bottom: 20px;">
            <?php echo htmlspecialchars($access_message); ?>
        </div>

        <?php if (!empty($plans)) : ?>
        <div class="billing-plan-grid">
            <?php foreach ($plans as $plan_option) : ?>
            <?php
            $plan_option = (array)$plan_option;
            $is_featured = isset($featured_plan['code'], $plan_option['code']) && $featured_plan['code'] === $plan_option['code'];
            $is_current_plan = $current_plan_code !== '' && isset($plan_option['code']) && $current_plan_code === $plan_option['code'];
            $is_pending_plan = $pending_plan_code !== '' && isset($plan_option['code']) && $pending_plan_code === $plan_option['code'];
            $cycle_label = billing_cycle_label($plan_option);
            $cycle_days = isset($plan_option['cycle_days']) ? (int)$plan_option['cycle_days'] : 30;
            $form_action = $has_paid_access && $can_change_plan ? base_url('billing/change-plan') : base_url('billing/pay');
            $button_label = 'Continue To PayFast';
            $button_disabled = false;
            $note = '';

            if ($has_paid_access && $can_change_plan) {
                if ($is_pending_plan) {
                    $button_label = 'Change Scheduled';
                    $button_disabled = true;
                    $note = 'This billing cycle will start when the current paid term ends.';
                } elseif ($is_current_plan && empty($pending_plan_code)) {
                    $button_label = 'Current Plan';
                    $button_disabled = true;
                } elseif ($is_current_plan && !empty($pending_plan_code)) {
                    $button_label = 'Keep Current Plan';
                    $note = 'Choose this to remove the scheduled billing cycle change.';
                } else {
                    $button_label = $cycle_days >= 365 ? 'Switch To Annual Billing' : 'Switch To Monthly Billing';
                    $note = 'The change will take effect after your current paid term ends.';
                }
            } elseif (!$can_pay) {
                $button_label = 'Subscription Active';
                $button_disabled = true;
            }
            ?>
            <div class="billing-plan-option <?php echo $is_featured ? 'featured' : ''; ?>">
                <div class="billing-plan-top">
                    <span
                        class="billing-plan-badge"><?php echo $is_pending_plan ? 'Next Renewal' : ($is_current_plan ? 'Current Plan' : ($is_featured ? 'Selected Plan' : 'Available Plan')); ?></span>
                    <span class="billing-plan-cycle"><?php echo htmlspecialchars($cycle_label); ?></span>
                </div>

                <h4><?php echo htmlspecialchars($plan_option['name']); ?></h4>
                <div class="billing-plan-price">
                    R<?php echo htmlspecialchars($plan_option['amount']); ?>
                    <small>/ <?php echo strtolower(htmlspecialchars($cycle_label)); ?></small>
                </div>
                <p class="billing-plan-description">
                    <?php echo !empty($plan_option['description']) ? htmlspecialchars($plan_option['description']) : 'Secure subscription access for your Boost workspace.'; ?>
                </p>

                <div class="billing-plan-feature"><strong>Renewal schedule:</strong> every <?php echo $cycle_days; ?>
                    day<?php echo $cycle_days === 1 ? '' : 's'; ?></div>
                <div class="billing-plan-feature"><strong>Gateway:</strong> PayFast secure checkout</div>

                <form class="billing-plan-form" method="post" action="<?php echo $form_action; ?>"
                    data-native-submit="true">
                    <input type="hidden" name="plan_code" value="<?php echo htmlspecialchars($plan_option['code']); ?>">
                    <button class="billing-plan-button" type="submit"
                        <?php echo $button_disabled ? 'disabled="disabled"' : ''; ?>>
                        <?php echo htmlspecialchars($button_label); ?>
                    </button>
                </form>

                <?php if ($note !== '') : ?>
                <p class="billing-plan-note"><?php echo htmlspecialchars($note); ?></p>
                <?php elseif (!$can_pay && $has_paid_access) : ?>
                <p class="billing-plan-note">Renewal will become available again once the current paid coverage period
                    has ended.</p>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else : ?>
        <div class="billing-inline-alert">
            No subscription plans are currently configured for this workspace.
        </div>
        <?php endif; ?>
    </div>

    <div class="billing-section billing-panel billing-card">
        <div class="billing-card-header">
            <div>
                <h3 class="billing-card-title">Subscription Management</h3>
                <p class="billing-card-copy">Manage what happens at the end of the current paid term. Billing cycle
                    changes are scheduled for the next renewal, and cancellations keep access active until the current
                    period finishes.</p>
            </div>
        </div>

        <div class="billing-management-grid">
            <div class="billing-management-card">
                <h4 class="billing-management-title">Next Renewal</h4>
                <p class="billing-management-copy">
                    <?php if (!empty($pending_plan)) : ?>
                    <?php echo htmlspecialchars($pending_plan['name']); ?> will take effect on
                    <?php echo htmlspecialchars(billing_display_datetime($plan_change_effective_at)); ?>.
                    <?php elseif ($has_paid_access) : ?>
                    Your workspace will stay on <?php echo htmlspecialchars(isset($current_plan['name']) ? $current_plan['name'] : 'the current plan'); ?>
                    unless you schedule a billing cycle change above.
                    <?php else : ?>
                    There is no active paid term yet. Start a payment above to begin billing.
                    <?php endif; ?>
                </p>
            </div>

            <div class="billing-management-card">
                <h4 class="billing-management-title">Cancel Subscription</h4>
                <p class="billing-management-copy">
                    <?php if ($cancel_at_period_end) : ?>
                    Cancellation is already scheduled. Access remains available until
                    <?php echo htmlspecialchars(billing_display_datetime(isset($subscription['paid_until']) ? $subscription['paid_until'] : null)); ?>.
                    <?php elseif ($can_cancel) : ?>
                    Cancel now to stop renewal after the current paid term. Your workspace keeps access until
                    <?php echo htmlspecialchars(billing_display_datetime(isset($subscription['paid_until']) ? $subscription['paid_until'] : null)); ?>.
                    <?php else : ?>
                    Cancellation becomes available once the workspace has an active paid subscription.
                    <?php endif; ?>
                </p>

                <form id="billing-cancel-form" method="post" action="<?php echo base_url('billing/cancel'); ?>" data-native-submit="true">
                    <button class="billing-management-button danger" type="button"
                        <?php echo (!$can_cancel || $cancel_at_period_end) ? 'disabled="disabled"' : ''; ?>
                        <?php echo ($can_cancel && !$cancel_at_period_end) ? 'data-toggle="modal" data-target="#billing-cancel-modal"' : ''; ?>>
                        <?php echo $cancel_at_period_end ? 'Cancellation Scheduled' : 'Cancel Subscription'; ?>
                    </button>
                </form>
            </div>

            <?php if ($is_test_mode) : ?>
            <div class="billing-management-card">
                <h4 class="billing-management-title">Sandbox Renewal Test</h4>
                <p class="billing-management-copy">
                    Trigger the recurring renewal flow locally to verify that a new subscription payment row, invoice, and PDF link are created before going live.
                </p>

                <form method="post" action="<?php echo base_url('billing/simulate-recurring'); ?>" data-native-submit="true"
                    onsubmit="return confirm('Simulate a sandbox recurring renewal for the latest completed subscription payment?');">
                    <input type="hidden" name="payment_id" value="<?php echo !empty($latest_payment['id']) ? (int)$latest_payment['id'] : ''; ?>">
                    <button class="billing-management-button" type="submit"
                        <?php echo $can_simulate_recurring ? '' : 'disabled="disabled"'; ?>>
                        Simulate Renewal
                    </button>
                </form>

                <?php if (!$can_simulate_recurring) : ?>
                <p class="billing-plan-note">Complete one sandbox payment first. The simulator uses the latest completed payment as the renewal source.</p>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="billing-section billing-panel billing-card">
        <div class="billing-card-header">
            <div>
                <h3 class="billing-card-title">Payment History</h3>
                <p class="billing-card-copy">Review completed, pending, and unsuccessful subscription payments for this
                    workspace. Completed payments include an invoice download.</p>
            </div>
        </div>

        <?php if (!empty($history)) : ?>
        <div class="billing-history-shell">
            <table class="billing-history-table">
                <thead>
                    <tr>
                        <th>Reference</th>
                        <th>Status</th>
                        <th>Amount</th>
                        <th>Billing Until</th>
                        <th>Created</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($history as $payment) : ?>
                    <?php $payment = (array)$payment; ?>
                    <tr>
                        <td class="billing-history-ref"><?php echo htmlspecialchars($payment['merchant_reference']); ?>
                        </td>
                        <td>
                            <span class="billing-status-pill <?php echo strtolower($payment['payment_status']); ?>">
                                <?php echo htmlspecialchars($payment['payment_status']); ?>
                            </span>
                        </td>
                        <td class="billing-amount">R<?php echo number_format((float)$payment['amount_gross'], 2); ?>
                        </td>
                        <td><?php echo !empty($payment['billing_date_to']) ? htmlspecialchars(billing_display_datetime($payment['billing_date_to'])) : '&mdash;'; ?>
                        </td>
                        <td><?php echo !empty($payment['date_created']) ? htmlspecialchars(billing_display_datetime($payment['date_created'])) : '&mdash;'; ?>
                        </td>
                        <td class="billing-history-actions">
                            <?php if (isset($payment['payment_status']) && strtolower($payment['payment_status']) === 'complete') : ?>
                            <a class="billing-history-link"
                                href="<?php echo base_url('billing/invoice/' . (int)$payment['id']); ?>">
                                Download PDF
                            </a>
                            <?php else : ?>
                            &mdash;
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else : ?>
        <div class="billing-inline-alert billing-empty">
            No subscription payments have been recorded yet.
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Cancel Subscription Confirmation Modal -->
<div class="modal fade" id="billing-cancel-modal" tabindex="-1" role="dialog" aria-labelledby="billing-cancel-modal-title">
    <div class="modal-dialog modal-sm" role="document" style="max-width:460px;margin:80px auto;">
        <div class="modal-content" style="border:none;border-radius:20px;box-shadow:0 24px 60px rgba(15,23,42,0.18);overflow:hidden;">

            <div style="padding:28px 28px 0;text-align:center;">
                <div style="display:inline-flex;align-items:center;justify-content:center;width:56px;height:56px;border-radius:50%;background:#fff2f2;margin-bottom:16px;">
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#b42318" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="12" y1="8" x2="12" y2="12"/>
                        <line x1="12" y1="16" x2="12.01" y2="16"/>
                    </svg>
                </div>
                <h4 id="billing-cancel-modal-title" style="margin:0 0 10px;font-size:20px;font-weight:700;color:#102033;letter-spacing:-.01em;">
                    Cancel Subscription?
                </h4>
                <p style="margin:0;color:#5a7083;font-size:14px;line-height:1.7;">
                    Your subscription will <strong>not renew</strong> after the current billing period ends.
                    <?php if (!empty($subscription['paid_until'])) : ?>
                    <br><br>
                    You keep full access until
                    <strong><?php echo htmlspecialchars(billing_display_datetime($subscription['paid_until'])); ?></strong>.
                    <?php endif; ?>
                </p>
            </div>

            <div style="padding:24px 28px 28px;display:flex;flex-direction:column;gap:10px;margin-top:8px;">
                <button type="button" class="btn" id="billing-cancel-confirm"
                    style="width:100%;padding:13px;border:none;border-radius:13px;background:linear-gradient(180deg,#d93025,#b42318);color:#fff;font-size:14px;font-weight:700;box-shadow:0 6px 18px rgba(180,35,24,0.28);cursor:pointer;">
                    Yes, Cancel My Subscription
                </button>
                <button type="button" class="btn" data-dismiss="modal"
                    style="width:100%;padding:12px;border:1px solid #dde7f0;border-radius:13px;background:#fff;color:#3a5268;font-size:14px;font-weight:700;cursor:pointer;">
                    Keep Subscription
                </button>
            </div>

        </div>
    </div>
</div>

<script>
document.getElementById('billing-cancel-confirm').addEventListener('click', function () {
    this.disabled = true;
    this.textContent = 'Cancelling\u2026';
    document.getElementById('billing-cancel-form').submit();
});
</script>
