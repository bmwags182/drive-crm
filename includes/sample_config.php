<?php
/*
    In the beginning, there was nothing,
    the screen was dark.
    
    Dev grew bored with this darkness
    Before long Dev decided to create
    
    On the first day Dev created the databse
    with it, the tables, and columns too
    
    On the second day, Dev created the config
    within the config were the GLOBALS and KEYS

    On the third day, Dev created the classes
    each class needed much love and attention.

    On the fourth day, Dev created the methods
    expanding the classes meant better systems

    And on the fifth day, Dev rested for he was done
    These things came together to create the backend
    upon which everything is built.

    Remember to thank your Dev 
    and buy him a beer
 */

/**
 *  Drive CRM
 *  @author Bret Wagner <bwagner@drivestl.com>
 *  @version 0.1.0 
 *   
 */
session_start();

// db connection properties
define('DBHOST', /* HOST */);
define('DBNAME', /* DATABASE */);
define('DBUSER', /* USERNAME */);
define('DBPASS', /* PASSWORD */);

// Create key for encryption
define('KEY', pack('H*', bin2hex(openssl_random_pseudo_bytes(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC)))));

// Define site path
define('DIR', /* Enter homepage for site here */);
// Define admin path
define('DIRADMIN', /* Enter admin url here */);
// Define site title
define('SITETITLE', /* Enter title for your site */);

// Define include checker
define('included', 1);
include('functions.php');
