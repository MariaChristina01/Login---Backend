<?php
session_start();

$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "appdev";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname  = $_POST['firstname'];
    $middlename = $_POST['middlename'];
    $lastname   = $_POST['lastname'];
    $email      = $_POST['email'];
    $password   = $_POST['password'];

    $hashed_pass = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (firstname, middlename, lastname, email, password) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("SQL error: " . $conn->error);
    }

    $stmt->bind_param("sssss", $firstname, $middlename, $lastname, $email, $hashed_pass);

    if ($stmt->execute()) {
    echo "
    <div class='container mt-3'>
        <div class='row'>
            <div class='col-12 text-center'>
                <div class='alert alert-success d-inline-block'>
                    🎉 Registration successful! You can now 
                    <a href='login.html' class='alert-link'>login here</a>.
                </div>
            </div>
        </div>
    </div>
    ";
} else {
        echo "<div class='alert alert-danger text-center mt-3'>
                Registration failed: " . $stmt->error . "
              </div>";
              
    }
}

?>


<!DOCTYPE html>
<html>
<head>
    <title>Sign Up</title>
    
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-light d-flex justify-content-center align-items-center vh-100">

    <div class="card shadow p-4" style="width: 400px;">
        <h3 class="text-center mb-3">Create Account</h3>
        <form method="POST" action="signup.php">
            <div class="mb-3">
                <label class="form-label">First Name</label>
                <input type="text" name="firstname" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Middle Name</label>
                <input type="text" name="middlename" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Last Name</label>
                <input type="text" name="lastname" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success w-100">Sign Up</button>
        </form>
        <div class="text-center mt-3">
            <small>Already have an account? <a href="login.php">Login here</a></small>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>