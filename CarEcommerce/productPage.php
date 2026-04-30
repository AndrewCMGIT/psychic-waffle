<?php 
session_start();
require_once "cardealershipDB.php";

$result = $conn->query("
    SELECT p.productID, p.model_name, p.model_year, p.fuel_type, p.engine_size, p.colour, p.price, s.stock_level
    FROM product p
    LEFT JOIN stock s ON p.productID = s.productID
    ORDER BY p.productID ASC
");

$imageMap = [
    1 => "images/corolla.png",
    2 => "images/rav4.png",
    3 => "images/yaris.png",
    4 => "images/prius.png",
    5 => "images/hilux.png"
];

$stats = $conn->query("
    SELECT 
      COUNT(*) AS car_count,
      MIN(price) AS starting_price,
      MAX(price) AS highest_price
    FROM product
")->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>COM336 Car Dealership — Shop Cars</title>
  <link rel="stylesheet" href="styles.css" />
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
      <a href="productPage.php">For sale!</a>
      <a href="reviews.php">Reviews</a>

      <a href="basket.php" class="basket-icon">
        🛒 <span id="basket-count">0</span>
      </a>

      <?php if (isset($_SESSION['userID'])): ?>

        <a href="account.php">My Account</a>

        <?php if ($_SESSION['role'] === 'admin'): ?>
          <a href="admin/dashboard.php">Admin</a>
        <?php endif; ?>

        <a href="logout.php">Logout</a>

      <?php else: ?>

        <a href="login.php">Login</a>
        <a href="register.php">My Account</a>

      <?php endif; ?>
    </nav>
  </div>
</header>

<main class="container">

  <section class="hero">
    <div class="hero-content">
      <h2>Find your perfect Toyota!</h2>
      <p>Explore our latest range of Toyota vehicles, including efficient hybrids and reliable everyday models, all at competitive prices.</p>

      <div class="pill-row">
        <div class="pill">
          <span class="pill-label">Cars Available</span>
          <span class="pill-value"><?php echo htmlspecialchars($stats["car_count"]); ?></span>
        </div>

        <div class="pill">
          <span class="pill-label">Starting from</span>
          <span class="pill-value">£<?php echo number_format($stats["starting_price"], 0); ?></span>
        </div>

        <div class="pill">
          <span class="pill-label">Manufacturer</span>
          <span class="pill-value">Toyota</span>
        </div>
      </div>
    </div>

    <div class="hero-card">
      <h3>Why choose us?</h3>
      <ul>
        <li>Trusted Toyota dealership</li>
        <li>Hybrid & fuel-efficient options</li>
        <li>Secure online ordering</li>
        <li>Fast customer support</li>
      </ul>
    </div>
  </section>

  <section class="panel">
    <div class="panel-head">
      <h2>Available Cars</h2>
      <p>Click any car to view full details</p>
    </div>

    <div class="product-grid">

      <?php while ($car = $result->fetch_assoc()): ?>
        <?php
          $image = $imageMap[$car["productID"]] ?? "images/default-car.png";
          $fuelClass = strtolower($car["fuel_type"]);
        ?>

        <div class="car-card">
          <div class="car-image">
            <img 
              src="<?php echo htmlspecialchars($image); ?>" 
              alt="<?php echo htmlspecialchars($car["model_name"]); ?>"
            >
          </div>

          <h3><?php echo htmlspecialchars($car["model_name"]); ?></h3>

          <p class="price">
            £<?php echo number_format($car["price"], 0); ?>
          </p>

          <div class="car-specs">
            <span><?php echo htmlspecialchars($car["model_year"]); ?></span>
            <span class="tag <?php echo htmlspecialchars($fuelClass); ?>">
              <?php echo htmlspecialchars($car["fuel_type"]); ?>
            </span>
            <span><?php echo htmlspecialchars($car["engine_size"]); ?>L</span>
          </div>

          <p class="muted">
            Stock: <?php echo htmlspecialchars($car["stock_level"] ?? 0); ?>
          </p>

          <a class="btn" href="car.php?id=<?php echo htmlspecialchars($car["productID"]); ?>">
            View Details
          </a>
        </div>

      <?php endwhile; ?>

    </div>
  </section>

</main>

<script src="basket.js"></script>
</body>
</html>