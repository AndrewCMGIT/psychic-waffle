<?php
session_start();
include '../cardealershipDB.php';

if (!isset($_SESSION['userID']) || $_SESSION['role'] !== 'admin') {
    die("Access denied");
}


$sql = "
SELECT p.productID, p.model_name, s.stock_level
FROM product p
JOIN stock s ON p.productID = s.productID
";

$result = $conn->query($sql);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $productID = $_POST['productID'];
    $stock = $_POST['stock_level'];

    $stmt = $conn->prepare("
        UPDATE stock 
        SET stock_level = ? 
        WHERE productID = ?
    ");

    $stmt->bind_param("ii", $stock, $productID);
    $stmt->execute();

    header("Location: manage_stock.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Stock</title>
  <link rel="stylesheet" href="../styles.css">
</head>

<body>

<main class="container">

  <section class="panel">

    <div class="panel-head">
      <div>
        <?php if (isset($_SESSION['success'])): ?>
        <p class="muted"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></p>
        <?php endif; ?>
        <h2>Manage Stock</h2>
        <p>Update vehicle stock levels</p>
      </div>

      <a href="dashboard.php" class="btn">← Back</a>
    </div>

    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>Product</th>
            <th>Stock</th>
            <th>Update</th>
          </tr>
        </thead>

        <tbody>

        <?php while ($row = $result->fetch_assoc()): ?>

        <tr>
          <td><?php echo htmlspecialchars($row['model_name']); ?></td>

          <td>
            <span class="status <?php echo $row['stock_level'] < 3 ? 'low' : 'ok'; ?>">
              <?php echo $row['stock_level']; ?>
            </span>
          </td>

          <td>
            <form method="POST" action="update_stock.php" class="stock-form">
              <input type="hidden" name="productID" value="<?php echo $row['productID']; ?>">

              <input 
                type="number" 
                name="stock_level" 
                value="<?php echo $row['stock_level']; ?>" 
                min="0" 
                required
              >

              <button type="submit" class="btn">Update</button>
            </form>
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