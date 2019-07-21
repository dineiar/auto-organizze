<?php
namespace POCFW\Model;

use \POCFW\Util\Singleton;

class Database extends Singleton {

    protected $conn;

    protected function __construct() {
        $cfg = CONFIG['database'];
        $this->conn = new \PDO($cfg['driver'].':host='.$cfg['host'].';dbname='.$cfg['db'], $cfg['user'], $cfg['pass']);
    }

    public function __destruct() { 
        $this->conn = null;
    }

    public function __call($name, $arguments) {
        return call_user_func_array(array($this->conn, $name), $arguments);
    }

}
