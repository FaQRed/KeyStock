<?php
require_once __DIR__ . '/ReviewRepositoryInterface.php';
require_once __DIR__ . '/../Database_connection.php';
require_once __DIR__ . '/../../entities/Review.php';

class ReviewRepository implements ReviewRepositoryInterface
{
    private $connection;

    public function __construct()
    {
        $database = new DatabaseConnection();
        $this->connection = $database->getConnection();
    }

    public function createReviewFromRow($row)
    {
        $review = new Review();
        $review->setId($row['id']);
        $review->setProductId($row['product_id']);
        $review->setUserId($row['user_id']);
        $review->setRating($row['rating']);
        $review->setComment($row['comment']);
        $review->setCreatedAt($row['created_at']);
        return $review;
    }

    public function create(Review $review)
    {
        $query = "INSERT INTO reviews (product_id, user_id, rating, comment) VALUES (:product_id, :user_id, :rating, :comment)";
        $stmt = $this->connection->prepare($query);

        $productId = $review->getProductId();
        $userId = $review->getUserId();
        $rating = $review->getRating();
        $comment = $review->getComment();


        $stmt->bindParam(':product_id', $productId);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':rating', $rating);
        $stmt->bindParam(':comment', $comment);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function read($id)
    {
        $query = "SELECT * FROM reviews WHERE id = :id";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return $this->createReviewFromRow($row);
        }
        return null;
    }

    public function update(Review $review)
    {
        $query = "UPDATE reviews SET rating = :rating, comment = :comment WHERE id = :id";
        $stmt = $this->connection->prepare($query);

        $id = $review->getId();
        $comment = $review->getComment();
        $rating = $review->getRating();

        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':rating', $rating);
        $stmt->bindParam(':comment', $comment);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function delete($id)
    {
        $query = "DELETE FROM reviews WHERE id = :id";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':id', $id);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function readByProductId($productId)
    {
        $query = "SELECT * FROM reviews WHERE product_id = :product_id ORDER BY created_at DESC";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':product_id', $productId);
        $stmt->execute();

        $reviews = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $reviews[] = $this->createReviewFromRow($row);
        }
        return $reviews;
    }

    public function getProductAverageRating($productId)
    {
        $query = "SELECT AVG(rating) as average_rating FROM reviews WHERE product_id = :product_id";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':product_id', $productId);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return floatval($row['average_rating']);
        } else {
            return 0.0;
        }
    }

    public function readByUserId($user_id) {
        $query = "SELECT reviews.*, products.name as product_name FROM reviews 
                  JOIN products ON reviews.product_id = products.id
                  WHERE reviews.user_id = :user_id ORDER BY reviews.created_at DESC";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

        $reviews = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $reviews[] = $this->createReviewFromRow($row);
        }
        return $reviews;
    }

}
