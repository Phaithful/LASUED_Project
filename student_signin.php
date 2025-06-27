<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Sign In</title>

    <!-- Documents Links  -->
    <link rel="icon" href="images/favicon.png" type="image/png">
    <link rel="stylesheet" href="assets/css/student_signin.css">
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
            LOGIN
        </h2>

        <div>
            <form action="" class="container p-5 mt-4">


                <div class="mb-3">
                    <label for="matric" class="form-label">Matric Number</label>
                    <input type="text" id="matric" name="matric" class="form-control" placeholder="PHY/23/23453">
                </div>

                <div class="mb-3 password_div">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="**********">
                    <span class="eye-icon fas fa-thin fa-eye-slash" id="togglePassword"></span>  
                </div>

                <p class="mt-3 sign_in_link">Not a user? <a href="student_reg.html">Register here</a></p>

                <input type="submit" class="btn w-100" value="Login" onclick="this.disabled=true; this.form.submit();">
            </form>

        </div>
    </section>
    

    <script src="assets/js/student_signin.js"></script>
</body>
</html>