<?php
/*------------------------------
 *  Drive CRM - Login Page
 *  Author: Bret Wagner
 *------------------------------
 */

require('../includes/config.php');
include('../includes/templates/header.php');

$page_title = "Drive CRM Login Page";
if (!is_null($_SESSION['authorized']) && !is_null($_SESSION['userid']) && $_SESSION['userid'] != 0) {
    header('Location: ' DIR);
} else {
    $user = new User();
}

// Check if login form submitted
if ($_POST['login'] && $_POST['login'] != '') {
    $user->login($username, $password);
}
?>

<div class="content">

    <div id="login">
        <p><?php messages();?></p>
        <form method="post" action="">
            <p><label><strong>Username</strong><input type="text" name="username" /></label></p>
            <p><label><strong>Password</strong><input type="password" name="password" /></label></p>
            <p><br /><br/><input type="submit" name="submit" class="button" value="login" /><a href="<?php echo DIRADMIN;?>register.php">Register</a></p>
        </form>
    </div>

</div>
<div class="clear"></div>
