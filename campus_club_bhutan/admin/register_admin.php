<?php
require_once '../includes/db_connect.php';
include '../includes/header.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    if ($full_name && $email && $password && $confirm_password) {
        if ($password === $confirm_password) {
            try {
                $check = $pdo->prepare("SELECT * FROM users WHERE email = ?");
                $check->execute([$email]);

                if ($check->rowCount() > 0) {
                    $error = "This email is already registered.";
                } else {
                    $hashed_pw = hash('sha256', $password);
                    $insert = $pdo->prepare("INSERT INTO users (full_name, email, password, role) VALUES (?, ?, ?, 'admin')");
                    $insert->execute([$full_name, $email, $hashed_pw]);
                    $success = "Admin registered successfully. You can now <a href='login.php'>log in</a>.";
                }
            } catch (PDOException $e) {
                error_log("Admin registration error: " . $e->getMessage());
                $error = "Error occurred. Please try again.";
            }
        } else {
            $error = "Passwords do not match.";
        }
    } else {
        $error = "All fields are required.";
    }
}
?>

<section class="admin-register-section">
    <div class="container">
        <h1>Register as Admin</h1>

        <?php if ($success): ?>
            <p class="success-message"><?php echo $success; ?></p>
        <?php elseif ($error): ?>
            <p class="error-message"><?php echo $error; ?></p>
        <?php endif; ?>

        <form action="register_admin.php" method="POST" class="admin-register-form">
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
