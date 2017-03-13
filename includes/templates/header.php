<?php
/*------------------------------
 *  Drive CRM - Header Template
 *  Author: Bret Wagner
 *------------------------------
 */
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
if (isset($page_title) && $page_title != '') {
    echo '<title>'. $page_title .'</title>';
} else {
    echo '<title>'. SITETITLE .'</title>';
}
?>
<meta name="viewport" content="width=device-width, maximum-scale=1, minimum-scale=1, user-scalable=0, initial-scale=1">
<link href="https://fonts.googleapis.com/css?family=Anton|Domine|Montserrat|Titillium+Web" rel="stylesheet">
</head>
<body>
<?php include('navigation.php');
?>
