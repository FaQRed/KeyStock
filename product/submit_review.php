<?php
session_start();
include_once '../database/Database_connection.php';
include_once '../database/review_repo/ReviewRepository.php';
include_once '../entities/Review.php';

$product_id = intval($_POST['product_id']);
$user_id = intval($_POST['user_id']);
$rating = intval($_POST['rating']);
$comment = htmlspecialchars(strip_tags($_POST['review']));

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "You must be logged in to leave reviews on products.";
    header("Location: ../login_registration/login.php");
    exit();
}
$reviewRepository = new ReviewRepository();

$review = new Review();
$review->setRating($rating);
$review->setComment($comment);
$review->setUserId($user_id);
$review->setProductId($product_id);


if ($reviewRepository->create($review)) {
    $_SESSION['success'] = "Review updated successfully.";
} else {
    $_SESSION['error'] = "Failed to update review.";
}

header("Location: /product/product.php?id=" . $review->getProductId());
exit();
?>