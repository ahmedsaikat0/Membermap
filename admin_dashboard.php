<?php
include 'db.php';
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <h1><a href="index.php" style="text-decoration: none; color: inherit;">MemberMap</a></h1>
    
        <nav>
            <a href="admin_dashboard.php">Dashboard</a>
            <a href="manage_clubs.php">Clubs</a>
            <a href="manage_events.php">Events</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>
    <main>
        <h2>Welcome, Admin</h2>
        <section>
            <h3>Clubs</h3>
            <a href="manage_clubs.php">Manage Clubs</a>
        </section>
        <section>
            <h3>Events</h3>
            <a href="manage_events.php">Manage Events</a>
        </section>
    </main>
</body>
</html>
