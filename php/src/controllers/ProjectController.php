<?php
class ProjectController {
    private $projectService;

    public function __construct($projectService) {
        $this->projectService = $projectService;
    }

    public function addProject($data) {
        return $this->projectService->addProject($data);
    }

    public function updateProject($projectId, $data) {
        return $this->projectService->updateProject($projectId, $data);
    }
}
?>