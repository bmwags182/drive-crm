<?php
/*------------------------------
 *  Drive CRM - New Client Page
 *  Author: Bret Wagner
 *------------------------------
 */

require('../includes/config.php');

$page_title = "Drive CRM Tickets";

if (!$_SESSION['userid'] || $_SESSION['userid'] == '') {
    header('Location: ' DIRADMIN . "/login.php");
    exit();
} else {
    $user = new User($_SESSION['userid']);
}

if ($user->level <=2) {
    die("You are not allowed to create clients.");
}
?>

<?php 
include('../includes/templates/header.php');
?>