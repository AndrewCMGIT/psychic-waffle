<?php
session_start();
require 'cardealershipDB.php';

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $first = $_POST['first_name'];
    $last = $_POST['last_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $address = $_POST['address'];
    $city = $_POST['city'];
    $postcode = $_POST['postcode'];

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Check if email exists
    $check = $conn->prepare("SELECT userID FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $error = "Email already registered";
    } else {

        $stmt = $conn->prepare("
            INSERT INTO users 
            (first_name, last_name, email, password, role, address, city, postcode)
            VALUES (?, ?, ?, ?, 'customer', ?, ?, ?)
        ");

        $stmt->bind_param("sssssss", $first, $last, $email, $hashedPassword, $address, $city, $postcode);

        if ($stmt->execute()) {
            $success = "Account created! You can now login.";
        } else {
            $error = "Something went wrong.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Register</title>
<link rel="stylesheet" href="styles.css">
</head>

<body>

<header class="site-header">
  <div class="header-inner">
    <div class="brand">
      <div class="logo">EC</div>
      <div class="brand-text">
        <h1>E-Commerce</h1>
        <p>Create Account</p>
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
      <h2>Register</h2>
      <p>Create a new account</p>
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
          <input type="text" name="first_name" required>
        </div>

        <div class="form-group">
          <label>Last Name</label>
          <input type="text" name="last_name" required>
        </div>

        <div class="form-group">
          <label>Email</label>
          <input type="email" name="email" required>
        </div>

        <div class="form-group">
          <label>Password</label>
          <input type="password" name="password" required>
        </div>

        <div class="form-group">
          <label>Address</label>
          <input type="text" name="address" required>
        </div>

        <div class="form-group">
          <label>City</label>
          <input type="text" name="city" required>
        </div>

        <div class="form-group">
          <label>Postcode</label>
          <input type="text" name="postcode" required>
        </div>

        <button class="btn" type="submit">Create Account</button>

      </form>

    </div>
  </div>

</main>

</body>
</html>