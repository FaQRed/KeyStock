<?php
session_start();
include_once __DIR__ . '/../database/cart_repo/CartRepository.php';
include_once __DIR__ . '/../database/product_repo/ProductRepository.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "You must be logged in to add products to the cart.";
    header("Location: ../login_registration/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$cartRepository = new CartRepository();
$productRepository = new ProductRepository();
$cart = $cartRepository->getCartByUserId($user_id);

if (!$cart) {
    $cart = $cartRepository->createCart($user_id);
}

$product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
$quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

if ($product_id > 0 && $quantity > 0) {
    $product = $productRepository->read($product_id);

    if ($product) {
        if ($cartRepository->addItemToCart($cart->getId(), $product_id, $quantity)) {
            $_SESSION['success'] = "Product added to cart successfully.";
        } else {
            $_SESSION['error'] = "Failed to add product to cart.";
        }
    } else {
        $_SESSION['error'] = "Product not found.";
    }
} else {
    $_SESSION['error'] = "Invalid product ID or quantity.";
}

header("Location: /product/product.php?id=" . $product_id);
exit();
?>