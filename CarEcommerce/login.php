<?php
session_start();// REQUIRED IN ALL PAGES!! connection to account  
require 'cardealershipDB.php'; // REQUIRED IN ALL PAGES!!

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        if (password_verify($password, $user['password'])) {
            
            // Store session
            $_SESSION['userID'] = $user['userID'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['first_name'] = $user['first_name'];

            // Redirect based on role
            if ($user['role'] === 'admin') {
                header("Location: /CarEcommerce/admin/dashboard.php");
            } else {
                header("Location: /CarEcommerce/account.php");
            }
            exit();

        } else {
            $error = "Invalid password";
        }
    } else {
        $error = "No account found";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Login</title>
<link rel="stylesheet" href="styles.css">
</head>

<body>

<header class="site-header">
  <div class="header-inner">
    <div class="brand">
      <div class="logo">EC</div>
      <div class="brand-text">
        <h1>E-Commerce</h1>
        <p>Login</p>
      </div>
    </div>

    <nav class="nav">
      <a href="reviews.php">Home</a>
      <a href="register.php">Register</a>
    </nav>
  </div>
</header>

<main class="container">

  <div class="panel" style="max-width:500px; margin:40px auto;">
    
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

    <div class="form-wrapper">

      <form method="POST">

        <?php if ($error): ?>
          <p class="muted" style="color:#ff7b7b;"><?php echo $error; ?></p>
        <?php endif; ?>

        <div class="form-group">
          <label>Email</label>
          <input type="email" name="email" required>
        </div>

        <div class="form-group">
          <label>Password</label>
          <input type="password" name="password" required>
        </div>

        <button class="btn" type="submit">Login</button>

      </form>

    </div>
  </div>

</main>

</body>
</html>