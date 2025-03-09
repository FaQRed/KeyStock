<?php

use category_repo\CategoryRepository;

session_start();
include_once '../../database/Database_connection.php';
include_once '../../database/category_repo/CategoryRepository.php';

$category_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$categoryRepository = new CategoryRepository();
$category = $categoryRepository->read($category_id);

if ($category) {
    if ($categoryRepository->hasProducts($category_id)) {
        $_SESSION['error'] = "Cannot delete category with products.";
    } else {
        if ($categoryRepository->delete($category_id)) {
            $_SESSION['success'] = "Category deleted successfully.";
        } else {
            $_SESSION['error'] = "Failed to delete category.";
        }
    }
} else {
    $_SESSION['error'] = "Category does not exist.";
}


header("Location: moderator_categories.php");
exit();
?>