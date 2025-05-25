<?php
require_once '../includes/db_connect.php';
include '../includes/header.php';

// Restrict access to admins only
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Fetch events for dropdown
try {
    $events = $pdo->query("SELECT * FROM events ORDER BY event_date DESC")->fetchAll();
} catch (PDOException $e) {
    error_log("Event fetch failed: " . $e->getMessage());
    $events = [];
}

// Process attendance marking
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['event_id'], $_POST['attendance'])) {
    $event_id = $_POST['event_id'];
    $attendance_data = $_POST['attendance']; // [user_id => attended (on/off)]

    foreach ($attendance_data as $user_id => $attended) {
        $is_attended = $attended === 'on' ? 1 : 0;
        $stmt = $pdo->prepare("UPDATE attendance SET attended = ?, attendance_date = NOW() WHERE event_id = ? AND user_id = ?");
        $stmt->execute([$is_attended, $event_id, $user_id]);
    }

    header("Location: track_attendance.php?event_id=" . $event_id . "&updated=true");
    exit;
}

// Get selected event ID and fetch attendees
$selected_event_id = $_GET['event_id'] ?? null;
$attendees = [];
if ($selected_event_id) {
    $stmt = $pdo->prepare("
        SELECT u.id AS user_id, u.full_name, a.attended
        FROM attendance a
        JOIN users u ON a.user_id = u.id
        WHERE a.event_id = ?
    ");
    $stmt->execute([$selected_event_id]);
    $attendees = $stmt->fetchAll();
}

?>

<section class="attendance-section">
    <div class="container">
        <h1>Track Attendance</h1>

        <form method="GET" action="track_attendance.php" class="event-selector">
            <label for="event_id">Select Event:</label>
            <select name="event_id" id="event_id" onchange="this.form.submit()">
                <option value="">-- Choose Event --</option>
                <?php foreach ($events as $event): ?>
                    <option value="<?php echo $event['id']; ?>" <?php if ($selected_event_id == $event['id']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($event['title']); ?> (<?php echo date('d M Y', strtotime($event['event_date'])); ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </form>

        <?php if ($selected_event_id && $attendees): ?>
            <form method="POST" class="attendance-form">
                <input type="hidden" name="event_id" value="<?php echo $selected_event_id; ?>">
                <table class="attendance-table">
                    <thead>
                        <tr>
                            <th>Member Name</th>
                            <th>Attended</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($attendees as $attendee): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($attendee['full_name']); ?></td>
                                <td>
                                    <input type="checkbox" name="attendance[<?php echo $attendee['user_id']; ?>]" <?php if ($attendee['attended']) echo 'checked'; ?>>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <button type="submit">Update Attendance</button>
            </form>
        <?php elseif ($selected_event_id): ?>
            <p>No registered members found for this event.</p>
        <?php endif; ?>

        <?php if (isset($_GET['updated'])): ?>
            <p class="success-message">Attendance updated successfully.</p>
        <?php endif; ?>
    </div>
</section>

<?php include '../includes/footer.php'; ?>
