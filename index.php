<?php
/*------------------------------
 *  Drive CRM - Home Page
 *  Author: Bret Wagner
 *------------------------------
 */

require('includes/config.php');

$page_title = "Drive CRM User Panel";

if (!$_SESSION['userid'] || $_SESSION['userid'] == '') {
    header('Location: '. DIRADMIN . "/login.php");
    exit();
} else {
    $user = new User($_SESSION['userid']);
}

$clients = $user->get_clients(5);
$tickets = $user->get_tickets(5);
$ticket_notes = $user->get_ticket_notes(5);
$client_notes = $user->get_notes(5);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
if (isset($page_title) && $page_title != '') {
    echo '<title>'. $page_title .'</title>';
} else {
    echo '<title>'. SITETITLE .'</title>';
}
?>
<meta name="viewport" content="width=device-width, maximum-scale=1, minimum-scale=1, user-scalable=0, initial-scale=1">
<link href="https://fonts.googleapis.com/css?family=Anton|Domine|Montserrat|Titillium+Web" rel="stylesheet">
</head>
<body>
<!-- NAV -->
<div id="navigation">
    <div class="menu">

    </div>
</div>
<!-- END NAV -->

<div class="content">
<section class="row">
<div class="wrapper">
<div class="column">
<h4 class="column-title">Pod Clients</h4>
<div class="wrapper">
<table>
<col width="40%">
<col width="30%">
<col width="30%">
<tr>
<th><h4>Client</h4></th>
<th><h4>Notes</h4></th>
<th><h4>Tickets</h4></th>
</tr>
<?php
/*
    list results for clients here
 */
foreach ($clients as $client) {
    $row = new Client($client['id']);

    ?>
    <tr class="client">
    <td class="client-name"><p><a href="<?php echo DIR . '/clients.php?id=' . $row['id'];?>" title="<?php echo $client['name']; ?>"><?php echo $client['name']; ?></a></p></td>
    <td class="note-count"><p><a href="<?php echo DIR . '/notes.php?clientid=' . $client['id'];?>" title="View Notes"><?php echo $row->count_notes(); ?></a></p></td>
    <td class="ticket-count"><p><?php echo $row->count_tickets(); ?></p></td>
    </tr>
    <?php
}
?>
<tr>
<td colspan="3"><p><a href="<?php echo DIR . '/clients.php';?>" title="View All Clients">View all</a></p></td></tr>
</table>
</div>
</div>
<div class="column">
<h4 class="column-title">Pod Tickets</h4>
<div class="wrapper">
<table>
<col width="40%">
<col width="30%">
<col width="30%">
<tr>
<th><h4>Client Name</h4></th>
<th><h4>Type</h4></th>
<th><h4>Due</h4></th>
</tr>
<?php
/*
    list results for tickets here
 */
//*
foreach ($tickets as $ticket) {
    $row = new Ticket($ticket['id']);
    $client = new Client($ticket['clientid']);
    ?>
    <tr class="client-ticket">
    <td class="client-name"><p><a href="<?php echo DIR . '/tickets.php?id=' . $row['id'];?>"><?php echo $client->name; ?></a></p></td>
    <td class="request"><p><?php echo $ticket->request_type; ?></p></td>
    <td class="due-date"><p><?php echo $ticket->due_date; ?></p></td>
    </tr>
    <?php
}
?>
<tr>
<td colspan="3"><p><a href="<?php echo DIR . '/tickets.php'; ?>" title="View All Tickets">View all</a></p></td>
</tr>
</table>
</div>
</div>
</div>
</section>

<section class="row">
<div class="wrapper">
<div class="column">
<h4 class="column-title">Client Notes</h4>
<div class="wrapper">
<table>
<col width="40%">
<col width="30%">
<col width="30%">
<tr>
<th><h4>Client Name</h4></th>
<th><h4>Username</h4></th>
<th><h4>Date</h4></th>
</tr>
<?php
/*
    list results for client notes here
 */
foreach ($client_notes as $note) {
    $row = new Note($note['id']);
    $client = new Client($note['id']);
    $created_by = new User($client->userid);
    ?>
    <tr class="client-note">
    <td class="client-name"><p><a href="<?php echo DIR . '/notes.php?clientid=' . $client->id;?>" title="View Note"><?php echo $client->name;?></a></p></td>
    <td class="created-by"><p><a href="<?php echo DIR . '/users.php?id=' . $created_by->id; ?>" title="View <?php echo $created_by->username;?>'s Tickets"><?php echo $created_by->username; ?></a></p></td>
    <td class="creation-date"><p><?php echo $row->due_date;?></p></td>
    </tr>
    <?php
}
?>
</table>
</div>
</div>
<div class="column">
<h4 class="column-title">Ticket Notes</h4>
<div class="wrapper">
<table>
<col width="40%">
<col width="30%">
<col width="30%">
<tr>
<th><h4>Client Name</h4></th>
<th><h4>Created By</h4></th>
<th><h4>Ticket Id</h4></th>
</tr>
<?php
/*
    list results for ticket notes here
 */
foreach ($ticket_notes as $note) {
    $row = new Ticket_note($note['id']);
    $ticket = new Ticket($note['ticketid']);
    $client = new Client($ticket->clientid);
    $created_by = new User($row->userid);
    ?>
    <tr class="ticket-note">
    <td class="client-name"><p><a href="<?php echo DIR . '/ticket-notes.php?ticketid=' . $ticket->id; ?>" title="View Ticket"><?php echo $client->name; ?></a></p></td>
    <td class="created-by"><p><?php echo $created_by->username; ?></p></td></tr>
    <?php
}
?>
</table>
</div>
</div>
</div>
</section>
</div>
    <div id="footer">
            <div class="copy"><p>&copy; <?php echo SITETITLE.' '. date('Y');?> </p></div>
    </div><!-- close footer -->


</body>
</html>
