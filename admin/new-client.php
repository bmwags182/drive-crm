<?php
/*------------------------------
 *  Drive CRM - New Client Page
 *  Author: Bret Wagner
 *------------------------------
 */

require('../includes/config.php');

$page_title = "Drive CRM New Client";

if (!$_SESSION['userid'] || $_SESSION['userid'] == '') {
    header('Location: ' . DIRADMIN . "/login.php");
    exit();
} else {
    $user = new User($_SESSION['userid']);
}

if ($user->level <=2) {
    die("You are not allowed to create clients.");
}

if (isset($_POST['new-client']) && $_POST['new-client'] != '' ) {
    $name = $_POST['client-name'];
    $social = $_POST['social'];
    $web = $_POST['web'];
    if (isset($_POST['contracts']) && $_POST['contracts'] != '') {
        $contracts = $_POST['contracts'];
    } else {
        $contracts = "none";
    }

    $url = $_POST['url'];
    $contact_name = $_POST['contact-name'];
    $contact_email = $_POST['contact-email'];
    if ($_POST['contact-phone'] && $_POST['contact-phone'] != '') {
        $contact_phone = $_POST['contact-phone'];
    } else {
        $contact_phone = "XXX-XXX-XXXX";
    }
    if (isset($_POST['pod']) && $_POST['pod'] != '') {
        $pod = $_POST['pod'];
    } else {
        $pod = $user->pod;
    }

    if (isset($_POST['office']) && $_POST['office'] != '') {
        $office = $_POST['office'];
    } else {
        $office = $user->office;
    }

    $client = new Client();
    $client->create($name, $pod, $social, $web, $office, $contracts, $contact_name, $contact_email, $contact_phone, $url);
}
?>

<?php
include('../includes/templates/header.php');
?>
<div class="content">
<div class="form-wrapper">
<form action="" method="post" name="new-client">
<label>Client Name:</label><br />
<input type="text" name="client-name" /><br />
<label>Social Contract:</label><br />
<input type="text" name="social" /><br />
<label>Web Contract:</label><br />
<input type="text" name="web" /><br />
<label>Contracts:</label><br />
<input type="text" name="contracts" /><br />
<label>Website:</label><br />
<input type="text" name="url" /><br />
<label>Contact Name:</label><br />
<input type="text" name="contact-name" /><br />
<label>Contact Email:</label><br />
<input type="text" name="contact-email" /><br />
<label>Contact Phone:</label><br />
<input type="text" name="contact-phone" /><br />
<?php
if ($user->is_admin()) {
    ?>
    <label>Office:</label><br />
    <select name="office">
    <option>SELECT ONE</option>
    <option value="St. Louis">St. Louis</option>
    <option value="Nashville">Nashville</option>
    <option value="Chicago">Chicago</option>
    </select><br />
    <label>POD:</label><br />
    <select name="pod">
    <option>SELECT ONE</option>
    <option value="1">1</option>
    <option value="2">2</option>
    <option value="3">3</option>
    <option value="4">4</option>
    <option value="5">5</option>
    </select><br />
    <?php
} else {
    ?>
    <input type="text" name="office" value="<?php echo $user->office; ?>" />
    <input type="text" name="office" value="<?php echo $user->pod; ?>" />
    <?php
}
?>
<br />
<input type="submit" name="new-client" value="Create Client" />
</form>
</div>
</div>
<?php
include('../includes/templates/footer.php');
