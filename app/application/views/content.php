<?php $this->load->view('includes/html_pre_content'); //start HTML and include css,js,metatags etc ?>

<?php $this->load->view('includes/modal'); //include modal HTML ?>

<?php $this->load->view('includes/image_view_modal'); //include modal HTML ?>

<?php $this->load->view('includes/offline_error_modal'); //include offline error modal HTML ?>

<?php
$billing_only_access = false;
$subscription_notice = 'Your subscription has been cancelled or expired. Please renew to continue using Boost Accounting.';
$current_uri = trim(uri_string(), '/');
$is_billing_page = strpos($current_uri, 'billing') === 0;
$is_admin_page = strpos($current_uri, 'admin') === 0;
$content_user_data = isset($request['user_data']) ? (array)$request['user_data'] : array();
$is_super_admin = (isset($content_user_data['email']) && ($content_user_data['email'] == 'babu313136@gmail.com' || $content_user_data['email'] == 'darren@boostaccounting.com'));

if (!$is_super_admin && !$is_admin_page) {
    $billing_status_response = $this->curl->rest_api_call('GET', 'billing/status');
    if (isset($billing_status_response['status']) && $billing_status_response['status'] === 'OK' && isset($billing_status_response['data']['subscription'])) {
        $subscription = $billing_status_response['data']['subscription'];
        $subscription_status = isset($subscription['status']) ? strtolower($subscription['status']) : '';
        $can_pay = isset($subscription['can_pay']) ? (bool)$subscription['can_pay'] : false;
        $grace_ends_at = !empty($subscription['grace_period_ends_at']) ? strtotime($subscription['grace_period_ends_at']) : false;
        $has_grace_access = ($subscription_status === 'grace_period') || ($grace_ends_at && $grace_ends_at > time());
        $blocked_statuses = array('cancelled', 'expired', 'past_due');
        $billing_only_access = $can_pay && in_array($subscription_status, $blocked_statuses, true) && !$has_grace_access;

        if (!empty($subscription['access_message'])) {
            $subscription_notice = $subscription['access_message'];
        }

        if ($has_grace_access && !$billing_only_access && isset($request) && is_array($request)) {
            $request['subscription_warning_message'] = $subscription_notice;
            $request['subscription_warning_link'] = base_url('billing');
        }
    }
}

if ($billing_only_access && !$is_billing_page) {
    redirect('billing?subscription_notice=' . urlencode($subscription_notice));
    exit;
}

if (isset($request) && is_array($request)) {
    $request['billing_only_access'] = $billing_only_access;
}
?>

<!-- START main content loading overlay -->
<div class="main_content_loader">
    <img src="<?php echo base_url('images/logo_upload_waiting.gif'); ?>" alt="Loading..." />
</div>
<!-- END main content loading overlay  -->

<?php $this->load->view('includes/header',$request); //include header with menu and subheader ?>


<!-- START content area -->
<main>
    <div class="outerContainer_table">
        <div class="tableRow">
            <div class="tableCell contentMainLeft">
                <!-- START main content area -->
                <?php $this->load->view($page['main_view']); ?>
                <!-- END main content area -->
            </div>
            <div class="tableCell activityBar hidden-xs" style="position:relative;">
                <!-- START activeity content area -->
                <a class="activity_toggle activity-menu-icon">
                    <div class="patty"></div>
                </a>
                <div class="activityBar_outer">
                    <div class="activityBar_inner">
                        <div id="activity_bar_content" data-activity-category="<?php echo @$activity['category']; ?>"
                            data-activity-document-id="<?php echo @$activity['document_id']; ?>">
                            <div id="activity_heading">
                                <h4><?php if(isset($activity['heading'])){ echo $activity['heading']; }else{ echo 'All Activity'; }?>
                                </h4>
                            </div>
                            <div id="activity_data">
                            </div>
                            <div id="activity_loader">
                                <img src="<?php echo base_url('images/activity-bar-loader.gif'); ?>" />
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END activeity content area -->
            </div>
        </div>
    </div>
</main>
<!-- END content area -->

<?php $this->load->view('includes/footer'); //include footer ?>

<?php $this->load->view('includes/html_post_content'); //End HTML ?>
