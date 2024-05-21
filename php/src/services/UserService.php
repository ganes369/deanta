<?php
class UserService {
    private $userModel;
    private $blacklistModel;

    public function __construct($userModel) {
        $this->userModel = $userModel;
    }

    public function addUser($data) {
        return $this->userModel->addUser($data);
    }

    public function updateUser($id, $data, $blacklistModel) {
        $result =  $this->userModel->updateUser($id, $data);

        if($result === false) return false;

        if(!empty($data['password']) || !empty($data['email'])) {
            $this->blacklistModel = $blacklistModel;
            $data['status'] = 0;
            $project = $this->blacklistModel->updatetoken($data);
        }
        return $result;
    }
}
?>
