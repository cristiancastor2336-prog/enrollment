<?php

include 'backend/connection.php';

session_start();
if (!isset($_SESSION["studentid"]) || !$_SESSION["schoolid"] || !$_SESSION["firstname"] || !$_SESSION["lastname"] || $_SESSION["role"] !== "student") {
    header('Location: login.php');
    exit();
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student | Dashboard</title>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/3.0.0/uicons-thin-rounded/css/uicons-thin-rounded.css'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <style>
        <?php
        include 'design/dashboard.css';
        ?>
    </style>
</head>

<body>

    <header>
        <div class="logo">
            <p style="font-family: cursive; color: navy;">EnrollSys</p>
        </div>

        <div class="user-profile">
            <div class="user-avatar">
                <?php echo strtoupper(substr($_SESSION['firstname'], 0, 1)); ?>
            </div>
            <div class="user-info">
                <span
                    class="user-name"><?php echo htmlspecialchars($_SESSION['firstname'] . ' ' . $_SESSION['lastname']); ?></span>
                <span class="user-role">Student</span>
            </div>
        </div>
    </header>

    <aside class="sidebar">
        <ul class="menu">
            <li>
                <a href="index.php" class="active">
                    <i class="fi fi-tr-house-window"></i>
                    Dashboard
                </a>
            </li>
            <li>
                <a href="enroll.php">
                    <i class="fi fi-tr-books"></i>
                    Subject List
                </a>
            </li>

            <li>
                <a href="enroll_status.php">
                    <i class="fi fi-tr-file-signature"></i>
                    Enroll
                </a>
            </li>
            <li>
                <a href="payment.php">
                    <i class="fi fi-tr-expense"></i>
                    Payment
                </a>
            </li>
        </ul>
        <a style="text-decoration: none; bottom: 0px; position: absolute;" href="backend/logout.php" class="logout-btn">
            <i class="fi fi-tr-user-logout"></i>
            Logout
        </a>
    </aside>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</body>

</html>