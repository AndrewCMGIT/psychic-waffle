
<?php
session_start();
include 'cardealershipDB.php';


$sql = "SELECT r.reviewID, r.review, r.rating, r.review_date, u.first_name, u.last_name, p.model_name
  FROM reviews r
  INNER JOIN users u ON r.customerID = u.userID
  INNER JOIN product p ON r.productID = p.productID
  ORDER BY r.review_date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>COM336 Car Dealership — Data Dashboard</title>
  <link rel="stylesheet" href="styles.css" />
</head>

<body>
  <header class="site-header">
    <div class="header-inner">
      <div class="brand">
        <div class="logo" aria-hidden="true">CD</div>
        <div class="brand-text">
          <h1>COM336 Car Dealership</h1>
          <p>Coursework — Reviews</p>
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
  
<section class="panel" id="reviews">
    <div class="panel-head">
      <h2>Reviews</h2>
      <p>Customer reviews for purchased products.</p>

      <a href="addReview.php" class="btn">+ Write a Review</a>
</div>

      <div class="grid two">

<?php while($row = $result->fetch_assoc()): ?>

  <article class="review-card">
    <div class="review-top">
      <div>
        <h3><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></h3>
        <p class="muted"><?php echo htmlspecialchars($row['model_name']); ?></p>
      </div>

      <div class="stars">
        <?php
          $rating = $row['rating'];
          for ($i = 0; $i < $rating; $i++) echo "★";
          for ($i = $rating; $i < 5; $i++) echo "☆";
        ?>
      </div>
    </div>

    <p class="quote">"<?= $row['review'] ?>"</p>

    <p class="meta">
      Rating: <span class="mono"><?= $row['rating'] ?></span>
    </p>
  </article>

<?php endwhile; ?>

</div>
    </section>
  </main>

  <footer class="site-footer">
    <div class="container footer-inner">
      <p>COM336 Coursework — Car Dealership Database</p>
      <p class="muted">Dynamic customer reviews from the dealership database</p>
    </div>
  </footer>
</body>
</html>