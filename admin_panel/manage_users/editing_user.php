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


$user_id = intval($_POST['id']);
$username = htmlspecialchars(strip_tags($_POST['username']));
$email = htmlspecialchars(strip_tags($_POST['email']));
$password = htmlspecialchars(strip_tags($_POST['password']));
$first_name = htmlspecialchars(strip_tags($_POST['first_name']));
$last_name = htmlspecialchars(strip_tags($_POST['last_name']));
$role = htmlspecialchars(strip_tags($_POST['role']));


$userRepository = new UserRepository();
$user = $userRepository->read($user_id);

if (!$user) {
    $_SESSION['error'] = "User not found.";
    header("Location: admin_users.php");
    exit();
}


$user->setUsername($username);
$user->setEmail($email);
if (!empty($password)) {
    $user->setNewPassword($password);
}
$user->setFirstName($first_name);
$user->setLastName($last_name);
$user->setRole($role);


try{
    if ($userRepository->update($user)) {
        $_SESSION['success'] = "User updated successfully.";

    } else {
        $_SESSION['error'] = "Failed to update user.";
    }
}catch (Exception $e){
    $_SESSION['error'] = $e->getMessage();
}


header("Location: admin_edit_user.php?id=" . $user->getId());
exit();
?>