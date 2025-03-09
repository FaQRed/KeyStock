<?php
session_start();
include_once '../database/Database_connection.php';
include_once '../database/user_repo/UserRepository.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Please log in to update your profile.";
    header("Location: ../login_registration/login.php");
    exit();
}

$userRepository = new UserRepository();
$user = $userRepository->read($_SESSION['user_id']);

if (!$user) {
    $_SESSION['error'] = "User not found.";
    header("Location: profile.php");
    exit();
}

$firstName = htmlspecialchars(strip_tags($_POST['first_name']));
$lastName = htmlspecialchars(strip_tags($_POST['last_name']));

$user->setFirstName($firstName);
$user->setLastName($lastName);

try {
    if ($userRepository->update($user)) {
        $_SESSION['first_name'] = $firstName;
        $_SESSION['success'] = "Profile updated successfully.";
    } else {
        $_SESSION['error'] = "Failed to update profile.";
    }
} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
}

header("Location: profile.php");
exit();
?>