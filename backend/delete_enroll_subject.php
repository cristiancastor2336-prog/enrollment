<?php
session_start();
include("connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_subject'])) {
    try {
        $subject_id = $_POST['enrollid'];

        $query = "DELETE FROM tb_enroll_subject WHERE enrollid = :enrollid";
        
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':enrollid', $subject_id);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Subject deleted successfully!";
        } else {
            $_SESSION['success'] = "Failed to delete subject."; // Changed to success for error message
        }
    } catch (PDOException $e) {
        $_SESSION['success'] = "Database error: " . $e->getMessage(); // Changed to success for error message
    }

    header('Location: ../enroll_status.php');
    exit();
}
?>