<?php
include '../../header.php';
include_once __DIR__ . '/../../database/order_repo/OrderRepository.php';
include_once __DIR__ . '/../../vendor/autoload.php';
include_once __DIR__ . '/../../database/contact_repo/ContactRepository.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "You must be logged in to view this page.";
    header("Location: ../../login_registration/login.php");
    exit();
}

$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
if ($order_id <= 0) {
    $_SESSION['error'] = "Invalid order ID.";
    header("Location: order_history.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$orderRepository = new OrderRepository();
$order = $orderRepository->getOrderById($order_id);

if (!$order || $order->getUserId() !== $user_id) {
    $_SESSION['error'] = "Order not found.";
    header("Location: order_history.php");
    exit();
}

$order_items = $orderRepository->getOrderItems($order_id);

?>

<div class="wrapper">
    <?php include '../../navBar.php'; ?>
    <main class="container mt-5">
        <div class="order-details">
            <h3>Order: <?php echo htmlspecialchars($order->getId()); ?></h3>
            <p><strong>Status:</strong> <?php echo htmlspecialchars($order->getStatus()); ?></p>
            <p><strong>Date:</strong> <?php echo htmlspecialchars($order->getCreatedAt()); ?></p>
            <h4>Order Items</h4>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($order_items as $item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item->getProductName()); ?></td>
                        <td><?php echo htmlspecialchars($item->getQuantity()); ?></td>
                        <td><?php echo htmlspecialchars(($item->getPrice())) ?> PLN</td>
                        <td><?php echo htmlspecialchars(number_format($item->getQuantity() * $item->getPrice(), 2)); ?> PLN</td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
                <tfoot>
                <tr>
                    <th colspan="3">Total</th>
                    <th>
                        <?php
                        $total_price = 0;
                        foreach ($order_items as $item) {
                            $total_price += $item->getQuantity() * $item->getPrice();
                        }
                        echo htmlspecialchars(number_format($total_price, 2)); ?> PLN
                    </th>
                </tr>
                </tfoot>
            </table>
        </div>
    </main>
    <?php include '../../footer.php'; ?>
</div>