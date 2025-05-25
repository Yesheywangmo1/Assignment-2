<?php
require_once '../includes/db_connect.php';
include '../includes/header.php';

$success = '';
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    if ($full_name && $email && $password && $confirm_password) {
        if ($password === $confirm_password) {
            try {
                // Check if email already exists
                $check_stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
                $check_stmt->execute([$email]);

                if ($check_stmt->rowCount() > 0) {
                    $error = "Email is already registered.";
                } else {
                    // Insert new member
                    $hashed_pw = hash('sha256', $password);
                    $insert_stmt = $pdo->prepare("INSERT INTO users (full_name, email, password, role) VALUES (?, ?, ?, 'member')");
                    $insert_stmt->execute([$full_name, $email, $hashed_pw]);
                    $success = "Registration successful. You can now <a href='login.php'>log in</a>.";
                }
            } catch (PDOException $e) {
                error_log("Registration error: " . $e->getMessage());
                $error = "Something went wrong. Please try again later.";
            }
        } else {
            $error = "Passwords do not match.";
        }
    } else {
        $error = "All fields are required.";
    }
}
?>

<section class="register-section">
    <div class="container">
        <h1>Join the Club</h1>

        <?php if ($success): ?>
            <p class="success-message"><?php echo $success; ?></p>
        <?php elseif ($error): ?>
            <p class="error-message"><?php echo $error; ?></p>
        <?php endif; ?>

        <form action="register.php" method="POST" class="register-form">
            <label for="full_name">Full Name:</label>
            <input type="text" id="full_name" name="full_name" required>

            <label for="email">Email Address:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <label for="confirm_password">Confirm Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>

            <button type="submit">Register</button>
        </form>
    </div>
</section>

<?php include '../includes/footer.php'; ?>
