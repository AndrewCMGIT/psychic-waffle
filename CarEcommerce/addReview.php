
<?php
session_start();
include 'cardealershipDB.php';

if (!isset($_SESSION['userID'])) {
    die("You must be logged in.");
}

$customerID = $_SESSION['userID'];

$sql = "
SELECT DISTINCT p.productID, p.model_name
FROM orders o
JOIN orderitem oi ON o.orderID = oi.orderID
JOIN product p ON oi.productID = p.productID
WHERE o.customerID = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $customerID);
$stmt->execute();

$products = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>COM336 Car Dealership — Add Review</title>
  <link rel="stylesheet" href="styles.css" />
</head>

<body>
  <header class="site-header">
    <div class="header-inner">
      <div class="brand">
        <div class="logo">CD</div>
        <div class="brand-text">
          <h1>COM336 Car Dealership</h1>
          <p>Coursework — Add Review</p>
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
    <section class="panel">
      
      <div class="panel-head">
        <div>
          <h2>Write a Review</h2>
          <p>Submit your feedback on a vehicle.</p>
        </div>

        
        <a href="reviews.php" class="btn">← Back</a>
      </div>

<div class="form-wrapper">
  <form method="POST" action="submit_review.php">

    <p>Reviewing as: <?php echo $_SESSION['first_name']; ?></p>

    <?php if ($products->num_rows > 0): ?>

      <div class="form-group">
        <label>Select Product</label>
        <select name="product_id" required>
          <option value="">-- Choose a product --</option>

          <?php while ($row = $products->fetch_assoc()): ?>
            <option value="<?php echo $row['productID']; ?>">
              <?php echo htmlspecialchars($row['model_name']); ?>
            </option>
          <?php endwhile; ?>

        </select>
      </div>

      <div class="form-group">
        <label>Rating (1–5)</label>
        <input type="number" name="rating" min="1" max="5" required>
      </div>

      <div class="form-group">
        <label>Review</label>
        <textarea name="review_text" placeholder="Write your review..." required></textarea>
      </div>

      <button type="submit" class="btn">Submit Review</button>

    <?php else: ?>

      <p>You haven’t purchased any vehicles yet.</p>

    <?php endif; ?>

  </form>
</div>
    </section>
  </main>

  <footer class="site-footer">
    <div class="container footer-inner">
      <p>COM336 Coursework — Car Dealership Database</p>
      <p class="muted">Review submission page</p>
    </div>
  </footer>
</body>
</html>