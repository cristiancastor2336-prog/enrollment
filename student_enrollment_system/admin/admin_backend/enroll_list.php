<?php

include("../../backend/connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_enroll_status'])) {
    try {
        $subject_id = $_POST['enrollid'];
        $status = $_POST['status'];

        $query = "UPDATE tb_enroll_subject SET  
                  status = :status 
                  WHERE enrollid = :enrollid";

        $stmt = $conn->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':enrollid', $subject_id);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Subject updated successfully!";
        } else {
            $_SESSION['error'] = "Failed to update subject.";
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Database error: " . $e->getMessage();
    }

    header('Location: ../student_enroll_list.php');
    exit();
}

?>