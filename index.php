<?php
// Start the session
session_start();

// Initialize error message
$error_message = "";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the submitted email and password
    $email = $_POST['email'];
    $password = $_POST['pass'];

    // Validate the email domain
    $isStudent = str_ends_with($email, '@g.bracu.ac.bd');
    $isFaculty = str_ends_with($email, '@bracu.ac.bd');

    if ($isStudent) {
        // Redirect student to the student page
        header("Location: studentsignin.php");
        exit();
    } elseif ($isFaculty) {
        // Redirect faculty to the faculty page
        header("Location: facultysignin.php");
        exit();
    } else {
        // Set error message if email domain is invalid
        $error_message = "Invalid email domain. Use @g.bracu.ac.bd for students or @bracu.ac.bd for faculty.";
    }
}

// Helper function for PHP 7.4 and earlier (since `str_ends_with` was introduced in PHP 8.0)
if (!function_exists('str_ends_with')) {
    function str_ends_with($haystack, $needle) {
        return substr($haystack, -strlen($needle)) === $needle;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign In</title>
  <style>
    /* Basic Styling */
    body {
      background: linear-gradient(120deg, #4caf50, #2196f3);
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
      font-family: Arial, sans-serif;
      color: #fff;
    }

    .form-container {
      background: #fff;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
      width: 400px;
      text-align: center;
      color: #333;
    }

    .form-container h1 {
      margin-bottom: 20px;
      color: #4caf50;
    }

    .form-container input {
      width: 100%;
      padding: 10px;
      margin: 10px 0;
      border: 1px solid #ddd;
      border-radius: 5px;
      font-size: 16px;
    }

    .form-container input[type="submit"] {
      background: #4caf50;
      color: #fff;
      border: none;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    .form-container input[type="submit"]:hover {
      background: #45a049;
    }

    .error-message {
      font-size: 14px;
      color: #f44336;
      margin-top: 10px;
    }
  </style>
</head>
<body>
  <div class="form-container">
    <h1>Sign In</h1>
    <form id="loginForm" action="" method="post">
      <label for="email">Email:</label>
      <input type="email" id="email" name="email" placeholder="Enter your email" required>
      <label for="password">Password:</label>
      <input type="password" id="password" name="pass" placeholder="Enter your password" required>
      <input type="submit" value="Sign In">
    </form>
    <?php
    // Display error message if email domain is invalid
    if (!empty($error_message)) {
        echo '<p class="error-message">' . htmlspecialchars($error_message) . '</p>';
    }
    ?>
  </div>
</body>
</html>
