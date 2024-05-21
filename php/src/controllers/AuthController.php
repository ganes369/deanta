<?php
class AuthController {
    private $authService;

    public function __construct($authService) {
        $this->authService = $authService;
    }

    public function login($data, $blacklistModel) {
        return $this->authService->login($data, $blacklistModel);
    }
}
?>
