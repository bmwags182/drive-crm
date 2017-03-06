<?php 
/*------------------------------
 *  Drive CRM - Navigation Template
 *  Author: Bret Wagner
 *------------------------------
 */
?>

<!-- NAV -->
<div id="navigation">
<div class="menu">
<a href="<?php echo DIR;?>">Home</a>
<a href="<?php echo DIR . '/all-games.php/'; ?>">View Games</a>

<?php 
    if(isset($_SESSION['authorized'])) { ?>
        <a href="<?php echo DIR . "/users.php"; ?>" style="position:absolute; top:10px; right:10px;" >Profile</a><?php 
    } else { ?>
        <a href="<?php echo DIRADMIN;?>" style="position:absolute; top:10px; right:10px;" >Login</a>
        <?php
    } ?>
    </div>
</div>
<!-- END NAV -->