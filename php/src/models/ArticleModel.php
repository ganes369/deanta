<?php
class ArticleModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function addArticle($data) {

        $stmt = $this->conn->prepare("INSERT INTO articles (title, descriptions, content, project_id) VALUES (:title, :descriptions, :content, :project_id)");
        $stmt->bindParam(':title', $data['title']);
        $stmt->bindParam(':descriptions', $data['descriptions']);
        $stmt->bindParam(':content', $data['content']);
        $stmt->bindParam(':project_id', $data['project_id']);
        $stmt->execute();
        $insertedId = $this->conn->lastInsertId();

        return ['id' => $insertedId, 'project_id' => $data['project_id'], 'content' => $data['content'], 'descriptions' => $data['descriptions'], 'title' => $data['title']];       
        
    }

    public function updateArticle($data) {
        $stmt = $this->conn->prepare("UPDATE articles SET title = :title, descriptions = :descriptions, content = :content WHERE id = :id");
        $stmt->bindValue(':title', $data['title']);
        $stmt->bindValue(':descriptions', $data['descriptions']);
        $stmt->bindValue(':content', $data['content']);
        $stmt->bindValue(':id', $data['articleId']);
        $stmt->execute();
        return true; // ou você pode retornar algo indicando o sucesso ou falha da atualização
    }

    public function listArticleById($data){
        $stmt = $this->conn->prepare("SELECT * FROM articles WHERE id = :id");
        $stmt->bindParam(':id', $data['id']);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function deleteArticle($data) {
        $stmt = $this->conn->prepare("DELETE FROM articles WHERE id = :id");
        $stmt->bindValue(':id', $data['id'], PDO::PARAM_INT); // Ensure ID is bound as an integer
        $stmt->execute();
    
        // Optionally return a boolean indicating success or failure
        return $stmt->rowCount() > 0; // Returns true if a row was deleted, false otherwise
      }
}
?>
