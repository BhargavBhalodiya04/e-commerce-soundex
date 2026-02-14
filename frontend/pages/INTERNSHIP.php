<?php
require_once '../../backend/php/db_config.php';
require_once '../../backend/php/ApplicationManager.php';

// Initialize ApplicationManager
$appManager = new ApplicationManager($pdo);

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

$isLoggedIn = isset($_SESSION['user_id']);
$username = $isLoggedIn ? $_SESSION['username'] : '';

$message = '';
$messageType = '';

if ($_POST) {
  // Get form data
  $name = trim($_POST['name'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $phone = trim($_POST['phone'] ?? '');
  $address = trim($_POST['address'] ?? '');
  $qualification = trim($_POST['qualification'] ?? '');
  $gender = trim($_POST['gender'] ?? '');
  $school = trim($_POST['school'] ?? '');
  $scholarship = trim($_POST['scholarship'] ?? '');
  $description = trim($_POST['description'] ?? '');

  // Validate input
  $errors = [];

  if (empty($name)) {
    $errors[] = 'Name is required.';
  }

  if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Valid email is required.';
  }

  if (empty($phone) || strlen($phone) !== 10 || !is_numeric($phone)) {
    $errors[] = 'Phone number must be exactly 10 digits.';
  }

  if (empty($address)) {
    $errors[] = 'Address is required.';
  }

  if (empty($qualification)) {
    $errors[] = 'Qualification is required.';
  }

  if (empty($gender)) {
    $errors[] = 'Gender is required.';
  }

  if (empty($school)) {
    $errors[] = 'College/School is required.';
  }

  if (empty($scholarship)) {
    $errors[] = 'Scholarship status is required.';
  }

  if (empty($description)) {
    $errors[] = 'Description is required.';
  }

  // Handle file uploads
  if (!isset($_FILES['photos']) || count($_FILES['photos']['name']) < 5) {
    $errors[] = 'Please upload at least 5 photos.';
  }

  if (empty($errors)) {
    // Process file uploads
    $uploadedPhotos = [];
    $uploadDir = '../../uploads/internship/';

    // Create directory if it doesn't exist
    if (!file_exists($uploadDir)) {
      mkdir($uploadDir, 0777, true);
    }

    // Process each uploaded file
    for ($i = 0; $i < count($_FILES['photos']['name']); $i++) {
      if ($_FILES['photos']['error'][$i] === UPLOAD_ERR_OK) {
        $fileName = basename($_FILES['photos']['name'][$i]);
        $fileTmpName = $_FILES['photos']['tmp_name'][$i];
        $fileSize = $_FILES['photos']['size'][$i];
        $fileType = $_FILES['photos']['type'][$i];

        // Validate file type
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
        if (in_array($fileType, $allowedTypes)) {
          $newFileName = uniqid() . '_' . $fileName;
          $destination = $uploadDir . $newFileName;

          if (move_uploaded_file($fileTmpName, $destination)) {
            $uploadedPhotos[] = $destination;
          } else {
            $errors[] = 'Failed to upload photo: ' . $fileName;
          }
        } else {
          $errors[] = 'Invalid file type for: ' . $fileName . '. Only JPG, PNG, GIF allowed.';
        }
      }
    }

    if (empty($errors) && count($uploadedPhotos) >= 5) {
      // Prepare application data
      $applicationData = [
        'full_name' => $name,
        'email' => $email,
        'phone' => $phone,
        'address' => $address,
        'qualification' => $qualification,
        'gender' => $gender,
        'college_school' => $school,
        'previous_scholarship' => $scholarship,
        'self_description' => $description
      ];

      // Prepare file data
      $fileData = [];
      foreach ($uploadedPhotos as $photoPath) {
        $fileData[] = [
          'name' => basename($photoPath),
          'path' => $photoPath,
          'size' => filesize($photoPath)
        ];
      }

      // Save application to database
      $result = $appManager->submitApplication($applicationData, $fileData);

      if ($result['success']) {
        $message = 'Application submitted successfully!';
        $messageType = 'success';
        // Clear form after successful submission
        $_POST = array();
      } else {
        $message = $result['message'];
        $messageType = 'error';
      }
    } else {
      $message = 'Failed to upload required number of photos.';
      $messageType = 'error';
    }
  } else {
    $message = implode('<br>', $errors);
    $messageType = 'error';
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Internship Application - Soundex Audio Solutions</title>
  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="../css/header.css" />
  <link rel="stylesheet" href="../css/shared.css">
  <link rel="stylesheet" href="../css/internship.css">
</head>

<body>
  <!-- Navigation Header -->
  <nav>
    <ul>
      <div class="logo"><a href="../pages/about.php">
          <h1>Soun<p>Dex</p>
          </h1>
        </a></div>
      <li><a href="../pages/home.php">Home</a></li>
      <li><a href="../pages/Gallery.php">Gallery</a></li>
      <li><a href="../pages/faqs.php">FAQs</a></li>
      <li><a href="../pages/services.php">Services</a></li>
      <li><a href="../pages/contact us.php">Contact</a></li>
      <li><a href="../pages/about.php">About</a></li>
      <!-- Admin Link is missing session logic in original file, added for consistency but relies on external session start or this file needs session_start() -->
      <!-- Since this file didn't have session_start() at top but accessed SESSION implicitly in other files, added check -->
      <!-- Wait, this file handles form submission but didn't have session_start(). Adding it. -->
    </ul>
  </nav>

  <!-- Since shared header logic requires session, need to ensure session is started at top of file -->
  <!-- Modifying top of file to include session_start() -->

  <main class="main-content">
    <section class="internship-section">
      <div class="container">
        <h1 class="section-title">Scholarship Eligibility Form</h1>

        <?php if ($message): ?>
          <div
            style="padding: 15px; margin-bottom: 20px; border-radius: 8px; <?php echo $messageType === 'error' ? 'background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb;' : 'background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb;'; ?>">
            <?php echo $message; ?>
          </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
          <label>Name</label>
          <input type="text" name="name" value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>" required />

          <label>E-mail</label>
          <input type="email" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required />
          <div id="emailError" class="error"></div>

          <label>Phone Number</label>
          <input type="tel" name="phone" value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>" required />
          <div id="phoneError" class="error"></div>

          <label>Address</label>
          <textarea name="address" rows="3" required><?php echo htmlspecialchars($_POST['address'] ?? ''); ?></textarea>

          <label>Qualification</label>
          <input type="text" name="qualification" value="<?php echo htmlspecialchars($_POST['qualification'] ?? ''); ?>"
            required />

          <label>Gender</label>
          <select name="gender" required>
            <option value="">--Select--</option>
            <option value="Male" <?php echo (isset($_POST['gender']) && $_POST['gender'] === 'Male') ? 'selected' : ''; ?>>Male</option>
            <option value="Female" <?php echo (isset($_POST['gender']) && $_POST['gender'] === 'Female') ? 'selected' : ''; ?>>Female</option>
            <option value="Other" <?php echo (isset($_POST['gender']) && $_POST['gender'] === 'Other') ? 'selected' : ''; ?>>Other</option>
          </select>

          <label>College / School</label>
          <input type="text" name="school" value="<?php echo htmlspecialchars($_POST['school'] ?? ''); ?>" required />

          <label>Have you received any scholarship?</label>
          <select name="scholarship" required>
            <option value="">--Select--</option>
            <option value="Yes" <?php echo (isset($_POST['scholarship']) && $_POST['scholarship'] === 'Yes') ? 'selected' : ''; ?>>Yes</option>
            <option value="No" <?php echo (isset($_POST['scholarship']) && $_POST['scholarship'] === 'No') ? 'selected' : ''; ?>>No</option>
          </select>

          <label>Describe Yourself</label>
          <textarea name="description" rows="4"
            required><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>

          <label>Upload Your Photos (Minimum 5)</label>
          <input type="file" name="photos[]" multiple accept="image/*" required />
          <div id="photoError" class="error"></div>

          <button type="submit">Submit Application</button>
        </form>
      </div>
    </section>
  </main>

  <!-- Footer -->
  <footer class="site-footer">
    <div class="footer-content">
      <div class="footer-logo">
        <h2>Soun<span>Dex</span></h2>
        <p>Your one-stop shop for premium audio.</p>
      </div>
      <div class="footer-links">
        <h3>Quick Links</h3>
        <ul>
          <li><a href="../pages/home.php">Home</a></li>
          <li><a href="../pages/buy.php">Shop</a></li>
          <li><a href="../pages/services.php">Services</a></li>
          <li><a href="../pages/contact us.php">Contact</a></li>
        </ul>
      </div>
      <div class="footer-social">
        <h3>Follow Us</h3>
        <div class="social-icons">
          <a href="#" class="social-icon">FB</a>
          <a href="#" class="social-icon">IG</a>
          <a href="#" class="social-icon">TW</a>
        </div>
      </div>
    </div>
    <div class="footer-bottom">
      <p>&copy; <?php echo date('Y'); ?> Soundex Audio Solutions. All rights reserved.</p>
    </div>
  </footer>

  <script>
    document.querySelector("form").addEventListener("submit", function (e) {
      let valid = true;
      const email = document.querySelector("[name='email']").value;
      const phone = document.querySelector("[name='phone']").value;
      const photosInput = document.querySelector("[name='photos[]']");
      const photos = photosInput.files;

      // Reset errors
      document.getElementById("emailError").textContent = "";
      document.getElementById("phoneError").textContent = "";
      document.getElementById("photoError").textContent = "";

      // Email validation
      if (!email.includes("@") || email.length < 5) {
        document.getElementById("emailError").textContent = "Please enter a valid email.";
        valid = false;
      }

      // Phone validation
      if (phone.length !== 10 || isNaN(phone)) {
        document.getElementById("phoneError").textContent = "Phone number must be exactly 10 digits.";
        valid = false;
      }

      // Photo validation
      if (photos.length < 5) {
        document.getElementById("photoError").textContent = "Please upload at least 5 images.";
        valid = false;
      }

      if (!valid) {
        e.preventDefault();
      }
    });

    // Cart Logic
    function updateCartCount() {
      const cart = JSON.parse(localStorage.getItem('cart')) || [];
      const totalItems = cart.reduce((total, item) => total + (item.quantity || 1), 0);
      const cartCountElement = document.getElementById('cartCount');
      const cartIconElement = document.getElementById('cartIcon');

      if (cartCountElement) {
        cartCountElement.textContent = totalItems;
        if (totalItems > 0) {
          cartIconElement.classList.remove('empty');
        } else {
          cartIconElement.classList.add('empty');
        }
      }
    }
    document.addEventListener('DOMContentLoaded', updateCartCount);
  </script>
</body>

</html>