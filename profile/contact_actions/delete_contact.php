<?php
session_start();
include_once '../../database/Database_connection.php';
include_once '../../database/contact_repo/ContactRepository.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Please log in to delete a contact.";
    header("Location: ../../login_register/login.php");
    exit();
}

$contactRepository = new ContactRepository();
$contactId = isset($_GET['id']) ? intval($_GET['id']) : 0;
$contact = $contactRepository->read($contactId);

if (!$contact || $contact->getUserId() !== $_SESSION['user_id']) {
    $_SESSION['error'] = "Contact not found.";
    header("Location: ../profile.php");
    exit();
}

if ($contactRepository->delete($contactId)) {
    $_SESSION['success'] = "Contact deleted successfully.";
} else {
    $_SESSION['error'] = "Failed to delete contact.";
}

header("Location: ../profile.php");
exit();
?>