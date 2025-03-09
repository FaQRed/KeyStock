<?php
include '../header.php';


if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'moderator' && $_SESSION['role'] !== 'admin')) {
    $_SESSION['error'] = "Access denied.";
    header("Location: ../index.php");
    exit();
}
?>
<div class="wrapper">
    <?php include '../navBar.php'; ?>
    <main class="container mt-5">
        <div class="row">
            <?php include 'moderator_panel_nav.php'; ?>
            <div class="col-md-9">
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
                <h3>Moderator Panel</h3>
                <p>Welcome to the Moderator Panel. Use the navigation on the left to manage categories, products, promo codes, orders, reviews, and generate reports.</p>
            </div>
        </div>
    </main>

<?php include '../footer.php'; ?>
</div>
