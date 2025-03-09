<?php

use category_repo\CategoryRepository;

session_start();
include_once '../../database/Database_connection.php';
include_once '../../database/category_repo/CategoryRepository.php';


$id = intval($_POST['id']);
$name = htmlspecialchars(strip_tags($_POST['category_name']));

$categoryRepository = new CategoryRepository();
$category = $categoryRepository->read($id);

if($categoryRepository-> exists($name)){
    $_SESSION['error'] = "This category name is already exists";
    header('Location: moderator_categories.php');
    exit();
}
if (!$category) {
    $_SESSION['error'] = "Category not found.";
    header("Location: moderator_categories.php");
    exit();
}

$category->setName($name);

if ($categoryRepository->update($category)) {
    $_SESSION['success'] = "Category updated successfully.";
} else {
    $_SESSION['error'] = "Failed to update category.";
}

header("Location: moderator_categories.php");
exit();
?>