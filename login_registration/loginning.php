<?php
session_start();
include_once '../database/user_repo/UserRepository.php';
include_once '../entities/User.php';


$userRepository = new UserRepository();


$username = htmlspecialchars(strip_tags($_POST['username']));
$password = htmlspecialchars(strip_tags($_POST['password']));
$rememberMe = isset($_POST['remember_me']);


$user = new User();
$user->setUsername($username);


$query = "SELECT * FROM users WHERE username = :username LIMIT 0,1";
$stmt = $userRepository->conn->prepare($query);
$stmt->bindParam(':username', $username);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if ($row) {
    $user = $userRepository->createUserFromRow($row);
    if (password_verify($password, $user->getPassword())) {
        $_SESSION['user_id'] = $user->getId();
        $_SESSION['email'] = $user->getEmail();
        $_SESSION['username'] = $user->getUsername();
        $_SESSION['first_name'] = $user->getFirstName();
        $_SESSION['last_name'] = $user->getLastName();
        $_SESSION['role'] = $user->getRole();
        $_SESSION['success'] = "Login successful!";


        switch ($user->getRole()) {
            case 'admin':
                header("Location: ../admin_panel/admin_panel.php");
                break;
            case 'moderator':
                header("Location: ../moderator_panel/moderator_panel.php");
                break;
            default:
                header("Location: ../index.php");
        }

    } else {
        $_SESSION['error'] = "Invalid username or password.";
        header("Location: /login_registration/login.php");
        exit();
    }
} else {
    $_SESSION['error'] = "Invalid username or password.";
    header("Location: /login_registration/login.php");
    exit();
}
