<?php
include 'DBConnection.php';
$error = "";

// 1. CREATE: Add New Course
if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['name']) && !isset($_POST['update_id'])){
    $name = $_POST['name'];
    $descriptions = $_POST['descriptions'];
    $date_of_start = $_POST['date'];
    $price = $_POST['price'];

    // Assuming: name(s), descriptions(s), date_of_start(s), price(d or i)
    $stmt = $conn->prepare("INSERT INTO course (name, descriptions, date_of_start, price) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssd", $name, $descriptions, $date_of_start, $price);

    if ($stmt->execute()){
        header("Location: ManageCourses.php?success=1");
        exit();
    } else {
        $error = "Insert failed: " . $stmt->error;
    }
    $stmt->close();
}

// 2. UPDATE: Edit Existing Course
if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['update_id'])){
    $id = $_POST['update_id'];
    $name = $_POST['update_name'];
    $descriptions = $_POST['update_description'];
    $date_of_start = $_POST['update_date'];
    $price = $_POST['update_price'];

    $stmt2 = $conn->prepare("UPDATE course SET name=?, descriptions=?, date_of_start=?, price=? WHERE id=?");
    $stmt2->bind_param("sssdi", $name, $descriptions, $date_of_start, $price, $id);

    if($stmt2->execute()){
        header("Location: ManageCourses.php?updated=1");
        exit();
    } else {
        $error = "Update failed: " . $stmt2->error;
    }
    $stmt2->close();
}

// 3. DELETE: Remove Course
if (isset($_GET['delete_id'])){
    $id = $_GET['delete_id'];
    $stmt3 = $conn->prepare("DELETE FROM course WHERE id = ?");
    $stmt3->bind_param("i", $id);

    if($stmt3->execute()){
        header("Location: ManageCourses.php?deleted=1");
        exit();
    } else {
        $error = "Delete failed: " . $stmt3->error;
    }
    $stmt3->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Course Management</title>
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
    <div class="container-fluid"><span class="navbar-brand">Student Management System</span></div>
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
                    <?php echo $error; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3>Manage Courses</h3>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCourseModal">+ Add New Course</button>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">Course List</div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                        <tr>
                            <th>Course Name</th>
                            <th>Description</th>
                            <th>Start Date</th>
                            <th>Price</th>
                            <th class="text-center">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $result = $conn->query("SELECT * FROM course ORDER BY id DESC");
                        if ($result && $result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $name_js = htmlspecialchars($row['name'], ENT_QUOTES);
                                $desc_js = htmlspecialchars($row['descriptions'], ENT_QUOTES);
                                echo "<tr>
                                    <td>{$row['name']}</td>
                                    <td>{$row['descriptions']}</td>
                                    <td>{$row['date_of_start']}</td>
                                    <td>Rs.{$row['price']}</td>
                                    <td class='text-center'>
                                        <button class='btn btn-warning btn-sm' onclick=\"setUpdateData('{$row['id']}', '{$name_js}', '{$desc_js}', '{$row['date_of_start']}', '{$row['price']}')\" data-bs-toggle='modal' data-bs-target='#updateCourseModal'>Edit</button>
                                        <a href='ManageCourses.php?delete_id={$row['id']}' class='btn btn-danger btn-sm' onclick=\"return confirm('Delete this course?')\">Delete</a>
                                    </td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5' class='text-center p-4'>No courses found.</td></tr>";
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addCourseModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Add Course</h5></div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Course Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="descriptions" class="form-control" required></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Start Date</label>
                    <input type="date" name="date" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Price</label>
                    <input type="number" step="0.01" name="price" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Course</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="updateCourseModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title">Update Course</h5>
            </div>
            <div class="modal-body">
                <input type="hidden" name="update_id" id="upd_id">
                <div class="mb-3">
                    <label class="form-label">Course Name</label><input type="text" name="update_name" id="upd_name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Description</label><textarea name="update_description" id="upd_desc" class="form-control" required></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Start Date</label><input type="date" name="update_date" id="upd_date" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Price</label><input type="number" step="0.01" name="update_price" id="upd_price" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-warning">Update Course</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content text-center p-4">
            <div id="statusMsg">
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    
   function setUpdateData(id, name, description, date_of_start, price) {
        document.getElementById('upd_id').value = id;
        document.getElementById('upd_name').value = name;
        document.getElementById('upd_desc').value = description;
        document.getElementById('upd_date').value = date_of_start; 
        document.getElementById('upd_price').value = price;
    }

    // Auto-show success modals based on URL
    window.onload = function() {
        const urlParams = new URLSearchParams(window.location.search);
        let msg = "";

        if (urlParams.has('status')) msg = "Course Saved Successfully!";
        if (urlParams.has('updated')) msg = "Course Updated Successfully!";
        if (urlParams.has('Deleted')) msg = "Course Deleted Successfully!";

        if (msg !== "") {
            document.getElementById('statusMsg').innerText = msg;
            var myModal = new bootstrap.Modal(document.getElementById('statusModal'));
            myModal.show();
            setTimeout(() => { window.history.replaceState({}, document.title, "ManageCourses.php"); }, 2000);
        }
    }

</script>

</body>
</html>