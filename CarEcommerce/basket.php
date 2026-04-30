<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>COM336 Car Dealership — Basket</title>
  <link rel="stylesheet" href="styles.css" />
</head>
<body>
  <header class="site-header">
    <div class="header-inner">
      <div class="brand">
        <div class="logo" aria-hidden="true">CD</div>
        <div class="brand-text">
          <h1>COM336 Car Dealership</h1>
          <p>Your selected vehicles</p>
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
        <h2>Your Basket</h2>
        <p class="muted">Review your selected vehicles before continuing to checkout.</p>
      </div>
    </section>

    <section class="panel">
      <div class="panel-head">
        <div>
          <h2>Basket Summary</h2>
          <p class="muted">Review your selected vehicles.</p>
        </div>
      </div>

      <div class="basket-spacing">
          <!--- Basket Items --->
        <div class="basket-container" id="basket-container">
          <div class="empty-basket">Your basket is currently empty.</div>
        </div>

          <!--- Basket Summary --->
          <aside class="basket-summary">
            <h3>Order Total</h3>

            <p class="total-price">Total: <span id="basket-total-price">£0</span></p>
            <?php if (isset($_SESSION['userID'])): ?>
              <form method="POST" action="checkout.php" id="checkout-form">
                <input type="hidden" name="basket_data" id="basket-data">
                <button id="checkout-btn" class="btn" type="submit">
                  Checkout
                </button>
              </form>
            <?php else: ?>
              <p class="muted">Please log in before checking out.</p>
              <a href="login.php" class="btn">Login to Checkout</a>
            <?php endif; ?>
          </aside>
        </div>
      </section>

    </main>

<script src="basket.js"></script>   
</body>
</html>