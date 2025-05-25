<?php
require_once '../includes/db_connect.php';
include '../includes/header.php';

$login_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if ($email && $password) {
        try {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND role = 'admin'");
            $stmt->execute([$email]);
            $admin = $stmt->fetch();

            if ($admin && hash('sha256', $password) === $admin['password']) {
                // Set session for admin
                $_SESSION['user_id'] = $admin['id'];
                $_SESSION['full_name'] = $admin['full_name'];
                $_SESSION['role'] = $admin['role'];

                header("Location: dashboard.php");
                exit;
            } else {
                $login_error = "Invalid admin credentials.";
            }
        } catch (PDOException $e) {
            error_log("Admin login error: " . $e->getMessage());
            $login_error = "An error occurred. Please try again.";
        }
    } else {
        $login_error = "Both fields are required.";
    }
}
?>

<section class="admin-login-section">
    <div class="container">
        <h1>Admin Login</h1>

        <?php if ($login_error): ?>
            <p class="error-message"><?php echo $login_error; ?></p>
        <?php endif; ?>

        <form action="login.php" method="POST" class="admin-login-form">
            <label for="email">Admin Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Login</button>
        </form>

        <p class="register-link">Need an account? <a href="register_admin.php">Register as Admin</a></p>
    </div>
</section>

<?php include '../includes/footer.php'; ?>

