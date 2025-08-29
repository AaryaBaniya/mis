<?php
// Get the role from the database first to decide session type
include 'db.php'; 
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            // ✅ Use different session names for user and admin
            if ($user['role'] === 'admin') {
                session_name("admin_session");
                session_start();
                $_SESSION['admin_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = 'admin';
                header("Location: admin.php");
            } else {
                session_name("user_session");
                session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = 'user';
                header("Location: dashboard.php");
            }
            exit();
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "Email not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Sign In</title>
  <link rel="stylesheet" href="auth.css" />
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
        <h2>Welcome Back!</h2>
        <?php if (!empty($error)): ?>
            <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <form action="signin.php" method="POST">
            <div class="user-box">
                <input type="email" name="email" required />
                <label>Email</label>
            </div>
            <div class="user-box">
                <input type="password" name="password" required />
                <label>Password</label>
            </div>
            <button class="login-btn" type="submit">Sign In</button>
            <p class="alt-link">No account? <a href="signup.php">Sign Up</a></p>
        </form>
    </div>
</body>
</html>
