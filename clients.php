<?php
/*------------------------------
 *  Drive CRM - Clients Page
 *  Author: Bret Wagner
 *------------------------------
 */

require('includes/config.php');

$page_title = "Drive CRM Clients";

if (!$_SESSION['userid'] || $_SESSION['userid'] == '') {
    header('Location: ' . DIRADMIN . "/login.php");
    exit();
} else {
    $user = new User($_SESSION['userid']);
}

if (isset($_GET['id']) && $_GET['id'] != 0) {
    $client = new Client($_GET['id']);
    if ($user->pod != $client->pod && !$user->is_admin()) {
        die("NOT ALLOWED!");
    }
    $notes = $client->get_notes();
    $accounts = $client->get_accounts();
} else {
    $clients = $user->get_clients();
}

?>

<?php
include('includes/templates/header.php');

if (isset($_GET['id'])) {
    ?>
    <div class="content">
    <section class="row">
    <div class="wrapper">
    <div class="client-header">
    <div class="client-title">
    <h2><?php echo $client->name; ?></h2>
    </div>
    <div class="client-data">
    <div class="client-row-top">
    <table>
    <col width="25%">
    <col width="25%">
    <col width="25%">
    <col width="25%">
    <tr>
    <th><h4>Office</h4></th>
    <th><h4>Pod</h4></th>
    <th><h4>Social Contract</h4></th>
    <th><h4>Web Contract</h4></th>
    </tr>
    <tr>
    <td><p><?php echo $client->office; ?></p></td>
    <td><p><?php echo $client->pod; ?></p></td>
    <td><p><?php echo $client->social; ?></p></td>
    <td><p><?php echo $client->web; ?></p></td>
    </tr>
    </table>
    </div>
    <div class="client-row-bottom">
    <table>
    <col width="50%">
    <col width="50%">
    <tr>
    <th><h4>Contract Links</h4></th>
    <th><h4>Client Website</h4></th>
    </tr>
    <tr>
    <td>
    <ul class="contract-list">
    <?php
    $contracts = $client->contracts;
    if (is_array($contracts) && $contracts != '') {
        foreach ($contracts as $contract) {
            ?>
            <li class="contract-link"><p><a href="<?php echo $contract; ?>" title="View Contract"><?php echo $contract; ?></a></p></li>
            <?php
        }
    } else {
        ?>
        <li class="contract-link"><p><a href="<?php echo $contracts; ?>" title="View Contract"><?php echo $contract; ?></a></p></li>
        <?php
    }
    ?>
    </ul>
    </td>
    <td><p><a href="<?php echo $client->url;?>" title="View Website"><?php echo $client->url; ?></a></p></td>
    </tr>
    </table>
    </div>
    </div>
    </div>
    </section>
    <section class="row">
    <div class="wrapper">
    <div class="client-accounts">
    <table>
    <col width="25%">
    <col width="25%">
    <col width="25%">
    <col width="25%">
    <tr>
    <th><h4>Account Name</h4></th>
    <th><h4>URL</h4></th>
    <th><h4>Username</h4></th>
    <th><h4>View Password</h4></th>
    </tr>
    <?php
    foreach ($client->get_accounts() as $account) {
        ?>
        <tr>
        <td><p><?php echo $account['name']; ?></p></td>
        <td><p><?php echo $account['url']; ?></p></td>
        <td><p><?php echo $account['username']; ?></p></td>
        <td><button class="view-btn" id="view-pass" onclick="CopyToClipboard(<?php echo decrypt_string($account->view_password()); ?>)">View Password</button></td>
        </tr>
        <?php
    }
    ?>
    </table>
    </div>
    </div>
    </section>
    </div> <!-- End .content -->
    <div class="notes">
    <?php
    if (isset($notes)) {
        foreach ($notes as $note) {
            $created_by = new User($note['userid']);
            ?>
            <div class="client-note">
            <div class="note-header">
            <h4 class="note-username"><?php echo $created_by->username; ?></h4>
            <h4 class="creation-date"><?php echo $note['date']; ?></h4>
            </div>
            <div class="message-wrapper"><p><?php echo $note['message']; ?></p></div>
            </div>
            <?php
        }
    }
    ?>
    </div>
    <?php
} else {
    ?>
    <div class="content">
    <div class="wrapper">
    <div class="client-list">
    <table>
    <col width="40%">
    <col width="30%">
    <col width="30%">
    <tr>
    <th><h4>Client Name</h4></th>
    <th><h4>Notes</h4></th>
    <th><h4>Tickets</h4></th>
    </tr>
    <?php
    foreach ($clients as $client) {
        $client = new Client($client['id']);
        ?>
        <tr>
        <td><p><a href="<?php echo DIR . '/cients.php?id=' . $client->id; ?>" title="View Client"><?php echo $client->name; ?></a></p></td>
        <td><p><?php echo $client->count_notes(); ?></p></td>
        <td><p><?php echo $client->count->tickets(); ?></p></td>
        </tr>
        <?php
    }
    ?>
    </table>
    </div>
    </div>
    </div>
    <?php
}

include('includes/templates/footer.php');
