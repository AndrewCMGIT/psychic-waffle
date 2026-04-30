<?php
session_start();

// Clear session
$_SESSION = [];
session_destroy();

// Redirect to products page
header("Location: /CarEcommerce/productPage.php");
exit();
?>