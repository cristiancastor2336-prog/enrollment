<?php

include("../../backend/connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_subject'])) {
    try {
        $subject_id = $_POST['subject_id'];
        $subject_code = $_POST['subject_code'];
        $description = $_POST['description'];
        $fees = $_POST['fees'];
        $teacher = $_POST['teacher'];
        $status = $_POST['status'];

        $query = "UPDATE tb_subject_add SET 
                  subject_code = :subject_code, 
                  description = :description, 
                  fees = :fees, 
                  teacher = :teacher, 
                  status = :status 
                  WHERE subjectid = :subject_id";

        $stmt = $conn->prepare($query);
        $stmt->bindParam(':subject_code', $subject_code);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':fees', $fees);
        $stmt->bindParam(':teacher', $teacher);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':subject_id', $subject_id);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Subject updated successfully!";
        } else {
            $_SESSION['error'] = "Failed to update subject.";
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Database error: " . $e->getMessage();
    }

    header('Location: ../handle_subject.php');
    exit();
}

?>