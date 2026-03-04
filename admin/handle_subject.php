<?php
session_start();
include "../backend/connection.php";


if (!isset($_SESSION["adminid"]) || !$_SESSION["firstname"] || !$_SESSION["lastname"] || $_SESSION["role"] !== "admin") {
    header('Location: ../login.php');
    exit();
}

$query = "SELECT * FROM tb_subject_add ORDER BY subjectid DESC";
$stmt = $conn->prepare($query);
$stmt->execute();
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
        <?php include 'admin_design/subject.css'; ?>
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
                <a href="handle_subject.php" class="active">
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
                <a href="handle_payment.php">
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

    <div class="subject-add">
        <button data-bs-toggle="modal" data-bs-target="#addSubjectModal">
            Add Subject
        </button>
    </div>

    <div class="enrollment-container">
        <table class="table align-middle">
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
                                <button class="btn btn-sm btn-warning edit-btn" data-bs-toggle="modal"
                                    data-bs-target="#updateSubjectModal" data-subjectid="<?= $subject['subjectid']; ?>"
                                    data-subjectcode="<?= htmlspecialchars($subject['subject_code']); ?>"
                                    data-description="<?= htmlspecialchars($subject['description']); ?>"
                                    data-fees="<?= $subject['fees']; ?>"
                                    data-teacher="<?= htmlspecialchars($subject['teacher']); ?>"
                                    data-status="<?= $subject['status']; ?>">
                                    Edit
                                </button>
                                <button class="btn btn-sm btn-danger delete-btn" data-bs-toggle="modal"
                                    data-bs-target="#deleteSubjectModal" data-subjectid="<?= $subject['subjectid']; ?>"
                                    data-subjectcode="<?= htmlspecialchars($subject['subject_code']); ?>"
                                    <?= ($subject['status'] === 'active') ? 'disabled' : ''; ?>>
                                    Delete
                                </button>
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
                    <h5 class="modal-title" id="addSubjectModalLabel">Add Subject</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="admin_backend/add_subject.php" method="POST">
                        <div class="mb-3">
                            <label for="subjectCode" class="form-label">Subject Code</label>
                            <input type="text" class="form-control" id="subjectCode" name="subject_code"
                                placeholder="Input Code" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <input type="text" class="form-control" id="description" name="description"
                                placeholder="Subject description" required>
                        </div>
                        <div class="mb-3">
                            <label for="fees" class="form-label">Fees</label>
                            <input type="number" class="form-control" id="fees" name="fees" placeholder="₱0.00"
                                step="0.01" min="0" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" class="input-label" for="course">Course</label>
                            <select class="form-control" id="course" name="course" required>
                                <option value="" disabled selected>Select a course</option>
                                <option value="bs-info-tech">BS Information Technology</option>
                                <option value="bs-hm">BS HOSPITALITY MANAGEMENT</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="teacher" class="form-label">Teacher</label>
                            <input type="text" class="form-control" id="teacher" name="teacher"
                                placeholder="Teacher name" required>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="" disabled selected>Select Status</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="modal-footer px-0 pb-0">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" name="add_subject" class="btn btn-primary">Save Subject</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="updateSubjectModal" tabindex="-1" aria-labelledby="updateSubjectModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateSubjectModalLabel">Edit Subject</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="admin_backend/update_subject.php" method="POST">
                        <!-- Hidden field for subject ID -->
                        <input type="hidden" id="edit_subject_id" name="subject_id">

                        <div class="mb-3">
                            <label for="edit_subject_code" class="form-label">Subject Code</label>
                            <input type="text" class="form-control" id="edit_subject_code" name="subject_code" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_description" class="form-label">Description</label>
                            <input type="text" class="form-control" id="edit_description" name="description" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_fees" class="form-label">Fees</label>
                            <input type="number" class="form-control" id="edit_fees" name="fees" step="0.01" min="0"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_teacher" class="form-label">Teacher</label>
                            <input type="text" class="form-control" id="edit_teacher" name="teacher" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_status" class="form-label">Status</label>
                            <select class="form-select" id="edit_status" name="status" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="modal-footer px-0 pb-0">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" name="update_subject" class="btn btn-primary">Update Subject</button>
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
                    <h5 class="modal-title" id="deleteSubjectModalLabel">Delete Subject</h5> <!-- Fixed title -->
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="admin_backend/delete_subject.php" method="POST">
                        <input type="hidden" id="delete_subject_id" name="subjectid">

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
            const editButtons = document.querySelectorAll('.edit-btn');
            editButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const subjectId = this.getAttribute('data-subjectid');
                    const subjectCode = this.getAttribute('data-subjectcode');
                    const description = this.getAttribute('data-description');
                    const fees = this.getAttribute('data-fees');
                    const teacher = this.getAttribute('data-teacher');
                    const status = this.getAttribute('data-status');

                    document.getElementById('edit_subject_id').value = subjectId;
                    document.getElementById('edit_subject_code').value = subjectCode;
                    document.getElementById('edit_description').value = description;
                    document.getElementById('edit_fees').value = fees;
                    document.getElementById('edit_teacher').value = teacher;
                    document.getElementById('edit_status').value = status;
                });
            });

            // Add new code for delete buttons
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