<?php
include 'db.php';
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Display events for user's clubs
$query = "SELECT e.id, e.title, e.location, e.date, c.name as club_name 
          FROM events e 
          JOIN memberships m ON e.club_id = m.club_id 
          JOIN clubs c ON e.club_id = c.id 
          WHERE m.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <h1><a href="index.php" style="text-decoration: none; color: inherit;">MemberMap</a></h1>
    
        <nav>
            <a href="user_dashboard.php">Dashboard</a>
            <a href="clubs.php">Clubs</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>
    <main>
        <h2>Events</h2>
        <section>
            <h3>Your Events</h3>
            <table>
                <tr>
                    <th>Title</th>
                    <th>Location</th>
                    <th>Date</th>
                    <th>Club</th>
                </tr>
                <?php
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>{$row['title']}</td>
                        <td>{$row['location']}</td>
                        <td>{$row['date']}</td>
                        <td>{$row['club_name']}</td>
                    </tr>";
                }
                ?>
            </table>
        </section>
    </main>
</body>
</html>
