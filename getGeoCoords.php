<?php

include_once 'include/db.php';
include_once "backend/listingManager.php";

$listingManager = new ListingManager();
$dbManager = DatabaseManager::getInstance();

$min_price = $_GET['min_price'];
$max_price = $_GET['max_price'];
$search_radius = $_GET['search_distance'];
$num_bedrooms = $_GET['num_bedrooms'];
$num_bathrooms = $_GET['num_bathrooms'];
$num_garages = $_GET['num_garages'];
$search_lat = $_GET['search_lat'];
$search_lon = $_GET['search_lon'];

//var_dump($min_price, $max_price, $search_radius, $num_bedrooms, $num_bathrooms, $num_garages);
$result = $listingManager->getListingsByAttr($search_lat, $search_lon, $search_radius, $num_bedrooms, $num_bathrooms, $num_garages, $min_price, $max_price);

echo json_encode($result);
?>
