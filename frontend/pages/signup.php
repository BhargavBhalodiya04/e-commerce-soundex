<?php
require_once '../../backend/php/db_config.php';
require_once '../../backend/php/UserManager.php';

// Initialize UserManager
$userManager = new UserManager($pdo);

$message = '';

if ($_POST) {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    
    // Validate input
    if (empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
        $message = "All fields are required.";
    } elseif ($password !== $confirmPassword) {
        $message = "Passwords do not match.";
    } elseif (strlen($password) < 6) {
        $message = "Password must be at least 6 characters long.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email format.";
    } else {
        // Attempt to register user
        $result = $userManager->registerUser($username, $email, $password);
        
        if ($result['success']) {
            // Redirect to home page
            header("Location: home.php");
            exit();
        } else {
            $message = $result['message'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Soundex</title>
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/footer.css">
    <style>
        .signup-page-container {
            padding-top: 100px;
            padding-bottom: 50px;
            min-height: calc(100vh - 140px);
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #50bbed;
            font-family: 'Segoe UI', sans-serif;
        }

        .signup-container {
            width: 370px; 
            padding: 25px; 
            background-color: white; 
            border-radius: 12px; 
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2); 
            animation: fadeZoom 0.8s ease;
            text-align: center;
        }

        .input-field {
            width: 100%; 
            padding: 10px; 
            margin-bottom: 12px; 
            border: 1px solid #ccc; 
            border-radius: 6px;
        }

        .submit-btn {
            width: 100%; 
            padding: 12px; 
            background-color: #0077cc; 
            color: white; 
            border: none; 
            border-radius: 6px; 
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .submit-btn:hover {
            background-color: #005fa3;
        }

        .login-link {
            margin-top: 15px;
        }

        .login-link a {
            color: #0077cc;
            text-decoration: none;
        }

        .error-message {
            color: red;
            margin-bottom: 10px;
        }

        @keyframes fadeZoom {
            0% { opacity: 0; transform: scale(0.9); }
            100% { opacity: 1; transform: scale(1); }
        }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div class="signup-page-container">
        <div class="signup-container">
            <h2 style="margin-bottom: 20px; color: #0077cc;">Welcome to Soundex</h2>
            <p>Where Every Beat Becomes Unforgettable</p>
            
            <?php if (!empty($message)): ?>
                <div class="error-message"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <input type="text" name="username" placeholder="Username" class="input-field" required>
                <input type="email" name="email" placeholder="Email ID" class="input-field" required>
                <input type="password" name="password" placeholder="Password" class="input-field" required>
                <input type="password" name="confirm_password" placeholder="Confirm Password" class="input-field" required>
                <button type="submit" class="submit-btn">Create Account</button>
            </form>
            <br>
            <div class="login-link">Already have an account? <a href="login.php">Login here</a></div>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>
</html>