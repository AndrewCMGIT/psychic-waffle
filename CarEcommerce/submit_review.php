<?php
session_start();
include 'cardealershipDB.php';

if (!isset($_SESSION['userID'])) {
    die("You must be logged in.");
}

$customerID = $_SESSION['userID'];
$productID = $_POST['product_id'];
$rating = $_POST['rating'];
$review = $_POST['review_text'];

/* Validate purchase */
$check = $conn->prepare("
    SELECT *
    FROM orders o
    JOIN orderitem oi ON o.orderID = oi.orderID
    WHERE o.customerID = ? AND oi.productID = ?
");
$check->bind_param("ii", $customerID, $productID);
$check->execute();

if ($check->get_result()->num_rows == 0) {
    die("You can only review products you have purchased.");
}

/* Insert review */
$stmt = $conn->prepare("
    INSERT INTO reviews (customerID, productID, rating, review)
    VALUES (?, ?, ?, ?)
");

$stmt->bind_param("iiis", $customerID, $productID, $rating, $review);
$stmt->execute();

header("Location: reviews.php");
exit();
?>