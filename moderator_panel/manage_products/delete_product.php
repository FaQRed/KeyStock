<?php
session_start();
include_once '../../database/Database_connection.php';
include_once '../../database/product_repo/ProductRepository.php';

$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$productRepository = new ProductRepository();
if ($productRepository->delete($product_id)) {
    $_SESSION['success'] = "Product deleted successfully.";
} else {
    $_SESSION['error'] = "Failed to delete product.";
}

header("Location: moderator_manage_products.php");
exit();
?>