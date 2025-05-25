<?php
require_once '../includes/db_connect.php';
include '../includes/header.php';

// Restrict access to logged-in members
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'member') {
    header("Location: login.php");
    exit;
}

// Fetch all upcoming events
try {
    $stmt = $pdo->prepare("SELECT * FROM events WHERE event_date >= CURDATE() ORDER BY event_date ASC");
    $stmt->execute();
    $events = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log("Event fetch error: " . $e->getMessage());
    $events = [];
}

// Handle registration success message
$registered = isset($_GET['registered']) && $_GET['registered'] == 'true';
?>

<section class="events-section">
    <div class="container">
        <h1>Available Club Events</h1>

        <?php if ($registered): ?>
            <p class="success-message">You have successfully registered for the event!</p>
        <?php endif; ?>

        <?php if ($events): ?>
            <div class="event-grid">
                <?php foreach ($events as $event): ?>
                    <div class="event-box">
                        <img src="../<?php echo $event['image']; ?>" alt="Event Image">
                        <h2><?php echo htmlspecialchars($event['title']); ?></h2>
                        <p><strong>Date:</strong> <?php echo date('d M Y', strtotime($event['event_date'])); ?></p>
                        <p><strong>Location:</strong> <?php echo htmlspecialchars($event['location']); ?></p>
                        <p><?php echo htmlspecialchars(substr($event['description'], 0, 120)); ?>...</p>
                        <form action="signup_event.php" method="POST">
                            <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
                            <button type="submit">Register</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No upcoming events at the moment.</p>
        <?php endif; ?>
    </div>
</section>

<?php include '../includes/footer.php'; ?>
