<?php

include_once "include/db.php";
$dbManager = DatabaseManager::getInstance();

$id = $_GET['listing_id'];

$final_array = $dbManager->getGeoCoordsByListId($id);

echo json_encode($final_array);
?>
