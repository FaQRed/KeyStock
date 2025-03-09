<?php
session_start();
include_once 'database/Database_connection.php';
include_once 'database/user_repo/UserRepository.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Please log in to change your password.";
    header("Location: login.php");
    exit();
}

$userRepository = new UserRepository();
$user = $userRepository->read($_SESSION['user_id']);

if (!$user) {
    $_SESSION['error'] = "User not found.";
    header("Location: profile.php");
    exit();
}

$currentPassword = htmlspecialchars(strip_tags($_POST['current_password']));
$newPassword = htmlspecialchars(strip_tags($_POST['new_password']));
$confirmNewPassword = htmlspecialchars(strip_tags($_POST['confirm_new_password']));

if (!password_verify($currentPassword, $user->getPassword())) {
    $_SESSION['error'] = "Current password is incorrect.";
    header("Location: profile.php");
    exit();
}

if ($newPassword !== $confirmNewPassword) {
    $_SESSION['error'] = "New passwords do not match.";
    header("Location: profile.php");
    exit();
}
if (strlen($newPassword) < 6 || !preg_match('/[A-Z]/', $newPassword) ||
    !preg_match('/[!@#$%^&*(),.?":{}|<>]/', $newPassword)) {
    $_SESSION['error'] = "Password must be at least 6 characters long and must contain at
    least one uppercase letter and one special character.";
    header("Location: profile.php");
    exit();
}

$user->setNewPassword($newPassword);
try {
    if ($userRepository->update($user)) {
        $_SESSION['success'] = "Password changed successfully.";
    } else {
        $_SESSION['error'] = "Failed to change password.";
    }
} catch (Exception $e){
    $_SESSION['error'] = $e->getMessage();
}


header("Location: profile.php");
exit();
?>