<?php
class BlacklistController {
    private $blacklist;

    public function __construct($blacklist) {
        $this->blacklist = $blacklist;
    }

    public function addtoken($data) {
        return  $this->blacklist->addtoken($data);
    }

    public function verifytoken($data) {
 
        return $this->blacklist->verifytoken($data);
    }
}
?>
