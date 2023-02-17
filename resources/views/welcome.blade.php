<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>iHomp | Login</title>

        <link rel="stylesheet" href="<?php echo asset('vendor/bootstrap/css/bootstrap.min.css')?>" type="text/css">
        <link rel="stylesheet" href="<?php echo asset('fonts/font-awesome-4.7.0/css/font-awesome.min.css')?>" type="text/css">
        <link rel="stylesheet" href="<?php echo asset('vendor/animate/animate.css')?>" type="text/css">
        <link rel="stylesheet" href="<?php echo asset('vendor/css-hamburgers/hamburgers.min.css')?>" type="text/css">
        <link rel="stylesheet" href="<?php echo asset('vendor/select2/select2.min.css')?>" type="text/css">
        <link rel="stylesheet" href="<?php echo asset('css/util.css')?>" type="text/css">
        <link rel="stylesheet" href="<?php echo asset('css/main.css')?>" type="text/css">

    </head>
    <body>
        <div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100" style="padding: 94px 130px 92px 75px">
				<div class="login100-pic js-tilt" data-tilt>
					<img style="margin-top: 5px;" src="images/img-02.png" alt="IMG">
				</div>

				<form class="login100-form validate-form">
					<span class="login100-form-title" style="padding-bottom: 40px;">
                        <center>
                            <div style="width: 54%; margin-bottom: 50px; padding: 5px; border-bottom: solid #3732c6 5px"  >
                                AUTH LOGIN
                            </div>
                        <center>
                    </span>

					<div class="wrap-input100 validate-input" data-validate = "Valid email is required: ex@abc.xyz">
						<input class="input100" type="text" id="username" name="username" placeholder="Username">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-envelope" aria-hidden="true"></i>
						</span>
					</div>

					<div class="wrap-input100 validate-input" data-validate = "Password is required">
						<input class="input100" id="password" type="password" name="pass" placeholder="Password">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-lock" aria-hidden="true"></i>
						</span>
					</div>
                    <div>
                        <p id='errorMessage' style="margin-left: 10px; color: #ff6969;"></p>
                    </div>

					<div class="container-login100-form-btn">
						<button onclick="loginAuth()" class="login100-form-btn">
							Login
						</button>
                    </div>


				</form>
			</div>
		</div>
	</div>
    </body>

        <!----Script ---->
        <script src="<?php echo asset('vendor/jquery/jquery-3.2.1.min.js')?>"></script>
        <script src="<?php echo asset('vendor/bootstrap/js/popper.js')?>"></script>
        <script src="<?php echo asset('vendor/bootstrap/js/bootstrap.min.js')?>"></script>
        <script src="<?php echo asset('vendor/select2/select2.min.js')?>"></script>
        <script src="<?php echo asset('vendor/tilt/tilt.jquery.min.js')?>"></script>
        <script >
            $('.js-tilt').tilt({
                scale: 1.1
            })
            function loginAuth(){
                base_url = "{{url('/loginAuth')}}";
                event.preventDefault();
                $.post( base_url, {
                    _token: "{{ csrf_token() }}",
                    username: $("#username").val(),
                    password: $("#password").val()
                }, function( data ) {
                    window.location.href = "{{URL::to('/landingPage')}}";

                }).fail(function(e) {
                    $( "#errorMessage" ).html("Invalid username or password")
                });
            }



        </script>
        <script src="js/main.js"></script>
</html>
