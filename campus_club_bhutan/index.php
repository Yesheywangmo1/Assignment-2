<?php
require_once 'includes/db_connect.php';
include 'includes/header.php';

// Optional: Fetch upcoming events to display on homepage
$events_stmt = $pdo->prepare("SELECT * FROM events WHERE event_date >= CURDATE() ORDER BY event_date ASC LIMIT 3");
$events_stmt->execute();
$upcoming_events = $events_stmt->fetchAll();
?>

<section class="hero">
    <div class="hero-text">
        <h1>Welcome to the Campus Club & Event Management System</h1>
        <p>Explore, register, and participate in exciting student events across Bhutanese campuses.</p>
        <a href="member/register.php" class="btn">Join a Club</a>
        <a href="member/events.php" class="btn btn-secondary">Browse Events</a>
    </div>
</section>

<section class="highlights">
    <h2>Upcoming Events</h2>
    <div class="event-cards">
        <?php if ($upcoming_events): ?>
            <?php foreach ($upcoming_events as $event): ?>
                <div class="event-card">
                    <img src="<?php echo $event['image']; ?>" alt="Event image">
                    <h3><?php echo htmlspecialchars($event['title']); ?></h3>
                    <p><?php echo date("d M, Y", strtotime($event['event_date'])); ?> at <?php echo htmlspecialchars($event['location']); ?></p>
                    <p><?php echo substr(htmlspecialchars($event['description']), 0, 100); ?>...</p>
                    <a href="member/events.php" class="btn-sm">View More</a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No upcoming events at the moment. Check back soon!</p>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
