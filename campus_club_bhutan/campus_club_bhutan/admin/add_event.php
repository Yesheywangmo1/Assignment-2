<?php
require_once '../includes/db_connect.php';
include '../includes/header.php';

// Restrict access to admins
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $event_date = $_POST['event_date'];
    $location = trim($_POST['location']);
    $image = $_POST['image']; // Static path input for simplicity (later, you can use actual file upload)

    if ($title && $description && $event_date && $location && $image) {
        try {
            $stmt = $pdo->prepare("INSERT INTO events (title, description, event_date, location, image, created_by) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$title, $description, $event_date, $location, $image, $_SESSION['user_id']]);
            $success = "Event created successfully.";
        } catch (PDOException $e) {
            error_log("Event creation error: " . $e->getMessage());
            $error = "Unable to create event. Please try again.";
        }
    } else {
        $error = "All fields are required.";
    }
}
?>

<section class="add-event-section">
    <div class="container">
        <h1>Create New Event</h1>

        <?php if ($success): ?>
            <p class="success-message"><?php echo $success; ?></p>
        <?php elseif ($error): ?>
            <p class="error-message"><?php echo $error; ?></p>
        <?php endif; ?>

        <form action="add_event.php" method="POST" class="event-form">
            <label for="title">Event Title:</label>
            <input type="text" id="title" name="title" required>

            <label for="description">Event Description:</label>
            <textarea id="description" name="description" rows="5" required></textarea>

            <label for="event_date">Event Date:</label>
            <input type="date" id="event_date" name="event_date" required>

            <label for="location">Location:</label>
            <input type="text" id="location" name="location" required>

            <label for="image">Image Path (relative):</label>
            <input type="text" id="image" name="image" placeholder="e.g., images/events/my_event.jpg" required>

            <button type="submit">Create Event</button>
        </form>
    </div>
</section>

<?php include '../includes/footer.php'; ?>
