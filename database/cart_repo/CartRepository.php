<?php
include_once __DIR__ . '/../Database_connection.php';
include_once __DIR__ . '/../../entities/Cart.php';
include_once __DIR__ . '/../../entities/CartItem.php';
include_once __DIR__ . '/CartRepositoryInterface.php';

class CartRepository implements \cart_repo\CartRepositoryInterface
{
    private $conn;

    public function __construct()
    {
        $database = new DatabaseConnection();
        $this->conn = $database->getConnection();
    }

    public function getCartByUserId($user_id)
    {
        $query = "SELECT * FROM carts WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

        $cart = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($cart) {
            return new Cart($cart['id'], $cart['user_id'], $cart['created_at']);
        }

        return null;
    }

    public function createCart($user_id)
    {
        $query = "INSERT INTO carts (user_id) VALUES (:user_id)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);

        if ($stmt->execute()) {
            return $this->getCartByUserId($user_id);
        }

        return null;
    }

    public function getItemsByCartId($cart_id)
    {
        $query = "SELECT cart_items.*, products.name, products.price FROM cart_items
                  JOIN products ON cart_items.product_id = products.id
                  WHERE cart_items.cart_id = :cart_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':cart_id', $cart_id);
        $stmt->execute();

        $items = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $item = new CartItem($row['id'], $row['cart_id'], $row['product_id'], $row['quantity']);
            $item->setProductName($row['name']);
            $item->setProductPrice($row['price']);
            $items[] = $item;
        }

        return $items;
    }

    public function addItemToCart($cart_id, $product_id, $quantity)
    {
        $query = "SELECT id, quantity FROM cart_items WHERE cart_id = :cart_id AND product_id = :product_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':cart_id', $cart_id);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->execute();
        $existing_item = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existing_item) {

            $new_quantity = $existing_item['quantity'] + $quantity;
            $query = "UPDATE cart_items SET quantity = :quantity WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':quantity', $new_quantity);
            $stmt->bindParam(':id', $existing_item['id']);
        } else {
            $query = "INSERT INTO cart_items (cart_id, product_id, quantity) VALUES (:cart_id, :product_id, :quantity)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':cart_id', $cart_id);
            $stmt->bindParam(':product_id', $product_id);
            $stmt->bindParam(':quantity', $quantity);
        }

        return $stmt->execute();


    }


    public function updateCartItemQuantity($cart_item_id, $quantity)
    {
        $query = "UPDATE cart_items SET quantity = :quantity WHERE id = :cart_item_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':cart_item_id', $cart_item_id);
        $stmt->bindParam(':quantity', $quantity);

        return $stmt->execute();
    }

    public function removeItemFromCart($cart_item_id)
    {
        $query = "DELETE FROM cart_items WHERE id = :cart_item_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':cart_item_id', $cart_item_id);

        return $stmt->execute();
    }

    public function clearCart($cart_id)
    {
        $query = "DELETE FROM cart_items WHERE cart_id = :cart_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':cart_id', $cart_id);

        return $stmt->execute();
    }

    public function getItemById($cart_item_id)
    {
        $query = "SELECT * FROM cart_items WHERE id = :cart_item_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':cart_item_id', $cart_item_id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $item = new CartItem($row['id'], $row['cart_id'], $row['product_id'], $row['quantity']);
            return $item;
        }

        return null;
    }

    public function getCartIdByUserId($user_id)
    {
        $query = "SELECT id FROM carts WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return isset($row['id']) ? $row['id'] : null;
    }

    public function getCartItemCount($cart_id)
    {
        $query = "SELECT SUM(quantity) as item_count FROM cart_items WHERE cart_id = :cart_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':cart_id', $cart_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return isset($row['item_count']) ? $row['item_count'] : 0;
    }
}