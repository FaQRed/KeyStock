<?php
include '../../header.php';
include_once __DIR__ . '/../../database/promoCode_repo/PromoCodeRepository.php';
include_once __DIR__ . '/../../entities/PromoCode.php';


if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'moderator' && $_SESSION['role'] !== 'admin')) {
    $_SESSION['error'] = "Access denied.";
    header("Location: ../../index.php");
    exit();
}

$promoCodeRepository = new PromoCodeRepository();
$promoCodes = $promoCodeRepository->getAllPromoCodes();
?>
<div class="wrapper">
    <?php include '../../navBar.php'; ?>
    <main class="container mt-5">
        <div class="row">
            <?php include '../moderator_panel_nav.php'; ?>
            <div class="col-md-9">
                <h2 style="text-align: center; margin-bottom: 10px;">Manage Promo Codes</h2>
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger"><?php echo $_SESSION['error'];
                        unset($_SESSION['error']); ?></div>
                <?php endif; ?>
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success"><?php echo $_SESSION['success'];
                        unset($_SESSION['success']); ?></div>
                <?php endif; ?>
                <a href="add_promocode.php" class="btn_product">Add New Promo Code</a>
                <table class="table table-bordered mt-3">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Code</th>
                        <th>Discount (%)</th>
                        <th>Valid Until</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($promoCodes as $promoCode): ?>
                        <tr>
                            <td><?php echo $promoCode->getId(); ?></td>
                            <td><?php echo htmlspecialchars($promoCode->getCode()); ?></td>
                            <td><?php echo htmlspecialchars($promoCode->getDiscount()); ?></td>
                            <td><?php echo htmlspecialchars($promoCode->getValidUntil()); ?></td>
                            <td>
                                <a href="edit_promocode.php?id=<?php echo $promoCode->getId(); ?>"
                                   class="btn-sm btn_product">Edit</a>
                                <a href="delete_promocode.php?id=<?php echo $promoCode->getId(); ?>"
                                   class="btn-sm btn_product"
                                   onclick="return confirm('Are you sure you want to delete this promo code?');">Delete</a>
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