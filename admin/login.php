<?php
/*------------------------------
 *  Drive CRM - Login Page
 *  Author: Bret Wagner
 *------------------------------
 */

require('../includes/config.php');


$page_title = "Drive CRM Login Page";
if (!is_null($_SESSION['authorized']) && !is_null($_SESSION['userid']) && $_SESSION['userid'] != 0) {
    header('Location: ' . DIR);
} else {
    $user = new User();
}

// Check if login form submitted
if ($_POST['login'] && $_POST['login'] != '') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $user->login($username, $password);
}
include('../includes/templates/header.php');
?>

<div class="content">
<p><?php if ($_SESSION['error'] && $_SESSION['error'] != '') {
    echo $_SESSION['error'];
    unset($_SESSION['error']);
}
?></p>
    <div id="login">
        <form method="post" action="">
            <p><label><strong>Username</strong><input type="text" name="username" /></label></p>
            <p><label><strong>Password</strong><input type="password" name="password" /></label></p>
            <p><br /><br/><input type="submit" name="login" class="button" value="login" /><a href="<?php echo DIRADMIN;?>/register.php">Register</a></p>
        </form>
    </div>

</div>
<div class="clear"></div>
