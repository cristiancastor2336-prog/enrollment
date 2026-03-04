<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link rel="stylesheet" href="design/register.css?v=1"> -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <title>Student || Register</title>

    <style>
        <?php
        include 'design/register.css';
        ?>
    </style>
</head>

<body>

    <div class="register-form">
        <div class="card">
            <form action="backend/student_register.php" method="POST">
                <div class="input-container">
                    <div class="input-label-container">
                        <div class="firstname col-md-6">
                            <label class="input-label" for="firstname">First Name</label>
                            <input class="input-field" type="text" name="firstname" required>
                        </div>
                        <div class="lastname col-md-6">
                            <label class="input-label" for="lastname">Last Name</label>
                            <input class="input-field" type="text" name="lastname" required>
                        </div>
                    </div>
                    <div>
                        <label class="input-label" for="course">Course</label>
                        <select class="input-field" id="course" name="course" required>
                            <option value="" disabled selected>Select a course</option>
                            <option value="bs-info-tech">BS Information Technology</option>
                            <option value="bs-hm">BS HOSPITALITY MANAGEMENT</option>
                            <option value="bs-it">BS INDUSTRIAL TECHNOLOGY</option>
                            <option value="bs-education">BS EDUCATION</option>
                            <option value="bs-crim">BS CRIM</option>
                        </select>
                    </div>
                    <div class="input-label-container">
                        <div class="firstname col-md-6">
                            <label class="input-label" for="age">Age</label>
                            <input class="input-field" type="number" name="age" required>
                        </div>
                        <div class="lastname col-md-6">
                            <label class="input-label" for="schoolid">School ID</label>
                            <input class="input-field" type="text" name="schoolid" pattern="[0-9]{2}-[0-9]{4}" maxlength="7" title="wrong format the form should(24-1232)" required>
                        </div>
                    </div>

                    <div>
                        <label class="input-label" for="email">Email Address</label>
                        <input class="input-field" type="email" name="email" required>
                    </div>
                    <div class="password-body">
                        <label class="input-label" for="password">Password</label>
                        <input class="input-field" type="password" name="password" required>
                    </div>

                    <div class="button-container">
                        <button type="submit" name="register">
                            Register
                        </button>
                    </div>
                    <div class="link-login">
                        <p><span class="login-link">You already have an account?</span> <a href="login.php">Login
                                here!</a></p>
                    </div>

                </div>
            </form>
        </div>

    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</body>

</html>