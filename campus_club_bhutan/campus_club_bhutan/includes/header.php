<?php
// Start session for login/logout
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campus Club & Event Management</title>
    <link rel="stylesheet" href="/css/style.css"> <!-- Ensure the path is correct -->
    <link rel="icon" type="image/png" href="/images/logo.png">
</head>
<body>
    <header>
        <div class="navbar">
            <a href="/index.php" class="logo">Bhutan Campus Clubs</a>
            <nav>
                <ul>
                    <li><a href="/index.php">Home</a></li>
                    <li><a href="/about.php">About</a></li>
                    <li><a href="/member/events.php">Events</a></li>
                    <li><a href="/member/register.php">Join Club</a></li>
                    <li><a href="/contact.php">Contact</a></li>

                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                        <li><a href="/admin/dashboard.php">Admin Panel</a></li>
                        <li><a href="/admin/logout.php">Logout</a></li>
                    <?php elseif (isset($_SESSION['role']) && $_SESSION['role'] === 'member'): ?>
                        <li><a href="/member/logout.php">Logout</a></li>
                    <?php else: ?>
                        <li><a href="/admin/login.php">Admin Login</a></li>
                        <li><a href="/member/login.php">Member Login</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>

    <main>
