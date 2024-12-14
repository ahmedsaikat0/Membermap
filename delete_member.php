<?php
include 'db.php';
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Check if a membership ID is provided
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['membership_id'], $_POST['club_id'])) {
    $membership_id = intval($_POST['membership_id']);
    $club_id = intval($_POST['club_id']);
    
    // Delete the membership
    $delete_query = $pdo->prepare("DELETE FROM memberships WHERE id = ?");
    $delete_query->execute([$membership_id]);

    // Redirect back to manage_members.php
    header("Location: manage_members.php?club_id=" . $club_id);
    exit();
} else {
    echo "Invalid request.";
    exit();
}
?>
