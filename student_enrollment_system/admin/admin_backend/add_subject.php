<?php
include("../../backend/connection.php");

if (isset($_POST["add_subject"])) {

    $subject_code = $_POST["subject_code"];
    $description = $_POST["description"];
    $fees = $_POST["fees"];
    $course = $_POST["course"];
    $teacher = $_POST["teacher"];
    $status = $_POST["status"];

    $query = "INSERT INTO tb_subject_add (subject_code, description, fees, course, teacher, status) 
              VALUES (:subject_code, :description, :fees, :course, :teacher, :status)";

    $query_run = $conn->prepare($query);

    $data = [
        ":subject_code" => $subject_code,
        ":description" => $description,
        ":fees" => $fees,
        ":course"=> $course,
        ":teacher" => $teacher,
        ":status" => $status
        
    ];

    if ($query_run->execute($data)) {
        echo "<script>
                alert('Add Subject successfully!');
                window.location = '../handle_subject.php';
              </script>";
    } else {
        echo "<script>
                alert('Add subject failed!');
                window.location = '../handle_subject.php';
              </script>";
    }
}
?>