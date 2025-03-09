<?php
include '../../header.php';
include_once __DIR__ . '/../../database/order_repo/OrderRepository.php';
include_once __DIR__ . '/../../database/contact_repo/ContactRepository.php';
include_once __DIR__ . '/../../database/product_repo/ProductRepository.php';

if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'moderator' && $_SESSION['role'] !== 'admin')) {
    $_SESSION['error'] = "Access denied.";
    header("Location: ../index.php");
    exit();
}

$orderRepository = new OrderRepository();
$contactRepository = new ContactRepository();
$productRepository = new ProductRepository();

$statuses = ['Pending Payment', 'Paid', 'Shipped', 'Completed', 'Cancelled'];
$selectedStatus = isset($_GET['status']) ? htmlspecialchars($_GET['status']) : '';
$startDate = isset($_GET['start_date']) ? htmlspecialchars($_GET['start_date']) : '';
$endDate = isset($_GET['end_date']) ? htmlspecialchars($_GET['end_date']) : '';

if ($selectedStatus) {
    $orders = $orderRepository->getOrdersByStatus($selectedStatus);
} elseif ($startDate && $endDate) {
    $orders = $orderRepository->getOrdersByDateRange($startDate, $endDate);
} else {
    $orders = $orderRepository->getAllOrders();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['status']) && isset($_POST['order_id'])) {
    $status = htmlspecialchars(strip_tags($_POST['status']));
    $order_id = intval($_POST['order_id']);
    if ($orderRepository->updateStatus($order_id, $status)) {
        $_SESSION['success'] = "Order status updated successfully.";
    } else {
        $_SESSION['error'] = "Failed to update order status.";
    }
    header("Location: moderator_manage_orders.php");
    exit();
}
?>


<div class="wrapper">
    <?php include '../../navBar.php'; ?>
    <main class="container mt-5">
        <div class="row">
            <?php include '../moderator_panel_nav.php'; ?>
            <div class="col-md-9">
                <h2>Manage Orders</h2>
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger"><?php echo $_SESSION['error'];
                        unset($_SESSION['error']); ?></div>
                <?php endif; ?>
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success"><?php echo $_SESSION['success'];
                        unset($_SESSION['success']); ?></div>
                <?php endif; ?>
                <form method="get" action="moderator_manage_orders.php" class="form-inline mb-3">
                    <label for="status" class="mr-2">Status</label>
                    <label>
                        <select name="status" class="form-control mr-2">
                            <option value="">All</option>
                            <?php foreach ($statuses as $status): ?>
                                <option value="<?php echo $status; ?>" <?php echo ($selectedStatus == $status) ? 'selected' : ''; ?>>
                                    <?php echo $status; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                    <label for="start_date" class="mr-2">Start Date</label>
                    <label>
                        <input type="date" name="start_date" class="form-control mr-2" value="<?php echo $startDate; ?>">
                    </label>
                    <label for="end_date" class="mr-2">End Date</label>
                    <label>
                        <input type="date" name="end_date" class="form-control mr-2" value="<?php echo $endDate; ?>">
                    </label>
                    <button type="submit" class="btn_product">Filter</button>
                </form>
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>User ID</th>
                        <th>Contact</th>
                        <th>Products</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($orders as $order):

                        ?>
                        <?php $contact = $contactRepository->read($order->getCartId()); ?>
                        <tr>
                            <td><?php echo $order->getId(); ?></td>
                            <td><?php echo $order->getUserId(); ?></td>
                            <td>
                                Address: <?php echo htmlspecialchars($contact->getAddress()); ?><br>
                                Phone: <?php echo htmlspecialchars($contact->getPhoneNumber()); ?>
                            </td>
                            <td>
                                <?php foreach ($orderRepository->getOrderItems($order->getId()) as $item): ?>
                                    <?php echo htmlspecialchars($productRepository->read($item->getProductId())->getName()); ?> x<?php echo $item->getQuantity(); ?>
                                    <br>
                                <?php endforeach; ?>
                            </td>
                            <td><?php
                                $order-> getTotalPrice() == null ?     $totalPrice = 0 : $totalPrice = $order->getTotalPrice();
                                echo htmlspecialchars(number_format($totalPrice, 2)); ?> PLN</td>
                            <td><?php echo htmlspecialchars($order->getStatus()); ?></td>
                            <td><?php echo htmlspecialchars($order->getCreatedAt()); ?></td>
                            <td>
                                <form method="post" action="moderator_manage_orders.php" class="form-inline">
                                    <input type="hidden" name="order_id" value="<?php echo $order->getId(); ?>">
                                    <label>
                                        <select name="status" class="form-control mr-2">
                                            <?php foreach ($statuses as $status): ?>
                                                <option value="<?php echo $status; ?>" <?php echo ($order->getStatus() == $status) ? 'selected' : ''; ?>>
                                                    <?php echo $status; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </label>
                                    <button type="submit" class="btn_product" style="margin-top: 5px">Update</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
    <?php include '../../footer.php'; ?>
</div>