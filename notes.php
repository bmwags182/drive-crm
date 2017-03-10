<?php
/*------------------------------
 *  Drive CRM - Client Notes Page
 *  Author: Bret Wagner
 *------------------------------
 */

require('includes/config.php');

$page_title = "Drive CRM Notes";

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
if ($client) {
    $notes = $client->get_notes();
    ?>
    <div class="content">
        <section class="row">
            <h2><?php echo $client->name . "'s Notes";?></h2>
        </section>
        <div class="notes">
        <?php 
        if (isset($notes) && $user->pod == $client->pod) {
            foreach ($notes as $note) {
                $created_by = new User($note['userid']);
                ?>
                <div class="client-note-full">
                    <div class="note-header">
                        <h4 class="note-username"><?php echo $created_by->username; ?></h4>
                        <h4 class="creation-date"><?php echo $note['date']; ?></h4>
                    </div>
                <div class="message-wrapper"><p><?php echo $note['message']; ?></p></div>
                </div>
            }
        }
        </div>
    </div>
    ?>
    <?php
} elseif ($user->id == $note->userid) {
    $created_by = new User($note['userid']);
    $client = new Client($note['clientid']);
    ?>
    <div class="content">
        <section class="row">
            <h2><?php echo $created_by->username . "'s Note on " . $client->name;?></h2>
        </section>
        <div class="note-edit-wrapper">
            <form name="edit-note" action="" method="get">
                <label>Message:</label><br/>
                <textarea rows="10" cols="150" name="message" class="message-box" autofocus=""><?php if ($note->message) { echo $note->message;} ?></textarea>
                <input type="submit" value="Update!" />
            </form>
        </div>
    </div>
<?php
} else {
    die("You are trying to do something you aren't allowed to do. And I couldn't come up with a good enough error message so this is what you got.");
}

include('includes/templates/footer.php');
?>
