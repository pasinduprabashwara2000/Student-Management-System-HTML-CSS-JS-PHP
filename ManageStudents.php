<?php
include "DBConnection.php";

$error = "";

// --- 1. HANDLE CREATE STUDENT ---
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['name']) && !isset($_POST['update_id'])) {
    $name   = $_POST['name'];
    $nic    = $_POST['nic'];
    $course = $_POST['course'];
    $phone  = $_POST['phone'];

    $stmt = $conn->prepare("INSERT INTO students (name, nic, course, phone) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $nic, $course, $phone);

    if ($stmt->execute()) {
        header("Location: ManageStudents.php?status=success");
        exit();
    } else {
        $error = "Save Failed: " . $stmt->error;
    }
    $stmt->close();
}

// --- 2. HANDLE UPDATE STUDENT ---
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['update_id'])) {
    $id     = $_POST['update_id'];
    $name   = $_POST['update_name'];
    $nic    = $_POST['update_nic'];
    $course = $_POST['update_course'];
    $phone  = $_POST['update_phone'];

    $stmt3 = $conn->prepare("UPDATE students SET name=?, nic=?, course=?, phone=? WHERE id=?");
    $stmt3->bind_param("ssssi", $name, $nic, $course, $phone, $id);

    if($stmt3->execute()){
        header("Location: ManageStudents.php?updated=1");
        exit();
    } else {
        $error = "Update Failed: " . $stmt3->error;
    }
    $stmt3->close();
}

// --- 3. HANDLE DELETE STUDENT ---
if (isset($_GET['delete_id'])){
    $id = $_GET['delete_id'];
    $stmt2 = $conn->prepare("DELETE FROM students WHERE id = ?");
    $stmt2->bind_param("i", $id);

    if($stmt2->execute()){
        header("Location: ManageStudents.php?Deleted=1");
        exit();
    } else {
        $error = "Delete Failed: " . $stmt2->error;
    }
    $stmt2->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manage Students</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .sidebar {
            background: #212529;
            min-height: 100vh;
            padding-top: 20px;
        }

        .sidebar a {
            display: block;
            color: white;
            padding: 12px 20px;
            text-decoration: none;
            transition: 0.3s;
        }

        .sidebar a:hover {
            background: #343a40;
            padding-left: 25px;
        }

        .main-content {
            padding: 30px;
        }

    </style>
</head>
<body>

<nav class="navbar navbar-dark bg-dark sticky-top">
    <div class="container-fluid">
        <span class="navbar-brand">Student Management System</span>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 sidebar">
        <a href="dashboard.php">Dashboard</a>
        <a href="ManageStudents.php">Manage Students</a>
        <a href="ManageCourses.php">Manage Courses</a>
        <a href="#">Manage Exams</a>
        <a href="#">Manage Reports</a>
        <a href="#">Settings</a>
        <a href="logout.php">Logout</a>
    </div>

        <div class="col-md-10 main-content">

            <?php if ($error): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <strong>Error:</strong> <?php echo $error; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3>Manage Students</h3>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStudentModal">+ Add New Student</button>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">Student Directory</div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>NIC</th>
                            <th>Course</th>
                            <th>Phone</th>
                            <th class="text-center">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $result = $conn->query("SELECT * FROM students ORDER BY id DESC");
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                                        <td>{$row['name']}</td>
                                        <td>{$row['nic']}</td>
                                        <td>{$row['course']}</td>
                                        <td>{$row['phone']}</td>
                                        <td class='text-center'>
                                            <button class='btn btn-warning btn-sm' onclick=\"setUpdateData('{$row['id']}', '{$row['name']}', '{$row['nic']}', '{$row['course']}', '{$row['phone']}')\" data-bs-toggle='modal' data-bs-target='#updateStudentModal'>Edit</button>
                                            <a href='ManageStudents.php?delete_id={$row['id']}' class='btn btn-danger btn-sm' onclick=\"return confirm('Are you sure?')\">Delete</a>
                                        </td>
                                    </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5' class='text-center p-4'>No records found.</td></tr>";
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addStudentModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" class="modal-content">
            <div class="modal-header bg-primary text-white"><h5 class="modal-title">Add Student</h5></div>
            <div class="modal-body">
                
                <div class="mb-3">
                    <label class="form-label">Full Name</label><input type="text" name="name" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">NIC</label>
                    <input type="text" name="nic" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Course</label>
                    <input type="text" name="course" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Phone</label><input type="text" name="phone" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Student</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="updateStudentModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title">Update Student Details</h5>
            </div>
            <div class="modal-body">
                <input type="hidden" name="update_id" id="upd_id">
                <div class="mb-3">
                    <label class="form-label">Full Name</label><input type="text" name="update_name" id="upd_name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">NIC</label><input type="text" name="update_nic" id="upd_nic" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Course</label><input type="text" name="update_course" id="upd_course" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Phone</label><input type="text" name="update_phone" id="upd_phone" class="form-control" required>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-warning">Update Records</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog modal-sm"><div class="modal-content text-center p-4"><div id="statusMsg"></div></div></div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Function to populate the Update Modal
    function setUpdateData(id, name, nic, course, phone) {
        document.getElementById('upd_id').value = id;
        document.getElementById('upd_name').value = name;
        document.getElementById('upd_nic').value = nic;
        document.getElementById('upd_course').value = course;
        document.getElementById('upd_phone').value = phone;
    }

    // Auto-show success modals based on URL
    window.onload = function() {
        const urlParams = new URLSearchParams(window.location.search);
        let msg = "";

        if (urlParams.has('status')) msg = "Student Saved Successfully!";
        if (urlParams.has('updated')) msg = "Student Updated Successfully!";
        if (urlParams.has('Deleted')) msg = "Student Deleted Successfully!";

        if (msg !== "") {
            document.getElementById('statusMsg').innerText = msg;
            var myModal = new bootstrap.Modal(document.getElementById('statusModal'));
            myModal.show();
            setTimeout(() => { window.history.replaceState({}, document.title, "ManageStudents.php"); }, 2000);
        }
    }
</script>

</body>
</html>