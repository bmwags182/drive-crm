<?php
/*------------------------------
 *  Drive CRM - Home Page
 *  Author: Bret Wagner
 *------------------------------
 */

require('../includes/config.php');

$page_title = "Drive CRM User Registration";

if (!is_null($_SESSION['authorized']) && !is_null($_SESSION['userid']) && $_SESSION['userid'] != 0) {
    header('Location: ' . DIR);
} else {
    $user = new User();
}

if (isset($_POST['register']) && $_POST ['register'] != '' ) {
    $fname = $_POST['fname'];                           //
    $lname = $_POST['lname'];                           //
    $password = encrypt_string($_POST['password']);     //
    $title = $_POST['title'];                           //
    $email = $_POST['email'];                           //
    $level = 1;                                         //
    $office = $_POST['office'];                         //
    $pod = $_POST['pod'];                               //
    $username = $_POST['username'];                     //
    $user->create($level, $title, $office, $pod, $fname, $lname, $email, $username, $password);
    header('Location: ' . DIRADMIN . "/login.php");
}

include('../includes/templates/header.php');
?>
<div class="content">
<div class="form-wrapper">
<form name="register" action="" method="post">
<label>First Name:</label><br />
<input type="text" name="fname" /><br />
<label>Last Name:</label><br />
<input type="text" name="lname" /><br />
<label>Title:</label><br />
<input type="text" name="title" /><br />
<label>Username:</label><br />
<input type="text" name="username" /><br />
<label>Email:</label><br />
<input type="text" name="email" /><br />
<label>Confirm Email:</label><br />
<input type="text" name="confirm-email" /><br />
<label>Password:</label><br />
<input type="password" name="password" /><br />
<label>Confirm Password:</label><br />
<input type="password" name="confirm-password" /><br />
<label>Office:</label><br />
<select name="office">
<option>SELECT CITY</option>
<option value="St. Louis">St. Louis</option>
<option value="Nashville">Nashville</option>
<option value="Chicago">Chicago</option>
</select><br />
<label>Pod:</label><br />
<select name="pod">
<option>SELECT POD</option>
<option value="1">1</option>
<option value="2">2</option>
<option value="3">3</option>
<option value="4">4</option>
<option value="5">5</option>
<option value="web">Web Team</option>
</select><br />
<input type="submit" value="Register" name="register" />
</form>
</div>
<?php
include('../includes/templates/footer.php');
