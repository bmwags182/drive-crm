<?php
/*------------------------------
 *  Drive CRM - New Account Page
 *  Author: Bret Wagner
 *------------------------------
 */

require('../includes/config.php');

$page_title = "Drive CRM New Account";

if (!$_SESSION['userid'] || $_SESSION['userid'] == '') {
    header('Location: ' . DIRADMIN . "/login.php");
    exit();
} else {
    $user = new User($_SESSION['userid']);
}

if ($user->level < 2) {
    die("You are not allowed to create client accounts.");
}

if (isset($_POST['new-client']) && $_POST['new-client'] != '') {

}
?>

<?php
include('../includes/templates/header.php');
?>
