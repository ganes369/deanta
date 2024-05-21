<?php
class ArticleController {
    private $articleService;

    public function __construct($articleService) {
        $this->articleService = $articleService;
    }

    public function addArticle($data, $projectModel) {
        return $this->articleService->addArticle($data, $projectModel);
    }

    public function updateArticle($data, $projectModel) {
        return $this->articleService->updateArticle($data, $projectModel);
    }

    public function listArticleById($data) {
        return $this->articleService->listArticleById($data);
    }

    public function deleteArticle($data, $projectModel) {
        return $this->articleService->deleteArticle($data, $projectModel);
    }
}
?>
