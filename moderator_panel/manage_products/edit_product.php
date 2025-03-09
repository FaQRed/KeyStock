<?php

use category_repo\CategoryRepository;

include '../../header.php';
include_once '../../database/Database_connection.php';
include_once '../../database/product_repo/ProductRepository.php';
include_once '../../database/category_repo/CategoryRepository.php';


if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'moderator' && $_SESSION['role'] !== 'admin')) {
    $_SESSION['error'] = "Access denied.";
    header("Location: ../../index.php");
    exit();
}

$productRepository = new ProductRepository();
$categoryRepository = new CategoryRepository();
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($product_id > 0) {
    $product = $productRepository->read($product_id);
    if (!$product) {
        $_SESSION['error'] = "Product not found.";
        header("Location: moderator_manage_products.php");
        exit();
    }
} else {
    $_SESSION['error'] = "Invalid product ID.";
    header("Location: moderator_manage_products.php");
    exit();
}

$categories = $categoryRepository->readAll();
?>

<div class="wrapper">
    <?php include '../../navBar.php'; ?>
    <main class="container mt-5">
        <div class="row">
                <?php include '../moderator_panel_nav.php'; ?>
            <div class="col-md-9">
                <h3>Edit Product</h3>
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
                <form action="editing_product.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?php echo $product->getId(); ?>">
                    <div class="form-group">
                        <label for="product_name">Product Name:</label>
                        <input type="text" id="product_name" name="product_name" class="form-control" value="<?php echo htmlspecialchars($product->getName()); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="category_id">Category:</label>
                        <select id="category_id" name="category_id" class="form-control" required>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo $category->getId(); ?>" <?php echo ($product->getCategoryId() == $category->getId()) ? 'selected' : ''; ?>><?php echo $category->getName(); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="product_image">Image:</label>
                        <input type="file" id="product_image" name="product_image" class="form-control">
                        <small>Leave blank to keep current image.</small>
                    </div>
                    <div class="form-group">
                        <label for="product_price">Price:</label>
                        <input type="number" step="0.01" id="product_price" name="product_price" class="form-control" value="<?php echo htmlspecialchars($product->getPrice()); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="product_quantity">Quantity:</label>
                        <input type="number" id="product_quantity" name="product_quantity" class="form-control" value="<?php echo htmlspecialchars($product->getQuantity()); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="product_manufacturer">Manufacturer:</label>
                        <input type="text" id="product_manufacturer" name="product_manufacturer" class="form-control" value="<?php echo htmlspecialchars($product->getManufacturer()); ?>">
                    </div>
                    <div class="form-group">
                        <label for="product_weight">Weight:</label>
                        <input type="number" step="0.01" id="product_weight" name="product_weight" class="form-control" value="<?php echo htmlspecialchars($product->getWeight()); ?>">
                    </div>
                    <div class="form-group">
                        <label for="product_dimensions">Dimensions:</label>
                        <input type="text" id="product_dimensions" name="product_dimensions" class="form-control" value="<?php echo htmlspecialchars($product->getDimensions()); ?>">
                    </div>
                    <div class="form-group">
                        <label for="product_description">Description:</label>
                        <textarea id="product_description" name="product_description" class="form-control"><?php echo htmlspecialchars($product->getDescription()); ?></textarea>
                    </div>
                    <button type="submit" class="btn " style="background-color: #86155a; color: white; margin-bottom: 10px"> Update Product</button>
                </form>
            </div>
        </div>
    </main>

<?php include '../../footer.php'; ?>

</div>
