<?php
include '../../header.php';

if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'moderator' && $_SESSION['role'] !== 'admin')) {
    $_SESSION['error'] = "Access denied.";
    header("Location: ../index.php");
    exit();
}

$reportData = isset($_SESSION['report_data']) ? $_SESSION['report_data'] : null;
$reportType = isset($_GET['report_type']) ? htmlspecialchars($_GET['report_type']) : '';

if (!$reportData) {
    $_SESSION['error'] = "No report data found.";
    header("Location: moderator_reports.php");
    exit();
}

?>

<div class="wrapper">
    <?php include '../../navBar.php'; ?>
    <main class="container mt-5">
        <div class="row">
            <?php include '../moderator_panel_nav.php'; ?>
            <div class="col-md-9">
        <?php
        if (isset($_SESSION['error'])) {
            echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
            unset($_SESSION['error']);
        }
        ?>
        <?php if ($reportType == 'sales'): ?>
            <h3>Sales Report</h3>
            <p><strong>Total Sales: </strong><?php echo number_format($reportData['total_sales'], 2); ?> PLN</p>
            <p><strong>Total Orders: </strong><?php echo $reportData['total_orders']; ?></p>

        <?php elseif ($reportType == 'customers'): ?>
            <h3>Customer Report</h3>
            <p><strong>Total Customers: </strong><?php echo $reportData['total_customers']; ?></p>
            <p><strong>Active Customers: </strong><?php echo $reportData['active_customers']; ?></p>

        <?php elseif ($reportType == 'products'): ?>
            <h3>Products Report</h3>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Total Sales</th>
                    <th>Total Quantity</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($reportData as $product): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($product['name']); ?></td>
                        <td><?php echo $product['total_sales']; ?></td>
                        <td><?php echo $product['total_quantity']; ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
        </div>
    </div>
    </main>
    <?php include '../../footer.php'; ?>
</div>
