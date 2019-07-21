<?php
namespace AutoOrganizze\Model\Entity;

use \POCFW\Model\Entity;
use \POCFW\Util\Security;

class Account extends Entity {
    public $email;
    public $api_key;
    public $password;

    /**
     * @return boolean
     */
    public function updatePassword($oldPassword, $newPassword) {
        if (!Security::checkPassword($this->password, $oldPassword)) {
            return false;
        }
        if ($this->api_key) {
            $api_key = Security::decrypt($this->api_key, $oldPassword);
            if (!$api_key) {
                return false;
            }
            $this->api_key = Security::encrypt($api_key, $newPassword);
        }
        $this->password = Security::hashPassword($newPassword);
        return true;
    }
}
