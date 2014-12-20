<?php

include_once 'include/db.php';

$dbManager = DatabaseManager::getInstance();


$id = (int)$_GET['id'];
header("Content-type: image/JPEG");
echo $dbManager->getImage($id);




?>
