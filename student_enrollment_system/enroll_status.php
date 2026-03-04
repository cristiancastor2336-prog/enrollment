<?php

include 'backend/connection.php';

session_start();
if (!isset($_SESSION["studentid"]) || !$_SESSION["schoolid"] || !$_SESSION["firstname"] || !$_SESSION["lastname"] || $_SESSION["role"] !== "student") {
    header('Location: login.php');
    exit();
}

$query = "SELECT * FROM tb_enroll_subject WHERE studentid = :studentid ORDER BY enrollid DESC";
$stmt = $conn->prepare($query);
$stmt->execute([':studentid' => $_SESSION['studentid']]);
$subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);


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
        include 'design/enroll.css';
        ?>
    </style>
</head>

<body>

    <header>
        <div class="logo">
            <p style="font-family: cursive; color: navy;">EnrollSys</p>
        </div>

        <div class="user-profile">
            <div class="user-avatar">
                <?php echo strtoupper(substr($_SESSION['firstname'], 0, 1)); ?>
            </div>
            <div class="user-info">
                <span
                    class="user-name"><?php echo htmlspecialchars($_SESSION['firstname'] . ' ' . $_SESSION['lastname']); ?></span>
                <span class="user-role">Student</span>
            </div>
        </div>

    </header>

    <aside class="sidebar">
        <ul class="menu">
            <li>
                <a href="index.php">
                    <i class="fi fi-tr-house-window"></i>
                    Dashboard
                </a>
            </li>
            <li>
                <a href="enroll.php">
                    <i class="fi fi-tr-books"></i>
                    Subject List
                </a>
            </li>
            <li>
                <a href="enroll_status.php" class="active">
                    <i class="fi fi-tr-file-signature"></i>
                    Enroll
                </a>
            </li>
            <li>
                <a href="payment.php">
                    <i class="fi fi-tr-expense"></i>
                    Payment
                </a>
            </li>
        </ul>
        <a style="text-decoration: none; bottom: 0px; position: absolute;" href="backend/logout.php" class="logout-btn">
            <i class="fi fi-tr-user-logout"></i>
            Logout
        </a>
    </aside>

    <div class="enrollment-container">
        <table class="table align-middle" style="text-align: center;">
            <thead>
                <tr>
                    <th>Subject Code</th>
                    <th>Description</th>
                    <th>Balance</th>
                    <th>Teacher</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($subjects): ?>
                    <?php foreach ($subjects as $subject): ?>
                        <tr>
                            <td>
                                <?= htmlspecialchars($subject['subject_code']); ?>
                            </td>
                            <td>
                                <?= htmlspecialchars($subject['description']); ?>
                            </td>
                            <td>
                                ₱<?= number_format($subject['fees'], 2); ?>
                            </td>
                            <td>
                                <?= htmlspecialchars($subject['teacher']); ?>
                            </td>
                            <td>
                                <?php if ($subject['status'] === 'Pending'): ?>
                                    <span class="badge bg-warning">Pending</span>

                                <?php elseif ($subject['status'] === 'Confirmed'): ?>
                                    <span class="badge bg-success">Confirmed</span>

                                <?php else: ?>
                                    <span class="badge bg-danger">Rejected</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-success edit-btn" data-bs-toggle="modal"
                                    data-bs-target="#paymentSubjectModal" data-subjectid="<?= $subject['enrollid']; ?>"
                                    <?= ($subject['status'] === 'Reject' || $subject['status'] === 'Pending') ? 'disabled' : ''; ?>>
                                    Pay
                                </button>
                                <button
                                    class="btn btn-sm <?= ($subject['status'] === 'Confirmed' || $subject['status'] === 'Rejected') ? 'btn-secondary' : 'btn-danger'; ?> delete-btn"
                                    data-bs-toggle="modal" data-bs-target="#deleteSubjectModal"
                                    data-subjectid="<?= $subject['enrollid']; ?>"
                                    data-subjectcode="<?= htmlspecialchars($subject['subject_code']); ?>"
                                    <?= ($subject['status'] === 'Confirmed') ? 'disabled' : ''; ?>>
                                    Delete
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">No subjects enroll.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="paymentSubjectModal" tabindex="-1" aria-labelledby="paymentSubjectModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="paymentSubjectModalLabel">Add Subject</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="backend/payment_insert.php" method="POST">
                        <input type="hidden" name="studentid"
                            value="<?php echo htmlspecialchars($_SESSION['studentid']); ?>" readonly>
                        <input type="hidden" name="schoolid"
                            value="<?php echo htmlspecialchars($_SESSION['schoolid']); ?>" readonly>
                        <input type="hidden" name="enrollid"
                            value="<?php echo htmlspecialchars($subject['enrollid']); ?>" readonly>
                        <input type="hidden" name="fees"
                            value="<?php echo htmlspecialchars($subject['fees']); ?>" readonly>
                        
                        <div class="mb-3">
                            <label for="subjectCode" class="form-label">Subject Code</label>
                            <input type="text" class="form-control" id="subject_code" name="subject_code" value="<?php echo htmlspecialchars($subject['subject_code']); ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="subjectCode" class="form-label">Payment</label>
                            <input type="number" class="form-control" id="payment" name="cost">
                        </div>
                        <div class="modal-footer px-0 pb-0">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" name="payment_send" class="btn btn-primary">Pay</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteSubjectModal" tabindex="-1" aria-labelledby="deleteSubjectModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteSubjectModalLabel">Delete Subject</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="backend/delete_enroll_subject.php" method="POST">
                        <input type="hidden" id="delete_subject_id" name="enrollid">

                        <p>Are you sure you want to delete this subject?</p>
                        <p class="text-danger">This action cannot be undone!</p>


                        <div class="modal-footer px-0 pb-0">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" name="delete_subject" class="btn btn-danger">Delete Subject</button>
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


            const deleteButtons = document.querySelectorAll('.delete-btn');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const subjectId = this.getAttribute('data-subjectid');

                    document.getElementById('delete_subject_id').value = subjectId;
                });
            });
        });
    </script>
</body>

</html>