<?php
session_start();
include "../backend/connection.php";


if (!isset($_SESSION["adminid"]) || !$_SESSION["firstname"] || !$_SESSION["lastname"] || $_SESSION["role"] !== "admin") {
    header('Location: login.php');
    exit();
}


$query = "SELECT * FROM tb_payment_transaction ORDER BY paymentid DESC";
$stmt = $conn->prepare($query);
$stmt->execute();
$payments = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student | Dashboard</title>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/3.0.0/uicons-thin-rounded/css/uicons-thin-rounded.css'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <style>
        <?php
        include 'admin_design/handle_payment.css';
        ?>
    </style>
</head>

<body>

    <header>
        <div class="logo">
            <p style="font-family: cursive; color: black;">EnrollSys</p>
        </div>
        <div class="user-profile">
            <div class="user-avatar">
                <?php echo strtoupper(substr($_SESSION['firstname'], 0, 1)); ?>
            </div>
            <div class="user-info">
                <span
                    class="user-name"><?php echo htmlspecialchars($_SESSION['firstname'] . ' ' . $_SESSION['lastname']); ?></span>
                <span class="user-role">Admin</span>
            </div>
        </div>
    </header>

    <aside class="sidebar">
        <ul class="menu">
            <li>
                <a href="admin_dashboard.php">
                    <i class="fi fi-tr-house-window"></i>
                    Dashboard
                </a>
            </li>
            <li>
                <a href="handle_subject.php">
                    <i class="fi fi-tr-file-signature"></i>
                    Subject Add
                </a>
            </li>
            <li>
                <a href="student_enroll_list.php">
                    <i class="fi fi-tr-file-signature"></i>
                    Student Enroll
                </a>
            </li>
            <li>
                <a href="handle_payment.php" class="active">
                    <i class="fi fi-tr-expense"></i>
                    Payment
                </a>
            </li>
        </ul>
        <a style="text-decoration: none; bottom: 0px; position: absolute;" href="../backend/logout.php"
            class="logout-btn">
            <i class="fi fi-tr-user-logout"></i>
            Logout
        </a>
    </aside>

    <div class="enrollment-container">
        <table class="table align-middle" style="text-align: center;">
            <thead>
                <tr>
                    <th>Student ID</th>
                    <th>Subject Code</th>
                    <th>Fees</th>
                    <th>Payment Send</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($payments): ?>
                    <?php foreach ($payments as $payment): ?>
                        <tr>
                            <td>
                                <?php echo htmlspecialchars($payment['schoolid']); ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($payment['subject_code']); ?>
                            </td>
                            <td>
                                ₱<?php echo number_format($payment['fees'], 2); ?>
                            </td>

                            <td>
                                ₱<?php echo number_format($payment['cost'], 2); ?>
                            </td>
                            <td>
                                <?php if ($payment['status'] === 'Process'): ?>
                                    <span class="badge bg-primary">Process</span>

                                <?php elseif ($payment['status'] === 'Confirmed'): ?>
                                    <span class="badge bg-success">Confirmed</span>

                                <?php else: ?>
                                    <span class="badge bg-danger">Reject</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($payment['status'] === 'Confirmed'): ?>
                                    <button class="btn btn-sm btn-secondary" disabled>Confirmed</button>
                                <?php else: ?>
                                    <button class="btn btn-sm btn-warning edit-btn" data-bs-toggle="modal"
                                        data-bs-target="#updateSubjectModal" data-paymentid="<?= $payment['paymentid']; ?>"
                                        data-status="<?= $payment['status']; ?>">
                                        Edit
                                    </button>
                                <?php endif; ?>

                            </td>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">No Payment.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="updateSubjectModal" tabindex="-1" aria-labelledby="updateSubjectModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateSubjectModalLabel">Edit Enroll Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="admin_backend/payment_status.php" method="POST">
                        <input type="hidden" name="paymentid" id="edit_paymentid" value="">
                        <div class="mb-3">
                            <label for="edit_status" class="form-label">Status</label>
                            <select class="form-select" id="edit_status" name="status" required>
                                <option value="Process">Process</option>
                                <option value="Confirmed">Confirmed</option>
                            </select>
                        </div>
                        <div class="modal-footer px-0 pb-0">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" name="update_payment_status" class="btn btn-primary">Update
                                Status</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const editButtons = document.querySelectorAll('.edit-btn');
            editButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const paymentId = this.getAttribute('data-paymentid');
                    const status = this.getAttribute('data-status');

                    document.getElementById('edit_paymentid').value = paymentId;
                    document.getElementById('edit_status').value = status;
                });
            });
        });
    </script>
</body>

</html>