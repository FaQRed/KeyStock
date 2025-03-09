<?php
include '../header.php';
include_once __DIR__ . '/../database/cart_repo/CartRepository.php';
include_once __DIR__ . '/../database/product_repo/ProductRepository.php';
include_once __DIR__ . '/../database/promoCode_repo/PromoCodeRepository.php';
include_once __DIR__ . '/../entities/Cart.php';
include_once __DIR__ . '/../entities/Product.php';
include_once __DIR__ . '/../entities/CartItem.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "You must be logged in to view the cart.";
    header("Location: ../login_registration/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$cartRepository = new CartRepository();
$productRepository = new ProductRepository();
$promoCodeRepository = new PromoCodeRepository();
$cart = $cartRepository->getCartByUserId($user_id);

if (!$cart) {
    $cart = $cartRepository->createCart($user_id);
}

$cart_items = $cartRepository->getItemsByCartId($cart->getId());
$total_price = 0;

foreach ($cart_items as $item) {
    $total_price += $item->getQuantity() * $item->getProductPrice();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['promo_code'])) {
    $promo_code = htmlspecialchars(strip_tags($_POST['promo_code']));
    $promoCode = $promoCodeRepository->getPromoCode($promo_code);
    if ($promoCode) {
        $_SESSION['promo_code'] = $promo_code;
        $_SESSION['discount'] = $total_price * ($promoCode->getDiscount() / 100);
    } else {
        $_SESSION['error'] = "Invalid or expired promo code.";
    }
}

$discount = isset($_SESSION['discount']) ? $_SESSION['discount'] : 0;
$total_price_after_discount = $total_price - $discount;
?>

<div class="wrapper">
    <?php include '../navBar.php'; ?>
    <main class="container mt-5">
        <h2>Your Cart</h2>
        <?php if (count($cart_items) > 0): ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
            <?php endif; ?>
            <form method="post" action="cart.php">
                <div class="form-group">
                    <label for="promo_code">Promo Code</label>
                    <input type="text" class="form-control" id="promo_code" name="promo_code" placeholder="Enter promo code">
                </div>
                <button type="submit" class="btn_product">Apply Promo Code</button>
            </form>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>Image</th>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($cart_items as $item):

                    $product = $productRepository->read($item->getProductId())?>
                    <tr>
                        <td><img src="/images/<?php echo $product->getImage(); ?>"  style="width: 50px; height: 50px;" alt="<?php echo $product->getName(); ?>"></td>
                        <td><?php echo $item->getProductName(); ?></td>
                        <td><?php echo $item->getQuantity(); ?></td>
                        <td><?php echo $item->getProductPrice(); ?> PLN</td>
                        <td><?php echo $item->getQuantity() * $item->getProductPrice(); ?> PLN</td>
                        <td>
                            <a href="update_cart.php?id=<?php echo $item->getId(); ?>&action=remove" class="btn_product" style="margin-top: 5px">Remove</a>
                            <a href="update_cart.php?id=<?php echo $item->getId(); ?>&action=add" class="btn_product" style="margin-top: 5px">Add</a>
                        </td>
                    </tr>
                <?php endforeach; ?>

                <tr>
                    <td colspan="5">Subtotal</td>
                    <td><?php echo htmlspecialchars(number_format($total_price, 2)); ?> PLN</td>
                </tr>
                <?php if ($discount > 0): ?>
                    <tr>
                        <td colspan="5">Discount</td>
                        <td>-<?php echo htmlspecialchars(number_format($discount, 2)); ?> PLN</td>
                    </tr>
                <?php endif; ?>
                <tr>
                    <td colspan="5">Total</td>
                    <td><?php echo htmlspecialchars(number_format($total_price_after_discount, 2)); ?> PLN</td>
                </tr>

                </tbody>
            </table>
            <a href="checkout.php" class="btn_product">Proceed to Checkout</a>
        <?php else: ?>
            <p>Your cart is empty.</p>
        <?php endif; ?>
    </main>
    <?php include '../footer.php'; ?>
</div>