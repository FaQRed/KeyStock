<?php

include '../../header.php';
include_once '../../database/Database_connection.php';
include_once '../../entities/User.php';
include_once '../../database/user_repo/UserRepository.php';


if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    $_SESSION['error'] = "Access denied.";
    header("Location: ../index.php");
    exit();
}

$userRepository = new UserRepository();

$users = $userRepository -> readAll();
?>

<div class="wrapper">
    <?php include '../../navBar.php'; ?>
    <main class="container mt-5">
        <div class="row">
            <?php include '../admin_panel_nav.php' ?>
            <div class="col-md-9">
                <h3>Manage Users</h3>
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
                <a href="admin_create_user.php" class="btn" style="background-color: #86155a; color: white; margin-bottom: 5px">Create New
                    User</a>
                <label for="searchInput"></label>
                <input class="form-control" id="searchInput" type="text" style="margin-bottom: 15px;" placeholder="Search for users...">
                <table class="table table-bordered mt-3">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody id="usersTable">
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user->getId()); ?></td>
                            <td><?php echo htmlspecialchars($user->getUsername()); ?></td>
                            <td><?php echo htmlspecialchars($user->getEmail()); ?></td>
                            <td><?php echo htmlspecialchars($user->getFirstName()); ?></td>
                            <td><?php echo htmlspecialchars($user->getLastName()); ?></td>
                            <td><?php echo htmlspecialchars($user->getRole()); ?></td>
                            <td class="action_table">
                                <a href="admin_edit_user.php?id=<?php echo $user -> getID(); ?>" class="btn btn-sm"
                                   style="background-color: #86155a; color: white; margin-bottom: 10px  ">Edit</a>
                                <a href="admin_delete_user.php?id=<?php echo $user-> getID(); ?>" class="btn btn-sm "
                                   style="background-color: #86155a; color: white; margin-bottom: 10px"
                                   onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>

            </div>
        </div>
    </main>

    <script>
        document.getElementById('searchInput').addEventListener('keyup', function() {
            let input, filter, table, tr, td, i, j, txtValue;
            input = document.getElementById('searchInput');
            filter = input.value.toUpperCase();
            table = document.getElementById('usersTable');
            tr = table.getElementsByTagName('tr');

            for (i = 0; i < tr.length; i++) {
                tr[i].style.display = 'none';
                td = tr[i].getElementsByTagName('td');
                for (j = 0; j < td.length; j++) {
                    if (td[j]) {
                        txtValue = td[j].textContent;
                        if (txtValue.toUpperCase().indexOf(filter) > -1) {
                            tr[i].style.display = '';
                            break;
                        }
                    }
                }
            }
        });
    </script>

<?php include '../../footer.php'; ?>

</div>
