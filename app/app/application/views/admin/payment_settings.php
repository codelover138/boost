<?php
$settings = isset($payment_settings) ? (array)$payment_settings : array();
$plans = isset($settings['plans']) && is_array($settings['plans']) ? $settings['plans'] : array();
$monthly = isset($plans['boost-monthly']) ? (array)$plans['boost-monthly'] : array();
$yearly = isset($plans['boost-yearly']) ? (array)$plans['boost-yearly'] : array();
$hosts = isset($settings['itn_valid_hosts']) && is_array($settings['itn_valid_hosts']) ? implode("\n", $settings['itn_valid_hosts']) : '';
$is_test_mode = !empty($settings['test_mode']);
$default_plan = isset($settings['default_plan_code']) ? $settings['default_plan_code'] : 'boost-monthly';
$trial_days = isset($settings['trial_days']) ? $settings['trial_days'] : '45';
$grace_days = isset($settings['grace_period_days']) ? $settings['grace_period_days'] : '7';
$monthly_amount = isset($monthly['amount']) ? $monthly['amount'] : '0.00';
$yearly_amount = isset($yearly['amount']) ? $yearly['amount'] : '0.00';
?>

<style>
.paycfg-page {
    padding: 8px 6px 42px;
}

.paycfg-shell {
    display: grid;
    gap: 22px;
}

.paycfg-hero {
    position: relative;
    overflow: hidden;
    padding: 30px 32px;
    border-radius: 26px;
    background:
        radial-gradient(circle at top right, rgba(76, 201, 138, 0.20), transparent 24%),
        radial-gradient(circle at left center, rgba(255, 255, 255, 0.08), transparent 34%),
        linear-gradient(135deg, #0f2235 0%, #173c52 58%, #1f5f52 100%);
    color: #fff;
    box-shadow: 0 24px 50px rgba(15, 34, 53, 0.18);
}

.paycfg-hero:before {
    content: "";
    position: absolute;
    inset: auto -60px -70px auto;
    width: 220px;
    height: 220px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.06);
}

.paycfg-hero-top {
    position: relative;
    z-index: 1;
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 16px;
    margin-bottom: 22px;
}

.paycfg-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 14px;
    padding: 7px 12px;
    border-radius: 999px;
    background: rgba(218, 245, 230, 0.14);
    color: #daf5e6;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .09em;
    text-transform: uppercase;
}

.paycfg-eyebrow:before {
    content: "";
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: currentColor;
}

.paycfg-hero h2 {
    margin: 0 0 10px;
    font-size: 34px;
    font-weight: 700;
    line-height: 1.08;
}

.paycfg-hero p {
    margin: 0;
    max-width: 760px;
    color: rgba(255, 255, 255, 0.82);
    font-size: 15px;
    line-height: 1.8;
}

.paycfg-mode-pill {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 14px;
    border-radius: 999px;
    background: rgba(255, 255, 255, 0.12);
    color: #fff;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: .06em;
    text-transform: uppercase;
}

.paycfg-mode-pill:before {
    content: "";
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #7ef0b0;
}

.paycfg-mode-pill.live:before {
    background: #ffd66b;
}

.paycfg-hero-metrics {
    position: relative;
    z-index: 1;
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 14px;
}

.paycfg-metric {
    padding: 15px 16px;
    border: 1px solid rgba(255, 255, 255, 0.10);
    border-radius: 16px;
    background: rgba(255, 255, 255, 0.08);
    backdrop-filter: blur(6px);
}

.paycfg-metric-label {
    display: block;
    margin-bottom: 6px;
    color: rgba(255, 255, 255, 0.68);
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .08em;
    text-transform: uppercase;
}

.paycfg-metric-value {
    color: #fff;
    font-size: 22px;
    font-weight: 700;
}

.paycfg-alert {
    position: relative;
    padding: 16px 50px 16px 18px;
    border-radius: 16px;
    border: 1px solid #dce7f1;
    background: #fff;
    box-shadow: 0 10px 24px rgba(15, 23, 42, 0.05);
}

.paycfg-alert.success {
    border-color: #ccecd8;
    background: #f2fbf5;
    color: #196845;
}

.paycfg-alert.danger {
    border-color: #f2d1d1;
    background: #fff6f6;
    color: #a33232;
}

.paycfg-alert.info {
    color: #30475f;
}

.paycfg-alert .close {
    position: absolute;
    top: 12px;
    right: 14px;
    opacity: .55;
    font-size: 22px;
    border: none;
    background: transparent;
    box-shadow: none;
    color: inherit;
}

.paycfg-grid {
    display: grid;
    grid-template-columns: minmax(0, 1.15fr) minmax(320px, .85fr);
    gap: 20px;
}

.paycfg-stack {
    display: grid;
    gap: 20px;
}

.paycfg-card {
    background: #fff;
    border: 1px solid #dbe6ef;
    border-radius: 22px;
    padding: 24px;
    box-shadow: 0 16px 38px rgba(15, 23, 42, 0.06);
}

.paycfg-card-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 16px;
    margin-bottom: 18px;
}

.paycfg-kicker {
    display: inline-block;
    margin-bottom: 8px;
    color: #698195;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .08em;
    text-transform: uppercase;
}

.paycfg-title {
    margin: 0 0 6px;
    color: #102033;
    font-size: 24px;
    font-weight: 700;
}

.paycfg-copy {
    margin: 0;
    color: #66788a;
    font-size: 14px;
    line-height: 1.75;
}

.paycfg-section-tag {
    padding: 8px 12px;
    border-radius: 999px;
    background: #eef5fb;
    color: #36506a;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .06em;
    text-transform: uppercase;
}

.paycfg-form-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 16px;
}

.paycfg-field {
    margin-bottom: 16px;
}

.paycfg-field.full {
    grid-column: 1 / -1;
}

.paycfg-field label {
    display: block;
    margin-bottom: 7px;
    color: #34485b;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: .05em;
    text-transform: uppercase;
}

.paycfg-field-hint {
    display: block;
    margin-top: 6px;
    color: #73879a;
    font-size: 12px;
    line-height: 1.55;
}

.paycfg-field input,
.paycfg-field select,
.paycfg-field textarea {
    width: 100%;
    padding: 12px 13px;
    border: 1px solid #d4e0ea;
    border-radius: 12px;
    background: #fbfdff;
    color: #102033;
    font-size: 14px;
    box-sizing: border-box;
    transition: border-color .18s ease, box-shadow .18s ease, background-color .18s ease;
}

.paycfg-field input:focus,
.paycfg-field select:focus,
.paycfg-field textarea:focus {
    outline: none;
    border-color: #80b79b;
    box-shadow: 0 0 0 4px rgba(53, 129, 91, 0.10);
    background: #fff;
}

.paycfg-field textarea {
    min-height: 132px;
    resize: vertical;
}

.paycfg-toggle {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    padding: 16px 18px;
    border: 1px solid #d6e1eb;
    border-radius: 16px;
    background: linear-gradient(180deg, #fbfdff, #f7fbff);
}

.paycfg-toggle-copy strong {
    display: block;
    color: #163049;
    font-size: 15px;
}

.paycfg-toggle-copy span {
    display: block;
    margin-top: 4px;
    color: #6c8092;
    font-size: 13px;
    line-height: 1.6;
}

.paycfg-switch {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    color: #35516d;
    font-size: 13px;
    font-weight: 700;
}

.paycfg-switch input {
    width: 18px;
    height: 18px;
    margin: 0;
}

.paycfg-plan-grid {
    display: grid;
    gap: 18px;
}

.paycfg-plan {
    padding: 22px;
    border: 1px solid #dce6f0;
    border-radius: 18px;
    background: linear-gradient(180deg, #fcfdff, #f6fafe);
}

.paycfg-plan-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    margin-bottom: 16px;
}

.paycfg-plan h3 {
    margin: 0;
    color: #102033;
    font-size: 20px;
    font-weight: 700;
}

.paycfg-plan-price {
    color: #13385b;
    font-size: 13px;
    font-weight: 700;
    padding: 7px 11px;
    border-radius: 999px;
    background: #eaf2f9;
}

.paycfg-side-card {
    padding: 22px;
    border: 1px solid #dbe6ef;
    border-radius: 18px;
    background: linear-gradient(180deg, #ffffff, #f8fbfe);
}

.paycfg-side-card + .paycfg-side-card {
    margin-top: 18px;
}

.paycfg-side-title {
    margin: 0 0 12px;
    color: #102033;
    font-size: 18px;
    font-weight: 700;
}

.paycfg-side-copy {
    margin: 0;
    color: #66788a;
    font-size: 13px;
    line-height: 1.75;
}

.paycfg-checklist {
    margin: 14px 0 0;
    padding: 0;
    list-style: none;
}

.paycfg-checklist li {
    position: relative;
    padding-left: 18px;
    color: #40566d;
    font-size: 13px;
    line-height: 1.8;
}

.paycfg-checklist li + li {
    margin-top: 7px;
}

.paycfg-checklist li:before {
    content: "";
    position: absolute;
    left: 0;
    top: 9px;
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: #2c8f5d;
}

.paycfg-stat-grid {
    display: grid;
    gap: 12px;
}

.paycfg-stat {
    padding: 14px 15px;
    border: 1px solid #e2ebf3;
    border-radius: 14px;
    background: #fff;
}

.paycfg-stat-label {
    display: block;
    margin-bottom: 5px;
    color: #6f8496;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .07em;
    text-transform: uppercase;
}

.paycfg-stat-value {
    color: #102033;
    font-size: 18px;
    font-weight: 700;
}

.paycfg-note {
    margin-top: 16px;
    color: #6a7d8f;
    font-size: 12px;
    line-height: 1.75;
}

.paycfg-submit {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    margin-top: 6px;
    padding: 20px 24px;
    border: 1px solid #dbe6ef;
    border-radius: 20px;
    background: linear-gradient(180deg, #fff, #f7fbff);
    box-shadow: 0 14px 34px rgba(15, 23, 42, 0.05);
}

.paycfg-submit-copy {
    color: #6a7d8f;
    font-size: 13px;
    line-height: 1.7;
}

.paycfg-submit-copy strong {
    display: block;
    color: #102033;
    font-size: 15px;
    margin-bottom: 3px;
}

.paycfg-submit button {
    min-width: 230px;
    padding: 14px 22px;
    border: none;
    border-radius: 14px;
    background: linear-gradient(180deg, #a9d879 0%, #9fd16f 100%);
    color: #fff;
    font-size: 14px;
    font-weight: 700;
    box-shadow: 0 14px 26px rgba(128, 176, 79, 0.28);
    text-shadow: 0 1px 0 rgba(0, 0, 0, 0.08);
}

.paycfg-submit button:hover,
.paycfg-submit button:focus {
    background: linear-gradient(180deg, #afdE7f 0%, #a2d471 100%);
    color: #fff;
}

@media (max-width: 1180px) {
    .paycfg-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 1024px) {
    .paycfg-hero-metrics,
    .paycfg-form-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media (max-width: 767px) {
    .paycfg-hero {
        padding: 24px 22px;
    }

    .paycfg-hero-top,
    .paycfg-submit,
    .paycfg-card-header,
    .paycfg-plan-header,
    .paycfg-toggle {
        display: block;
    }

    .paycfg-mode-pill,
    .paycfg-section-tag,
    .paycfg-plan-price {
        margin-top: 12px;
    }

    .paycfg-hero-metrics,
    .paycfg-form-grid {
        grid-template-columns: 1fr;
    }

    .paycfg-submit button {
        width: 100%;
        margin-top: 14px;
    }
}
</style>

<div class="paycfg-page">
    <div class="paycfg-shell">
        <?php if (!empty($flash_message)) : ?>
        <div class="paycfg-alert <?php echo !empty($flash_type) ? $flash_type : 'info'; ?> alert-dismissible" role="alert">
            <button type="button" class="close js-paycfg-dismiss" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <?php echo $flash_message; ?>
        </div>
        <?php endif; ?>

        <div class="paycfg-hero">
            <div class="paycfg-hero-top">
                <div>
                    <span class="paycfg-eyebrow">Billing Control Center</span>
                    <h2>Payment Settings</h2>
                    <p>Manage billing rules, gateway credentials, trial length, and plan pricing from one admin workspace. Changes made here flow directly into the subscription and PayFast billing experience.</p>
                </div>
                <span class="paycfg-mode-pill <?php echo $is_test_mode ? 'sandbox' : 'live'; ?>">
                    <?php echo $is_test_mode ? 'Sandbox Mode' : 'Live Mode'; ?>
                </span>
            </div>

            <div class="paycfg-hero-metrics">
                <div class="paycfg-metric">
                    <span class="paycfg-metric-label">Default Plan</span>
                    <span class="paycfg-metric-value"><?php echo $default_plan === 'boost-yearly' ? 'Yearly' : 'Monthly'; ?></span>
                </div>
                <div class="paycfg-metric">
                    <span class="paycfg-metric-label">Trial Window</span>
                    <span class="paycfg-metric-value"><?php echo htmlspecialchars($trial_days); ?> days</span>
                </div>
                <div class="paycfg-metric">
                    <span class="paycfg-metric-label">Grace Period</span>
                    <span class="paycfg-metric-value"><?php echo htmlspecialchars($grace_days); ?> days</span>
                </div>
                <div class="paycfg-metric">
                    <span class="paycfg-metric-label">Monthly Price</span>
                    <span class="paycfg-metric-value">R <?php echo htmlspecialchars($monthly_amount); ?></span>
                </div>
            </div>
        </div>

        <form method="post" action="<?php echo base_url('admin/payment-settings'); ?>" data-native-submit="true">
            <div class="paycfg-grid">
                <div class="paycfg-stack">
                    <div class="paycfg-card">
                        <div class="paycfg-card-header">
                            <div>
                                <span class="paycfg-kicker">Gateway Setup</span>
                                <h2 class="paycfg-title">PayFast Credentials</h2>
                                <p class="paycfg-copy">Keep merchant credentials, passphrase, environment, and notification details aligned with the PayFast account currently handling payments.</p>
                            </div>
                            <span class="paycfg-section-tag">Core Settings</span>
                        </div>

                        <div class="paycfg-form-grid">
                            <div class="paycfg-field">
                                <label for="merchant_id">Merchant ID</label>
                                <input type="text" id="merchant_id" name="merchant_id" value="<?php echo htmlspecialchars(isset($settings['merchant_id']) ? $settings['merchant_id'] : ''); ?>">
                            </div>

                            <div class="paycfg-field">
                                <label for="merchant_key">Merchant Key</label>
                                <input type="text" id="merchant_key" name="merchant_key" value="<?php echo htmlspecialchars(isset($settings['merchant_key']) ? $settings['merchant_key'] : ''); ?>">
                            </div>

                            <div class="paycfg-field">
                                <label for="passphrase">Passphrase</label>
                                <input type="text" id="passphrase" name="passphrase" value="<?php echo htmlspecialchars(isset($settings['passphrase']) ? $settings['passphrase'] : ''); ?>">
                                <span class="paycfg-field-hint">Must exactly match the passphrase configured in the active PayFast account.</span>
                            </div>

                            <div class="paycfg-field">
                                <label for="debug_email">Debug / Confirmation Email</label>
                                <input type="text" id="debug_email" name="debug_email" value="<?php echo htmlspecialchars(isset($settings['debug_email']) ? $settings['debug_email'] : ''); ?>">
                                <span class="paycfg-field-hint">Used for sandbox confirmation or operational visibility during testing.</span>
                            </div>

                            <div class="paycfg-field full">
                                <label>Gateway Mode</label>
                                <div class="paycfg-toggle">
                                    <div class="paycfg-toggle-copy">
                                        <strong><?php echo $is_test_mode ? 'Sandbox currently enabled' : 'Live mode currently enabled'; ?></strong>
                                        <span>Switch environments carefully. This setting affects the payment endpoint and ITN validation flow immediately.</span>
                                    </div>
                                    <label class="paycfg-switch" for="test_mode">
                                        <input type="checkbox" id="test_mode" name="test_mode" value="1" <?php echo $is_test_mode ? 'checked="checked"' : ''; ?>>
                                        Sandbox Mode
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="paycfg-card">
                        <div class="paycfg-card-header">
                            <div>
                                <span class="paycfg-kicker">Subscription Rules</span>
                                <h2 class="paycfg-title">Access Lifecycle</h2>
                                <p class="paycfg-copy">Define how long new workspaces remain on trial and how long expired accounts retain grace-period access before billing is required.</p>
                            </div>
                            <span class="paycfg-section-tag">Workspace Rules</span>
                        </div>

                        <div class="paycfg-form-grid">
                            <div class="paycfg-field">
                                <label for="trial_days">Trial Days</label>
                                <input type="number" min="0" id="trial_days" name="trial_days" value="<?php echo htmlspecialchars($trial_days); ?>">
                                <span class="paycfg-field-hint">Applied when a new workspace is created.</span>
                            </div>

                            <div class="paycfg-field">
                                <label for="grace_period_days">Grace Period Days</label>
                                <input type="number" min="0" id="grace_period_days" name="grace_period_days" value="<?php echo htmlspecialchars($grace_days); ?>">
                                <span class="paycfg-field-hint">Applied after paid access ends and before the workspace is fully restricted.</span>
                            </div>

                            <div class="paycfg-field full">
                                <label for="default_plan_code">Default Plan</label>
                                <select id="default_plan_code" name="default_plan_code">
                                    <option value="boost-monthly" <?php echo $default_plan === 'boost-monthly' ? 'selected="selected"' : ''; ?>>Monthly</option>
                                    <option value="boost-yearly" <?php echo $default_plan === 'boost-yearly' ? 'selected="selected"' : ''; ?>>Yearly</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="paycfg-card">
                        <div class="paycfg-card-header">
                            <div>
                                <span class="paycfg-kicker">Security</span>
                                <h2 class="paycfg-title">ITN Validation Hosts</h2>
                                <p class="paycfg-copy">These hosts are used when validating incoming ITN calls from PayFast. Keep the list precise and current for the selected environment.</p>
                            </div>
                            <span class="paycfg-section-tag">Validation</span>
                        </div>

                        <div class="paycfg-field" style="margin-bottom:0;">
                            <label for="itn_valid_hosts">Allowed Hosts</label>
                            <textarea id="itn_valid_hosts" name="itn_valid_hosts"><?php echo htmlspecialchars($hosts); ?></textarea>
                            <span class="paycfg-field-hint">Enter one host per line, for example `sandbox.payfast.co.za`.</span>
                        </div>
                    </div>
                </div>

                <div class="paycfg-stack">
                    <div class="paycfg-card">
                        <div class="paycfg-card-header">
                            <div>
                                <span class="paycfg-kicker">Pricing</span>
                                <h2 class="paycfg-title">Subscription Plans</h2>
                                <p class="paycfg-copy">Set the plan names, price points, and billing cadence shown to customers on the billing page.</p>
                            </div>
                            <span class="paycfg-section-tag">Customer Facing</span>
                        </div>

                        <div class="paycfg-plan-grid">
                            <div class="paycfg-plan">
                                <div class="paycfg-plan-header">
                                    <h3>Monthly Plan</h3>
                                    <span class="paycfg-plan-price">R <?php echo htmlspecialchars($monthly_amount); ?></span>
                                </div>

                                <div class="paycfg-field">
                                    <label for="monthly_name">Plan Name</label>
                                    <input type="text" id="monthly_name" name="monthly_name" value="<?php echo htmlspecialchars(isset($monthly['name']) ? $monthly['name'] : ''); ?>">
                                </div>

                                <div class="paycfg-field">
                                    <label for="monthly_description">Description</label>
                                    <textarea id="monthly_description" name="monthly_description"><?php echo htmlspecialchars(isset($monthly['description']) ? $monthly['description'] : ''); ?></textarea>
                                </div>

                                <div class="paycfg-field">
                                    <label for="monthly_amount">Amount</label>
                                    <input type="text" id="monthly_amount" name="monthly_amount" value="<?php echo htmlspecialchars(isset($monthly['amount']) ? $monthly['amount'] : ''); ?>">
                                </div>

                                <div class="paycfg-field">
                                    <label for="monthly_currency">Currency</label>
                                    <input type="text" id="monthly_currency" name="monthly_currency" value="<?php echo htmlspecialchars(isset($monthly['currency']) ? $monthly['currency'] : 'ZAR'); ?>">
                                </div>

                                <div class="paycfg-field" style="margin-bottom:0;">
                                    <label for="monthly_cycle_days">Cycle Days</label>
                                    <input type="number" min="1" id="monthly_cycle_days" name="monthly_cycle_days" value="<?php echo htmlspecialchars(isset($monthly['cycle_days']) ? $monthly['cycle_days'] : '30'); ?>">
                                </div>
                            </div>

                            <div class="paycfg-plan">
                                <div class="paycfg-plan-header">
                                    <h3>Yearly Plan</h3>
                                    <span class="paycfg-plan-price">R <?php echo htmlspecialchars($yearly_amount); ?></span>
                                </div>

                                <div class="paycfg-field">
                                    <label for="yearly_name">Plan Name</label>
                                    <input type="text" id="yearly_name" name="yearly_name" value="<?php echo htmlspecialchars(isset($yearly['name']) ? $yearly['name'] : ''); ?>">
                                </div>

                                <div class="paycfg-field">
                                    <label for="yearly_description">Description</label>
                                    <textarea id="yearly_description" name="yearly_description"><?php echo htmlspecialchars(isset($yearly['description']) ? $yearly['description'] : ''); ?></textarea>
                                </div>

                                <div class="paycfg-field">
                                    <label for="yearly_amount">Amount</label>
                                    <input type="text" id="yearly_amount" name="yearly_amount" value="<?php echo htmlspecialchars(isset($yearly['amount']) ? $yearly['amount'] : ''); ?>">
                                </div>

                                <div class="paycfg-field">
                                    <label for="yearly_currency">Currency</label>
                                    <input type="text" id="yearly_currency" name="yearly_currency" value="<?php echo htmlspecialchars(isset($yearly['currency']) ? $yearly['currency'] : 'ZAR'); ?>">
                                </div>

                                <div class="paycfg-field" style="margin-bottom:0;">
                                    <label for="yearly_cycle_days">Cycle Days</label>
                                    <input type="number" min="1" id="yearly_cycle_days" name="yearly_cycle_days" value="<?php echo htmlspecialchars(isset($yearly['cycle_days']) ? $yearly['cycle_days'] : '365'); ?>">
                                </div>
                            </div>
                        </div>

                        <div class="paycfg-note">
                            Customer-facing prices and descriptions are read directly from this configuration. Review carefully before switching the gateway into live mode.
                        </div>
                    </div>

                    <div class="paycfg-side-card">
                        <h3 class="paycfg-side-title">Configuration Snapshot</h3>
                        <div class="paycfg-stat-grid">
                            <div class="paycfg-stat">
                                <span class="paycfg-stat-label">Environment</span>
                                <span class="paycfg-stat-value"><?php echo $is_test_mode ? 'Sandbox' : 'Live'; ?></span>
                            </div>
                            <div class="paycfg-stat">
                                <span class="paycfg-stat-label">Default Customer Plan</span>
                                <span class="paycfg-stat-value"><?php echo $default_plan === 'boost-yearly' ? 'Yearly' : 'Monthly'; ?></span>
                            </div>
                            <div class="paycfg-stat">
                                <span class="paycfg-stat-label">Trial Duration</span>
                                <span class="paycfg-stat-value"><?php echo htmlspecialchars($trial_days); ?> days</span>
                            </div>
                            <div class="paycfg-stat">
                                <span class="paycfg-stat-label">Grace Duration</span>
                                <span class="paycfg-stat-value"><?php echo htmlspecialchars($grace_days); ?> days</span>
                            </div>
                        </div>
                    </div>

                    <div class="paycfg-side-card">
                        <h3 class="paycfg-side-title">Before You Save</h3>
                        <p class="paycfg-side-copy">A few checks help keep billing predictable and reduce failed payments or ITN validation issues.</p>
                        <ul class="paycfg-checklist">
                            <li>Keep merchant credentials and passphrase exactly aligned with the active PayFast account.</li>
                            <li>Only enable live mode after confirming the production notify URL and ITN hosts are correct.</li>
                            <li>Review trial and grace periods carefully because they affect workspace access automatically.</li>
                            <li>Confirm plan prices before saving, especially when changing the default customer plan.</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="paycfg-submit">
                <div class="paycfg-submit-copy">
                    <strong>Save payment configuration</strong>
                    Your changes will update the billing rules and plan settings used by the application.
                </div>
                <button type="submit">Save Payment Settings</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('click', function (event) {
    if (!event.target.closest('.js-paycfg-dismiss')) {
        return;
    }

    var alertBox = event.target.closest('.paycfg-alert');
    if (alertBox) {
        alertBox.style.display = 'none';
    }
});
</script>
