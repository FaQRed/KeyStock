<?php
include_once __DIR__ . '/../Database_connection.php';
include_once __DIR__ . '/../../entities/PromoCode.php';
include_once __DIR__ . '/PromoCodeRepositoryInterface.php';

class PromoCodeRepository implements \promoCode_repo\PromoCodeRepositoryInterface {
    private $conn;

    public function __construct() {
        $database = new DatabaseConnection();
        $this->conn = $database->getConnection();
    }

    private function createPromoCodeFromRow($row) {
        $promoCode = new PromoCode();
        $promoCode->setId($row['id']);
        $promoCode->setCode($row['code']);
        $promoCode->setDiscount($row['discount']);
        $promoCode->setValidUntil($row['valid_until']);
        return $promoCode;
    }
    public function create(PromoCode $promoCode) {
        $query = "INSERT INTO promo_codes (code, discount, valid_until) VALUES (:code, :discount, :valid_until)";
        $stmt = $this->conn->prepare($query);

        $code = $promoCode->getCode();
        $discount = $promoCode->getDiscount();
        $validUntil = $promoCode->getValidUntil();

        $stmt->bindParam(':code', $code);
        $stmt->bindParam(':discount', $discount);
        $stmt->bindParam(':valid_until', $validUntil);

        return $stmt->execute();
    }

    public function getAllPromoCodes() {
        $query = "SELECT * FROM promo_codes";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $promoCodes = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $promoCodes[] = $this->createPromoCodeFromRow($row);
        }
        return $promoCodes;
    }
    public function getPromoCodeById($id) {
        $query = "SELECT * FROM promo_codes WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $this->createPromoCodeFromRow($row) : null;
    }
    public function update(PromoCode $promoCode) {
        $query = "UPDATE promo_codes SET code = :code, discount = :discount, valid_until = :valid_until WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $id = $promoCode->getId();
        $code = $promoCode->getCode();
        $discount = $promoCode->getDiscount();
        $validUntil = $promoCode->getValidUntil();

        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':code', $code);
        $stmt->bindParam(':discount', $discount);
        $stmt->bindParam(':valid_until', $validUntil);

        return $stmt->execute();
    }
    public function delete($id) {
        $query = "DELETE FROM promo_codes WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }

    public function getPromoCode($code) {
        $query = "SELECT * FROM promo_codes WHERE code = :code AND valid_until >= CURDATE()";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':code', $code);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return $this->createPromoCodeFromRow($row);
        }

        return null;
    }


}
?>