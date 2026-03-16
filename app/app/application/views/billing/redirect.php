<?php $this->load->view('includes/html_pre_content'); ?>

<div class="outerContainer_table login_table">
    <div class="tableRow">
        <div class="tableCell">
            <div class="login_container" style="max-width:520px;">
                <div class="login_logo_container col-sm-12">
                    <img src="<?php echo base_url('images/boost_medium_logo.png'); ?>" alt="Boost">
                </div>
                <div class="login_form_container" style="padding:20px;">
                    <h3>Redirecting To PayFast</h3>
                    <p>Please wait while we send you to PayFast to complete your subscription payment.</p>
                    <form id="payfast_redirect_form" method="post" action="<?php echo $payment_url; ?>" data-native-submit="true">
                        <?php foreach ($fields as $key => $value) : ?>
                            <input type="hidden" name="<?php echo htmlspecialchars($key); ?>" value="<?php echo htmlspecialchars($value); ?>">
                        <?php endforeach; ?>
                        <button class="btn btn-success" type="submit">Continue To PayFast</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
window.setTimeout(function () {
    var form = document.getElementById('payfast_redirect_form');
    if (form) {
        form.submit();
    }
}, 400);
</script>

<?php $this->load->view('includes/html_post_content'); ?>
