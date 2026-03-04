<?php
session_start();

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student || Login</title>
    <!-- <link rel="stylesheet" href="design/login_register.css?v=1"> -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <style>
        <?php
        include 'design/login_register.css';
        ?>
    </style>

</head>

<body>
    <div class="login-form">
        <div class="card">
            <form action="backend/student_login.php" method="POST">
                <div class="input-container">
                    <div>
                        <label class="input-label" for="email">Email Address</label>
                        <input class="input-field" type="email" name="email" required>
                    </div>
                    <div>
                        <label class="input-label" for="password">Password</label>
                        <input class="input-field" type="password" name="password" required>
                    </div>
                </div>
                <div class="button-container">
                    <button class="login-button" type="submit" name="login">
                        Login
                    </button>
                </div>
                <div class="link-register">
                    <p><span class="register-link">You don't have an account?</span> <a class="link"
                            href="register.php">Register here!</a></p>
                </div>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</body>

</html>