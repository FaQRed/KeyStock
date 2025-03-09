<?php
include '../../header.php';
include_once '../../database/user_repo/UserRepository.php';
include_once '../../entities/User.php';


if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    $_SESSION['error'] = "Access denied.";
    header("Location: ../../index.php");
    exit();
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $userRepository = new UserRepository();


    $user = new User();
    $user->setUsername($_POST['username']);
    $user->setEmail($_POST['email']);
    $user->setFirstName($_POST['first_name']);
    $user->setLastName($_POST['last_name']);
    $user->setRole($_POST['role']);
    $user->setPassword($_POST['password']);


    if ($userRepository-> exists($user)) {
        $_SESSION['error'] = "Username or email is already taken.";
        header("Location: admin_create_user.php");
        exit();
    } elseif ($userRepository -> create($user)) {
        $_SESSION['success'] = "User created successfully!";
        header("Location: admin_users.php");
        exit();
    }



}
?>

<div class="wrapper">
    <?php include '../../navBar.php'; ?>
<main class="container mt-5">
    <div class="row">
        <?php include '../admin_panel_nav.php' ?>
        <div class="col-md-9">
            <h3>Create New User</h3>
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
            <form action="admin_create_user.php" method="post">
                <div class="form-group">
                    <label for="first_name">First Name:</label>
                    <input type="text" id="first_name" name="first_name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="last_name">Last Name:</label>
                    <input type="text" id="last_name" name="last_name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="role">Role:</label>
                    <select id="role" name="role" class="form-control" required>
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                        <option value="moderator">Moderator</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-secondary">Create User</button>
            </form>
        </div>
    </div>
</main>


<?php include '../../footer.php'; ?>

</div>
