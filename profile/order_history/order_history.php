<?php
include '../../header.php';
include_once __DIR__ . '/../../database/order_repo/OrderRepository.php';
include_once __DIR__ . '/../../database/contact_repo/ContactRepository.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "You must be logged in to view this page.";
    header("Location: ../login_registration/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$orderRepository = new OrderRepository();
$orders = $orderRepository->getOrdersByUserId($user_id);

?>

<div class="wrapper">
    <?php include '../../navBar.php'; ?>
    <main class="container mt-5">
        <div class="row">
            <div class="col-md-3">
                <?php include '../user_nav_bar.php'; ?>
            </div>
            <div class="col-md-9">
                <h2>Order History</h2>
                <?php
                if (isset($_SESSION['error'])) {
                    echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
                    unset($_SESSION['error']);
                }
                if (isset($_SESSION['success'])) {
                    echo '<div class="alert alert-success">' . $_SESSION['success'] . '</div>';
                    unset($_SESSION['success']);
                }
                ?>
                <div class="order-history">
                    <?php if (count($orders) > 0): ?>
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Total</th>
                                <th>Details</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($order->getId()); ?></td>
                                    <td><?php echo htmlspecialchars($order->getStatus()); ?></td>
                                    <td><?php echo htmlspecialchars($order->getCreatedAt()); ?></td>
                                    <td>
                                        <?php
                                        $order_items = $orderRepository->getOrderItems($order->getId());
                                        $total_price = 0;
                                        foreach ($order_items as $item) {
                                            $total_price += $item->getQuantity() * $item->getPrice();
                                        }
                                        echo htmlspecialchars(number_format($total_price, 2)); ?> PLN
                                    </td>
                                    <td><a href="order_details.php?order_id=<?php echo $order->getId(); ?>"
                                           class="btn_product">View Details</a></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p>No orders found.</p>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </main>
    <?php include '../../footer.php'; ?>
</div>