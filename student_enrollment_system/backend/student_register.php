<?php
include("connection.php");

if (isset($_POST["register"])) {
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $course = $_POST["course"];
    $age = $_POST["age"];
    $schoolid = $_POST["schoolid"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT studentid FROM tb_student_info WHERE email = :email");
    $stmt->execute([":email" => $email]);

    if ($stmt->rowCount() > 0) {
        echo "<script> alert('Email already exists!');
        window.location = '../register.php';
        </script>";
    } 
    else {
        $stmt2 = $conn->prepare("SELECT studentid FROM tb_student_info WHERE schoolid = :schoolid");
        $stmt2->execute(["schoolid" => $schoolid]);

        if ($stmt2->rowCount() > 0) {
            echo "<script> alert('School ID already exists!');
        window.location = '../register.php';
        </script>";
        } 

        else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $query = "INSERT INTO tb_student_info (firstname, lastname, course, age, schoolid, email, password) 
                  VALUES(:firstname, :lastname, :course, :age, :schoolid, :email, :password)";

            $query_run = $conn->prepare($query);

            $data = [
                ":firstname" => $firstname,
                ":lastname" => $lastname,
                ":course" => $course,
                ":age" => $age,
                ":schoolid" => $schoolid,
                ":email" => $email,
                ":password" => $hashedPassword
            ];

            $query_execute = $query_run->execute($data);

            if ($query_execute) {
                echo "<script> alert('Register succesfully!');
            window.location = '../login.php';
            </script>";
            } else {
                echo "<script> alert('Register failed!');
            window.location = '../register.php';
            </script>";
            }
        }
    }

}
?>