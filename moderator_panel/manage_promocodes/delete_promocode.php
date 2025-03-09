<?php
session_start();
include_once __DIR__ . '/../../database/promoCode_repo/PromoCodeRepository.php';

if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'moderator' && $_SESSION['role'] !== 'admin')) {
    $_SESSION['error'] = "Access denied.";
    header("Location: ../../index.php");
    exit();
}

$promoCodeRepository = new PromoCodeRepository();
$promo_code_id = intval($_GET['id']);

if ($promoCodeRepository->delete($promo_code_id)) {
    $_SESSION['success'] = "Promo code deleted successfully.";
} else {
    $_SESSION['error'] = "Failed to delete promo code.";
}

header("Location: moderator_manage_promo_codes.php");
exit();
?>