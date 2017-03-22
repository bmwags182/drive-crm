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
        <ul class="nav-menu">
        <li><a href="<?php echo DIR; ?>">Home</a></li>
        <li><a href="<?php echo DIR . '/clients.php'; ?>">Clients</a></li>
        <li><a href="<?php echo DIR . '/tickets.php';?>">Tickets</a></li>
        <li><a href="<?php echo DIRADMIN . '/users.php';?>">Users</a></li>
        <li><a href="?logout=true">Logout</a></li>
        </ul>
    </div>
</div>
<!-- END NAV -->
