<?php
require_once '../includes/db_connect.php';
include '../includes/header.php';

$login_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if ($email && $password) {
        try {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND role = 'member'");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user && hash('sha256', $password) === $user['password']) {
                // Set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['full_name'] = $user['full_name'];
                $_SESSION['role'] = $user['role'];

                // Redirect to events page
                header("Location: events.php");
                exit;
            } else {
                $login_error = "Invalid email or password.";
            }
        } catch (PDOException $e) {
            error_log("Login error: " . $e->getMessage());
            $login_error = "An error occurred. Please try again later.";
        }
    } else {
        $login_error = "Both fields are required.";
    }
}
?>

<section class="login-section">
    <div class="container">
        <h1>Member Login</h1>

        <?php if ($login_error): ?>
            <p class="error-message"><?php echo $login_error; ?></p>
        <?php endif; ?>

        <form action="login.php" method="POST" class="login-form">
            <label for="email">Email Address:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Login</button>
        </form>

        <p class="register-link">Not registered yet? <a href="register.php">Join now</a></p>
    </div>
</section>

<?php include '../includes/footer.php'; ?>
