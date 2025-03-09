<?php
include_once __DIR__ . '/../Database_connection.php';
include_once __DIR__ . '/../../entities/Order.php';
include_once __DIR__ . '/../../entities/CartItem.php';
include_once __DIR__ . '/../../entities/OrderItem.php';
include_once __DIR__ . '/OrderRepositoryInterface.php';

class OrderRepository implements \order_repo\OrderRepositoryInterface {
    private $conn;

    public function __construct() {
        $database = new DatabaseConnection();
        $this->conn = $database->getConnection();
    }

    private function createOrderFromRow($row) {
        return new Order(
            $row['id'],
            $row['cart_id'],
            $row['user_id'],
            $row['status'],
            $row['created_at'],
            $row['updated_at'],
            $row['total_price']
        );
    }
    public function createOrder($cart_id, $user_id, $totalPriceAfterDiscounts, $status = 'Awaiting Payment') {
        $query = "INSERT INTO orders (cart_id, user_id, status, total_price) VALUES (:cart_id, :user_id, :status, :total_price)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':cart_id', $cart_id);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':total_price', $totalPriceAfterDiscounts);

        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }

        return false;
    }

    public function getOrderById($order_id) {
        $query = "SELECT * FROM orders WHERE id = :order_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':order_id', $order_id);
        $stmt->execute();

        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($order) {
            return new Order($order['id'], $order['cart_id'], $order['user_id'], $order['status'], $order['created_at'], $order['updated_at'], $order['total_price']);
        }

        return null;
    }

    public function getOrderItems($order_id) {
        $query = "SELECT order_items.*, products.name FROM order_items
                  JOIN products ON order_items.product_id = products.id
                  WHERE order_items.order_id = :order_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':order_id', $order_id);
        $stmt->execute();

        $items = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $item = new OrderItem($row['id'], $row['order_id'], $row['product_id'], $row['quantity'], $row['price']);
            $item->setProductName($row['name']);
            $items[] = $item;
        }

        return $items;
    }

    public function getOrdersByUserId($user_id) {
        $query = "SELECT * FROM orders WHERE user_id = :user_id ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

        $orders = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $orders[] = $this->createOrderFromRow($row);
        }

        return $orders;
    }


    public function createOrderItem($order_id, $product_id, $quantity, $price) {
        $query = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (:order_id, :product_id, :quantity, :price)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':order_id', $order_id);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':price', $price);

        return $stmt->execute();
    }
    public function getAllOrders() {
        $query = "SELECT * FROM orders";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $orders = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $orders[] = $this->createOrderFromRow($row);
        }
        return $orders;
    }

    public function updateStatus($order_id, $status) {
        $query = "UPDATE orders SET status = :status, updated_at = NOW() WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $order_id);
        return $stmt->execute();
    }

    public function getOrdersByStatus($status) {
        $query = "SELECT * FROM orders WHERE status = :status";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->execute();

        $orders = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $orders[] = $this->createOrderFromRow($row);
        }
        return $orders;
    }
    public function getOrdersByDateRange($startDate, $endDate) {
        $query = "SELECT * FROM orders WHERE created_at BETWEEN :start_date AND :end_date";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':start_date', $startDate);
        $stmt->bindParam(':end_date', $endDate);
        $stmt->execute();

        $orders = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $orders[] = $this->createOrderFromRow($row);
        }
        return $orders;
    }


}