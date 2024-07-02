<form class="form-login" method="post" action="<?php echo BASE_URL . 'register_post' ?>">
    <div class="row">
        <div class="col-md-12 mb-3">
            <?php $this->load->view('Partial/alert_view'); ?> 
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 mb-3">
            <h2>Sign Up</h2>
            <p>Enter your email and password to register</p> 
        </div>
        
        <div class="col-md-12">
            <div class="input-group mb-3">
                <span class="input-group-text" id="name-addon"><i class="fa fa-user"></i></span>
                <input type="text" id="inputName" name="name" class="form-control" placeholder="Name" aria-label="Name" aria-describedby="name-addon" required >
            </div>
        </div>
        <div class="col-md-12">
            <div class="input-group mb-3">
                <span class="input-group-text" id="email-addon"><i class="fa fa-envelope"></i></span>
                <input type="email" class="form-control" id="inputEmail" name="email" placeholder="Email Address" aria-label="Email" aria-describedby="email-addon" required>
            </div>
        </div>
        <div class="col-12">
            <div class="input-group mb-3">
                <span class="input-group-text" id="password-addon"><i class="fa fa-lock"></i></span>
                <input type="password" class="form-control" id="inputPassword" name="password" placeholder="Password" aria-label="Password" aria-describedby="password-addon" required>
            </div>
        </div>
        <div class="col-12">
            <div class="input-group mb-3">
                <span class="input-group-text" id="phone-addon"><i class="fa fa-mobile"></i></span>
                <input type="text" class="form-control" id="inputPhone" name="phone_number" placeholder="Phone" aria-label="Phone" aria-describedby="phone-addon" required>
            </div>
        </div>                                        
        <div class="col-12">
            <div class="mb-4">
                <button class="btn btn-secondary w-100" type="submit">SIGN UP</button>
            </div>
        </div>
        <div class="col-12">
            <div class="text-center">
                <p class="mb-0">Already have an account ? <a href="<?php echo BASE_URL . 'login' ?>" class="text-warning">Sign In</a></p>
            </div>
        </div>                                    
    </div>
</form>