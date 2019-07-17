<?php
namespace POCFW\Model;

use \POCFW\Util\Utils;

abstract class Entity implements IEntity {
    protected static $TABLE_NAME;

    public $id;

    public static function getClassName() {
        return static::class;
    }

    public static function getTableName() {
        if (!self::$TABLE_NAME) {
            $reflect = new \ReflectionClass(static::getClassName());
            self::$TABLE_NAME = Utils::decamelize($reflect->getShortName());
        }
        return self::$TABLE_NAME;
    }
}
