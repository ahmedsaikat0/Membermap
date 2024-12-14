<?php
include 'db.php';
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Add a new event
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_event'])) {
    $title = $_POST['title'];
    $location = $_POST['location'];
    $date = $_POST['date'];
    $club_id = $_POST['club_id'];

    $stmt = $conn->prepare("INSERT INTO events (title, location, date, club_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $title, $location, $date, $club_id);
    $stmt->execute();
    $stmt->close();
}

// Remove an event
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_event'])) {
    $event_id = $_POST['event_id'];

    $stmt = $conn->prepare("DELETE FROM events WHERE id = ?");
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Events</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>MemberMap</h1>
        <nav>
            <a href="admin_dashboard.php">Dashboard</a>
            <a href="manage_clubs.php">Clubs</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>
    <main>
        <h2>Manage Events</h2>
        <section>
            <h3>Add Event</h3>
            <form method="POST">
                <label>Event Title:</label>
                <input type="text" name="title" required>
                <label>Location:</label>
                <input type="text" name="location" required>
                <label>Date:</label>
                <input type="date" name="date" required>
                <label>Club:</label>
                <select name="club_id" required>
                    <option value="">Select Club</option>
                    <?php
                    $result = $conn->query("SELECT * FROM clubs");
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='{$row['id']}'>{$row['name']}</option>";
                    }
                    ?>
                </select>
                <button type="submit" name="add_event">Add Event</button>
            </form>
        </section>
        <section>
            <h3>Existing Events</h3>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Location</th>
                    <th>Date</th>
                    <th>Club</th>
                    <th>Action</th>
                </tr>
                <?php
                $query = "SELECT e.id, e.title, e.location, e.date, c.name as club_name 
                          FROM events e 
                          LEFT JOIN clubs c ON e.club_id = c.id";
                $result = $conn->query($query);
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['title']}</td>
                        <td>{$row['location']}</td>
                        <td>{$row['date']}</td>
                        <td>{$row['club_name']}</td>
                        <td>
                            <form method='POST'>
                                <input type='hidden' name='event_id' value='{$row['id']}'>
                                <button type='submit' name='delete_event'>Delete</button>
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
