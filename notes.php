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

if ((!$_GET['id'] || $_GET['id'] == '') && (!$_GET['clientid'] || $_GET['clientid'] == '')) {
    die("No Client or Note Selected.");
} elseif ($_GET['clientid'] > 0 && is_int($_GET['clientid'])) {
    $client = new Client($_GET['clientid']);
} elseif ($_GET['id'] > 0 && is_int($_GET['id'])) {
    $note = new Note($_GET['id']);
} else {
    die("Undefined Error");
}
?>

<?php 
include('../includes/templates/header.php');
?>