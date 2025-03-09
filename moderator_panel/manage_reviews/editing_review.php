<?php
session_start();
include_once '../../database/Database_connection.php';
include_once '../../database/review_repo/ReviewRepository.php';
include_once '../../entities/Review.php';

$review_id = intval($_POST['id']);
$rating = intval($_POST['rating']);
$comment = htmlspecialchars(strip_tags($_POST['comment']));

$reviewRepository = new ReviewRepository();
$review = $reviewRepository->read($review_id);

if (!$review) {
    $_SESSION['error'] = "Review not found.";
    header("Location: moderator_manage_reviews.php");
    exit();
}

$review->setRating($rating);
$review->setComment($comment);

if ($reviewRepository->update($review)) {
    $_SESSION['success'] = "Review updated successfully.";
} else {
    $_SESSION['error'] = "Failed to update review.";
}

header("Location: view_reviews.php?product_id=" . $review->getProductId());
exit();
?>