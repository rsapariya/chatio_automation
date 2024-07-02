<form class="form-login">
    <div class="row">
        <div class="col-md-12 text-center mb-4">
            <!--<img alt="logo" src="assets/img/logo-3.png" class="theme-logo">-->
        </div>
        <div class="col-md-12">
            <label for="inputEmail" class="sr-only">Email address</label>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="icon-inputEmail"><i class="flaticon-user-7"></i> </span>
                </div>
                <input type="email" id="inputEmail" class="form-control" placeholder="Email Address" aria-describedby="inputEmail" required >
            </div>
            <button class="btn btn-lg btn-gradient-warning btn-block btn-rounded mb-4 mt-5" type="submit">Send Request</button>
        </div>
        <div class="col-md-12">
            <div class="login-text text-center">
                <p class="mt-3 text-white">New Here? <a href="<?php echo BASE_URL . 'register' ?>" class="">Register </a> a new user !</p>
            </div>
        </div>

    </div>
</form>   