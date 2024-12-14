<?php
include 'db.php';
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Add a new club
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_club'])) {
    $name = $_POST['name'];
    $location = $_POST['location'];
    $established_date = $_POST['established_date'];

    $stmt = $conn->prepare("INSERT INTO clubs (name, location, established_date) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $location, $established_date);
    $stmt->execute();
    $stmt->close();
}

// Remove a club
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_club'])) {
    $club_id = $_POST['club_id'];

    $stmt = $conn->prepare("DELETE FROM clubs WHERE id = ?");
    $stmt->bind_param("i", $club_id);
    $stmt->execute();
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Clubs</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>MemberMap</h1>
        <nav>
            <a href="admin_dashboard.php">Dashboard</a>
            <a href="manage_events.php">Events</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>
    <main>
        <h2>Manage Clubs</h2>
        <section>
            <h3>Add Club</h3>
            <form method="POST">
                <label>Club Name:</label>
                <input type="text" name="name" required>
                <label>Location:</label>
                <input type="text" name="location" required>
                <label>Established Date:</label>
                <input type="date" name="established_date" required>
                <button type="submit" name="add_club">Add Club</button>
            </form>
        </section>
        <section>
            <h3>Existing Clubs</h3>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Location</th>
                    <th>Established Date</th>
                    <th>Action</th>
                </tr>
                <?php
                $result = $conn->query("SELECT * FROM clubs");
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['name']}</td>
                        <td>{$row['location']}</td>
                        <td>{$row['established_date']}</td>
                        <td>
                            <form method='POST'>
                                <input type='hidden' name='club_id' value='{$row['id']}'>
                                <button type='submit' name='delete_club'>Delete</button>
                            </form>
                        </td>
                    </tr>";
                }
                ?>
            </table>
        </section>
    </main>
</body>
</html>
