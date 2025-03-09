<?php

use category_repo\CategoryRepository;

include '../../header.php';
include_once '../../database/Database_connection.php';
include_once '../../database/category_repo/CategoryRepository.php';

if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'moderator' && $_SESSION['role'] !== 'admin')) {
    $_SESSION['error'] = "Access denied.";
    header("Location: ../../index.php");
    exit();
}

$categoryRepository = new CategoryRepository();
$category_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($category_id > 0) {
    $category = $categoryRepository->read($category_id);
    if (!$category) {
        $_SESSION['error'] = "Category not found.";
        header("Location: moderator_categories.php");
        exit();
    }
} else {
    $_SESSION['error'] = "Invalid category ID.";
    header("Location: moderator_categories.php");
    exit();
}
?>

<div class="wrapper">
    <?php include '../../navBar.php'; ?>
<main class="container mt-5">
    <div class="row">

            <?php include '../moderator_panel_nav.php'; ?>
        <div class="col-md-9">
            <h3>Edit Category</h3>
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
            <form action="editing_category.php" method="post">
                <input type="hidden" name="id" value="<?php echo $category->getId(); ?>">
                <div class="form-group">
                    <label for="category_name">Category Name:</label>
                    <input type="text" id="category_name" name="category_name" class="form-control" value="<?php echo htmlspecialchars($category->getName()); ?>" required>
                </div>
                <button type="submit" class="btn" style="background-color: #86155a; color: white; margin-bottom: 10px" >Update Category</button>
            </form>
        </div>
    </div>
</main>
</div>

<?php include '../../footer.php'; ?>