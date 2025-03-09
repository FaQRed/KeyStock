<?php
include_once __DIR__ . '/database/cart_repo/CartRepository.php';

$cartRepository = new CartRepository();
$cartItemCount = 0;
if (isset($_SESSION['role']) && isset($_SESSION['user_id'])) {
    $cart_id = $cartRepository->getCartIdByUserId($_SESSION['user_id']);
    if ($cart_id) {
        $cartItemCount = $cartRepository->getCartItemCount($cart_id);
    }
}
?>

<header class="navbar navbar-expand-lg navbar-dark fixed-top " style="position: sticky">
    <div class="logo">
        <a href="/index.php" class="navbar-brand"><h1>KeyStock</h1></a>
    </div>
    <nav class="collapse navbar-collapse">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item"><a class="nav-link" href="/product/products.php">Products</a></li>
            <li class="nav-item"><a class="nav-link" href="/contact.php">Contact</a></li>
            <li class="nav-item"><a class="nav-link" href="/cart/cart.php">Cart
                   <?php
                   if($cartItemCount >0){
                       echo ' (' . $cartItemCount . ')';
                   }
                  ?>
                </a></li>
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
                <li class="nav-item"><a class="nav-link" href="/admin_panel/admin_panel.php">Admin Panel</a></li>
            <?php endif; ?>
            <?php if (isset($_SESSION['role']) && ($_SESSION['role'] == 'moderator' ||
                    $_SESSION['role'] == 'admin')): ?>
                <li class="nav-item"><a class="nav-link" href="/moderator_panel/moderator_panel.php">Moderator Panel</a></li>
            <?php endif; ?>
        </ul>
    </nav>
    <div class="user-auth">
        <?php if (isset($_SESSION['username'])): ?>
            <span>Welcome, <?php echo $_SESSION['first_name']; ?></span>
            <a href="/profile/profile.php" class="btn btn-outline-light btn-sm">Profile</a>
            <a href="/login_registration/logout.php" class="btn btn-outline-light btn-sm"><img src="/images/logout.png" alt="Logout" style="width:20px;height:20px;"></a>
        <?php else: ?>
            <a href="/login_registration/login.php" class="btn btn-outline-light btn-sm">Login</a> | <a href="/login_registration/register_page.php"
                                                                                                       class="btn btn-outline-light btn-sm">Register</a>
        <?php endif; ?>
    </div>
</header>