<?php
session_start();
include_once '../../database/Database_connection.php';
include_once '../../entities/User.php';
include_once '../../database/user_repo/UserRepository.php';


if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    $_SESSION['error'] = "Access denied.";
    header("Location: ../../index.php");
    exit();
}


$user_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$userRepository = new UserRepository();

if ($user_id > 0 && $userRepository -> read($user_id) !== null ) {

    if ($userRepository->delete($user_id)) {
        $_SESSION['success'] = "User deleted successfully.";
    } else {
        $_SESSION['error'] = "Failed to delete user.";
    }
} else {
    $_SESSION['error'] = "Invalid user ID.";
}

header("Location: admin_users.php");
exit();
?>