<?php

use category_repo\CategoryRepository;

include '../../header.php';
include_once '../../database/Database_connection.php';
include_once '../../database/category_repo/CategoryRepository.php';
include_once __DIR__ . '/../../database/product_repo/ProductRepository.php';
include_once __DIR__ . '/../../database/review_repo/ReviewRepository.php';


if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'moderator' && $_SESSION['role'] !== 'admin')) {
    $_SESSION['error'] = "Access denied.";
    header("Location: ../../index.php");
    exit();
}
$reviewRepository = new ReviewRepository();
$categoryRepository = new CategoryRepository();
$productRepository = new ProductRepository();
$categories = $categoryRepository->readAll();
?>
<div class="wrapper">
    <?php include '../../navBar.php' ?>
    <main class="container mt-5">
        <div class="row">
                <?php include '../moderator_panel_nav.php'; ?>
            <div class="col-md-9">
                <h3>Manage Products</h3>
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
                <a class="btn btn-primary mb-3" href="add_product.php"
                   style="background-color: #86155a; color: white; margin-bottom: 10px">Add Product</a>
                <label for="searchInput"></label>
                <input class="form-control" id="searchInput" type="text" style="margin-bottom: 15px;" placeholder="Search for products...">

                <ul class="nav nav-tabs" id="productTabs" role="tablist">
                    <?php foreach ($categories as $index => $category): ?>
                        <li class="nav-item">
                            <a class="nav-link categories_nav <?php echo $index === 0 ? 'active' : ''; ?>"
                               id="tab-<?php echo $category->getId(); ?>" data-toggle="tab"
                               href="#category-<?php echo $category->getId(); ?>" role="tab"><?php echo $category->getName(); ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <div class="tab-content" id="productTabsContent">
                    <?php foreach ($categories as $index => $category): ?>
                        <div class="tab-pane fade <?php echo $index === 0 ? 'show active' : ''; ?>" id="category-<?php echo $category->getId(); ?>" role="tabpanel">
                            <h4 class="mt-3"><?php echo $category->getName(); ?></h4>
                            <table class="table table-bordered product-table">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Image</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Manufacturer</th>
                                    <th>Rating</th>
                                    <th>Weight</th>
                                    <th>Dimensions</th>
                                    <th>Description</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $products = $productRepository->readByCategory($category->getId());
                                foreach ($products as $product): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($product->getId()); ?></td>
                                        <td class="product-name"><?php echo htmlspecialchars($product->getName()); ?></td>
                                        <td><img src="<?php echo htmlspecialchars($product->getImage()); ?>" alt="Product Image" style="width: 50px; height: 50px;"></td>
                                        <td><?php echo htmlspecialchars($product->getPrice()); ?></td>
                                        <td><?php echo htmlspecialchars($product->getQuantity()); ?></td>
                                        <td><?php echo htmlspecialchars($product->getManufacturer()); ?></td>
                                        <td><?php echo htmlspecialchars(number_format($reviewRepository -> getProductAverageRating($product-> getId()), 2)); ?></td>
                                        <td><?php echo htmlspecialchars($product->getWeight()); ?></td>
                                        <td><?php echo htmlspecialchars($product->getDimensions()); ?></td>
                                        <td><?php echo htmlspecialchars($product->getDescription()); ?></td>
                                        <td>
                                            <a href="edit_product.php?id=<?php echo $product->getId(); ?>" class="btn btn-sm" style="background-color: #86155a; color: white; margin-bottom: 10px">Edit</a>
                                            <a href="delete_product.php?id=<?php echo $product->getId(); ?>" class="btn btn-sm" style="background-color: #86155a; color: white; margin-bottom: 10px" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
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

    <script>
        document.getElementById('searchInput').addEventListener('keyup', function() {
            let searchValue = this.value.toLowerCase();
            let productTables = document.querySelectorAll('.product-table tbody tr');

            productTables.forEach(function(row) {
                let productName = row.querySelector('.product-name').textContent.toLowerCase();
                if (productName.indexOf(searchValue) !== -1) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>

</div>
