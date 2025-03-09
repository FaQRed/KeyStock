<?php
session_start();
include_once '../../database/Database_connection.php';
include_once '../../database/contact_repo/ContactRepository.php';
include_once '../../entities/Contact.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Please log in to add a new contact.";
    header("Location: ../../login_registration/login.php");
    exit();
}

$contactRepository = new ContactRepository();
$contact = new Contact();

$contact->setUserId($_SESSION['user_id']);
$contact->setAddress(htmlspecialchars(strip_tags($_POST['address'])));
$contact->setPhoneNumber(htmlspecialchars(strip_tags($_POST['phone_number'])));

if ($contactRepository->create($contact)) {
    $_SESSION['success'] = "Contact added successfully.";
} else {
    $_SESSION['error'] = "Failed to add contact.";
}

header("Location: ../profile.php");
exit();
?>