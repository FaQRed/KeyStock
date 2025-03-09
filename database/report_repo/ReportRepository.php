<?php
include_once __DIR__ . '/../Database_connection.php';

class ReportRepository {
    private $conn;

    public function __construct() {
        $database = new DatabaseConnection();
        $this->conn = $database->getConnection();
    }

    public function getSalesReport($startDate, $endDate) {
        $query = "SELECT SUM(total_price) as total_sales, COUNT(id) as total_orders FROM orders WHERE created_at BETWEEN :start_date AND :end_date";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':start_date', $startDate);
        $stmt->bindParam(':end_date', $endDate);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getCustomerReport($startDate, $endDate) {
        $query = "SELECT COUNT(id) as total_customers FROM users WHERE created_at BETWEEN :start_date AND :end_date";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':start_date', $startDate);
        $stmt->bindParam(':end_date', $endDate);
        $stmt->execute();
        $total_customers = $stmt->fetch(PDO::FETCH_ASSOC)['total_customers'];

        $query = "SELECT COUNT(DISTINCT user_id) as active_customers FROM orders WHERE created_at BETWEEN :start_date AND :end_date";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':start_date', $startDate);
        $stmt->bindParam(':end_date', $endDate);
        $stmt->execute();
        $active_customers = $stmt->fetch(PDO::FETCH_ASSOC)['active_customers'];

        return ['total_customers' => $total_customers, 'active_customers' => $active_customers];
    }


    public function getProductReport($startDate, $endDate) {
        $query = "SELECT products.name, COUNT(order_items.id) as total_sales, SUM(order_items.quantity) as total_quantity FROM order_items 
                  JOIN products ON order_items.product_id = products.id 
                  JOIN orders ON order_items.order_id = orders.id 
                  WHERE orders.created_at BETWEEN :start_date AND :end_date 
                  GROUP BY products.id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':start_date', $startDate);
        $stmt->bindParam(':end_date', $endDate);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}


?>