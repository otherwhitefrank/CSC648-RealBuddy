<?php
session_start();
include_once "include/db.php";
include_once "backend/listing.php";
include_once "backend/listingManager.php";
include_once 'include/QR.php';
include_once "checkPermission.php";
checkPermission("user");

$dbManager = DatabaseManager::getInstance();
$qr = new QR();

$listingManager = new ListingManager();
$result = $listingManager->getAllListings();

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link rel="icon" href="../../favicon.ico">

    <title>Software Engineering: Group 10-ContactAgent</title>

    <!--Jquery-->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>


    <!-- Bootstrap core CSS -->
    <link href="dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="include/contactAgent.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
      <![endif]-->



      <script type="text/javascript"
              src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB-ssGXLniMzVEATITsDPKXIQXZ0ggEwqE">
      </script>

  </head>

  <body>
    <?php include "header.php"?> 
    <div class="container-fluid">
      <div class="row">
        <div id="main-content" class="col-xs-6 col-md-6 col-lg-6">
<?php

foreach ($result as $listing) {
  $id = $listing->listing_id;
  echo " This is listing " .$id. "</br>";
  $url = "http://sfsuswe.com/~f14g10/qresults.php?listing_id=".$id;
  $size = 300;
  echo " ".$qr->google_qr($url, $size) . "</br>";
}
echo " </div>";

?>
      </div>
<?php include "footer.php"?>
 </body>
</html>
