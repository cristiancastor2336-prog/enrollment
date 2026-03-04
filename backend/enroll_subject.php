<?php
include 'connection.php';

if (isset($_POST["enroll_subject"])) {
    $studentid = $_POST["studentid"];
    $schoolid = $_POST["schoolid"];
    $subject_code = $_POST["subject_code"];
    $description = $_POST["description"];
    $fees = $_POST["fees"];
    $teacher = $_POST["teacher"];
    $status = $_POST["status"];

    try {
        $update_query = "UPDATE tb_subject_add 
                         SET studentid = :studentid 
                         WHERE subject_code = :subject_code";

        $update_run = $conn->prepare($update_query);
        $update_data = [
            ":studentid" => $studentid,
            ":subject_code" => $subject_code
        ];

        if (!$update_run->execute($update_data)) {
            throw new Exception("Update failed");
        }


        $query = "INSERT INTO tb_enroll_subject (studentid, schoolid, subject_code, description, fees, teacher, status) 
              VALUES (:studentid, :schoolid, :subject_code, :description, :fees, :teacher, 'Pending')";

        $query_run = $conn->prepare($query);

        $data = [
            ":studentid" => $studentid,
            ":schoolid" => $schoolid,
            ":subject_code" => $subject_code,
            ":description" => $description,
            ":fees" => $fees,
            ":teacher" => $teacher

        ];

        if ($query_run->execute($data)) {
            echo "<script>
                alert(' Subject enroll successfully!');
                window.location = '../enroll.php';
              </script>";
        } else {
            echo "<script>
                alert('Add subject failed!');
                window.location = '../enroll.php';
              </script>";
        }
    } catch (Exception $e) {
        $conn->rollBack();
        echo "<script>
                alert('Enrollment failed: " . $e->getMessage() . "');
                window.location = '../enroll.php';
              </script>";
    }

}
?>