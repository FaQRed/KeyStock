<?php
include '../header.php';
include_once __DIR__ . '/../database/order_repo/OrderRepository.php';

$orderRepo = new OrderRepository();

if (!isset($_GET['order_id'])) {
    $_SESSION['error'] = "Invalid order ID.";
    header("Location: ../index.php");
    exit();
}


$order_id = intval($_GET['order_id']);
?>

<div class="wrapper">
    <?php include '../navBar.php'; ?>
    <main class="container mt-5">
        <h2>Order Confirmation</h2>
        <p>Thank you for your order. Your order ID is <?php echo $order_id; ?>.</p>
        <p>You will receive a confirmation email shortly.</p>
        <a href="../index.php" class="btn_product">Back to Home</a>
    </main>
    <?php include '../footer.php'; ?>
</div>