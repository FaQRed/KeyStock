<?php

namespace cart_repo;

interface CartRepositoryInterface
{
    public function getCartByUserId($user_id);
    public function createCart($user_id);
    public function addItemToCart($cart_id, $product_id, $quantity);
    public function getItemsByCartId($cart_id);
    public function updateCartItemQuantity($cart_item_id, $quantity);
    public function removeItemFromCart($cart_item_id);
    public function clearCart($cart_id);
    public function getCartItemCount($user_id);
    public function getCartIdByUserId($user_id);
}