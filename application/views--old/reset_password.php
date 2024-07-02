<form class="form-login">
    <div class="row">
        <div class="col-md-12 text-center mb-4">
            <!--<img alt="logo" src="assets/img/logo-3.png" class="theme-logo">-->
        </div>

        <div class="col-md-12">
            <label for="inputNewPassword" class="sr-only">New Password</label>                
            <div class="input-group mb-4">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="icon-inputNewPassword"><i class="flaticon-key-2"></i> </span>
                </div>
                <input type="password" id="inputNewPassword" class="form-control" placeholder="New Password" aria-describedby="inputNewPassword" required >
            </div>

            <label for="inputConfirmPassword" class="sr-only">Confirm Password</label>

            <div class="input-group mb-4">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="icon-inputConfirmPassword"><i class="flaticon-key-2"></i> </span>
                </div>
                <input type="password" id="inputConfirmPassword" class="form-control" placeholder="Confirm Password" aria-describedby="inputConfirmPassword" required >
            </div>

            <button class="btn btn-lg btn-gradient-warning btn-block btn-rounded mb-4 mt-5" type="submit">Reset</button>                
        </div>

        <div class="col-md-12">
            <div class="login-text text-center">
                <p class="mt-3 text-white">Not you? <a href="<?php echo BASE_URL ?>" class="">Sign in </a> as different user!</p>
            </div>
        </div>
    </div>
</form>