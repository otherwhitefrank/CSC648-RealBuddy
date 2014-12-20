<?php
/**
 * Created by PhpStorm.
 * User: frank
 * Date: 12/1/14
 * Time: 3:34 PM
 */

session_start();
session_destroy();
$home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/index.php';
header('Location: ' . $home_url);

?>