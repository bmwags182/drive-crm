<?php
/*------------------------------
 *  Drive CRM - Client Notes Page
 *  Author: Bret Wagner
 *------------------------------
 */

require('../includes/config.php');

$page_title = "Drive CRM Client Notes";

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