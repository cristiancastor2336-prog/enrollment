<?php
include("../../backend/connection.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once "../../PHPMailer-master/src/PHPMailer.php";
require_once "../../PHPMailer-master/src/SMTP.php";
require_once "../../PHPMailer-master/src/Exception.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_payment_status'])) {

    try {
        $conn->beginTransaction();

        $payment_id = $_POST['paymentid'];
        $status = $_POST['status'];

        $getQuery = "SELECT pt.enrollid, pt.fees, pt.cost, pt.status, es.subject_code, es.studentid
                     FROM tb_payment_transaction pt
                     INNER JOIN tb_enroll_subject es ON pt.enrollid = es.enrollid
                     WHERE pt.paymentid = :paymentid";

        $getStmt = $conn->prepare($getQuery);
        $getStmt->bindParam(':paymentid', $payment_id);
        $getStmt->execute();
        $payment = $getStmt->fetch(PDO::FETCH_ASSOC);

        if (!$payment) {
            throw new Exception("Payment record not found.");
        }

        $enroll_id = $payment['enrollid'];
        $newFees = $payment['fees'];
        $subject_code = $payment['subject_code'];
        $studentid = $payment['studentid'];
        $cost = $payment['cost'];

        $emailQuery = "SELECT email, firstname, lastname FROM tb_student_info WHERE studentid = :studentid";
        $emailStmt = $conn->prepare($emailQuery);
        $emailStmt->bindParam(':studentid', $studentid);
        $emailStmt->execute();
        $student = $emailStmt->fetch(PDO::FETCH_ASSOC);

        $student_email = $student ? $student['email'] : 'admin@school.com';
        $student_name = $student ? $student['firstname'] . ' ' . $student['lastname'] : 'Student';

        if ($status === 'Confirmed' && $payment['status'] !== 'Confirmed') {
            $newFees = $payment['fees'] - $payment['cost'];

            if ($newFees < 0) {
                $newFees = 0;
            }
        }

        $updatePayment = "UPDATE tb_payment_transaction
                          SET status = :status,
                              fees = :fees
                          WHERE paymentid = :paymentid";

        $stmt1 = $conn->prepare($updatePayment);
        $stmt1->bindParam(':status', $status);
        $stmt1->bindParam(':fees', $newFees);
        $stmt1->bindParam(':paymentid', $payment_id);
        $stmt1->execute();

        // Update tb_enroll_subject
        // $updateEnroll = "UPDATE tb_enroll_subject
        //                  SET status = :status,
        //                      fees = :fees
        //                  WHERE enrollid = :enrollid";

        // $stmt2 = $conn->prepare($updateEnroll);
        // $stmt2->bindParam(':status', $status);
        // $stmt2->bindParam(':fees', $newFees);
        // $stmt2->bindParam(':enrollid', $enroll_id);
        // $stmt2->execute();

        // Send email only if status is Confirmed
        if ($status === 'Confirmed') {
            $mail = new PHPMailer(true);

            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->Port = 587;
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->SMTPAuth = true;
                $mail->Username = 'cristiancastor13@gmail.com';
                $mail->Password = 'owqnwqxekvxippcv';

                $mail->SMTPOptions = [
                    'ssl' => [
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    ]
                ];

                $mail->setFrom('cristiancastor13@gmail.com', 'School Payment System');
                $mail->addAddress($student_email);
                $mail->addCC('cristiancastor13@gmail.com');
                $mail->isHTML(true);
                $mail->Subject = 'Payment Confirmation - Subject: ' . $subject_code;

                $mail->Body = '<h2>Payment Confirmation</h2>';
                $mail->Body .= '<p>Dear ' . htmlspecialchars($student_name) . ',</p>';
                $mail->Body .= '<p>Your payment has been received and confirmed.</p>';
                $mail->Body .= '<ul>';
                $mail->Body .= '<li><strong>Student ID:</strong> ' . htmlspecialchars($studentid) . '</li>';
                $mail->Body .= '<li><strong>Subject Code:</strong> ' . htmlspecialchars($subject_code) . '</li>';
                $mail->Body .= '<li><strong>Amount Paid:</strong> ' . number_format($cost) . '</li>';
                $mail->Body .= '<li><strong>Remaining Fees:</strong> ' . number_format($newFees) . '</li>';
                // $mail->Body .= '<li><strong>Date:</strong> ' . date('F d, Y H:i A') . '</li>';
                $mail->Body .= '</ul>';
                $mail->Body .= '<p>Thank you for your payment!</p>';
                $mail->Body .= '<p>Best regards,<br>School Payment System</p>';

                if ($mail->send()) {
                } else {
                    error_log("Email failed to send for payment ID: " . $payment_id);
                }

            } catch (Exception $e) {
                error_log("PHPMailer Error: " . $e->getMessage());
            }
        }

        $conn->commit();

        echo "<script>alert('Payment updated and confirmed successfully!');</script>";
        echo "<script>window.location.href='../handle_payment.php';</script>";

    } catch (Exception $e) {

        $conn->rollBack();
        echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
    }
}
?>