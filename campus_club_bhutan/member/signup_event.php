<?php
require_once '../includes/db_connect.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'member') {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['event_id'])) {
    $event_id = $_POST['event_id'];
    $user_id = $_SESSION['user_id'];

    try {
        $stmt = $pdo->prepare("INSERT INTO registrations (user_id, event_id) VALUES (?, ?)");
        $stmt->execute([$user_id, $event_id]);

        // Optionally create default attendance record (false)
        $att_stmt = $pdo->prepare("INSERT INTO attendance (user_id, event_id, attended) VALUES (?, ?, 0)");
        $att_stmt->execute([$user_id, $event_id]);

        header("Location: events.php?registered=true");
        exit;
    } catch (PDOException $e) {
        error_log("Registration error: " . $e->getMessage());
        header("Location: events.php?registered=false");
        exit;
    }
} else {
    header("Location: events.php");
    exit;
}
