<?php
namespace POCFW\Util;

abstract class Security {

    public static function hashPassword($password) {
        return sodium_crypto_pwhash_str(
            $password, 
            SODIUM_CRYPTO_PWHASH_OPSLIMIT_INTERACTIVE,
            SODIUM_CRYPTO_PWHASH_MEMLIMIT_INTERACTIVE
        );
    }

    public static function checkPassword($hashedPassword, $password) {
        return sodium_crypto_pwhash_str_verify($hashedPassword, $password);
    }

    // public static function 

}
