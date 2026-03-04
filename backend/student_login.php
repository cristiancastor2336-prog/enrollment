<?php 
session_start();
include("connection.php");

if (isset($_POST["login"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $query = "SELECT * FROM tb_student_info WHERE email = :email";
    $query_stmt = $conn->prepare($query);
    $query_stmt->execute([":email" => $email]);
    $student = $query_stmt->fetch(PDO::FETCH_ASSOC);

    if ($student && password_verify($password, $student["password"])) {
        $_SESSION["studentid"] = $student["studentid"];
        $_SESSION["schoolid"] = $student["schoolid"];
        $_SESSION["firstname"] = $student["firstname"];
        $_SESSION["lastname"] = $student["lastname"];
        $_SESSION["role"] = "student";

        echo "<script> alert('login succesfully');
        window.location = '../index.php';
        </script>";
        exit();
    }

    $query1 = "SELECT * FROM tb_admin_info WHERE email = :email";
    $query_stmt1 = $conn->prepare($query1);
    $query_stmt1->execute([":email" => $email]);
    $admin = $query_stmt1->fetch(PDO::FETCH_ASSOC);

    if ($admin && $password === $admin["password"]) {
        $_SESSION["adminid"] = $admin["adminid"];
        $_SESSION["firstname"] = $admin["firstname"];
        $_SESSION["lastname"] = $admin["lastname"];
        $_SESSION["role"] = "admin";

        echo "<script> alert('login succesfully');
        window.location = '../admin/admin_dashboard.php';
        </script>";
        exit();
    }
    else {
        echo "<script>alert('Login failed!'); window.location = '../login.php';</script>";
        exit();
    }
    
   
}
?>