<?php
include '../header.php';
include_once '../database/Database_connection.php';
include_once '../database/user_repo/UserRepository.php';
include_once '../database/contact_repo/ContactRepository.php';


if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Please log in to access your profile.";
    header("Location: ../login_registration/login.php");
    exit();
}

$userRepository = new UserRepository();
$contactRepository = new ContactRepository();
$user = $userRepository->read($_SESSION['user_id']);
$contacts = $contactRepository->readByUserId($_SESSION['user_id']);

if (!$user) {
    $_SESSION['error'] = "User not found.";
    header("Location: ../index.php");
    exit();
}
?>
<div class="wrapper">
    <?php include '../navBar.php' ?>
    <main class="container mt-5">
        <div class="row">
            <div class="col-md-3">
                <?php include 'user_nav_bar.php'; ?>
            </div>
            <div class="col-md-9">
                <h3>Profile</h3>
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
                <h4>Personal Information</h4>
                <form action="update_profile.php" method="post">
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" id="username" name="username" class="form-control"
                               value="<?php echo htmlspecialchars($user->getUsername()); ?>" disabled>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" class="form-control"
                               value="<?php echo htmlspecialchars($user->getEmail()); ?>" disabled>
                    </div>
                    <div class="form-group">
                        <label for="first_name">First Name:</label>
                        <input type="text" id="first_name" name="first_name" class="form-control"
                               value="<?php echo htmlspecialchars($user->getFirstName()); ?>">
                    </div>
                    <div class="form-group">
                        <label for="last_name">Last Name:</label>
                        <input type="text" id="last_name" name="last_name" class="form-control"
                               value="<?php echo htmlspecialchars($user->getLastName()); ?>">
                    </div>
                    <button type="submit" class="btn" style="background-color: #86155a; color: white; margin-bottom: 10px">Update Profile</button>
                </form>

                <h4 class="mt-4">Contact Information</h4>
                <?php foreach ($contacts as $contact): ?>
                    <form action="contact_actions/update_contact.php" method="post" class="mb-3">
                        <input type="hidden" name="contact_id" value="<?php echo $contact->getId(); ?>">
                        <div class="form-group">
                            <label for="address">Address:</label>
                            <input type="text" id="address" name="address" class="form-control"
                                   value="<?php echo htmlspecialchars($contact->getAddress()); ?>">
                        </div>
                        <div class="form-group">
                            <label for="phone_number">Phone Number:</label>
                            <input type="text" id="phone_number" name="phone_number" class="form-control"
                                   value="<?php echo htmlspecialchars($contact->getPhoneNumber()); ?>">
                        </div>
                        <button type="submit" class="btn" style="background-color: #86155a; color: white; margin-bottom: 10px">Update Contact</button>
                        <a href="contact_actions/delete_contact.php?id=<?php echo $contact->getId(); ?>" class="btn" style="background-color: #86155a; color: white; margin-bottom: 10px"
                           onclick="return confirm('Are you sure you want to delete this contact?');">Delete Contact</a>
                    </form>
                <?php endforeach; ?>
                <form action="contact_actions/add_contact.php" method="post">
                    <div class="form-group">
                        <label for="new_address">New Address:</label>
                        <input type="text" id="new_address" name="address" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="new_phone_number">New Phone Number:</label>
                        <input type="text" id="new_phone_number" name="phone_number" class="form-control">
                    </div>
                    <button type="submit" class="btn " style="background-color: #86155a; color: white; margin-bottom: 10px">Add Contact</button>
                </form>

                <h4 class="mt-4">Change Password</h4>
                <form action="process_change_password.php" method="post">
                    <div class="form-group">
                        <label for="current_password">Current Password:</label>
                        <input type="password" id="current_password" name="current_password" class="form-control"
                               required>
                    </div>
                    <div class="form-group">
                        <label for="new_password">New Password:</label>
                        <input type="password" id="new_password" name="new_password" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="confirm_new_password">Confirm New Password:</label>
                        <input type="password" id="confirm_new_password" name="confirm_new_password"
                               class="form-control" required>
                    </div>
                    <button type="submit" class="btn" style="background-color: #86155a; color: white; margin-bottom: 10px">Change Password</button>
                </form>
            </div>
        </div>
    </main>

    <?php include '../footer.php'; ?>
</div>
