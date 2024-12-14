<?php
include 'db.php';
session_start();

// Redirect if the user is not logged in or does not have the "user" role
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch the logged-in user's name from the database
$query = "SELECT username FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($username);
$stmt->fetch();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <h1><a href="index.php" style="text-decoration: none; color: inherit;">MemberMap</a></h1>
    
        <nav>
            <a href="clubs.php">Clubs</a>
            <a href="events.php">Events</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>
    <main>
        <!-- Display the user's name -->
        <h2>Welcome, <?php echo htmlspecialchars($username); ?>!</h2>
        <section>
            <h3>Your Clubs</h3>
            <table>
                <tr>
                    <th>Club Name</th>
                    <th>Location</th>
                    <th>Membership Start</th>
                    <th>Membership End</th>
                </tr>
                <?php
                $query = "SELECT c.name, c.location, m.start_date, m.end_date 
                          FROM memberships m 
                          JOIN clubs c ON m.club_id = c.id 
                          WHERE m.user_id = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $result = $stmt->get_result();

                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>{$row['name']}</td>
                        <td>{$row['location']}</td>
                        <td>{$row['start_date']}</td>
                        <td>{$row['end_date']}</td>
                    </tr>";
                }
                ?>
            </table>
        </section>
    </main>
</body>
</html>
