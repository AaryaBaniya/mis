<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contact Us | Brass and Copper Hub</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'header.php'; ?>

<section class="hero-banner">
    <h1>Contact Us</h1>
    <p>Have questions or custom orders? We're here to help!</p>
</section>

<section class="contact-section">
    <div class="contact-form">
        <form action="send_message.php" method="post">
            <label for="name">Your Name</label>
            <input type="text" name="name" id="name" required>

            <label for="email">Your Email</label>
            <input type="email" name="email" id="email" required>

            <label for="message">Message</label>
            <textarea name="message" id="message" rows="5" required></textarea>

            <button type="submit">Send Message</button>
        </form>
    </div>
    <div class="contact-info">
        <h3>Visit Our Shop</h3>
        <p>New Road, Kathmandu, Nepal</p>
        <p>Phone: +977-980XXXXXXX</p>
        <p>Email: support@brasscopperhub.com</p>
    </div>
</section>

<?php include 'footer.php'; ?>

</body>
</html>
