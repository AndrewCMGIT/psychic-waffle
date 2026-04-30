<?php
session_start();
require_once "cardealershipDB.php";

if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit();
}

$stmt = $conn->prepare("
    SELECT 
        s.productID,
        p.model_name,
        s.stock_level,
        s.reorder
    FROM stock s
    INNER JOIN product p ON s.productID = p.productID
    ORDER BY s.productID ASC
");

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Stock | COM336 Car Dealership</title>
  <link rel="stylesheet" href="styles.css" />
</head>

<body>

<header class="site-header">
  <div class="header-inner">
    <div class="brand">
      <div class="logo" aria-hidden="true">CD</div>
      <div class="brand-text">
        <h1>COM336 Car Dealership</h1>
        <p>Admin stock management</p>
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
      <h2>Stock Management</h2>
      <p>View current inventory levels and reorder warnings for dealership vehicles.</p>
    </div>

    <div class="hero-card">
      <h3>Admin notes</h3>
      <ul>
        <li>This page is protected for admin users only.</li>
        <li>Stock data is loaded dynamically from MySQL.</li>
        <li>Low stock is detected using the reorder point.</li>
      </ul>
    </div>
  </section>

  <section class="panel" id="stock">
    <div class="panel-head">
      <div>
        <h2>Stock Records</h2>
        <p>Inventory levels per product.</p>
      </div>
    </div>

    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>ProductID</th>
            <th>Model</th>
            <th class="right">Stock level</th>
            <th class="right">Reorder point</th>
            <th>Status</th>
          </tr>
        </thead>

        <tbody>
          <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
              <?php
                $isLow = $row["stock_level"] <= $row["reorder"];
                $statusText = $isLow ? "Low" : "OK";
                $statusClass = $isLow ? "low" : "ok";
              ?>

              <tr>
                <td class="mono"><?php echo htmlspecialchars($row["productID"]); ?></td>
                <td><?php echo htmlspecialchars($row["model_name"]); ?></td>
                <td class="right mono"><?php echo htmlspecialchars($row["stock_level"]); ?></td>
                <td class="right mono"><?php echo htmlspecialchars($row["reorder"]); ?></td>
                <td>
                  <span class="status <?php echo htmlspecialchars($statusClass); ?>">
                    <?php echo htmlspecialchars($statusText); ?>
                  </span>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr>
              <td colspan="5" class="muted">No stock records found.</td>
            </tr>
          <?php endif; ?>
        </tbody>

      </table>
    </div>
  </section>

</main>

</body>
</html>