<?php
session_start();
include_once '../entities/User.php';
include_once '../database/user_repo/UserRepository.php';

$userRepository = new UserRepository();



$username = htmlspecialchars(strip_tags($_POST['username']));
$email = htmlspecialchars(strip_tags($_POST['email']));
$password = htmlspecialchars(strip_tags($_POST['password']));
$confirm_password = htmlspecialchars(strip_tags($_POST['confirm_password']));
$first_name = htmlspecialchars(strip_tags($_POST['first_name']));
$last_name = htmlspecialchars(strip_tags($_POST['last_name']));


if ($password !== $confirm_password) {
    $_SESSION['error'] = "Passwords do not match.";
    header("Location: register_page.php");
    exit();
}



if (strlen($password) < 6 || !preg_match('/[A-Z]/', $password) ||
    !preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)) {
    $_SESSION['error'] = "Password must be at least 6 characters long and must contain at
    least one uppercase letter and one special character.";
    header("Location: register_page.php");
    exit();
}

$user = new User();
$user->setUsername($username);


if ($userRepository->exists($user)) {
    $_SESSION['error'] = "Username or email is already taken.";
    header("Location: register_page.php");
    exit();
}


$user = new User();
$user->setUsername($username);
$user->setEmail($email);
$user->setPassword($password);
$user->setRole('user');
$user->setFirstName($first_name);
$user->setLastName($last_name);
try {
    if ($userRepository->create($user)) {
        $_SESSION['success'] = "Registration successful!";
        header("Location: login.php");
    } else {
        $_SESSION['error'] = "Registration failed.";
        header("Location: register_page.php");
    }
} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
    header("Location: register_page.php");
}
?>