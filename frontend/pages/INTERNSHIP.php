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
  
  <link rel="stylesheet" href="../css/header.css" />
  <link rel="stylesheet" href="../css/footer.css" />
  <link rel="stylesheet" href="../css/shared.css">
  <link rel="stylesheet" href="../css/internship.css">
</head>

<body>
  <!-- Navigation Header -->
  <?php include '../includes/header.php'; ?>

  <main class="main-content">
    <section class="internship-section">
      <div class="container" style="margin-top: 50px;">
        <h1 class="section-title">Scholarship Eligibility Form</h1>

        <?php if ($message): ?>
          <div
            style="padding: 15px; margin-bottom: 20px; border-radius: 8px; <?php echo $messageType === 'error' ? 'background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb;' : 'background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb;'; ?>">
            <?php echo $message; ?>
          </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
          <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>" required />
          </div>

          <div class="form-group">
            <label>E-mail</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required />
            <div id="emailError" class="error"></div>
          </div>

          <div class="form-group">
            <label>Phone Number</label>
            <input type="tel" name="phone" value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>" required />
            <div id="phoneError" class="error"></div>
          </div>

          <div class="form-group">
            <label>Address</label>
            <textarea name="address" rows="3" required><?php echo htmlspecialchars($_POST['address'] ?? ''); ?></textarea>
          </div>

          <div class="form-group">
            <label>Qualification</label>
            <input type="text" name="qualification" value="<?php echo htmlspecialchars($_POST['qualification'] ?? ''); ?>" required />
          </div>

          <div class="form-group">
            <label>Gender</label>
            <select name="gender" required>
              <option value="">--Select--</option>
              <option value="Male" <?php echo (isset($_POST['gender']) && $_POST['gender'] === 'Male') ? 'selected' : ''; ?>>Male</option>
              <option value="Female" <?php echo (isset($_POST['gender']) && $_POST['gender'] === 'Female') ? 'selected' : ''; ?>>Female</option>
              <option value="Other" <?php echo (isset($_POST['gender']) && $_POST['gender'] === 'Other') ? 'selected' : ''; ?>>Other</option>
            </select>
          </div>

          <div class="form-group">
            <label>College / School</label>
            <input type="text" name="school" value="<?php echo htmlspecialchars($_POST['school'] ?? ''); ?>" required />
          </div>

          <div class="form-group">
            <label>Have you received any scholarship?</label>
            <select name="scholarship" required>
              <option value="">--Select--</option>
              <option value="Yes" <?php echo (isset($_POST['scholarship']) && $_POST['scholarship'] === 'Yes') ? 'selected' : ''; ?>>Yes</option>
              <option value="No" <?php echo (isset($_POST['scholarship']) && $_POST['scholarship'] === 'No') ? 'selected' : ''; ?>>No</option>
            </select>
          </div>

          <div class="form-group">
            <label>Describe Yourself</label>
            <textarea name="description" rows="4" required><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
          </div>

          <div class="form-group">
            <label>Upload Your Photos (Minimum 5)</label>
            <input type="file" name="photos[]" multiple accept="image/*" required />
            <div id="photoError" class="error"></div>
          </div>

          <button type="submit">Submit Application</button>
        </form>
      </div>
    </section>
  </main>

  <script>
    document.querySelector("form").addEventListener("submit", function (e) {
      let valid = true;
      const email = document.querySelector("[name='email']").value;
      const phone = document.querySelector("[name='phone']").value;
      const photosInput = document.querySelector("[name='photos[]']");
      const photos = photosInput.files;

      // Reset errors
      const emailError = document.getElementById("emailError");
      const phoneError = document.getElementById("phoneError");
      const photoError = document.getElementById("photoError");
      
      if(emailError) emailError.textContent = "";
      if(phoneError) phoneError.textContent = "";
      if(photoError) photoError.textContent = "";

      // Email validation
      if (!email.includes("@") || email.length < 5) {
        if(emailError) emailError.textContent = "Please enter a valid email.";
        valid = false;
      }

      // Phone validation
      if (phone.length !== 10 || isNaN(phone)) {
        if(phoneError) phoneError.textContent = "Phone number must be exactly 10 digits.";
        valid = false;
      }

      // Photo validation
      if (photos.length < 5) {
        if(photoError) photoError.textContent = "Please upload at least 5 images.";
        valid = false;
      }

      if (!valid) {
        e.preventDefault();
      }
    });
  </script>
  <?php include '../includes/footer.php'; ?>
</body>

</html>