<?php
namespace POCFW\Model;

interface IEntity {
    public static function getClassName();
    static public function getTableName();
}
