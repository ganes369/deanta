<?php
class UserModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function addUser($data) {
 
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

 
        $stmt = $this->conn->prepare("INSERT INTO users (email, password) VALUES (:email, :password)");
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->execute();

        $userId = $this->conn->lastInsertId();
        $email = $data['email'];
        return ['id' => $userId, 'email' => $email];
    }

    public function updateUser($id, $data) {
        $sql = "UPDATE users SET email = :email";
        $params = [':email' => $data['email']];

        if (!empty($data['password'])) {
            $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
            $sql .= ", password = :password";
            $params[':password'] = $hashedPassword;
        }

        $sql .= " WHERE id = :id";
        $params[':id'] = $id;

        $stmt = $this->conn->prepare($sql);


        $stmt->execute($params);

 
        return $this->getUserById($id);
    }

    private function getUserById($id) {
     
        $stmt = $this->conn->prepare("SELECT id, email FROM users WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
