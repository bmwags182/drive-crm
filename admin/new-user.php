<?php
/*------------------------------
 *  Drive CRM - New User Page
 *  Author: Bret Wagner
 *------------------------------
 */

require('../includes/config.php');

$page_title = "Drive CRM New User";

if (!$_SESSION['userid'] || $_SESSION['userid'] == '') {
    header('Location: ' . DIRADMIN . "/login.php");
    exit();
} else {
    $user = new User($_SESSION['userid']);
}

if ($user->level <= 2) {
    die("You are not allowed to create users.");
}

if ($_POST['new-user'] && $_POST['new-user'] != '') {
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $title = $_POST['title'];
    $email = $_POST['email'];
    $password = encrypt_string($_POST['password']);
    $level = $_POST['level'];
    $username = $_POST['username'];
    if (isset($_POST['office']) && $_POST['office'] != '') {
        $office = $_POST['office'];
    }

    if (isset($_POST['pod']) && $_POST['pod'] != '') {
        $pod = $_POST['pod'];
    }

    $user->create($level, $title, $office, $pod, $fname, $lname, $email, $username, $password);
    header('Location: ' . DIR);
} else {
    create_error();
}


function create_error() {
    $_SESSION['error'] = "User not created.";
    header('Location: ' . $_SERVER[HTTP_REFERER]);
}

?>

<?php
include('../includes/templates/header.php');
?>
<div class="content">
<section class="row">
<div class="page-header">
<h1 class="page-title">New User</h1>
</div>
</section>
<section class="row">
<div class="form-wrapper">
<div class="wrapper-inner">
<form method="post" action="" name="new-user">
<div class="form-row">
<div class="form-column column3 first">
<label>First Name:</label><br />
<input type="text" name="fname" />
</div>
<div class="form-column column3 middle">
<label>Last Name:</label><br />
<input type="text" name="lname" />
</div>
<div class="form-column column3 last">
<label>Title:</label><br />
<input type="text" name="title" />
</div>
</div>
<div class="form-row">
<div class="form-column column3 first">
<label>Email Address:</label><br />
<input type="text" name="email" onCopy="return false" onCut="return false" onDrag="return false" /><br />
<label>Confirm Email:</label><br />
<input type="text" name="confirm-email" onpaste="return false;" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false" autocomplete=off />
</div>
<div class="form-column column3 middle">
<label>Password:</label><br />
<input type="password" name="password" onCopy="return false" onDrag="return false" onCut="return false" /><br />
<label>Confirm Password:</label><br />
<input type="password" name="confirm-password" onpaste="return false;" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false" autocomplete=off />
</div>
<div class="form-column column3 last">
<label>Position Level:</label><br />
<select name="level">
<option value="1">Intern</option>
<option value="2">Employee</option>
<?php
if ($user->level >=4) {
?>
<option value="3">Senior/Lead</option>
<?php
}
if ($user->level == 4) {
    ?>
    <option value="4">Manager/Web Team</option>
    <?php
}
if ($user->level == 5) {
?>
<option value="5">SuperAdmin</option>
<?php
}
?>
</select><br />
<label>Username:</label><br />
<input type="text" name="username" />
</div>
<?php
if ($user->is_admin()) {
    ?>

    <div class="form-row">
    <div class="form-column column2 first">
    <label>Office:</label><br />
    <select name="office">
    <option value="St. Louis">St. Louis</option>
    <option value="Nashville">Nashville</option>
    <option value="Chicago">Chicago</option>
    </select>
    </div>
    <div class="form-column column2 last">
    <label>POD Number</label><br />
    <select name="pod">
    <option>SELECT ONE</option>
    <option value="1">1</option>
    <option value="2">2</option>
    <option value="3">3</option>
    <option value="4">4</option>
    <option value="5">5</option>
    <option value="manager">Managers</option>
    <option value="web">Web Team</option>
    </select>
    </div>
    </div>
<?php
}
?>
<br />
<input type="submit" value="Create" name="new-user" />
</form>
</div>
</div>
</section>
</div>
