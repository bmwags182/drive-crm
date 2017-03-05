<?php
/*------------------------------
 *  Drive CRM - Client Accounts Page
 *  Author: Bret Wagner
 *------------------------------
 */

require('../includes/config.php');

$page_title = "Drive CRM Client Accounts";

if (!$_SESSION['userid'] || $_SESSION['userid'] == '') {
    header('Location: ' DIRADMIN . "/login.php");
    exit();
} else {
    $user = new User($_SESSION['userid']);
}
?>

<?php 
include('../includes/templates/header.php');
?>