<?php
require_once '../includes/db_connect.php';
include '../includes/header.php';

// Restrict access to admins only
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Fetch key metrics
try {
    $total_members = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'member'")->fetchColumn();
    $total_events = $pdo->query("SELECT COUNT(*) FROM events")->fetchColumn();
    $total_registrations = $pdo->query("SELECT COUNT(*) FROM registrations")->fetchColumn();
} catch (PDOException $e) {
    error_log("Dashboard error: " . $e->getMessage());
    $total_members = $total_events = $total_registrations = 0;
}
?>

<section class="admin-dashboard">
    <div class="container">
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['full_name']); ?>!</h1>

        <div class="dashboard-cards">
            <div class="card">
                <h2><?php echo $total_members; ?></h2>
                <p>Total Members</p>
            </div>
            <div class="card">
                <h2><?php echo $total_events; ?></h2>
                <p>Total Events</p>
            </div>
            <div class="card">
                <h2><?php echo $total_registrations; ?></h2>
                <p>Event Registrations</p>
            </div>
        </div>

        <div class="dashboard-actions">
            <a href="add_event.php" class="btn">Create New Event</a>
            <a href="edit_event.php" class="btn">Edit</a>
            <a href="track_attendance.php" class="btn btn-secondary">Track Attendance</a>
        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>
