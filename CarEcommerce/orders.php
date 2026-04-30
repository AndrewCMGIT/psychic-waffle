<?php
session_start();
require_once "cardealershipDB.php";

if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit();
}

$stmt = $conn->prepare("
    SELECT 
        o.orderID,
        o.customerID,
        u.first_name,
        u.last_name,
        o.order_date,
        o.order_status,
        o.total,
        o.delivery,
        o.address,
        o.city,
        o.postcode
    FROM orders o
    INNER JOIN users u ON o.customerID = u.userID
    ORDER BY o.order_date DESC
");

$stmt->execute();
$result = $stmt->get_result();

$countResult = $conn->query("SELECT COUNT(*) AS total FROM orders");
$orderCount = $countResult->fetch_assoc()["total"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Orders | COM336 Car Dealership</title>
  <link rel="stylesheet" href="styles.css" />
</head>

<body>

<header class="site-header">
  <div class="header-inner">
    <div class="brand">
      <div class="logo" aria-hidden="true">CD</div>
      <div class="brand-text">
        <h1>COM336 Car Dealership</h1>
        <p>Admin order management</p>
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
      <h2>Orders</h2>
      <p>View customer orders and delivery information from the database.</p>

      <div class="pill-row">
        <div class="pill">
          <span class="pill-label">Total Orders</span>
          <span class="pill-value"><?php echo htmlspecialchars($orderCount); ?></span>
        </div>
      </div>
    </div>

    <div class="hero-card">
      <h3>Admin notes</h3>
      <ul>
        <li>This page is protected for admin users only.</li>
        <li>Orders are loaded dynamically from MySQL.</li>
        <li>Customer names are joined from the users table.</li>
      </ul>
    </div>
  </section>

  <section class="panel" id="orders">
    <div class="panel-head">
      <div>
        <h2>Order Records</h2>
        <p>Customer orders and delivery information.</p>
      </div>
    </div>

    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>OrderID</th>
            <th>Customer</th>
            <th>Order date</th>
            <th>Status</th>
            <th class="right">Total</th>
            <th>Delivery</th>
            <th>Address</th>
            <th>City</th>
            <th>Postcode</th>
          </tr>
        </thead>

        <tbody>
          <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>

              <?php
                $statusClass = strtolower($row["order_status"]);
              ?>

              <tr>
                <td class="mono"><?php echo htmlspecialchars($row["orderID"]); ?></td>
                <td>
                  <?php echo htmlspecialchars($row["first_name"] . " " . $row["last_name"]); ?>
                  <br>
                  <span class="muted mono">ID: <?php echo htmlspecialchars($row["customerID"]); ?></span>
                </td>
                <td class="mono"><?php echo htmlspecialchars($row["order_date"]); ?></td>
                <td>
                  <span class="badge <?php echo htmlspecialchars($statusClass); ?>">
                    <?php echo htmlspecialchars($row["order_status"]); ?>
                  </span>
                </td>
                <td class="right mono">£<?php echo number_format($row["total"], 2); ?></td>
                <td><?php echo htmlspecialchars($row["delivery"] ?? "N/A"); ?></td>
                <td><?php echo htmlspecialchars($row["address"] ?? "N/A"); ?></td>
                <td><?php echo htmlspecialchars($row["city"] ?? "N/A"); ?></td>
                <td class="mono"><?php echo htmlspecialchars($row["postcode"] ?? "N/A"); ?></td>
              </tr>

            <?php endwhile; ?>
          <?php else: ?>
            <tr>
              <td colspan="9" class="muted">No orders found.</td>
            </tr>
          <?php endif; ?>
        </tbody>

      </table>
    </div>
  </section>

</main>

</body>
</html>