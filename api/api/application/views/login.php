<div class="container top-content">

    <div class="col-md-4 col-lg-4 col-md-offset-4 col-lg-offset-4">

        <?php echo form_open('login/validate_credentials'); ?>

        <div class="form-group">
            <label for="username">Username</label>
            <?php echo form_input('username', '', 'id="username" class="form-control" placeholder="Username"'); ?>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <?php echo form_password('password', '', 'id="password" class="form-control" placeholder="Password"'); ?>
        </div>

        <div class="form-group text-center">
            <?php echo form_submit('submit', 'LOGIN', 'class="btn btn-default2"'); ?>
        </div>

        <?php echo form_close(); ?>

    </div>

</div>