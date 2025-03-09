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
$categories = $categoryRepository->readAll();
?>
<div class="wrapper">
    <?php include '../../navBar.php' ?>
    <main class="container mt-5">
        <div class="row">
            <?php include '../moderator_panel_nav.php'; ?>
            <div class="col-md-9">
                <h3>Manage Categories</h3>
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
                <form action="create_category.php" method="post">
                    <div class="form-group">
                        <label for="category_name">Category Name:</label>
                        <input type="text" id="category_name" name="category_name" class="form-control" required>
                    </div>
                    <button type="submit" class="btn" style="background-color: #86155a; color: white">Add Category</button>
                </form>
                <hr>

                <table class="table table-bordered mt-3">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Category Name</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($categories as $category): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($category->getId()); ?></td>
                            <td><?php echo htmlspecialchars($category->getName()); ?></td>
                            <td>
                                <a href="edit_category.php?id=<?php echo $category->getId(); ?>" class="btn btn-sm"
                                   style="background-color: #86155a; color: white; margin-bottom: 10px  ">Edit</a>
                                <a href="delete_category.php?id=<?php echo $category->getId(); ?>"
                                   class="btn btn-sm" style="background-color: #86155a; color: white; margin-bottom: 10px  " onclick="return confirm('Are you sure you want to delete this category?');">Delete</a>
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
