<?php
session_start();
include 'db.php'; 
$success = '';
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and retrieve form data
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $password_hash = password_hash($password, PASSWORD_DEFAULT); // Hash the password

    // Check if the email already exists in the database
    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $error = "Email already registered."; // Error message for existing email
    } else {
        // Insert new user into the database
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $password_hash);

        if ($stmt->execute()) {
            $success = "Signup successful! You can now sign in."; // Success message
        } else {
            $error = "Signup failed. Please try again."; // General error message
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Sign Up</title>
  <link rel="stylesheet" href="auth.css" />
</head>
<body>
   <section id="header">
    <img src="image/itis.jpg" alt="logo" class ="logo">
        <div>
            <ul id="navbar">
                <li><a class="active" href="index.php">Home</a></li>
                <li><a href="shop.php">Shop</a></li>
                <li><a href="blog.php">Blog</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
        </div>
    </section>
  <div class="login-box">
    <h2>Create Account</h2>
    <?php if ($error): ?>
      <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <?php if ($success): ?>
      <p style="color: green;"><?php echo htmlspecialchars($success); ?></p>
    <?php endif; ?>

    <!-- Signup form -->
    <form action="signup.php" method="POST">
      <div class="user-box">
        <input type="text" name="username" required />
        <label>Username</label>
      </div>
      <div class="user-box">
        <input type="email" name="email" required />
        <label>Email</label>
      </div>
      <div class="user-box">
        <input type="password" name="password" required />
        <label>Password</label>
      </div>
      <button class="login-btn" type="submit">Sign Up</button>
      <p class="alt-link">Already have an account? <a href="signin.php">Sign In</a></p>
    </form>
  </div>
</body>
</html
