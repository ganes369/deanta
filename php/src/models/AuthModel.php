<?php
class AuthModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function login($data) {

        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $data['email']]);
        $user = $stmt->fetch();

        if ($user && password_verify($data['password'], $user['password'])) {
            return ['id' => $user['id'], 'email' => $user['email']];
        } else {
            return "invalid email or password";
        }
    }
}
?>
