<?php
class BlacklistService {
    private $blacklistModel;

    public function __construct($blacklistModel) {
        $this->blacklistModel = $blacklistModel;
    }

    public function addtoken($data) {
        return $this->blacklistModel->addtoken($data);
    }

    public function verifytoken($data) {
        return $this->blacklistModel->verifytoken($data);
        
    }
}
?>
