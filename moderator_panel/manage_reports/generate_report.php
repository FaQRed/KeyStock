<?php
session_start();
include_once '../../database/report_repo/ReportRepository.php';

if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'moderator' && $_SESSION['role'] !== 'admin')) {
    $_SESSION['error'] = "Access denied.";
    header("Location: ../index.php");
    exit();
}

$reportType = htmlspecialchars(strip_tags($_POST['report_type']));
$startDate = htmlspecialchars(strip_tags($_POST['start_date']));
$endDate = htmlspecialchars(strip_tags($_POST['end_date']));

$reportRepository = new ReportRepository();
$reportData = [];

switch ($reportType) {
    case 'sales':
        $reportData = $reportRepository->getSalesReport($startDate, $endDate);
        break;
    case 'customers':
        $reportData = $reportRepository->getCustomerReport($startDate, $endDate);
        break;
    case 'products':
        $reportData = $reportRepository->getProductReport($startDate, $endDate);
        break;
    default:
        $_SESSION['error'] = "Invalid report type.";
        header("Location: moderator_reports.php");
        exit();
}

$_SESSION['report_data'] = $reportData;
header("Location: display_report.php?report_type=$reportType");
exit();