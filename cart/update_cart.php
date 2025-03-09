<?php
session_start();
include_once __DIR__ . '/../database/cart_repo/CartRepository.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "You must be logged in to update the cart.";
    header("Location: ../login_registration/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$cartRepository = new CartRepository();
$cart = $cartRepository->getCartByUserId($user_id);

if (!$cart) {
    $_SESSION['error'] = "Cart not found.";
    header("Location: cart.php");
    exit();
}

$cart_item_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($cart_item_id && $action) {
    $item = $cartRepository->getItemById($cart_item_id);

    if ($item && $item->getCartId() == $cart->getId()) {
        if ($action == 'add') {
            $cartRepository->updateCartItemQuantity($cart_item_id, $item->getQuantity() + 1);
        } elseif ($action == 'remove') {
            if ($item->getQuantity() > 1) {
                $cartRepository->updateCartItemQuantity($cart_item_id, $item->getQuantity() - 1);
            } else {
                $cartRepository->removeItemFromCart($cart_item_id);
            }
        }
    }
}

header("Location: cart.php");
exit();
?>