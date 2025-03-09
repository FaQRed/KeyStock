<?php
session_start();
include_once '../../database/Database_connection.php';
include_once '../../database/review_repo/ReviewRepository.php';

$review_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0;

$reviewRepository = new ReviewRepository();
if ($reviewRepository->delete($review_id)) {
$_SESSION['success'] = "Review deleted successfully.";
} else {
$_SESSION['error'] = "Failed to delete review.";
}
if($_SESSION['role'] == 'user'){
  header("Location : /profile/review_history/review_history.php");
}

header("Location: view_reviews.php?product_id=" . $product_id);
exit();
?>