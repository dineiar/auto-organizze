<?php
namespace POCFW\Model;

use POCFW\Util\Singleton;

abstract class BaseDao extends Singleton implements IDao {
    /**
     * @return \POCFW\Model\IEntity
     */
    abstract function getEntity();

    public function list() {
        $sql = 'SELECT * FROM ' . $this->getEntity()::getTableName();
        return Database::getInstance()->query($sql, \PDO::FETCH_CLASS, $this->getEntity()::getClassName());
    }
}
