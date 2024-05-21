<?php
class UserController {
    private $userService;

    public function __construct($userService) {
        $this->userService = $userService;
    }

    public function addUser($data) {
        return $this->userService->addUser($data);
    }

    public function updateUser($id, $data, $blacklistModel) {
        return $this->userService->updateUser($id, $data, $blacklistModel);
    }
}
?>
