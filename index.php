<?php
    include "DBConnection.php";

    $loginStatus = "";

    if($_SERVER['REQUEST_METHOD'] == "POST"){
        $username = $_POST['username'];
        $password = $_POST['password'];

        $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
        $result = $conn->query($sql);

        if($result->num_rows > 0){
            $loginStatus = "success";
            header("Location: dashboard.php");
            exit();
        } else {
            $loginStatus = "error";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <div class="container">
        <h1 class="title">User Login</h1>
        <form method="post" action="">
        <div class="form-group">
            <label for="username">Username : </label>
            <input type="text" name="username" class="form-control" id="username"  placeholder="Enter your username">
        </div>
        <div class="form-group">
            <label for="password">Password : </label>
            <input type="password" name="password" class="form-control" id="password" placeholder="Enter your password">
        </div>
    <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>


<!-- FAIL MODAL -->
<div class="modal fade" id="login-fail" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Message</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                Invalid Username or Password !
            </div>

        </div>
    </div>
</div>

   <!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

<script>
    <?php if ($loginStatus == "error") { ?>
    var modal = new bootstrap.Modal(document.getElementById('login-fail'));
    modal.show();
    <?php } ?>
</script>

</body>
</html>