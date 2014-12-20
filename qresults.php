<?php
include_once 'include/QR.php';
include_once "backend/listingManager.php";

$dbManager = DatabaseManager::getInstance();
$qr = new QR();
$listingManager = new ListingManager();

$id = $_GET['listing_id'];
$listing = $listingManager->getListingById($id);

$street = $listing[0] -> street;
$zip = $listing[0] -> zip;
$city = $listing[0] -> city;

$address = $street ." ". $zip . " " . $city;


$url = 'http://sfsuswe.com/~f14g10/results.php';

    $postdata = http_build_query(array('address'=>$address));
    $curl = curl_init();
    //set the url, number of POST vars, POST data
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
    
    //execute
    curl_exec($curl);
    
    //close connection
    curl_close($curl);
?>