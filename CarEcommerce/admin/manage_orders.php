<?php
session_start();
include '../cardealershipDB.php';

if (!isset($_SESSION['userID']) || $_SESSION['role'] !== 'admin') {
    die("Access denied");
}
$sql = "
SELECT 
    o.orderID,
    o.order_date,
    o.total,
    o.order_status,
    u.first_name,
    u.last_name
FROM orders o
JOIN users u ON o.customerID = u.userID
ORDER BY o.order_date DESC
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Orders</title>
  <link rel="stylesheet" href="../styles.css">
</head>

<body>

<!-- NAVBAR (use your fixed one here) -->

<main class="container">

  <section class="panel">

    <div class="panel-head">
      <div>
        <h2>Manage Orders</h2>
        <p>View all customer orders</p>
      </div>

      <a href="dashboard.php" class="btn">← Back</a>
    </div>

    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>Order ID</th>
            <th>Customer</th>
            <th>Total</th>
            <th>Status</th>
            <th>Date</th>
            <th>Items</th>
          </tr>
        </thead>

        <tbody>

        <?php while ($row = $result->fetch_assoc()): ?>

        <tr>
          <td>#<?php echo $row['orderID']; ?></td>

          <td>
            <?php echo htmlspecialchars($row['first_name'] . " " . $row['last_name']); ?>
          </td>

          <td>£<?php echo number_format($row['total'], 2); ?></td>

          <td>
            <span class="badge <?php echo strtolower($row['order_status']); ?>">
              <?php echo $row['order_status']; ?>
            </span>
          </td>

          <td><?php echo $row['order_date']; ?></td>

          <td>
<?php
$itemStmt = $conn->prepare("
    SELECT p.model_name
    FROM orderitem oi
    JOIN product p ON oi.productID = p.productID
    WHERE oi.orderID = ?
");

$itemStmt->bind_param("i", $row['orderID']);
$itemStmt->execute();
$items = $itemStmt->get_result();

while ($item = $items->fetch_assoc()) {
    echo "<div class='tag'>" . htmlspecialchars($item['model_name']) . "</div>";
}
?>
          </td>

        </tr>

        <?php endwhile; ?>

        </tbody>
      </table>
    </div>

  </section>

</main>

</body>
</html>