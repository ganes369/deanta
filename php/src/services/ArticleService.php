<?php
class ArticleService {
    private $articleModel;
    private $projectModel;

    public function __construct($articleModel) {
        $this->articleModel = $articleModel;
    }

    public function addArticle($data, $projectModel) {
        $this->projectModel = $projectModel;
        $project = $this->projectModel->getProjectById($data['project_id']);

        if($project !== false && $project['user_id'] == $data['userId']){
            return $this->articleModel->addArticle($data);
        }
   
        return $project;
    }

    public function updateArticle($data, $projectModel) {
        $this->projectModel = $projectModel;
        $project = $this->projectModel->getProjectById($data['project_id']);

        if($project !== false && $project['user_id'] == $data['userId']){
            return  $this->articleModel->updateArticle($data);
        }
   
        return $project;
    }

    public function listArticleById($data) {
        return $this->articleModel->listArticleById($data);
    }

    public function deleteArticle($data, $projectModel) {
        $article = $this->articleModel->listArticleById($data);

        if($article == false) return $article;
        
        $this->projectModel = $projectModel;
        $project = $this->projectModel->getProjectById($article['project_id']);

        if($project !== false && $project['user_id'] == $data['userId']){
            return  $this->articleModel->deleteArticle($data);
        }

        return $project;
    }
}
?>
