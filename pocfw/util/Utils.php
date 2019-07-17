<?php
namespace POCFW\Util;

abstract class Utils {

    public static function decamelize($input) {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $input));
    }
    
}