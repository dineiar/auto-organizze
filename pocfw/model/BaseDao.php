<?php
namespace POCFW\Model;

use POCFW\Util\Singleton;
use Exception;

abstract class BaseDao extends Singleton implements IDao {
    /**
     * @return \POCFW\Model\IEntity
     */
    abstract function getEntity();

    public function get($id) {
        $sql = 'SELECT * FROM ' . $this->getEntity()::getTableName() . ' WHERE id = :id';
        $sth = Database::getInstance()->prepare($sql);
        $sth->bindValue(':id', $id, \PDO::PARAM_INT);
        if ($sth->execute()) {
            $sth->setFetchMode(\PDO::FETCH_CLASS, $this->getEntity()::getClassName());
            $r = $sth->fetch();
            $sth->closeCursor();
            return $r;
        }
    }

    public function list() {
        $sql = 'SELECT * FROM ' . $this->getEntity()::getTableName();
        return Database::getInstance()->query($sql, \PDO::FETCH_CLASS, $this->getEntity()::getClassName());
    }

    public function delete($id) {
        throw new Exception();
    }
}
