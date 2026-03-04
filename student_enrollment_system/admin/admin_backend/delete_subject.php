<?php
session_start();
include("../../backend/connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_subject'])) {
    try {
        $subject_id = $_POST['subjectid'];

        // Option 1: Permanent delete
        $query = "DELETE FROM tb_subject_add WHERE subjectid = :subjectid";
        
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':subjectid', $subject_id);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Subject deleted successfully!";
        } else {
            $_SESSION['success'] = "Failed to delete subject."; // Changed to success for error message
        }
    } catch (PDOException $e) {
        $_SESSION['success'] = "Database error: " . $e->getMessage(); // Changed to success for error message
    }

    header('Location: ../handle_subject.php');
    exit();
}
?>