<?php
include_once __DIR__ . '/UserRepositoryInterface.php';
include_once __DIR__ . '/../Database_connection.php';
include_once __DIR__ . '/../../entities/User.php';


class UserRepository implements UserRepositoryInterface
{
    public $conn;
    private $table_name = "users";

    public function __construct()
    {
        $database = new DatabaseConnection();
        $this->conn = $database->getConnection();
    }

    public function createUserFromRow($row)
    {
        $user = new User();
        $user->setId($row['id']);
        $user->setUsername($row['username']);
        $user->setEmail($row['email']);
        $user->setRole($row['role']);
        $user->setFirstName($row['first_name']);
        $user->setLastName($row['last_name']);
        $user->setPassword($row['password']);
        $user->setCreatedAt($row['created_at']);
        $user->setContactID($row['contact_id']);

        return $user;
    }

    /**
     * @throws Exception
     */
    public function create(User $user)
    {
        if ($this->exists($user)) {
            throw new Exception("Username or email is already taken.");
        }
        $query = "INSERT INTO " . $this->table_name . " SET username=:username, email=:email, password=:password, role=:role, first_name=:first_name, last_name=:last_name";

        $stmt = $this->conn->prepare($query);


        $hashed_password = password_hash($user->getPassword(), PASSWORD_DEFAULT);


        $username = $user->getUsername();
        $email = $user->getEmail();
        $role = $user->getRole();
        $first_name = $user->getFirstName();
        $last_name = $user->getLastName();

        $stmt->bindParam(":username", $username);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":password", $hashed_password);
        $stmt->bindParam(":role", $role);
        $stmt->bindParam(":first_name", $first_name);
        $stmt->bindParam(":last_name", $last_name);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function read($id)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return $this->createUserFromRow($row);
        }

        return null;
    }

    /**
     * @throws Exception
     */
    public function update(User $user)
    {
        if ($this->exists($user, $user->getId())) {
            throw new Exception("Failed to update user. This login or email is already exists");
        }

        $query = "UPDATE " . $this->table_name . " SET username = :username, 
        email = :email, first_name = :first_name, last_name = :last_name, role = :role";

        if (!empty($user->getNewPassword())) {
            $query .= ", password = :password";
        }

        $query .= " WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $id = $user->getId();
        $username = $user->getUsername();
        $email = $user->getEmail();
        $first_name = $user->getFirstName();
        $last_name = $user->getLastName();
        $role = $user->getRole();

        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":username", $username);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":first_name", $first_name);
        $stmt->bindParam(":last_name", $last_name);
        $stmt->bindParam(":role", $role);

        if (!empty($user->getNewPassword())) {
            $hashed_password = password_hash($user->getNewPassword(), PASSWORD_DEFAULT);
            $stmt->bindParam(":password", $hashed_password);
        }

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function delete($id)
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function exists(User $user, $excludeId = null)
    {
        $query = "SELECT COUNT(*) as count FROM " . $this->table_name . " WHERE (username = :username OR email = :email)";
        if ($excludeId !== null) {
            $query .= " AND id != :excludeId";
        }
        $stmt = $this->conn->prepare($query);

        $username = $user->getUsername();
        $email = $user->getEmail();

        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        if ($excludeId !== null) {
            $stmt->bindParam(':excludeId', $excludeId);
        }

        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row['count'] > 0) {
            return true;
        }
        return false;
    }

    public function readAll()
    {
        $query = "SELECT * FROM " . $this->table_name;

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $users = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $users[] = $this->createUserFromRow($row);
        }
        return $users;
    }
}