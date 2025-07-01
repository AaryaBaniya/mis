<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<header class="navbar">
  <div class="logo">
      <a href="index.php">
      <img src="image/itis.jpg"alt="logo" class="logo-img">
    </a>
  </div>
  <nav class="nav-menu">
  <?php if (isset($_SESSION['user_id'])): ?>
  <a href="dashboard.php" class="nav-link">Dashboard</a>
<?php endif; ?>
    <a href="Shop.php" class="nav-link">Shop</a>
    <a href="newarrivals.php" class="nav-link">New Arrivals</a>
    <a href="decor.php" class="nav-link">Decor</a>
    <a href="statue.php" class="nav-link">Statue</a>
    <a href="antiques.php" class="nav-link">Antiques</a>
  </nav>

  <!-- Search, Cart, Profile -->
  <div class="search-cart-profile">
    <input type="text" placeholder="Search products..." class="search-bar" />
    <div class="dropdown profile-dropdown">
      <div class="profile-toggle">
        <span class="profile-icon">👤</span>
      </div>
      <div class="dropdown-content right-align">
        <?php if (isset($_SESSION['user_id']) && isset($_SESSION['username'])): ?>
          <a href="logout.php" class="nav-link">Logout</a>
        <?php else: ?>
          <a href="signin.php" class="nav-link">Sign In</a>
          <a href="signup.php" class="nav-link">Sign Up</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</header>
