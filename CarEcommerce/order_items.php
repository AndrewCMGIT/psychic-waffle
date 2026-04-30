<?php
session_start();
require_once "cardealershipDB.php";

if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit();
}

$stmt = $conn->prepare("
    SELECT 
        oi.orderItemID,
        oi.orderID,
        oi.productID,
        p.model_name,
        oi.quantity,
        p.price,
        (oi.quantity * p.price) AS line_total
    FROM orderitem oi
    INNER JOIN product p ON oi.productID = p.productID
    ORDER BY oi.orderID ASC
");

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Order Items | COM336 Car Dealership</title>
  <link rel="stylesheet" href="styles.css" />
</head>

<body>
<header class="site-header">
  <div class="header-inner">
    <div class="brand">
      <div class="logo" aria-hidden="true">CD</div>
      <div class="brand-text">
        <h1>COM336 Car Dealership</h1>
        <p>Admin order item records</p>
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
      <h2>Order Items</h2>
      <p>View the products included in each customer order.</p>
    </div>

    <div class="hero-card">
      <h3>Admin notes</h3>
      <ul>
        <li>This page is protected for admin users only.</li>
        <li>Order items are loaded dynamically from MySQL.</li>
        <li>Products are joined using the product table.</li>
      </ul>
    </div>
  </section>

  <section class="panel" id="orderitems">
    <div class="panel-head">
      <div>
        <h2>Order Item Records</h2>
        <p>Products included per order.</p>
      </div>
    </div>

    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>Order Item ID</th>
            <th>Order ID</th>
            <th>Product ID</th>
            <th>Product</th>
            <th class="right">Quantity</th>
            <th class="right">Price</th>
            <th class="right">Line Total</th>
          </tr>
        </thead>

        <tbody>
          <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
              <tr>
                <td class="mono"><?php echo htmlspecialchars($row["orderItemID"]); ?></td>
                <td class="mono"><?php echo htmlspecialchars($row["orderID"]); ?></td>
                <td class="mono"><?php echo htmlspecialchars($row["productID"]); ?></td>
                <td><?php echo htmlspecialchars($row["model_name"]); ?></td>
                <td class="right mono"><?php echo htmlspecialchars($row["quantity"]); ?></td>
                <td class="right mono">£<?php echo number_format($row["price"], 2); ?></td>
                <td class="right mono">£<?php echo number_format($row["line_total"], 2); ?></td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr>
              <td colspan="7" class="muted">No order items found.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </section>

</main>
</body>
</html>