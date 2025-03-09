<?php
include '../../header.php';
include_once '../../database/Database_connection.php';
include_once '../../database/review_repo/ReviewRepository.php';
include_once '../../database/user_repo/UserRepository.php';


if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'moderator' && $_SESSION['role'] !== 'admin')) {
    $_SESSION['error'] = "Access denied.";
    header("Location: ../../index.php");
    exit();
}

$userRepository = new UserRepository();
$reviewRepository = new ReviewRepository();
$product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0;

if ($product_id > 0) {
    $reviews = $reviewRepository->readByProductId($product_id);
    if (!$reviews) {
        $_SESSION['error'] = "No reviews found for this product.";
    }
} else {
    $_SESSION['error'] = "Invalid product ID.";
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
                <h3>Reviews for Product ID: <?php echo $product_id; ?></h3>
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
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Review ID</th>
                        <th>Rating</th>
                        <th>Comment</th>
                        <th>UserName</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($reviews as $review):
                        $user = $userRepository->read($review->getUserId());
                        $username = $user ? $user->getUsername() : 'Unknown';
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($review->getId()); ?></td>
                            <td><?php echo htmlspecialchars($review->getRating()); ?></td>
                            <td><?php echo htmlspecialchars($review->getComment()); ?></td>
                            <td><?php echo htmlspecialchars($username); ?></td>
                            <td><?php echo htmlspecialchars($review->getCreatedAt()); ?></td>
                            <td>
                                <a href="edit_review.php?id=<?php echo $review->getId(); ?>" class="btn btn-sm btn-secondary">Edit</a>
                                <a href="delete_review.php?id=<?php echo $review->getId(); ?>&product_id=<?php echo $product_id; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this review?');">Delete</a>
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
