<?php
/*------------------------------
 *  Drive CRM - Home Page
 *  Author: Bret Wagner
 *------------------------------
 */

require('../includes/config.php');
include('../includes/templates/header.php');

$page_title = "Drive CRM User Panel";

if (!$_SESSION['userid'] || $_SESSION['userid'] == '') {
    header('Location: ' DIRADMIN . "/login.php");
    exit();
} else {
    $user = new User($_SESSION['userid']);
}

$clients = $user->get_clients(5);
$tickets = $user->get_tickets(5);
$ticket_notes = $user->get_ticket_notes(5);
$client_notes = $user->get_notes(5);

?>
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
<th>Client</th>
<th>Notes</th>
<th>Tickets</th>
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
    <td class="ticket-count"><p><a href="<?php echo DIR . '/ticket-notes.php?clientid=' . $client['id'];?>" title="View Ticket Notes"><?php echo $row->count_tickets(); ?></td>
    </tr>
    <?php
}
?>
<tr>
<td colspan="3"><p><a href="<?php echo DIR . '/clients.php';?>" title="View All Clients">View all</a></p></td>
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
<th>Client Name</th>
<th>Type</th>
<th>Due</th>
</tr>
<?php
/*
    list results for tickets here
 */
foreach ($tickets as $ticket) {
    $row = new Ticket($ticket['id']);
    $client = new Client($ticket['clientid']);
    ?>
    <tr class="client-ticket">
    <td class="client-name"><p><a href="<?php echo DIR . '/tickets.php?id=' . $row['id'];?>" title="<?php echo $row['name']; ?>"><?php echo $client->name; ?></a></p></td>
    <td class="request"><p><?php echo $ticket->request_type; ?></p></td>
    <td class="due-date"><p><?php echo $ticket->due_date; ?></p></td>
    </tr>
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
<th>Client Name</th>
<th>Username</th>
<th>Date</th>
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
    <td class="created-by"><p><a href="<?php echo DIR . '/users.php?id=' $created_by->id; ?>" title="View <?php echo $created_by->username;?>'s Tickets"><?php echo $created_by->username; ?></a></p></td>
    <td class="creation-date"><p><?php echo $row->due_date;?></p></td>
    </tr>
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
<th>Client Name</th>
<th>Created By</th>
<th>Ticket Id</th>
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
    <td class="client-name"><p><a href="<?php DIR . '/ticket-notes.php?ticketid=' . $ticket->id; ?>" title="View Ticket"><?php echo $client->name; ?></a></p></td>
    <td class="created-by"><p><?php echo $created_by->username; ?></p></td>
}
?>
</table>
</div>
</div>
</div>
</section>
</div>

<?php
include('..includes/templates/footer.php');