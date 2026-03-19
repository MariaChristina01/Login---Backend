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
    $email    = $_POST['email'];
    $password = $_POST['password'];

    // Look up user by email
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("SQL error: " . $conn->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Verify hashed password
        if (password_verify($password, $row['password'])) {
            $_SESSION['email'] = $row['email'];
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "<div class='alert alert-danger text-center'>Invalid password!</div>";
        }
    } else {
        $error = "<div class='alert alert-danger text-center'>Email not found!</div>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
    
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
 
</head>
<style> 
    body {
    font-family: 'Segoe UI', Arial, sans-serif;
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    min-height: 100vh;
}

body {
    background: linear-gradient(135deg, #62666b, #343332);
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
}

.btn {
    background: linear-gradient(135deg, #62666b, #343332);
    border-radius: 8px;
    font-weight: 500;
    transition: background-color 0.2s ease, transform 0.1s ease;
}
.card {
    border: none;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
}


.card {
    background: linear-gradient(135deg, #959ea9, #c1a992);
    border-radius: 12px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
}

/* Card styling */
.card {
    border-radius: 12px;
    overflow: hidden;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
</style>
<body class="bg-light d-flex justify-content-center align-items-center vh-100">

    <div class="card shadow p-4" style="width: 400px;">
        <h3 class="text-center mb-3">Login</h3>
        <form method="POST" action="login.php">
            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <input type="password" name="password" id="password" class="form-control" required>
                    <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                        Show
                    </button>
                </div>
            </div>
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                <label class="form-check-label" for="remember">
                    Remember Me
                </label>
            </div>

            <button type="submit" class="btn btn-outline-secondary w-100">Login</button>
        </form>
        <div class="text-center mt-3">
            <small>Don’t have an account? <a href="signup.php">Sign up here</a></small>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const togglePassword = document.querySelector("#togglePassword");
        const password = document.querySelector("#password");

        togglePassword.addEventListener("click", function () {
            // Toggle password visibility
            const type = password.getAttribute("type") === "password" ? "text" : "password";
            password.setAttribute("type", type);

            // Toggle button text
            this.textContent = type === "password" ? "Show" : "Hide";
        });
    </script>

</body>
</html>