<?php

namespace POCFW\Util;

/**
 * Base Singleton implementation
 * @author danbettles@yahoo.co.uk ¶
 * @link https://www.php.net/manual/en/function.get-called-class.php#86231
 */
abstract class Singleton {

    protected function __construct() { }

    final public static function getInstance() {
        static $aoInstance = array();

        $calledClassName = static::class;

        if (!isset($aoInstance[$calledClassName])) {
            $aoInstance[$calledClassName] = new $calledClassName();
        }
        return $aoInstance[$calledClassName];
    }

    final private function __clone() { }
}
