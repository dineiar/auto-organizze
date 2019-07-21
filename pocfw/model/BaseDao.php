<?php
namespace POCFW\Model;

use POCFW\Util\Singleton;

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
        $sql = 'DELETE FROM ' . $this->getEntity()::getTableName() . ' WHERE id = :id';
        $sth = Database::getInstance()->prepare($sql);
        $sth->bindValue(':id', $id, \PDO::PARAM_INT);
        if ($sth->execute()) {
            $r = $sth->rowCount();
            $sth->closeCursor();
            return $r;
        }
    }

    public function insert($entity) {
        $fields = $this->listFields();
        $sql = 'INSERT INTO ' . $this->getEntity()::getTableName() . '(' . implode(',', $fields) . ') VALUES (';
        foreach($fields as $f) {
            $sql .= ':' . $f . ',';
        }
        $sql = substr($sql, 0, -1);
        $sql .= ')';

        $sth = Database::getInstance()->prepare($sql);
        foreach($fields as $f) {
            $sth->bindValue(':' . $f, $entity->$f);
        }
        if ($sth->execute()) {
            $sth->closeCursor();
            $entity->id = Database::getInstance()->lastInsertId();
            return $entity->id;
        }
    }

    public function update($entity) {
        $fields = $this->listFields();
        $sql = 'UPDATE ' . $this->getEntity()::getTableName() . ' SET ';
        foreach($fields as $f) {
            $sql .= $f . '=:' . $f . ',';
        }
        $sql = substr($sql, 0, -1);
        $sql .= ' WHERE id = :id';

        $sth = Database::getInstance()->prepare($sql);
        foreach($fields as $f) {
            $sth->bindValue(':' . $f, $entity->$f);
        }
        $sth->bindValue(':id', $entity->id);
        if ($sth->execute()) {
            $r = $sth->rowCount();
            $sth->closeCursor();
            return $r;
        }
    }

    public function listFields() {
        $reflect = new \ReflectionClass($this->getEntity()::getClassName());
        $public_properties = array_filter($reflect->getProperties(\ReflectionProperty::IS_PUBLIC), function($prop) {
            return !$prop->isStatic() && $prop->getName() != 'id';
        });
        return array_map(function($prop) { return $prop->getName(); }, $public_properties);
    }
}
