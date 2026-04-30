<?php
session_start();
include '../cardealershipDB.php';

if (!isset($_SESSION['userID']) || $_SESSION['role'] !== 'admin') {
    die("Access denied");
}

/* HANDLE UPDATE */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $productID = $_POST['productID'] ?? null;
    $stock = $_POST['stock_level'] ?? null;

    if ($productID !== null && $stock !== null) {

        $stmt = $conn->prepare("
            UPDATE stock 
            SET stock_level = ? 
            WHERE productID = ?
        ");

        $stmt->bind_param("ii", $stock, $productID);
        $stmt->execute();
    }
$_SESSION['success'] = "Stock updated successfully!";
    /* Refresh page */
    header("Location: manage_stock.php");
    exit();
}
?>
