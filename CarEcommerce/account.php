<?php
session_start(); // REQUIRED IN ALL PAGES!! this is connection to the registered account 
require 'cardealershipDB.php';// REQUIRED IN ALL PAGES!!

// Protect page
if (!isset($_SESSION['userID'])) {
    header("Location: login.php");
    exit();
}

$userID = $_SESSION['userID'];
$success = "";
$error = "";

// GET current user data
$stmt = $conn->prepare("SELECT * FROM users WHERE userID = ?");
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// UPDATE logic
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $first = $_POST['first_name'];
    $last = $_POST['last_name'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $postcode = $_POST['postcode'];
    $tele = $_POST['tele_no'];

    $update = $conn->prepare("
        UPDATE users 
        SET first_name=?, last_name=?, email=?, address=?, city=?, postcode=?, tele_no=?
        WHERE userID=?
    ");

    $update->bind_param("sssssssi", $first, $last, $email, $address, $city, $postcode, $tele, $userID);

    if ($update->execute()) {
        $success = "Details updated successfully";

        // Refresh data
        $_SESSION['name'] = $first;

        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
    } else {
        $error = "Update failed";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Account</title>
<link rel="stylesheet" href="styles.css">
</head>

<body>

<header class="site-header">
  <div class="header-inner">
    <div class="brand">
      <div class="logo">EC</div>
      <div class="brand-text">
        <h1>E-Commerce</h1>
        <p>My Account</p>
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

  <div class="panel" style="max-width:500px; margin:40px auto;">
    
    <div class="panel-head">
      <h2>Account Details</h2>
      <p>Edit your information</p>
    </div>

    <div class="form-wrapper">

      <form method="POST">

        <?php if ($error): ?>
          <p class="muted" style="color:#ff7b7b;"><?php echo $error; ?></p>
        <?php endif; ?>

        <?php if ($success): ?>
          <p class="muted" style="color:#75ffbe;"><?php echo $success; ?></p>
        <?php endif; ?>

        <div class="form-group">
          <label>First Name</label>
          <input type="text" name="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
        </div>

        <div class="form-group">
          <label>Last Name</label>
          <input type="text" name="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
        </div>

        <div class="form-group">
          <label>Email</label>
          <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
        </div>

        <div class="form-group">
          <label>Address</label>
          <input type="text" name="address" value="<?php echo htmlspecialchars($user['address']); ?>" required>
        </div>

        <div class="form-group">
          <label>City</label>
          <input type="text" name="city" value="<?php echo htmlspecialchars($user['city']); ?>" required>
        </div>

        <div class="form-group">
          <label>Postcode</label>
          <input type="text" name="postcode" value="<?php echo htmlspecialchars($user['postcode']); ?>" required>
        </div>

        <div class="form-group">
          <label>Telephone</label>
          <input type="text" name="tele_no" value="<?php echo htmlspecialchars($user['tele_no'] ?? ''); ?>">
        </div>

        <button class="btn" type="submit">Update Details</button>

      </form>

    </div>
  </div>

</main>

</body>
</html>