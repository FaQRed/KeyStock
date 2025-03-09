<?php

use category_repo\CategoryRepository;

include '../../header.php';
include_once '../../database/Database_connection.php';
include_once '../../database/category_repo/CategoryRepository.php';
include_once __DIR__ . '/../../database/product_repo/ProductRepository.php';


if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'moderator' && $_SESSION['role'] !== 'admin')) {
    $_SESSION['error'] = "Access denied.";
    header("Location: ../../index.php");
    exit();
}

$categoryRepository = new CategoryRepository();
$categories = $categoryRepository->readAll();
?>
<div class="wrapper">
    <?php include '../../navBar.php'; ?>
    <main class="container mt-5">
        <div class="row">
                <?php include '../moderator_panel_nav.php'; ?>

            <div class="col-md-9">
                <h3>Add New Product</h3>
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
                <form action="create_product.php" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="product_name">Product Name:</label>
                        <input type="text" id="product_name" name="product_name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="category_id">Category:</label>
                        <select id="category_id" name="category_id" class="form-control" required>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo $category->getId(); ?>"><?php echo $category->getName(); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="product_image">Image:</label>
                        <input type="file" id="product_image" name="product_image" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="product_price">Price:</label>
                        <input type="number" step="0.01" max="999999" id="product_price" name="product_price" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="product_quantity">Quantity:</label>
                        <input type="number" id="product_quantity" name="product_quantity" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="product_manufacturer">Manufacturer:</label>
                        <input type="text" id="product_manufacturer" name="product_manufacturer" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="product_weight">Weight:</label>
                        <input type="number" step="0.01" id="product_weight" name="product_weight" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="product_dimensions">Dimensions:</label>
                        <input type="text" id="product_dimensions" name="product_dimensions" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="product_description">Description:</label>
                        <textarea id="product_description" name="product_description" class="form-control"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Product</button>
                </form>
            </div>
        </div>
    </main>

<?php include '../../footer.php'; ?>
</div>
