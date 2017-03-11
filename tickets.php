<?php
/*------------------------------
 *  Drive CRM - Tickets Page
 *  Author: Bret Wagner
 *------------------------------
 */

require('includes/config.php');

$page_title = "Drive CRM Tickets";

if (!$_SESSION['userid'] || $_SESSION['userid'] == '') {
    header('Location: ' DIRADMIN . "/login.php");
    exit();
} else {
    $user = new User($_SESSION['userid']);
}

if ($_GET['id'] && $_GET['id'] > 0) {
    $ticket = new Ticket($_GET['id']);
} elseif ($_GET['clientid'] && $_GET['clientid'] > 0) {
    $client = new Client($_GET['clientid']);
    $tickets = $client->get_tickets();
} else {
    $tickets = $user->get_tickets();
}
?>

<?php 
include('includes/templates/header.php');

?>