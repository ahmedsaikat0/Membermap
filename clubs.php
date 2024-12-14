<?php
include 'db.php';
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle club membership
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $club_id = $_POST['club_id'];
    $start_date = date('Y-m-d');
    $end_date = date('Y-m-d', strtotime('+1 year'));
    $membership_id = uniqid('M');
    $fee = 500;
    $phone = $_POST['phone'];

    $stmt = $conn->prepare("INSERT INTO memberships (user_id, club_id, start_date, end_date, membership_id, fee, phone) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iisssis", $user_id, $club_id, $start_date, $end_date, $membership_id, $fee, $phone);
    $stmt->execute();
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clubs</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <h1><a href="index.php" style="text-decoration: none; color: inherit;">MemberMap</a></h1>
    
        <nav>
            <a href="user_dashboard.php">Dashboard</a>
            <a href="events.php">Events</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>
    <main>
        <h2>Clubs</h2>
        <section>
            <h3>Join a Club</h3>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Location</th>
                    <th>Established</th>
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
                                <label>Phone:</label>
                                <input type='text' name='phone' required>
                                <button type='submit'>Join</button>
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

