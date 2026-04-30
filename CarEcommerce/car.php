<?php
session_start();
require_once "cardealershipDB.php";

if (!isset($_GET["id"])) {
    header("Location: productPage.php");
    exit();
}

$productID = (int) $_GET["id"];

$stmt = $conn->prepare("
    SELECT 
        p.productID,
        p.model_name,
        p.model_year,
        p.fuel_type,
        p.engine_size,
        p.colour,
        p.price,
        s.stock_level
    FROM product p
    LEFT JOIN stock s ON p.productID = s.productID
    WHERE p.productID = ?
");

$stmt->bind_param("i", $productID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: productPage.php");
    exit();
}

$car = $result->fetch_assoc();

$imageMap = [
    1 => "images/corolla.png",
    2 => "images/rav4.png",
    3 => "images/yaris.png",
    4 => "images/prius.png",
    5 => "images/hilux.png"
];

$descriptions = [
    1 => "The Toyota Corolla is one of the most reliable and popular cars on the market. It offers a smooth driving experience, excellent fuel efficiency, and modern safety features. Ideal for both city driving and longer journeys.",
    2 => "The Toyota RAV4 offers excellent space, comfort and hybrid efficiency. Perfect for families and long-distance driving.",
    3 => "The Toyota Yaris is a compact and economical car, ideal for urban driving. It offers excellent fuel efficiency, easy handling, and a comfortable interior, making it perfect for daily commutes and first-time drivers.",
    4 => "The Toyota Prius is a pioneer in hybrid technology, offering outstanding fuel efficiency and reduced emissions. With a smooth driving experience and advanced safety features, it is perfect for environmentally conscious drivers.",
    5 => "The Toyota Hilux is a rugged and dependable pickup truck designed for both work and adventure. Known for its durability and off-road capability, it delivers strong performance, high towing capacity, and a comfortable interior."
];

$mileageMap = [
    1 => "12,000 miles",
    2 => "5,000 miles",
    3 => "9,500 miles",
    4 => "8,000 miles",
    5 => "5,000 miles"
];

$transmissionMap = [
    1 => "Automatic",
    2 => "Automatic",
    3 => "Manual",
    4 => "Automatic",
    5 => "Automatic"
];

$featureMap = [
    1 => [
        ["Adaptive Cruise Control", "Lane Assist", "Rear Camera"],
        ["Bluetooth Connectivity", "Touchscreen Display", "Air Conditioning"]
    ],
    2 => [
        ["AWD Capability", "Adaptive Cruise Control", "Parking Sensors"],
        ["Apple CarPlay", "Navigation System", "Heated Seats"]
    ],
    3 => [
        ["Lane Departure Warning", "Reversing Camera", "Cruise Control"],
        ["Bluetooth Connectivity", "Touchscreen Display", "Air Conditioning"]
    ],
    4 => [
        ["Hybrid Synergy Drive", "Adaptive Cruise Control", "Lane Assist"],
        ["Touchscreen Infotainment", "Bluetooth Connectivity", "Dual-Zone Climate Control"]
    ],
    5 => [
        ["4WD Capability", "Tow Assist", "Rear Camera"],
        ["Touchscreen Infotainment", "Bluetooth Connectivity", "Climate Control"]
    ]
];

$description = $descriptions[$car["productID"]] ?? "Vehicle description currently unavailable.";
$mileage = $mileageMap[$car["productID"]] ?? "Mileage unavailable";
$transmission = $transmissionMap[$car["productID"]] ?? "Transmission unavailable";
$features = $featureMap[$car["productID"]] ?? [[], []];

$image = $imageMap[$car["productID"]] ?? "images/default-car.png";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title><?php echo htmlspecialchars($car["model_name"]); ?> — Details</title>
  <link rel="stylesheet" href="styles.css" />
</head>

<body>

<header class="site-header">
  <div class="header-inner">
    <div class="brand">
      <div class="logo">CD</div>
      <div class="brand-text">
        <h1>COM336 Car Dealership</h1>
        <p>Vehicle Details</p>
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

  <section class="panel">
    <div class="panel-head">
      <div>
        <h2><?php echo htmlspecialchars($car["model_name"]); ?></h2>
        <p>View specifications, pricing and availability.</p>
      </div>

      <a href="productPage.php" class="btn">← Back to Products</a>
    </div>

    <div class="car-detail-layout">

      <div class="car-image-large">
        <img 
          src="<?php echo htmlspecialchars($image); ?>" 
          alt="<?php echo htmlspecialchars($car["model_name"]); ?>"
        >
      </div>

      <div class="car-info">

        <div class="review-card">
            <h3>Specifications</h3>
            <p><strong>Model Year:</strong> <?php echo htmlspecialchars($car["model_year"]); ?></p>
            <p><strong>Fuel Type:</strong> 
                <span class="tag <?php echo strtolower(htmlspecialchars($car["fuel_type"])); ?>">
                <?php echo htmlspecialchars($car["fuel_type"]); ?>
                </span>
            </p>
            <p><strong>Engine Size:</strong> <?php echo htmlspecialchars($car["engine_size"]); ?>L</p>
            <p><strong>Colour:</strong> <?php echo htmlspecialchars($car["colour"]); ?></p>
            <p><strong>Mileage:</strong> <?php echo htmlspecialchars($mileage); ?></p>
            <p><strong>Transmission:</strong> <?php echo htmlspecialchars($transmission); ?></p>
            <p><strong>Stock Available:</strong> <?php echo htmlspecialchars($car["stock_level"] ?? 0); ?></p>
        </div>

        <div class="review-card">
          <h3>Pricing</h3>
          <p class="mono car-price">
            £<?php echo number_format($car["price"], 2); ?>
          </p>

          <p class="muted">Finance options available</p>

          <div class="button-row">
            <?php if (($car["stock_level"] ?? 0) > 0): ?>
              <button 
                class="btn reserve-btn"
                data-id="<?php echo htmlspecialchars($car["productID"]); ?>"
                data-name="<?php echo htmlspecialchars($car["model_name"]); ?>"
                data-price="<?php echo htmlspecialchars($car["price"]); ?>">
                Add to Basket
              </button>
            <?php else: ?>
              <button class="btn disabled" disabled>Out of Stock</button>
            <?php endif; ?>

            <a href="addReview.php" class="btn secondary">Write a Review</a>
          </div>
        </div>

      </div>
    </div>
  </section>

  <section class="panel">
    <div class="panel-head">
      <h2>Description</h2>
    </div>

    <div class="grid">
        <p class="muted">
            <?php echo htmlspecialchars($description); ?>
        </p>
    </div>
  </section>

  <section class="panel">
    <div class="panel-head">
      <h2>Key Features</h2>
    </div>

    <div class="grid two">
        <?php foreach ($features as $featureColumn): ?>
            <div class="review-card">
            <ul class="muted feature-list">
                <?php foreach ($featureColumn as $feature): ?>
                <li>✔ <?php echo htmlspecialchars($feature); ?></li>
                <?php endforeach; ?>
            </ul>
            </div>
        <?php endforeach; ?>
    </div>
  </section>

</main>

<footer class="site-footer">
  <div class="container footer-inner">
    <p>COM336 Coursework — Car Dealership Database</p>
  </div>
</footer>

<script src="basket.js"></script>
</body>
</html>