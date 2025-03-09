<?php
session_start();
include_once '../../database/Database_connection.php';
include_once '../../database/product_repo/ProductRepository.php';
include_once '../../entities/Product.php';


$id = intval($_POST['id']);
$name = htmlspecialchars(strip_tags($_POST['product_name']));
$category_id = intval($_POST['category_id']);
$price = floatval($_POST['product_price']);
$quantity = intval($_POST['product_quantity']);
$manufacturer = htmlspecialchars(strip_tags($_POST['product_manufacturer']));
$weight = floatval($_POST['product_weight']);
$dimensions = htmlspecialchars(strip_tags($_POST['product_dimensions']));
$description = htmlspecialchars(strip_tags($_POST['product_description']));


if (!preg_match('/^\d+x\d+x\d+$/', $dimensions)) {
    $_SESSION['error'] = "Dimensions must be in the format LxWxH (35x40x30).";
    header("Location: edit_product.php?id=$id");
    exit();
}

$productRepository = new ProductRepository();
$product = $productRepository->read($id);

if (!$product) {
    $_SESSION['error'] = "Product not found.";
    header("Location: moderator_manage_products.php");
    exit();
}

$product->setName($name);
$product->setCategoryId($category_id);
$product->setPrice($price);
$product->setQuantity($quantity);
$product->setManufacturer($manufacturer);
$product->setWeight($weight);
$product->setDimensions($dimensions);
$product->setDescription($description);


if (!empty($_FILES['product_image']['name'])) {
    $image = $_FILES['product_image']['name'];
    $image_temp = $_FILES['product_image']['tmp_name'];
    $image_folder = "../../images/productImages" . basename($image);

    if (!move_uploaded_file($image_temp, $image_folder)) {
        $_SESSION['error'] = "Failed to upload image.";
        header("Location: edit_product.php?id=" . $product->getId());
        exit();
    }

    $product->setImage($image_folder);
}

if ($productRepository->update($product)) {
    $_SESSION['success'] = "Product updated successfully.";
    header("Location: moderator_manage_products.php");
} else {
    $_SESSION['error'] = "Failed to update product.";
    header("Location: edit_product.php?id=" . $product->getId());
}


exit();
?>