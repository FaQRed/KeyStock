<?php
include '../../header.php';
include_once '../../database/Database_connection.php';
include_once '../../entities/User.php';
include_once '../../database/user_repo/UserRepository.php';


if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    $_SESSION['error'] = "Access denied.";
    header("Location: ../../index.php");
    exit();
}

$userRepository = new UserRepository();
$user_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($user_id > 0) {
    $user = $userRepository->read($user_id);
    if (!$user) {
        $_SESSION['error'] = "User not found.";
        header("Location: admin_users.php");
        exit();
    }
} else {
    $_SESSION['error'] = "Invalid user ID.";
    header("Location: admin_users.php");
    exit();
}
?>
<div class="wrapper">
    <?php include '../../navBar.php'; ?>
    <main class="container mt-5">
        <div class="row">
            <?php include '../admin_panel_nav.php'; ?>
            <div class="col-md-9">
                <h3>Edit User</h3>
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
                <form action="editing_user.php" method="post">
                    <input type="hidden" name="id" value="<?php echo $user->getId(); ?>">
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" id="username" name="username" class="form-control" value="<?php echo htmlspecialchars($user->getUsername()); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user->getEmail()); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password (leave blank to keep current password):</label>
                        <input type="password" id="password" name="password" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="first_name">First Name:</label>
                        <input type="text" id="first_name" name="first_name" class="form-control" value="<?php echo htmlspecialchars($user->getFirstName()); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="last_name">Last Name:</label>
                        <input type="text" id="last_name" name="last_name" class="form-control" value="<?php echo htmlspecialchars($user->getLastName()); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="role">Role:</label>
                        <select id="role" name="role" class="form-control" required>
                            <option value="user" <?php echo ($user->getRole() == 'user') ? 'selected' : ''; ?>>User</option>
                            <option value="moderator" <?php echo ($user->getRole() == 'moderator') ? 'selected' : ''; ?>>Moderator</option>
                            <option value="admin" <?php echo ($user->getRole() == 'admin') ? 'selected' : ''; ?>>Admin</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-secondary">Update User</button>
                </form>
            </div>
        </div>
    </main>

<?php include '../../footer.php'; ?>

</div>
