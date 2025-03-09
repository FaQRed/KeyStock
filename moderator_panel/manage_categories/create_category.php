<?php

use category_repo\CategoryRepository;

session_start();
include_once '../../database/Database_connection.php';
include_once '../../database/category_repo/CategoryRepository.php';

$category_name = htmlspecialchars(strip_tags($_POST['category_name']));

$categoryRepository = new CategoryRepository();
if($categoryRepository-> exists($category_name)){
    $_SESSION['error'] = "This category name is already exists";
    header('Location: moderator_categories.php');
    exit();
}
if ($categoryRepository->create($category_name)) {
    $_SESSION['success'] = "Category created successfully.";
} else {
    $_SESSION['error'] = "Failed to create category.";
}

header("Location: moderator_categories.php");
exit();
?>