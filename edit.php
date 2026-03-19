<?php
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "appdev";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = $_GET['id'] ?? null;
if (!$id) {
    die("No user ID provided.");
}

// Fetch current user data
$sql = "SELECT * FROM users WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$message = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname  = $_POST['firstname'];
    $middlename = $_POST['middlename'];
    $lastname   = $_POST['lastname'];
    $email      = $_POST['email'];

    $sql = "UPDATE users SET firstname=?, middlename=?, lastname=?, email=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $firstname, $middlename, $lastname, $email, $id);

    if ($stmt->execute()) {
        $message = "<div class='alert alert-success text-center'>✅ User updated successfully!</div> ";
        // Refresh user data after update
        $sql = "SELECT * FROM users WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

         header("Location: dashboard.php?updated=1");
        exit();

    } else {
        $message = "<div class='alert alert-danger text-center'>❌ Error updating record: " . $conn->error . "</div>";
    }
}



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <?php echo $message; ?> <!-- Echo success/error message -->

    <div class="card shadow p-4 mx-auto" style="width: 500px;">
        <h3 class="text-center mb-3">Edit User</h3>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">First Name</label>
                <input type="text" name="firstname" class="form-control"
                       value="<?php echo htmlspecialchars($user['firstname']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Middle Name</label>
                <input type="text" name="middlename" class="form-control"
                       value="<?php echo htmlspecialchars($user['middlename']); ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Last Name</label>
                <input type="text" name="lastname" class="form-control"
                       value="<?php echo htmlspecialchars($user['lastname']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control"
                       value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Save Changes</button>
            <a href="dashboard.php" class="btn btn-secondary w-100 mt-2">Cancel</a>
        </form>
    </div>
</div>

</body>
</html>
