<form class="form-register" method="post" action="<?php echo BASE_URL . 'register_post' ?>">
    <div class="row">
        <div class="col-md-12 mb-1">
            <?php $this->load->view('Partial/alert_view'); ?> 
        </div>

        <div class="col-md-12">
            <label for="inputName" class="sr-only">Name</label>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="icon-username"><i class="flaticon-user-7"></i> </span>
                </div>
                <input type="text" id="inputName" name="name" class="form-control" placeholder="Name" aria-describedby="inputName" required >
            </div>

            <label for="inputEmail" class="sr-only">Email address</label>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="icon-inputEmail"><i class="flaticon-email-fill-2"></i> </span>
                </div>
                <input type="email" id="inputEmail"  name="email" class="form-control" placeholder="Email Address" aria-describedby="inputEmail" required >
            </div>

            <label for="inputPassword" class="sr-only">Password</label>
            <div class="input-group mb-4">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="icon-inputPassword"><i class="flaticon-key-2"></i> </span>
                </div>
                <input type="password" id="inputPassword" name="password" class="form-control" placeholder="Password" aria-describedby="inputPassword" required >
            </div>

            <label for="inputPhone" class="sr-only">Phone Number</label>
            <div class="input-group mb-4">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="icon-inputPhone"><i class="flaticon-key-2"></i> </span>
                </div>
                <input type="text" id="inputPhone"  name="phone_number"  class="form-control" placeholder="Phone Number" aria-describedby="inputPhone" required >
            </div>

            <button class="btn btn-lg btn-gradient-warning btn-block btn-rounded mb-4 mt-5" type="submit">Register</button>
            <!--            <div class="forgot-pass text-center">
                            <a href="<?php echo BASE_URL . 'forgot_password' ?>">Forgot Password ?</a>
                        </div>-->
        </div>
        <div class="col-md-12">
            <div class="login-text text-center">
                <p class="mt-3 text-white">Already Here? <a href="<?php echo BASE_URL ?>" class="">Login </a> a user !</p>
            </div>
        </div>
    </div>
</form>