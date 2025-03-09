<?php

namespace category_repo;
use PDO;

include_once __DIR__ . '/CategoryRepositoryInterface.php';
include_once __DIR__ . '/../../entities/Category.php';

class CategoryRepository implements CategoryRepositoryInterface
{

    private $connection;

    public function __construct()
    {
        $database = new \DatabaseConnection();
        $this->connection = $database -> getConnection();
    }

    public function createCategoryFromRow($row)
    {
        $category = new \Category();
        $category->setId($row['id']);
        $category->setName($row['name']);
        return $category;
    }
    public function create($category_name)
    {
        $query = "INSERT INTO categories (name) VALUES (:name)";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':name', $category_name);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function read($id)
    {
        $query = "SELECT * FROM categories WHERE id = :id";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return $this->createCategoryFromRow($row);
        }
        return null;
    }

    public function update(\Category $category)
    {
        $query = "UPDATE categories SET name = :name WHERE id = :id";
        $stmt = $this->connection->prepare($query);
        $id = $category->getId();
        $name = $category-> getName();
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function delete($id)
    {
        $query = "DELETE FROM categories WHERE id = :id";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':id', $id);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function readAll()
    {
        $query = "SELECT * FROM categories";
        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        $categories = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
           $categories[] = $this->createCategoryFromRow($row);
        }
        return $categories;
    }
    public function exists($name)
    {
        $query = "SELECT COUNT(*) as count FROM categories WHERE name = :name";

        $stmt = $this->connection->prepare($query);



        $stmt->bindParam(':name', $name);

        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row['count'] > 0) {
            return true;
        }
        return false;
    }

    public function hasProducts($categoryId)
    {
        $query = "SELECT COUNT(*) as count FROM products WHERE category_id = :category_id";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':category_id', $categoryId);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['count'] > 0;
    }
}