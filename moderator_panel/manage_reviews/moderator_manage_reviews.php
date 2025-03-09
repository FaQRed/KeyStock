<?php
include '../../header.php';
include_once '../../database/Database_connection.php';
include_once __DIR__ . '/../../database/product_repo/ProductRepository.php';
include_once __DIR__ . '/../../database/review_repo/ReviewRepository.php';
include_once __DIR__ . '/../../database/category_repo/CategoryRepository.php';


if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'moderator' && $_SESSION['role'] !== 'admin')) {
    $_SESSION['error'] = "Access denied.";
    header("Location: ../../index.php");
    exit();
}

$productRepository = new ProductRepository();
$reviewRepository = new ReviewRepository();
$categoryRepository = new \category_repo\CategoryRepository();
$categories = $categoryRepository->readAll();

?>
<div class="wrapper">
    <?php include '../../navBar.php'; ?>
    <main class="container mt-5">
        <div class="row">
            <?php include '../moderator_panel_nav.php'; ?>

            <div class="col-md-9">
                <h3>Manage Reviews</h3>
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
                <ul class="nav nav-tabs" id="productTabs" role="tablist">
                    <?php foreach ($categories as $index => $category): ?>
                        <li class="nav-item">
                            <a class="nav-link categories_nav <?php echo $index === 0 ? 'active' : ''; ?>"
                               id="tab-<?php echo $category->getId(); ?>" data-toggle="tab"
                               href="#category-<?php echo $category->getId(); ?>"
                               role="tab"><?php echo $category->getName(); ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <div class="tab-content" id="productTabsContent">
                    <?php foreach ($categories as $index => $category): ?>
                        <div class="tab-pane fade <?php echo $index === 0 ? 'show active' : ''; ?>"
                             id="category-<?php echo $category->getId(); ?>" role="tabpanel">
                            <h4 class="mt-3"><?php echo $category->getName(); ?></h4>
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>Product ID</th>
                                    <th>Product Name</th>
                                    <th>Number of Reviews</th>
                                    <th>Rating</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $products = $productRepository->readByCategory($category->getId());
                                foreach ($products as $product):
                                    $reviews = $reviewRepository->readByProductId($product->getId());
                                    $reviewCount = count($reviews);
                                    $averageRating = $reviewRepository->getProductAverageRating($product->getId());
                                    ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($product->getId()); ?></td>
                                        <td><?php echo htmlspecialchars($product->getName()); ?></td>
                                        <td><?php echo $reviewCount; ?></td>
                                        <td><?php echo number_format($averageRating, 2); ?></td>
                                        <td>
                                            <a href="view_reviews.php?product_id=<?php echo $product->getId(); ?>"
                                               class="btn_product">View Reviews</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </main>

    <?php include '../../footer.php'; ?>
</div>
