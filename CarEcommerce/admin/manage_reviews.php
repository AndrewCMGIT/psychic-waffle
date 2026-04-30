<?php
session_start();
include '../cardealershipDB.php';

if (!isset($_SESSION['userID']) || $_SESSION['role'] !== 'admin') {
    die("Access denied");
}
$sql = "
SELECT 
    r.reviewID,
    r.review,
    r.rating,
    r.review_date,
    u.first_name,
    u.last_name,
    p.model_name
FROM reviews r
JOIN users u ON r.customerID = u.userID
JOIN product p ON r.productID = p.productID
ORDER BY r.review_date DESC
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Reviews</title>
  <link rel="stylesheet" href="../styles.css">
</head>

<body>

<!-- NAVBAR HERE -->

<main class="container">

  <section class="panel">

    <div class="panel-head">
      <div>
        <h2>Manage Reviews</h2>
        <p>Moderate customer feedback</p>
      </div>

      <a href="dashboard.php" class="btn">← Back</a>
    </div>

<div class="grid two">

<?php while ($row = $result->fetch_assoc()): ?>

  <div class="review-card">

    <div class="review-top">
      <h3><?php echo htmlspecialchars($row['model_name']); ?></h3>

      <div class="stars">
        <?php echo str_repeat("⭐", $row['rating']); ?>
      </div>
    </div>

    <p class="quote">
      "<?php echo htmlspecialchars($row['review']); ?>"
    </p>

    <p class="meta">
      By <?php echo htmlspecialchars($row['first_name'] . " " . $row['last_name']); ?>
      • <?php echo $row['review_date']; ?>
    </p>

    <form method="POST" action="delete_review.php">
      <input type="hidden" name="reviewID" value="<?php echo $row['reviewID']; ?>">
      <button type="submit" class="danger-btn">Delete</button>
    </form>

  </div>

<?php endwhile; ?>

</div>

  </section>

</main>

</body>
</html>