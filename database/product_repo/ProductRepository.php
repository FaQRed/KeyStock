<?php
include_once __DIR__ . '/ProductRepositoryInterface.php';
include_once __DIR__ . '/../Database_connection.php';
include_once __DIR__ . '/../../entities/Product.php';
class ProductRepository implements ProductRepositoryInterface
{
    private $conn;

    public function __construct()
    {
        $database = new DatabaseConnection();
        $this->conn = $database->getConnection();
    }

    public function createProductFromRow($row)
    {
        $product = new Product();
        $product->setId($row['id']);
        $product->setName($row['name']);
        $product->setImage($row['image']);
        $product->setPrice($row['price']);
        $product->setQuantity($row['quantity']);
        $product->setManufacturer($row['manufacturer']);
        $product->setWeight($row['weight']);
        $product->setDimensions($row['dimensions']);
        $product->setDescription($row['description']);
        $product->setCategoryId($row['category_id']);

        return $product;
    }

    public function create(Product $product)
    {
        $query = "INSERT INTO products (name, image, price, quantity, manufacturer,
                     weight, dimensions, description, category_id)
VALUES (:name, :image, :price, :quantity, :manufacturer, :weight, 
        :dimensions, :description, :category_id)";
        $stmt = $this->conn->prepare($query);

        $name = $product->getName();
        $image = $product->getImage();
        $price = $product->getprice();
        $quantity = $product->getQuantity();
        $manufacturer = $product->getManufacturer();
        $weight = $product->getWeight();
        $dimensions = $product->getDimensions();
        $description = $product->getDescription();
        $category_id = $product->getCategoryId();

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':image', $image);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':manufacturer', $manufacturer);
        $stmt->bindParam(':weight', $weight);
        $stmt->bindParam(':dimensions', $dimensions);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':category_id', $category_id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function read($id)
    {
        $query = "SELECT * FROM products WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return $this -> createProductFromRow($row);
        }
        return null;
    }

    public function update(Product $product)
    {
        $query = "UPDATE products SET name = :name, image = :image, 
                    price = :price, quantity = :quantity, manufacturer = :manufacturer,
                    weight = :weight, dimensions = :dimensions,
                    description = :description, category_id = :category_id WHERE id = :id";


        $stmt = $this->conn->prepare($query);

        $id = $product->getId();
        $name = $product->getName();
        $image = $product->getImage();
        $price = $product->getprice();
        $quantity = $product->getQuantity();
        $manufacturer = $product->getManufacturer();
        $weight = $product->getWeight();
        $dimensions = $product->getDimensions();
        $description = $product->getDescription();
        $category_id = $product->getCategoryId();

        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':image', $image);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':manufacturer', $manufacturer);
        $stmt->bindParam(':weight', $weight);
        $stmt->bindParam(':dimensions', $dimensions);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':category_id', $category_id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function delete($id)
    {
        $query = "DELETE FROM products WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function readAll()
    {
        $query = "SELECT * FROM products";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $products = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $products[] = $this->createProductFromRow($row);
        }
        return $products;
    }

    public function readByCategory($id)
    {
        $query = "SELECT * FROM products WHERE category_id = :category_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':category_id', $id);
        $stmt->execute();
        $products = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $products[] = $this->createProductFromRow($row);
        }
        return $products;
    }
    public function getRecentProducts() {
        $query = "SELECT * FROM products ORDER BY created_at DESC LIMIT 4";

        $result = $this->conn->query($query);
        $products = [];
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $products[] = $this->createProductFromRow($row);
        }
        return $products;
    }

    public function getProductsByCategory($category_id, $exclude_product_id = null) {
        $query = "SELECT * FROM products WHERE category_id = :category_id";
        if ($exclude_product_id) {
            $query .= " AND id != :exclude_product_id";
        }
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':category_id', $category_id);
        if ($exclude_product_id) {
            $stmt->bindParam(':exclude_product_id', $exclude_product_id);
        }
        $stmt->execute();

        $products = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $products[] = $this->createProductFromRow($row);
        }
        return $products;
    }
}