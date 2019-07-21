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

    /**
     * Symmetric encryption
     * 
     * @param plaintext Plain text for encryption
     * @param key Encryption key
     * @param nonce Optional nonce. If not provided we generate random bytes for it.
     * @return string Encrypted text
     */
    public static function encrypt($plaintext, $key, $nonce = null) {
        if (!$nonce) {
            $nonce = random_bytes(SODIUM_CRYPTO_AEAD_XCHACHA20POLY1305_IETF_NPUBBYTES);
        }
        // TODO Use bin2hex instead of base64?
        return strlen($nonce) . '$' . base64_encode($nonce . sodium_crypto_aead_xchacha20poly1305_ietf_encrypt($plaintext, $nonce, $nonce, md5($key)));
    }

    /**
     * Symmetric decryption of text encrypted by Security::encrypt
     * 
     * @param ciphertext Plain text for encryption
     * @param key Encryption key
     * @param nonce Optional nonce. If not provided we generate random bytes for it.
     * @return string Decrypted text
     */
    public static function decrypt($ciphertext, $key) {
        $base64Noncesize = explode('$', $ciphertext);
        $nonceSize = (int)$base64Noncesize[0];
        $bytes = base64_decode($base64Noncesize[1]);
        $nonce = substr($bytes, 0, $nonceSize);
        $cipher_bytes = substr($bytes, $nonceSize, strlen($bytes));
        return sodium_crypto_aead_xchacha20poly1305_ietf_decrypt($cipher_bytes, $nonce, $nonce, md5($key));
    }

}
