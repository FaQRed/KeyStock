<?php
session_start();
include_once '../../database/Database_connection.php';
include_once '../../database/contact_repo/ContactRepository.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Please log in to update your contact information.";
    header("Location: ../../login_registration/login.php");
    exit();
}

$contactRepository = new ContactRepository();
$contactId = intval($_POST['contact_id']);
$contact = $contactRepository->read($contactId);

if (!$contact || $contact->getUserId() !== $_SESSION['user_id']) {
    $_SESSION['error'] = "Contact not found.";
    header("Location: ../profile.php");
    exit();
}

$address = htmlspecialchars(strip_tags($_POST['address']));
$phoneNumber = htmlspecialchars(strip_tags($_POST['phone_number']));

$contact->setAddress($address);
$contact->setPhoneNumber($phoneNumber);

if ($contactRepository->update($contact)) {
    $_SESSION['success'] = "Contact updated successfully.";
} else {
    $_SESSION['error'] = "Failed to update contact.";
}

header("Location: ../profile.php");
exit();
?>