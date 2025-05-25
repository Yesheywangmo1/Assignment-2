<?php
require_once '../includes/db_connect.php';
include '../includes/header.php';

// Only allow access to admins
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$event_id = $_GET['id'] ?? null;
$success = '';
$error = '';

// Redirect if no event ID is provided
if (!$event_id) {
    header("Location: dashboard.php");
    exit;
}

// Fetch existing event data
try {
    $stmt = $pdo->prepare("SELECT * FROM events WHERE id = ?");
    $stmt->execute([$event_id]);
    $event = $stmt->fetch();

    if (!$event) {
        $error = "Event not found.";
    }
} catch (PDOException $e) {
    error_log("Event fetch error: " . $e->getMessage());
    $error = "Failed to load event.";
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $event_date = $_POST['event_date'];
    $location = trim($_POST['location']);
    $image = trim($_POST['image']);

    if ($title && $description && $event_date && $location && $image) {
        try {
            $update_stmt = $pdo->prepare("UPDATE events SET title = ?, description = ?, event_date = ?, location = ?, image = ? WHERE id = ?");
            $update_stmt->execute([$title, $description, $event_date, $location, $image, $event_id]);
            $success = "Event updated successfully.";
        } catch (PDOException $e) {
            error_log("Event update error: " . $e->getMessage());
            $error = "Failed to update event.";
        }
    } else {
        $error = "All fields are required.";
    }
}
?>

<section class="edit-event-section">
    <div class="container">
        <h1>Edit Event</h1>

        <?php if ($success): ?>
            <p class="success-message"><?php echo $success; ?></p>
        <?php elseif ($error): ?>
            <p class="error-message"><?php echo $error; ?></p>
        <?php endif; ?>

        <?php if ($event): ?>
            <form action="edit_event.php?id=<?php echo $event_id; ?>" method="POST" class="event-form">
                <label for="title">Event Title:</label>
                <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($event['title']); ?>" required>

                <label for="description">Event Description:</label>
                <textarea id="description" name="description" rows="5" required><?php echo htmlspecialchars($event['description']); ?></textarea>

                <label for="event_date">Event Date:</label>
                <input type="date" id="event_date" name="event_date" value="<?php echo $event['event_date']; ?>" required>

                <label for="location">Location:</label>
                <input type="text" id="location" name="location" value="<?php echo htmlspecialchars($event['location']); ?>" required>

                <label for="image">Image Path (relative):</label>
                <input type="text" id="image" name="image" value="<?php echo htmlspecialchars($event['image']); ?>" required>

                <button type="submit">Update Event</button>
            </form>
        <?php endif; ?>
    </div>
</section>

<?php include '../includes/footer.php'; ?>
