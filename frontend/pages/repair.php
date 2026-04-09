<?php
session_start();
$isLoggedIn = isset($_SESSION['user_id']);
$username = $isLoggedIn ? $_SESSION['username'] : '';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Repair Services - Soundex</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="../css/shared.css">
    <style>
        .repair-section {
            padding: 40px 20px;
            text-align: center;
            background-color: #f5f5f5;
            margin-top: 100px;
        }

        .repair-options {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
            margin: 30px 0;
        }

        .repair-option {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            min-width: 250px;
            text-align: center;
        }

        .book-btn {
            background-color: #ff5722;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
            font-weight: 600;
        }

        .book-btn:hover {
            background-color: #e64a19;
        }

        .repair-process {
            max-width: 800px;
            margin: 40px auto;
            text-align: left;
            background: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .repair-process h2 {
            margin-bottom: 20px;
            color: #1a1a2e;
        }

        .repair-process ol {
            padding-left: 20px;
        }

        .repair-process li {
            margin: 15px 0;
            color: #444;
            font-size: 1.1rem;
        }
    </style>
</head>

<body>
    <!-- Fixed Navigation Header -->
    <?php include '../includes/header.php'; ?>

    <!-- Main Content -->
    <main>
        <section class="repair-section">
            <h1>Live Repair Services</h1>
            <p>Book a live repair session with our expert technicians.</p>

            <div class="repair-options">
                <div class="repair-option">
                    <h2>Basic Repair</h2>
                    <p>Simple fixes for common issues</p>
                    <p class="price">Starting at ₹499</p>
                    <a href="../pages/booknow.php"><button class="book-btn">Book Now</button></a>
                </div>
                <div class="repair-option">
                    <h2>Premium Repair</h2>
                    <p>Comprehensive repair with warranty</p>
                    <p class="price">Starting at ₹999</p>
                    <a href="../pages/booknow.php"><button class="book-btn">Book Now</button></a>
                </div>
                <div class="repair-option">
                    <h2>On-Site Repair</h2>
                    <p>Technician visits your location</p>
                    <p class="price">Starting at ₹1499</p>
                    <a href="../pages/booknow.php"><button class="book-btn">Book Now</button></a>
                </div>
            </div>

            <div class="repair-process">
                <h2>Our Repair Process</h2>
                <ol>
                    <li>Schedule a repair appointment</li>
                    <li>Our technician contacts you for live assistance</li>
                    <li>Diagnosis and repair process begins</li>
                    <li>Quality check and testing</li>
                    <li>Complete repair with warranty</li>
                </ol>
            </div>
        </section>
    </main>
    <?php include '../includes/footer.php'; ?>
</body>

</html>