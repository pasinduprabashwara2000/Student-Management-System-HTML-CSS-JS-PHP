<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Manage Courses</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

	<style>
        .sidebar {
            background: #212529;
            min-height: 100vh;
            padding-top: 20px;
        }

        .sidebar a {
            display: block;
            color: white;
            padding: 10px;
            text-decoration: none;
        }

        .sidebar a:hover {
            background: #343a40;
        }
    </style>

</head>
<body>

	<!-- Navbar -->
<nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
        <span class="navbar-brand">Student Management System</span>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">

        <!-- Sidebar -->
        <div class="col-md-2 sidebar">
            <a href="dashboard.php">🏠 Dashboard</a>
            <a href="ManageStudents.php">👨‍🎓 Students</a>
            <a href="ManageCourses.php">📚 Courses</a>
            <a href="#">📝 Exams</a>
            <a href="#">📊 Reports</a>
            <a href="#">⚙ Settings</a>
            <a href="logout.php">🚪 Logout</a>
        </div>

         <!-- Main Content -->
        <div class="col-md-10 p-4">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3>Manage Courses</h3>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStudentModal">
                    + Add Course
                </button>
            </div>

            <!-- Table -->
            <div class="card">
                <div class="card-header bg-dark text-white">
                    Student List
                </div>

                <div class="card-body">

                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>NIC</th>
                                <th>Course</th>
                                <th>Phone</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                        <?php
                        $result = $conn->query("SELECT * FROM students");

                        if ($result && $result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                        <td>{$row['name']}</td>
                        <td>{$row['nic']}</td>
                        <td>{$row['course']}</td>
                        <td>{$row['phone']}</td>
            <td>
                <a href = 'ManageStudents.php?id={$row['id']}>' class='btn btn-danger btn-sm'>Delete</a>
            </td>
        </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6' class='text-center'>No students found</td></tr>";
                        }
                        ?>
                        </tbody>

                    </table>

                </div>
            </div>

        </div>
    </div>
</div>

<!-- Add Course Modal -->
<div class="modal fade" id="addStudentModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header bg-dark text-white">
        <h5 class="modal-title">Add Student</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">

        <form method="POST" action="">

          <div class="mb-3">
            <label class="form-label">Student Name</label>
            <input type="text" name="name" class="form-control" required>
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
            <label class="form-label">Phone</label>
            <input type="text" name="phone" class="form-control" required>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save Student</button>
          </div>

        </form>

      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>