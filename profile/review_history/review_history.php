<?php
include '../../header.php';
include_once __DIR__ . '/../../database/review_repo/ReviewRepository.php';
include_once __DIR__ . '/../../entities/Review.php';
include_once __DIR__ . '/../../database/product_repo/ProductRepository.php';
include_once __DIR__ . '/../../entities/Product.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "You must be logged in to view this page.";
    header("Location: ../../login_registration/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$reviewRepository = new ReviewRepository();
$user_reviews = $reviewRepository->readByUserId($user_id);
$productRepo = new ProductRepository();

?>

<div class="wrapper">
    <?php include '../../navBar.php'; ?>
    <main class="container mt-5">
        <div class="row">
        <div class="col-md-3">
            <?php include '../user_nav_bar.php'; ?>
        </div>
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
        <h2>Review History</h2>
        <?php if (count($user_reviews) > 0): ?>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>Product</th>
                    <th>Rating</th>
                    <th>Comment</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($user_reviews as $review):
                    $productName = $productRepo->read($review->getProductId())->getName();
                    ?>
                    <tr>
                        <td><a class="categories_nav" href="../../product/product.php?id=<?php echo $review->getProductId(); ?>"><?php echo $productName ?></a></td>
                        <td><?php echo $review->getRating(); ?>/5</td>
                        <td><?php echo $review->getComment(); ?></td>
                        <td><?php echo $review->getCreatedAt(); ?></td>
                        <td> <a href="../../moderator_panel/manage_reviews/delete_review.php?id=<?php echo $review->getId(); ?>&product_id=<?php echo $review->getProductId(); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this review?');">Delete</a></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>You have not left any reviews yet.</p>
        <?php endif; ?>
        </div>
        </div>
    </main>
    <?php include '../../footer.php'; ?>
</div>