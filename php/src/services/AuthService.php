<?php
class AuthService {
    private $authModel;
    private $blackListModel;

    public function __construct($authModel) {
        $this->authModel = $authModel;
    }

    public function login($data, $blackListModel) {
        $result =  $this->authModel->login($data);
        if(!empty($result)){
            $this->blackListModel = $blackListModel;
            $blResult = $this->blackListModel->lastToken($result['id']);

            if(!empty($result)){
                $this->blackListModel = $blackListModel;
                $data['status'] = 0;
                $data['token'] = $blResult['token'];
                $blResult = $this->blackListModel->updatetoken($data);
            }
        }
        return $result;
    }
}
?>
