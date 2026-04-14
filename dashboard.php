<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>

    <link rel="stylesheet" href="css/style2.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<!-- Navbar -->
<nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
        <span class="navbar-brand mb-0 h1">Student Management System</span>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">

    <!-- Sidebar -->
    <div class="col-md-2 sidebar">
        <a href="dashboard.php">🏠 Dashboard</a>
        <a href="pages/ManageStudents.php">👨‍🎓 Manage Students</a>
        <a href="pages/ManageCourses.php">📚 Manage Courses</a>
        <a href="#">📝 Manage Exams</a>
        <a href="#">📊 Manage Reports</a>
        <a href="#">⚙ Settings</a>
        <a href="logout.php">🚪 Logout</a>
    </div>

        <!-- Main Content -->
        <div class="col-md-10 main">

            <h3 class="mb-4">Welcome Admin</h3>

            <div class="row g-4">

                <div class="col-md-3">
                    <div class="card text-white bg-primary card-box">
                        <div class="card-body">
                            <h5>Total Students</h5>
                            <h2>120</h2>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card text-white bg-success card-box">
                        <div class="card-body">
                            <h5>Active Courses</h5>
                            <h2>8</h2>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card text-white bg-warning card-box">
                        <div class="card-body">
                            <h5>Exams</h5>
                            <h2>5</h2>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card text-white bg-danger card-box">
                        <div class="card-body">
                            <h5>Pending Tasks</h5>
                            <h2>3</h2>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Table Section -->
            <div class="card mt-4">
                <div class="card-header bg-dark text-white">
                    Recent Students
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Course</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>1</td>
                            <td>Pasindu</td>
                            <td>IT</td>
                            <td><span class="badge bg-success">Active</span></td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Nimal</td>
                            <td>Business</td>
                            <td><span class="badge bg-warning">Pending</span></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>