<?php
session_start();
include '../cardealershipDB.php';

if (!isset($_SESSION['userID']) || $_SESSION['role'] !== 'admin') {
    die("Access denied");
}

$reviewID = $_POST['reviewID'] ?? null;

if ($reviewID) {

    $stmt = $conn->prepare("
        DELETE FROM reviews WHERE reviewID = ?
    ");

    $stmt->bind_param("i", $reviewID);
    $stmt->execute();
}

header("Location: manage_reviews.php");
exit();
?>