<?php
session_start();

include_once("libs/dbfunctions.php");

$dbobject = new dbobject();

// echo $dbobject->generateHTTP();
header("Cache-Control: no-cache;no-store, must-revalidate");
header_remove("X-Powered-By");
header_remove("Server");
header('X-Frame-Options: SAMEORIGIN');

$crossorigin = 'anonymous';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration</title>

    <!-- Documents Links  -->
    <link rel="icon" href="images/favicon.png" type="image/png">
    <link rel="stylesheet" href="assets/css/student_reg.css">
	<!-- <script src="js/settings.js"></script> -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

</head>
<body>

    <section>
        <h1>
            DIRECTORATE OF ENTERPRENEURSHIP AND SKILL DEVELOPMENT
        </h1>
        <h2>
            Create an account
        </h2>

        <div>
<<<<<<< HEAD
            <form id="form1" onsubmit="return false" autocomplete="off" class="container p-4 mt-4">
                <input type="hidden" name="op" value="Users.registerUser">
=======
            <form action="" class="container p-5 mt-4">
>>>>>>> 213a148d04280124804887e65b9889f20ba22d63
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="fname" class="form-label">First Name</label>
                        <input type="text" id="fname" name="firstname" class="form-control" placeholder="John">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="lname" class="form-label">Last Name</label>
                        <input type="text" id="lname" name="lastname" class="form-control" placeholder="Doe">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email" class="form-control" placeholder="johndoe@gmail.com">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="matric" class="form-label">Matric Number</label>
                        <input type="text" id="matric" name="matric" class="form-control" placeholder="PHY/23/23453">
                    </div>
                </div>

                <div class="mb-3 password_div">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="**********">
                    <span class="eye-icon fas fa-thin fa-eye-slash" id="togglePassword"></span>  
                </div>

                <div class="mb-3 cpassword_div">
                    <label for="cpassword" class="form-label">Confirm Password</label>
                    <input type="password" id="cpassword" name="confirm_password" class="form-control" placeholder="**********">
                    <span class="eye-icon fas fa-thin fa-eye-slash" id="toggleCPassword"></span>  
                </div>

                <p class="mt-3 sign_in_link">Already a user? <a href="student_signin.php">Sign in here</a></p>

                <div class="form-check mb-3">
                    <input class="form-check-input check_box" type="checkbox" name="terms" id="flexCheckDefault" required>
                    <label class="form-check-label" for="flexCheckDefault">
                    I agree to all the terms, privacy policy and fees.
                    </label>
                </div>
				<div id="server_mssg"></div>
                <input type="submit" class="btn w-100" value="Create Account" id="button" onclick="registerUser('form1')">
            </form>

        </div>
    </section>
    <script src="js/jquery-3.6.0.min.js" ></script>
	<script src="js/jquery.blockUI.js" ></script>
	<script src="js/parsely.js" ></script>

	<script src="js/sweet_alerts.js" ></script>
	<script src="js/main.js"></script>

    <script src="assets/js/student_reg.js"></script>
    	<script>
		function registerUser(id) {
			var forms = $('#' + id);
			forms.parsley().validate();
			if (forms.parsley().isValid()) {
				$.blockUI();
				var data = $("#" + id).serialize();
				$.ajax({
					type: "post",
					url: "utilities_default.php",
					data: data,
					dataType: "json",
					beforeSend: function() {
						$.blockUI({
							message: "Processing..... Please wait...",
						});
					},
					success: function(data) {
						$.unblockUI();

						if (data.response_code == 0) {
							$("#button").attr("disabled", true);
							$("#server_mssg").text(data.response_message);
							setTimeout(() => {
								window.location = 'index.php';
							}, 2000);
						} else {
							// regenerateCORS();
							$("#server_mssg").html(data.response_message);
						}
					},
					error: function(data) {
						// regenerateCORS();
						$.unblockUI();
						$("#server_mssg").html("Unable to process request at the moment! Please try again");
					},
				});
			}
		}
	</script>
</body>
</html>