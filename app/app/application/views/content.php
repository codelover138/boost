<?php $this->load->view('includes/html_pre_content'); //start HTML and include css,js,metatags etc ?>

<?php $this->load->view('includes/modal'); //include modal HTML ?>

<?php $this->load->view('includes/image_view_modal'); //include modal HTML ?>

<?php $this->load->view('includes/offline_error_modal'); //include offline error modal HTML ?>

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