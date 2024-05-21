<?php
class BlacklistModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function addtoken($data) {
 
        $stmt = $this->conn->prepare("INSERT INTO blacklist (token, status, user_id) VALUES (:token, :status, :user_id)");
        $stmt->bindParam(':token', $data['token']);
        $stmt->bindParam(':status', $data['status']);
        $stmt->bindParam(':user_id', $data['userId']);
        $stmt->execute();

        $id = $this->conn->lastInsertId();
        return $id;
    }

    public function verifytoken($token) {
 
        $stmt = $this->conn->prepare("SELECT * FROM blacklist WHERE token = :token AND status = 1");
        $stmt->bindParam(':token', $token);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updatetoken($data){
        $stmt = $this->conn->prepare("UPDATE blacklist SET status = :status WHERE  token = :token");
        $stmt->bindValue(':status', $data['status']);
        $stmt->bindValue(':token', $data['token']);
        $stmt->execute();

        return true;
    }

    public function lastToken($data) {
        $stmt = $this->conn->prepare("SELECT * FROM blacklist WHERE user_id = :user_id ORDER BY id DESC LIMIT 1");
        $stmt->bindParam(':user_id', $data);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
