<?php
class ProjectModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function addProject($data) {

        $stmt = $this->conn->prepare("INSERT INTO projects (title, content, user_id) VALUES (:title, :content, :user_id)");
        $stmt->bindParam(':title', $data['title']);
        $stmt->bindParam(':content', $data['content']);
        $stmt->bindParam(':user_id', $data['userId']);
        $stmt->execute();
        return $this->conn->lastInsertId();
        
    }

    public function updateProject($projectId, $data) {
        $stmt = $this->conn->prepare("UPDATE projects SET title = :title, content = :content WHERE id = :id AND user_id = :user_id");
        $stmt->bindValue(':title', $data['title']);
        $stmt->bindValue(':content', $data['content']);
        $stmt->bindValue(':id', $projectId);
        $stmt->bindValue(':user_id', $data['userId']);
        $stmt->execute();
        return true; // ou você pode retornar algo indicando o sucesso ou falha da atualização
    }

    public function getProjectById($id) {
     
        $stmt = $this->conn->prepare("SELECT * FROM projects WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

}
?>
