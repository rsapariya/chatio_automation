<form class="form-login" method="post" action="<?php echo BASE_URL . 'login_post' ?>">
    <div class="row">
        <div class="col-md-12 mb-1">
            <?php $this->load->view('Partial/alert_view'); ?> 
        </div>
        <div class="col-md-12">
            <label for="inputEmail" class="sr-only">Email address</label>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="icon-inputEmail"><i class="flaticon-user-7"></i> </span>
                </div>
                <input type="email" id="inputEmail" name="username" class="form-control" placeholder="Email Address" aria-describedby="inputEmail" required >
            </div>

            <label for="inputPassword" class="sr-only">Password</label>                
            <div class="input-group mb-4">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="icon-inputPassword"><i class="flaticon-key-2"></i> </span>
                </div>
                <input type="password" id="inputPassword" name="password" class="form-control" placeholder="Password" aria-describedby="inputPassword" required >
            </div>

            <!--            <div class="checkbox d-flex justify-content-center mt-3">
                            <div class="custom-control custom-checkbox mr-3">
                                <input type="checkbox" class="custom-control-input" id="customCheck1" value="remember-me">
                                <label class="custom-control-label" for="customCheck1">Remember me</label>
                            </div>
                        </div>-->

            <button class="btn btn-lg btn-gradient-warning btn-block btn-rounded mb-4 mt-5" type="submit">Login</button>

            <!--            <div class="forgot-pass text-center">
                            <a href="<?php echo BASE_URL . 'forgot_password' ?>">Forgot Password ?</a>
                        </div>-->

        </div>
        <div class="col-md-12">
            <div class="login-text text-center">
                <p class="mt-3 text-white">New Here? <a href="<?php echo BASE_URL . 'register' ?>" class="">Register </a> a new user !</p>
            </div>
        </div>

    </div>
</form>   