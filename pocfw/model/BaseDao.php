<?php
namespace POCFW\Model;

use POCFW\Util\Singleton;

abstract class BaseDao extends Singleton implements IDao {
    /**
     * @return IEntity
     */
    abstract function getEntity();

    /**
     * Fetches a single entity from the database
     * @param int $id Id of the entity to be fetched
     * @return IEntity
     */
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

    /**
     * Fetches a list of entities from the database
     * @return PDOStatement
     */
    public function list() {
        $sql = 'SELECT * FROM ' . $this->getEntity()::getTableName();
        return Database::getInstance()->query($sql, \PDO::FETCH_CLASS, $this->getEntity()::getClassName());
    }

    /**
     * Delete a single entity from the database
     * @param int $id Id of the entity to be deleted
     * @return int Number of deleted entities: 1 if succeded, 0 if failed
     */
    public function delete($id) {
        $sql = 'DELETE FROM ' . $this->getEntity()::getTableName() . ' WHERE id = :id';
        $sth = Database::getInstance()->prepare($sql);
        $sth->bindValue(':id', $id, \PDO::PARAM_INT);
        if ($sth->execute()) {
            $r = $sth->rowCount();
            $sth->closeCursor();
            return $r;
        }
        return 0;
    }

    /**
     * Persist an entity in the database
     * @param IEntity $entity The entity to be persisted
     * @return int Id of the inserted entity, which is also filled in $entity->id
     */
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

    /**
     * Save changes in an entity in the database
     * @param IEntity $entity The changed entity
     * @return int Number of affected entities: 1 if succeded, 0 if failed
     */
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

    /**
     * List database fields of the entity
     * @return array Array of field names
     */
    public function listFields() {
        $reflect = new \ReflectionClass($this->getEntity()::getClassName());
        $public_properties = array_filter($reflect->getProperties(\ReflectionProperty::IS_PUBLIC), function($prop) {
            return !$prop->isStatic() && $prop->getName() != 'id';
        });
        return array_map(function($prop) { return $prop->getName(); }, $public_properties);
    }
}
