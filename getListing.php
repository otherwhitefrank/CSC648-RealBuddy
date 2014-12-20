<?php

include_once "backend/listingManager.php";
$listingManager = new ListingManager();

$id = $_GET['listing_id'];

$final_array = $listingManager->getListingById($id);

echo json_encode($final_array);
?>
