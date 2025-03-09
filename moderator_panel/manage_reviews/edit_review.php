<?php
include '../../header.php';
include_once '../../database/Database_connection.php';
include_once '../../database/review_repo/ReviewRepository.php';


if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'moderator' && $_SESSION['role'] !== 'admin'))  {
    $_SESSION['error'] = "Access denied.";
    header("Location: ../../index.php");
    exit();
}

$reviewRepository = new ReviewRepository();
$review_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($review_id > 0) {
    $review = $reviewRepository->read($review_id);
    if (!$review) {
        $_SESSION['error'] = "Review not found.";
        header("Location: moderator_manage_reviews.php");
        exit();
    }
} else {
    $_SESSION['error'] = "Invalid review ID.";
    header("Location: moderator_manage_reviews.php");
    exit();
}
?>
<div class="wrapper">
    <?php include '../../navBar.php' ?>
<main class="container mt-5">
    <div class="row">

            <?php include '../moderator_panel_nav.php'; ?>
        <div class="col-md-9">
            <h3>Edit Review</h3>
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
            <form action="editing_review.php" method="post">
                <input type="hidden" name="id" value="<?php echo $review->getId(); ?>">
                <div class="form-group">
                    <label for="rating">Rating:</label>
                    <select id="rating" name="rating" class="form-control" required>
                        <option value="1" <?php echo ($review->getRating() == 1) ? 'selected' : ''; ?>>1</option>
                        <option value="2" <?php echo ($review->getRating() == 2) ? 'selected' : ''; ?>>2</option>
                        <option value="3" <?php echo ($review->getRating() == 3) ? 'selected' : ''; ?>>3</option>
                        <option value="4" <?php echo ($review->getRating() == 4) ? 'selected' : ''; ?>>4</option>
                        <option value="5" <?php echo ($review->getRating() == 5) ? 'selected' : ''; ?>>5</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="comment">Comment:</label>
                    <textarea id="comment" name="comment" class="form-control" required><?php echo htmlspecialchars($review->getComment()); ?></textarea>
                </div>
                <button type="submit" class="btn btn-secondary">Update Review</button>
            </form>
        </div>
    </div>
</main>

<?php include '../../footer.php'; ?>
</div>
