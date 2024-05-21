<?php
class ProjectService {
    private $projectModel;

    public function __construct($projectModel) {
        $this->projectModel = $projectModel;
    }

    public function addProject($data) {
        return $this->projectModel->addProject($data);
    }

    public function updateProject($projectId, $data) {
        return $this->projectModel->updateProject($projectId, $data);
    }
}
?>
