<?php
session_start();
include '../cardealershipDB.php';

if (!isset($_SESSION['userID']) || $_SESSION['role'] !== 'admin') {
    die("Access denied");
}


/* Get totals */
$totalUsers = $conn->query("SELECT COUNT(*) as total FROM users")->fetch_assoc()['total'];
$totalOrders = $conn->query("SELECT COUNT(*) as total FROM orders")->fetch_assoc()['total'];
$totalReviews = $conn->query("SELECT COUNT(*) as total FROM reviews")->fetch_assoc()['total'];
$totalProducts = $conn->query("SELECT COUNT(*) as total FROM product")->fetch_assoc()['total'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="../styles.css">
</head>
<body>
<header class="site-header">
  <div class="header-inner">
    <div class="brand">
      <div class="logo">CD</div>
      <div class="brand-text">
        <h1>COM336 Car Dealership</h1>
        <p>Browse and buy your next car!</p>
      </div>
    </div>

<nav class="nav">

  <a href="/CarEcommerce/productPage.php">For sale!</a>
  <a href="/CarEcommerce/reviews.php">Reviews</a>

  <a href="/CarEcommerce/basket.php" class="basket-icon">
    🛒 <span id="basket-count">0</span>
  </a>

  <?php if (isset($_SESSION['userID'])): ?>

    <a href="/CarEcommerce/account.php">My Account</a>

    <?php if ($_SESSION['role'] === 'admin'): ?>
      <a href="/CarEcommerce/admin/dashboard.php">Admin</a>
    <?php endif; ?>

    <a href="/CarEcommerce/logout.php">Logout</a>

  <?php else: ?>

    <a href="/CarEcommerce/login.php">Login</a>
    <a href="/CarEcommerce/register.php">My Account</a>

  <?php endif; ?>

</nav>
  </div>
</header>
<main class="container">

  <section class="panel">
    <div class="panel-head">
      <h2>Admin Dashboard</h2>
      <p>System overview</p>
    </div>

    <div class="grid two">
      <div class="review-card">
        <h3>Total Users</h3>
        <p class="price"><?php echo $totalUsers; ?></p>
      </div>

      <div class="review-card">
        <h3>Total Orders</h3>
        <p class="price"><?php echo $totalOrders; ?></p>
      </div>

      <div class="review-card">
        <h3>Total Products</h3>
        <p class="price"><?php echo $totalProducts; ?></p>
      </div>

      <div class="review-card">
        <h3>Total Reviews</h3>
        <p class="price"><?php echo $totalReviews; ?></p>
      </div>
    </div>

  </section>
  <section class="panel">
    <div class="panel-head">
      <h2>Admin Actions</h2>
      <p>Manage your system</p>
    </div>

    <div class="grid two">

        <a href="manage_stock.php" class="review-card">
        <h3>Manage Stock</h3>
        <p class="muted">Update vehicle stock levels</p>
        </a>

        <a href="manage_orders.php" class="review-card">
        <h3>View Orders</h3>
        <p class="muted">See all customer orders</p>
        </a>

        <a href="manage_reviews.php" class="review-card">
        <h3>Manage Reviews</h3>
        <p class="muted">Delete or moderate reviews</p>
        </a>

        <a href="manage_users.php" class="review-card">
        <h3>Manage Users</h3>
        <p class="muted">View, promote or delete users</p>
        </a>

    </div>
  </section>
</main>
</body>
</html>
