<?php
session_start();
require_once "cardealershipDB.php";

/* Login check */
if (!isset($_SESSION["userID"])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: basket.php");
    exit();
}
/* Basket exists */
if (empty($_POST["basket_data"])) {
    header("Location: basket.php");
    exit();
}
/* Store details */
$customerID = $_SESSION["userID"];
$userStmt = $conn->prepare("
    SELECT address, city, postcode 
    FROM users 
    WHERE userID = ?
");
$userStmt->bind_param("i", $customerID);
$userStmt->execute();

$userResult = $userStmt->get_result();
$user = $userResult->fetch_assoc();
$basket = json_decode($_POST["basket_data"], true);

/* Basket not empty */
if (!is_array($basket) || count($basket) === 0) {
    header("Location: basket.php");
    exit();
}

$conn->begin_transaction();

try {
    $total = 0;

    foreach ($basket as $item) {
        $productID = (int)$item["id"];

        $stmt = $conn->prepare("
            SELECT p.price, s.stock_level
            FROM product p
            INNER JOIN stock s ON p.productID = s.productID
            WHERE p.productID = ?
        ");
        $stmt->bind_param("i", $productID);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            throw new Exception("Product not found.");
        }

        $product = $result->fetch_assoc();

        if ($product["stock_level"] <= 0) {
            throw new Exception("One or more vehicles are out of stock.");
        }

        $total += (float)$product["price"];
    }

    $delivery = "Standard";
    $orderStatus = "Pending";

    $orderStmt = $conn->prepare("
        INSERT INTO orders 
        (customerID, order_status, total, delivery, address, city, postcode)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    $orderStmt->bind_param(
    "isdssss",
    $customerID,
    $orderStatus,
    $total,
    $delivery,
    $user["address"],
    $user["city"],
    $user["postcode"]
);
    $orderStmt->execute();

    $orderID = $conn->insert_id;

    foreach ($basket as $item) {
        $productID = (int)$item["id"];
        $quantity = 1;

        $itemStmt = $conn->prepare("
            INSERT INTO orderitem 
            (orderID, productID, quantity)
            VALUES (?, ?, ?)
        ");
        $itemStmt->bind_param("iii", $orderID, $productID, $quantity);
        $itemStmt->execute();

        $stockStmt = $conn->prepare("
            UPDATE stock
            SET stock_level = stock_level - 1
            WHERE productID = ? AND stock_level > 0
        ");
        $stockStmt->bind_param("i", $productID);
        $stockStmt->execute();

        if ($stockStmt->affected_rows === 0) {
            throw new Exception("Stock update failed.");
        }
    }

    $conn->commit();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Confirmed</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<main class="container">
    <section class="panel" style="max-width:600px; margin:60px auto;">
        <div class="panel-head">
            <h2>Order Confirmed</h2>
            <p>Your order has been saved successfully.</p>
        </div>

        <div class="form-wrapper">
            <div>
                <p><strong>Order ID:</strong> <?php echo htmlspecialchars($orderID); ?></p>
                <p><strong>Total:</strong> £<?php echo number_format($total, 2); ?></p>

                <a href="productPage.php" class="btn" onclick="localStorage.removeItem('basket');">
                    Continue Shopping
                </a>
            </div>
        </div>
    </section>
</main>

<script>
    localStorage.removeItem("basket");
</script>

</body>
</html>

<?php
} catch (Exception $e) {
    $conn->rollback();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout Error</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<main class="container">
    <section class="panel" style="max-width:600px; margin:60px auto;">
        <div class="panel-head">
            <h2>Checkout Failed</h2>
            <p>There was a problem processing your order.</p>
        </div>

        <div class="form-wrapper">
            <div>
                <p class="muted"><?php echo htmlspecialchars($e->getMessage()); ?></p>
                <a href="basket.php" class="btn">Return to Basket</a>
            </div>
        </div>
    </section>
</main>

</body>
</html>

<?php
}
?>