<?php
session_start();
require_once '../../backend/php/db_config.php';
require_once '../../backend/php/UserManager.php';

// Initialize UserManager
$userManager = new UserManager($pdo);

// Check if user is logged in
$isLoggedIn = isset($_SESSION['user_id']);
$username = $isLoggedIn ? $_SESSION['username'] : '';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>About Us - Soundex Audio Solutions</title>
  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="../css/header.css" />
  <link rel="stylesheet" href="../css/shared.css">
  <link rel="stylesheet" href="../css/about.css">
  <link rel="stylesheet" href="../css/footer.css" />
</head>

<body>
  <!-- Navigation Header -->
  <?php include '../includes/header.php'; ?>

  <main class="main-content">
    <section class="about-us section-padding">
      <div class="container">
        <h2>About Our Company</h2>
        <p>Soundex contributes towards selling, repairing and exchanging speakers and also provides a way to learn about
          repairing speakers. We are passionate about high-quality audio and sustainable technology.</p>
      </div>
    </section>

    <section class="team section-padding">
      <div class="container">
        <h2>Meet Our Team</h2>
        <div class="team-members">
          <div class="member">
            <img src="../../assets/images/team/member1.jpg" alt="Amrutiya Bhavy"
              onerror="this.src='https://via.placeholder.com/150?text=AB'">
            <h3>Amrutiya Bhavy</h3>
            <p>Team Leader</p>
          </div>
          <div class="member">
            <img src="../../assets/images/team/member2.jpg" alt="Babriya Dhruv"
              onerror="this.src='https://via.placeholder.com/150?text=BD'">
            <h3>Babriya Dhruv</h3>
            <p>Team Member</p>
          </div>
          <div class="member">
            <img src="../../assets/images/team/member3.jpg" alt="Bhatt Parv"
              onerror="this.src='https://via.placeholder.com/150?text=BP'">
            <h3>Bhatt Parv</h3>
            <p>Team Member</p>
          </div>
        </div>
      </div>
    </section>

    <section class="contact-details section-padding">
      <div class="container">
        <h2>Contact Info</h2>
        <p>If you have any questions, feel free to reach out to us!</p>
        <p>Email: <a href="mailto:Soundex6@gmail.com">Soundex6@gmail.com</a></p>
        <p>Phone: +91 1234567890</p>
      </div>
    </section>
  </main>

  <?php include '../includes/footer.php'; ?>
</body>

</html>