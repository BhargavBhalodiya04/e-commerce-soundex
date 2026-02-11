<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../../backend/php/db_config.php';
require_once '../../backend/php/UserManager.php';

$userManager = new UserManager($pdo);

// Check if user is an admin
if (!$userManager->isAdmin()) {
    if (isset($_SESSION['user_id'])) {
        // Logged in but not admin - go to home
        header("Location: ../pages/home.php");
    } else {
        // Not logged in at all - go to login with redirect back
        header("Location: ../pages/login.php?redirect=" . urlencode($_SERVER['REQUEST_URI']));
    }
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Soundex</title>
    <!-- Google Fonts: Outfit and Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/admin.css">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        // Sync PHP session token to localStorage for JS API calls
        <?php if (isset($_SESSION['session_token'])): ?>
            localStorage.setItem('session_token', '<?php echo $_SESSION['session_token']; ?>');
        <?php endif; ?>
    </script>
</head>

<body>
    <div class="admin-container">