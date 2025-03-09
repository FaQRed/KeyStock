<?php
include_once __DIR__ . '/ContactRepositoryInterface.php';
include_once __DIR__ . '/../../entities/Contact.php';
class ContactRepository implements ContactRepositoryInterface
{
    private $conn;

    public function __construct()
    {
        $database = new DatabaseConnection();
        $this->conn = $database->getConnection();
    }

    public function create(Contact $contact)
    {
        $query = "INSERT INTO contacts (user_id, address, phone_number) VALUES (:user_id, :address, :phone_number)";
        $stmt = $this->conn->prepare($query);

        $userId = $contact->getUserId();
        $address = $contact->getAddress();
        $phoneNumber = $contact->getPhoneNumber();
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':phone_number', $phoneNumber);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function read($id)
    {
        $query = "SELECT * FROM contacts WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return $this->createContactFromRow($row);
        }
        return null;
    }

    public function readByUserId($userId)
    {
        $query = "SELECT * FROM contacts WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();

        $contacts = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $contacts[] = $this->createContactFromRow($row);
        }
        return $contacts;
    }

    private function createContactFromRow($row)
    {
        $contact = new Contact();
        $contact->setId($row['id']);
        $contact->setUserId($row['user_id']);
        $contact->setAddress($row['address']);
        $contact->setPhoneNumber($row['phone_number']);
        return $contact;
    }

    public function update(Contact $contact)
    {
        $query = "UPDATE contacts SET address = :address, phone_number = :phone_number WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $id = $contact->getId();
        $address = $contact->getAddress();
        $phoneNumber = $contact->getPhoneNumber();

        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':phone_number', $phoneNumber);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function delete($id)
    {
        $query = "DELETE FROM contacts WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
