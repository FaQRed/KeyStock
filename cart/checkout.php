<?php
include '../header.php';
include_once __DIR__ . '/../database/cart_repo/CartRepository.php';
include_once __DIR__ . '/../database/order_repo/OrderRepository.php';
include_once __DIR__ . '/../database/contact_repo/ContactRepository.php';
include_once __DIR__ . '/../send_email.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "You must be logged in to checkout.";
    header("Location: ../login_registration/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$cartRepository = new CartRepository();
$orderRepository = new OrderRepository();
$contactRepository = new ContactRepository();
$cart = $cartRepository->getCartByUserId($user_id);
$contacts = $contactRepository->readByUserId($user_id);

if (!$cart) {
    $_SESSION['error'] = "Cart not found.";
    header("Location: cart.php");
    exit();
}

$cart_items = $cartRepository->getItemsByCartId($cart->getId());
$total_price = 0;
foreach ($cart_items as $item) {
    $total_price += $item->getQuantity() * $item->getProductPrice();
}
$discount = isset($_SESSION['discount']) ? $_SESSION['discount'] : 0;
$total_price_after_discount = $total_price - $discount;

$discount = 0;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $contact_id = intval($_POST['contact_id']);
    $payment_method = htmlspecialchars(strip_tags($_POST['payment_method']));


    $selected_contact = $contactRepository->read($contact_id);

    if (!$selected_contact) {
        $_SESSION['error'] = "Invalid contact selected.";
        header("Location: checkout.php");
        exit();
    }

    if (!isset($_SESSION['error'])) {
        $order_id = $orderRepository->createOrder($cart->getId(), $user_id, $total_price_after_discount);
        if ($order_id) {
            foreach ($cart_items as $item) {
                $orderRepository->createOrderItem($order_id, $item->getProductId(), $item->getQuantity(), $item->getProductPrice());
            }
            $cartRepository->clearCart($cart->getId());



            $to = $_SESSION['email'];
            $subject = 'Order Confirmation';
            $body = '<h1>Thank you ' . $_SESSION['username'] . ' for your order</h1>';
            $body .= '<p>Your order ID is ' . $order_id . '.</p>';
            $body .= '<h2>Order Details</h2>';
            $body .= '<table>';
            $body .= '<tr><th>Product</th><th>Quantity</th><th>Price</th><th>Total</th></tr>';
            foreach ($cart_items as $item) {
                $body .= '<tr>';
                $body .= '<td>' . htmlspecialchars($item->getProductName()) . '</td>';
                $body .= '<td>' . htmlspecialchars($item->getQuantity()) . '</td>';
                $body .= '<td>' . htmlspecialchars(number_format($item->getProductPrice(), 2)) . ' PLN</td>';
                $body .= '<td>' . htmlspecialchars(number_format($item->getQuantity() * $item->getProductPrice(), 2)) . ' PLN</td>';
                $body .= '</tr>';
            }
            $body .= '<tr><th colspan="3">Subtotal</th><th>' . htmlspecialchars(number_format($total_price, 2)) . ' PLN</th></tr>';
            if ($discount > 0) {
                $body .= '<tr><th colspan="3">Discount</th><th>-' . htmlspecialchars(number_format($discount, 2)) . ' PLN</th></tr>';
            }
            $body .= '<tr><th colspan="3">Total</th><th>' . htmlspecialchars(number_format($total_price_after_discount, 2)) . ' PLN</th></tr>';
            $body .= '</table>';

            sendOrderConfirmationEmail($to, $subject, $body);


            $_SESSION['success'] = "Order placed successfully.";
            header("Location: order_confirmation.php?order_id=" . $order_id);
            exit();
        } else {
            $_SESSION['error'] = "Failed to place order.";
        }
    }
}
?>

<div class="wrapper">
    <?php include '../navBar.php'; ?>
    <main class="container mt-5">
        <h2>Checkout</h2>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>
        <form method="post" action="checkout.php">
            <div class="form-group">
                <label for="contact_id">Select Contact Information from your profile</label>
                <select class="form-control" id="contact_id" name="contact_id" required>
                    <?php foreach ($contacts as $contact): ?>
                        <option value="<?php echo $contact->getId(); ?>">
                            <?php echo 'Address: ' . $contact->getAddress() . ', Tel: ' . $contact->getPhoneNumber(); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="payment_method">Payment Method</label>
                <select class="form-control" id="payment_method" name="payment_method" required>
                    <option value="Credit Card">Credit Card</option>
                    <option value="PayPal">PayPal</option>
                    <option value="Bank Transfer">Bank Transfer</option>
                </select>
            </div>

            <div class="form-group">
                <label for="promo_code">Promo Code</label>
                <input type="text" class="form-control" id="promo_code" name="promo_code" value="<?php echo isset($_SESSION['promo_code']) ? htmlspecialchars($_SESSION['promo_code']) : ''; ?>" readonly>
            </div>
            <h3>Order Summary</h3>
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
                <?php foreach ($cart_items as $item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item->getProductName()); ?></td>
                        <td><?php echo htmlspecialchars($item->getQuantity()); ?></td>
                        <td><?php echo htmlspecialchars(number_format($item->getProductPrice(), 2)); ?> PLN</td>
                        <td><?php echo htmlspecialchars(number_format($item->getQuantity() * $item->getProductPrice(), 2)); ?> PLN</td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="3">Subtotal</td>
                    <td><?php echo htmlspecialchars(number_format($total_price, 2)); ?> PLN</td>
                </tr>
                <?php if ($discount > 0): ?>
                    <tr>
                        <td colspan="3">Discount</td>
                        <td>-<?php echo htmlspecialchars(number_format($discount, 2)); ?> PLN</td>
                    </tr>
                <?php endif; ?>
                <tr>
                    <td colspan="3">Total</td>
                    <td><?php echo htmlspecialchars(number_format($total_price_after_discount, 2)); ?> PLN</td>
                </tr>
                </tbody>
            </table>

            <button type="submit" class="btn_product">Place Order</button>
        </form>
    </main>
    <?php include '../footer.php'; ?>
</div>