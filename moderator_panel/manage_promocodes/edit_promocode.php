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
$promo_code_id = intval($_GET['id']);
$promoCode = $promoCodeRepository->getPromoCodeById($promo_code_id);

if (!$promoCode) {
    $_SESSION['error'] = "Promo code not found.";
    header("Location: moderator_manage_promo_codes.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $code = htmlspecialchars(strip_tags($_POST['code']));
    $discount = floatval($_POST['discount']);
    $valid_until = htmlspecialchars(strip_tags($_POST['valid_until']));

    $promoCode->setCode($code);
    $promoCode->setDiscount($discount);
    $promoCode->setValidUntil($valid_until);

    if ($promoCodeRepository->update($promoCode)) {
        $_SESSION['success'] = "Promo code updated successfully.";
        header("Location: moderator_manage_promo_codes.php");
        exit();
    } else {
        $_SESSION['error'] = "Failed to update promo code.";
    }
}
?>

<div class="wrapper">
    <?php include '../../navBar.php'; ?>
    <main class="container mt-5">
        <div class="row">
            <?php include '../moderator_panel_nav.php'; ?>
            <div class="col-md-9">
        <h2>Edit Promo Code</h2>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>
        <form method="post" action="edit_promocode.php?id=<?php echo $promo_code_id; ?>">
            <div class="form-group">
                <label for="code">Code</label>
                <input type="text" class="form-control" id="code" name="code" value="<?php echo htmlspecialchars($promoCode->getCode()); ?>" required>
            </div>
            <div class="form-group">
                <label for="discount">Discount (%)</label>
                <input type="number" class="form-control" id="discount" name="discount" value="<?php echo htmlspecialchars($promoCode->getDiscount()); ?>" required>
            </div>
            <div class="form-group">
                <label for="valid_until">Valid Until</label>
                <input type="date" class="form-control" id="valid_until" name="valid_until" value="<?php echo htmlspecialchars($promoCode->getValidUntil()); ?>" required>
            </div>
            <button type="submit" class="btn_product">Update Promo Code</button>
        </form>
            </div>
        </div>
    </main>
    <?php include '../../footer.php'; ?>
</div>