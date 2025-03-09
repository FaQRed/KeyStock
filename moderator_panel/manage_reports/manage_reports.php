<?php
include '../../header.php';

if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'moderator' && $_SESSION['role'] !== 'admin')) {
    $_SESSION['error'] = "Access denied.";
    header("Location: ../../index.php");
    exit();
}
?>

<div class="wrapper">
    <?php include '../../navBar.php'; ?>
    <main class="container mt-5">
        <div class="row">
            <?php include '../moderator_panel_nav.php'; ?>
            <div class="col-md-9">
                <h2>Generate Reports</h2>
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

                <form action="generate_report.php" method="post">
                    <div class="form-group">
                        <label for="reportType">Select Report Type</label>
                        <select id="reportType" name="report_type" class="form-control" required>
                            <option value="">Select...</option>
                            <option value="sales">Sales Report</option>
                            <option value="customers">Customer Report</option>
                            <option value="products">Product Report</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="startDate">Start Date</label>
                        <input type="date" id="startDate" name="start_date" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="endDate">End Date</label>
                        <input type="date" id="endDate" name="end_date" class="form-control" required>
                    </div>

                    <button type="submit" class="btn_product">Generate Report</button>
                </form>
            </div>
        </div>
    </main>
    <?php include '../../footer.php'; ?>
</div>