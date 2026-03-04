<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include 'connection.php';

if (isset($_POST["payment_send"])) {

    $studentid = $_POST["studentid"];
    $schoolid = $_POST["schoolid"];
    $enrollid = $_POST["enrollid"];
    $subject_code = $_POST["subject_code"];
    $cost = $_POST["cost"];
    $fees = $_POST["fees"];

    require_once "../PHPMailer-master/src/PHPMailer.php";
    require_once "../PHPMailer-master/src/SMTP.php";
    require_once "../PHPMailer-master/src/Exception.php";

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

        $mail->setFrom('mail@gmail.com', 'School Payment System');
        $mail->addCC('cristiancastor2336@gmail.com');
        $mail->isHTML(true);
        $mail->Subject = 'Payment Confirmation - Subject: ' . $subject_code;

        $mail->Body = '<h2>Payment Confirmation</h2>';
        $mail->Body .= '<p>Dear Manog sukot,</p>';
        $mail->Body .= '<p>You have Recieve Payment .</p>';
        $mail->Body .= '<ul>';
        $mail->Body .= '<li><strong>School ID:</strong> ' . htmlspecialchars($schoolid) . '</li>';
        $mail->Body .= '<li><strong>Subject Code:</strong> ' . htmlspecialchars($subject_code) . '</li>';
        $mail->Body .= '<li><strong>Amount:</strong> ' . htmlspecialchars($cost) . '</li>';
        $mail->Body .= '</ul>';
        $mail->Body .= '<p>Thank you!</p>';

        if ($mail->send()) {

            $query = "INSERT INTO tb_payment_transaction 
                      (studentid, schoolid, enrollid, subject_code, cost, fees, status) 
                      VALUES (:studentid, :schoolid, :enrollid, :subject_code, :cost, :fees, 'Process')";

            $query_run = $conn->prepare($query);
            $query_run->execute([
                ":studentid" => $studentid,
                ":schoolid" => $schoolid,
                ":enrollid" => $enrollid,
                ":subject_code" => $subject_code,
                ":cost" => $cost,
                ":fees" => $fees
            ]);

            echo "<script>
                    alert('Email sent and payment recorded!');
                    window.location.href='../enroll_status.php';
                  </script>";
            exit();

        } else {

            echo "<script>
                    alert('Error: " . addslashes($mail->ErrorInfo) . "');
                    window.location.href='../enroll_status.php';
                  </script>";
            exit();
        }

    } catch (Exception $e) {

        echo "<script>
                alert('Error: " . addslashes($mail->ErrorInfo) . "');
                window.location.href='../enroll_status.php';
              </script>";
        exit();
    }
}
?>