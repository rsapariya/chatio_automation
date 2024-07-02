    <form class="form-login" method="post" action="<?php echo BASE_URL . 'login_post' ?>">
    <div class="row">
        <div class="col-md-12 mb-3">
            <?php $this->load->view('Partial/alert_view'); ?> 
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 mb-3">
            <h2>Sign In</h2>
            <p>Enter your email and password to login</p> 
        </div>
        <div class="col-md-12">
            <div class="input-group mb-3">
                <span class="input-group-text" id="email-addon"><i class="fa fa-user"></i></span>
                <input type="email" class="form-control" id="inputEmail" name="username" placeholder="Email Address" aria-label="Username" aria-describedby="email-addon" required>
            </div>
        </div>
        <div class="col-12">
            <div class="input-group mb-3">
                <span class="input-group-text" id="password-addon"><i class="fa fa-lock"></i></span>
                <input type="password" class="form-control" id="inputPassword" name="password" placeholder="Password" aria-label="Username" aria-describedby="password-addon" required>
            </div>
        </div>
        <!--<div class="col-12">
            <div class="mb-3">
                <div class="form-check form-check-primary form-check-inline">
                    <input class="form-check-input me-3" type="checkbox" id="form-check-default">
                    <label class="form-check-label" for="form-check-default">
                        Remember me
                    </label>
                </div>
            </div>
        </div>-->                                    
        <div class="col-12">
            <div class="mb-4">
                <button class="btn btn-secondary w-100" type="submit">SIGN IN</button>
            </div>
        </div>
        <div class="col-12">
            <div class="text-center">
                <p class="mb-0">Dont't have an account ? <a href="<?php echo BASE_URL . 'register' ?>" class="text-warning">Sign Up</a></p>
            </div>
        </div>                                    
    </div>
</form>