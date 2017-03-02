<?php
/*------------------------------
 *  Drive CRM - Functions File
 *  Author: Bret Wagner
 *------------------------------
 */

if (!defined('included')) {
    die("You cannot access this file.");
}

require('classes/user.php');
require('classes/user.php');
require('classes/databse.php');


/**
 *  Encrypt a string (password)
 *  @param string $to_encrypt string to be encrypted, usually a password
 *  @return string encrypted string
 */
function encrypt_string($to_encrypt) {
    $iv_size = mcrypt_encrypt_get__iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC);
    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
    return base64_encode($iv . mcrypt_encrypt(MCRYPT_RIJNDAEL_256, KEY, $to_encrypt, MCRYPT_MODE_CBC, $iv));
}


/**
 *  Decrypt a string (password)
 *  @param string $to_decrypt encrypted string to be decrypted
 *  @return string plain text password
 */
function decrypt_string($to_decrypt) {
    $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC);
    $to_decrypt = base64_decode($to_decrypt);
    return rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, KEY, substr($to_decrypt, $iv_size), MCRYPT_MODE_CBC, substr($to_decrypt, 0, $iv_size)));
}

