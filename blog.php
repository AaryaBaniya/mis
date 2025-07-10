<?php
session_start();
$isLoggedIn = isset($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Blog | Brass and Copper Hub</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'navbar.php'; ?>

<section class="hero-banner">
    <h1>Our Blog</h1>
    <p>Learn about the beauty, care, and heritage of brass and copper products.</p>
</section>

<section class="blog-section">
    <div class="blog-card">
        <img src="images/blog1.jpg" alt="Polishing Brass">
        <h3>How to Keep Your Brass Shining Bright</h3>
        <p>Discover traditional and modern methods to clean and preserve your brass items.</p>
        <a href="#">Read More →</a>
    </div>
    <div class="blog-card">
        <img src="images/blog2.jpg" alt="Cultural Decor">
        <h3>The Cultural Significance of Copper Decor</h3>
        <p>Explore how copper has been used in Nepali homes and temples for centuries.</p>
        <a href="#">Read More →</a>
    </div>
    <div class="blog-card">
        <img src="images/blog3.jpg" alt="Handmade items">
        <h3>Behind the Scenes: Crafting Handmade Pieces</h3>
        <p>Meet the artisans and see how your favorite products are carefully created.</p>
        <a href="#">Read More →</a>
    </div>
</section>

<?php include 'footer.php'; ?>

</body>
</html>
