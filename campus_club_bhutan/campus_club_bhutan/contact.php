<?php
require_once 'includes/db_connect.php';
include 'includes/header.php';

// Initialize feedback message
$success = '';
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);

    if ($name && $email && $subject && $message) {
        try {
            $stmt = $pdo->prepare("INSERT INTO messages (name, email, subject, message) VALUES (?, ?, ?, ?)");
            $stmt->execute([$name, $email, $subject, $message]);
            $success = "Thank you for contacting us. We’ll get back to you soon.";
        } catch (PDOException $e) {
            error_log("Message insert error: " . $e->getMessage());
            $error = "There was an error. Please try again later.";
        }
    } else {
        $error = "All fields are required.";
    }
}
?>

<section class="contact-section">
    <div class="container">
        <h1>Contact Us</h1>

        <?php if ($success): ?>
            <p class="success-message"><?php echo $success; ?></p>
        <?php elseif ($error): ?>
            <p class="error-message"><?php echo $error; ?></p>
        <?php endif; ?>

        <form action="contact.php" method="POST" class="contact-form">
            <label for="name">Full Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="email">Email Address:</label>
            <input type="email" id="email" name="email" required>

            <label for="subject">Subject:</label>
            <input type="text" id="subject" name="subject" required>

            <label for="message">Message:</label>
            <textarea id="message" name="message" rows="6" required></textarea>

            <button type="submit">Send Message</button>
        </form>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
