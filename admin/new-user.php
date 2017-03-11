<?php
/*------------------------------
 *  Drive CRM - New User Page
 *  Author: Bret Wagner
 *------------------------------
 */

require('../includes/config.php');

$page_title = "Drive CRM New User";

if (!$_SESSION['userid'] || $_SESSION['userid'] == '') {
    header('Location: ' DIRADMIN . "/login.php");
    exit();
} else {
    $user = new User($_SESSION['userid']);
}

if ($user->level <= 2) {
    die("You are not allowed to create users.");
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
<form method="post" action="<?php echo DIRADMIN;?>/create-user.php" name="new-user">
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
<label>Confirm Email:</label>
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
<select name="title">
<option value="1">Intern</option>
<option value="2">Employee</option>
<?php
if ($user->level >=4) {
?>
<option value="3">Senior/Lead</option>
<?php 
}
if ($user->level == 5) {
?>
<option value="4">Manager/Web Team</option>
<option value="5">SuperAdmin</option>
<?php
}
?>
</select><br />
<label>Username:</label>
<input type="text" name="username" />
</div>
<?php
if ($user->is_admin()) {
    ?>
    
    <div class="form-row">
    <div class="form-column column2 first">
    <label>Office:</label><br />
    <select name="office">
    <option value="St Louis">St. Louis</option>
    <option value="Nashville">Nashville</option>
    <option value="Chicago">Chicago</option>
    </select>
    </div>
    <div class="form-column column2 last">
    <label>POD Number</label><br />
    <input type="text" name="pod" />
    </div>
    </div>
<?php
}
?>
<input type="submit" value="Create" />
</form>
</div>
</div>
</section>
</div>