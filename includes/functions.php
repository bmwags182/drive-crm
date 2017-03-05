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
require('classes/databse.php');
require('classes/client.php');
require('classes/account.php');
require('classes/note.php');
require('classes/ticket-note.php');
require('classes/ticket.php');


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


function log_access($user, $access, $client = null, $account = null) {
    /*
        This will be written later to help monitor user access to data.
        When a user accesses client data, client accounts, or account passwords
        their access will be logged to a file for admin review.
     */
}


function log_error($user, $access, $client = null, $account = null) {
    /*
        This will be written later to log any inappropriate acceess attempts.
        Any inappropriate attempts should also alert an admin for investigation.
     */
}


function alert_admins($subject, $message) {
    /*
        Get the admin emails from the database and alert them of an issue

        Loop through the result_set and send mail for each row (read: admin)
     */
}


function original_user($email, $username) {
    /*
        This will be written later to check for pre-existing users.
        If the username or email already exists in the databse this
        function will return false.
     */
}