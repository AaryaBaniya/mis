<?php
session_start();
include 'db.php'; 
$success = '';
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize inputs
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // === Validation ===
    if (strlen($username) < 3 || !preg_match("/^[a-zA-Z0-9_]+$/", $username)) {
        $error = "Username must be at least 3 characters and contain only letters, numbers, and underscores.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } elseif (strlen($password) < 8 ||
              !preg_match("/[A-Z]/", $password) || 
              !preg_match("/[a-z]/", $password) || 
              !preg_match("/[0-9]/", $password) || 
              !preg_match("/[\W]/", $password)) {
        $error = "Password must be at least 8 characters, include uppercase, lowercase, number, and special character.";
    } else {
        // Check if the email already exists
        $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $error = "Email already registered.";
        } else {
            // Hash password and insert
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $email, $password_hash);

            if ($stmt->execute()) {
                $success = "Signup successful! You can now sign in.";
            } else {
                $error = "Signup failed. Please try again.";
            }
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
  <style>
    .strength {
      font-size: 14px;
      margin-top: 5px;
    }
    .strength.weak { color: red; }
    .strength.medium { color: orange; }
    .strength.strong { color: green; }
  </style>
</head>
<body>
   <section id="header">
    <img src="image/itis.jpg" alt="logo" class="logo">
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
        <input type="text" name="username" required 
               pattern="[A-Za-z0-9_]{3,}" 
               title="At least 3 characters, letters/numbers/underscores only" />
        <label>Username</label>
      </div>
      <div class="user-box">
        <input type="email" name="email" required />
        <label>Email</label>
      </div>
      <div class="user-box">
        <input type="password" name="password" id="password" required
               pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*\W).{8,}"
               title="Min 8 chars, with uppercase, lowercase, number, and special character." />
        <label>Password</label>
        <div id="strengthMessage" class="strength"></div>
      </div>
      <button class="login-btn" type="submit">Sign Up</button>
      <p class="alt-link">Already have an account? <a href="signin.php">Sign In</a></p>
    </form>
  </div>

  <script>
    const passwordInput = document.getElementById("password");
    const strengthMessage = document.getElementById("strengthMessage");

    passwordInput.addEventListener("input", () => {
      const val = passwordInput.value;
      let strength = 0;
      if (val.length >= 8) strength++;
      if (/[A-Z]/.test(val)) strength++;
      if (/[a-z]/.test(val)) strength++;
      if (/[0-9]/.test(val)) strength++;
      if (/[\W]/.test(val)) strength++;

      if (strength <= 2) {
        strengthMessage.textContent = "Weak password";
        strengthMessage.className = "strength weak";
      } else if (strength === 3 || strength === 4) {
        strengthMessage.textContent = "Medium strength";
        strengthMessage.className = "strength medium";
      } else if (strength === 5) {
        strengthMessage.textContent = "Strong password";
        strengthMessage.className = "strength strong";
      } else {
        strengthMessage.textContent = "";
      }
    });
  </script>
</body>
</html>
