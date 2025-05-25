<?php
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
    <link rel="stylesheet" href="/CAMPUS_CLUB_BHUTAN/css/style.css">
    <link rel="icon" type="image/png" href="/CAMPUS_CLUB_BHUTAN/images/logo.png">
</head>
<body>
    <header>
        <div class="navbar">
            <a href="/CAMPUS_CLUB_BHUTAN/index.php" class="logo">Bhutan Campus Clubs</a>
            <nav>
                <ul>
                    <li><a href="/CAMPUS_CLUB_BHUTAN/index.php">Home</a></li>
                    <li><a href="/CAMPUS_CLUB_BHUTAN/about.php">About</a></li>
                    <li><a href="/CAMPUS_CLUB_BHUTAN/member/events.php">Events</a></li>
                    <li><a href="/CAMPUS_CLUB_BHUTAN/member/register.php">Join Club</a></li>
                    <li><a href="/CAMPUS_CLUB_BHUTAN/contact.php">Contact</a></li>

                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                        <li><a href="/CAMPUS_CLUB_BHUTAN/admin/dashboard.php">Admin Panel</a></li>
                        <li><a href="/CAMPUS_CLUB_BHUTAN/admin/logout.php">Logout</a></li>
                    <?php elseif (isset($_SESSION['role']) && $_SESSION['role'] === 'member'): ?>
                        <li><a href="/CAMPUS_CLUB_BHUTAN/member/logout.php">Logout</a></li>
                    <?php else: ?>
                        <li><a href="/CAMPUS_CLUB_BHUTAN/admin/login.php">Admin Login</a></li>
                        <li><a href="/CAMPUS_CLUB_BHUTAN/member/login.php">Member Login</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>

    <main>
