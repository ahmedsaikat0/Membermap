<?php
include 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Special condition for admin access with hardcoded credentials
    if ($username === 'saikat' && $password === '22701076') {
        $_SESSION['user_id'] = 1; // Assign an arbitrary ID for the session
        $_SESSION['role'] = 'admin';
        header("Location: admin_dashboard.php");
        exit();
    }

    // Check credentials from the database for regular users
    $stmt = $conn->prepare("SELECT id, password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($user_id, $hashed_password, $role);

    if ($stmt->fetch() && password_verify($password, $hashed_password)) {
        $_SESSION['user_id'] = $user_id;
        $_SESSION['role'] = $role;

        if ($role === 'admin') {
            header("Location: admin_dashboard.php");
        } else {
            header("Location: user_dashboard.php");
        }
    } else {
        echo "<p style='color: red;'>Invalid username or password.</p>";
    }
    $stmt->close();
}

// Initialize variables to ensure the form fields are empty
$username = '';
$password = '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <h1><a href="index.php" style="text-decoration: none; color: inherit;">MemberMap</a></h1>
    
    </header>
    <main>
        <form action="login.php" method="POST">
            <label>Username:</label>
            <input type="text" name="username" value="<?php echo htmlspecialchars($username); ?>" required>
            <label>Password:</label>
            <input type="password" name="password" value="<?php echo htmlspecialchars($password); ?>" required>
            <button type="submit">Login</button>
        </form>
    </main>
</body>
</html>
