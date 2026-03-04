<?php

include 'backend/connection.php';

session_start();
if (!isset($_SESSION["studentid"]) || !$_SESSION["schoolid"] || !$_SESSION["firstname"] || !$_SESSION["lastname"] || $_SESSION["role"] !== "student") {
    header('Location: login.php');
    exit();
}

$query = "SELECT * FROM tb_subject_add WHERE studentid != :studentid ORDER BY subjectid DESC";
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
                <a href="enroll.php" class="active">
                    <i class="fi fi-tr-books"></i>
                    Subject List
                </a>
            </li>
            <li>
                <a href="enroll_status.php">
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
                    <th>Fees</th>
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
                                <?php if ($subject['status'] === 'active'): ?>
                                    <span class="badge bg-success">Active</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-info edit-btn" data-bs-toggle="modal"
                                    data-bs-target="#addSubjectModal" data-subjectid="<?= $subject['subjectid']; ?>"
                                    <?= ($subject['status'] === 'inactive') ? 'disabled' : ''; ?>>
                                    Enroll
                                </button>
                                <!-- <button class="btn btn-sm btn-danger delete-btn" data-bs-toggle="modal"
                                    data-bs-target="#deleteSubjectModal" data-subjectid="<?= $subject['subjectid']; ?>"
                                    data-subjectcode="<?= htmlspecialchars($subject['subject_code']); ?>">
                                    Delete
                                </button> -->
                            </td>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">No subjects found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="addSubjectModal" tabindex="-1" aria-labelledby="addSubjectModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addSubjectModalLabel">Enroll Subject</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="backend/enroll_subject.php" method="POST">
                        <input type="hidden" name="studentid"
                            value="<?php echo htmlspecialchars($_SESSION['studentid']); ?>" readonly>
                        <input type="hidden" name="schoolid"
                            value="<?php echo htmlspecialchars($_SESSION['schoolid']); ?>" readonly>
                        <div class="mb-3">
                            <label for="subjectCode" class="form-label">Subject Code</label>
                            <input type="text" class="form-control" id="subjectCode" name="subject_code"
                                value="<?php echo htmlspecialchars($subject['subject_code']); ?> " readonly>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <input type="text" class="form-control" id="description" name="description"
                                placeholder="Subject description"
                                value="<?php echo htmlspecialchars($subject['description']); ?> " readonly>
                        </div>
                        <div class="mb-3">
                            <label for="fees" class="form-label">Fees</label>
                            <input type="number" class="form-control" id="fees" name="fees"
                                value="<?php echo htmlspecialchars($subject['fees']); ?>" step="0.01" min="0" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="teacher" class="form-label">Teacher</label>
                            <input type="text" class="form-control" id="teacher" name="teacher"
                                value="<?php echo htmlspecialchars($subject['teacher']); ?>" readonly>
                        </div>
                        <div class="modal-footer px-0 pb-0">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" name="enroll_subject" class="btn btn-primary">Enroll Subject</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</body>

</html>