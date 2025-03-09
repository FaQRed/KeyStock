<?php

namespace order_repo;

interface OrderRepositoryInterface
{
    public function createOrder($cart_id, $user_id, $totalPriceAfterDiscounts, $status = 'Awaiting Payment');
    public function getOrderById($order_id);
    public function getOrderItems($order_id);
    public function getOrdersByUserId($user_id);
    public function createOrderItem($order_id, $product_id, $quantity, $price);
    public function getAllOrders();
    public function updateStatus($order_id, $status);
    public function getOrdersByDateRange($startDate, $endDate);
    public function getOrdersByStatus($status);
}