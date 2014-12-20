<?php
session_start();
include_once "checkPermission.php";
include_once "backend/image.php";
checkPermission("guest");

if (isset($_SESSION['userid'])) {
    $userid = $_SESSION['userid'];
}

?>
<?php
include_once "backend/listingManager.php";
include_once "checkPermission.php";
checkPermission("agent");
$listingManager = new ListingManager();
if (isset($_POST['deleteListingButton'])) // delete user
{
    $listingid = $_POST['deleteListingButton'];
    $listingManager->deleteListing($listingid);
}
else if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $listing_id = $_POST['listing_id'];
    $list_price = $_POST['list_price'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $zip = $_POST['zip'];
    $num_beds = $_POST['num_beds'];
    $num_baths = $_POST['num_baths'];
    $num_garages = $_POST['num_garages'];
    $sq_feet = $_POST['sq_feet'];
    $listing_desc = $_POST['listing_desc'];
    $user_id = $_SESSION["userid"];


    $listing = $listingManager->getListingById($listing_id);
    $listing = $listing[0];


    // FIXME: add map handler
    $new_address = $address . ", " . $city . ", " . $state . ", " . $zip;
    $url_address = str_replace(" ", "+", $new_address);
    //var_dump($url_address);
    $gmaps_api_url = "https://maps.googleapis.com/maps/api/geocode/json?address=" . $url_address . "?key=AIzaSyB-ssGXLniMzVEATITsDPKXIQXZ0ggEwqE";

    $du = file_get_contents($gmaps_api_url);
    $djd = json_decode(utf8_encode($du), true);

    //echo var_dump($djd);
    if (count($djd['results']) != 0) // no results
    {

        $search_lat = $djd['results'][0]['geometry']['location']['lat'];
        $search_lon = $djd['results'][0]['geometry']['location']['lng'];
        $listing->lat = $search_lat;
        $listing->lon = $search_lon;
    } else {
        $listing->lat = 0;
        $listing->lon = 0;
    }

    //update the values
    $listing->list_price = $list_price;
    $listing->street = $address;
    $listing->zip = $zip;
    $listing->num_beds = $num_beds;
    $listing->num_baths = $num_baths;
    $listing->num_garages = $num_garages;
    $listing->sq_feet = $sq_feet;
    $listing->listing_desc = $listing_desc;


//Save the images to the DB if they are present
    $imagesChanged = false;
    $arrImages = array();
    for ($i = 1; $i <= 5; $i++) {
        $pic_name = "picture{$i}";
//Stop the entry if any kind of error happened when uploading
        if (isset($_FILES[$pic_name]['tmp_name'])) {
            if (is_uploaded_file($_FILES[$pic_name]['tmp_name'])) {
                $pic = $_FILES[$pic_name]['tmp_name'];
                $pic_caption = "picture{$i}_caption";
                $pic_caption = $_POST[$pic_caption];

                $imgData = addslashes(file_get_contents($pic));
                $newImage = new image($listing->listing_id, $pic_caption, $imgData);
                $arrImages[] = $newImage;

                $imagesChanged = true;
            }
        }
    }

    if ($imagesChanged) {
        $listing->images = $arrImages;

    }
    //var_dump($listing);

    $id = $listingManager->updateListing($listing, $imagesChanged);

    /* Redirect browser */
    header("Location: editListing.php");

    /* Make sure that code below does not get executed when we redirect. */
    exit;
}
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

    <title>Software Engineering: Group 10-CreateManage</title>

    <!--Jquery-->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

    <!-- Bootstrap core CSS -->
    <link href="dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="include/editListing.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->


    <script src="http://cdn.jsdelivr.net/jquery.validation/1.13.1/jquery.validate.min.js"></script>
    <script src="http://cdn.jsdelivr.net/jquery.validation/1.13.1/additional-methods.min.js"></script>
    <script src="include/editListing.js"></script>


    <script type="text/javascript"
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB-ssGXLniMzVEATITsDPKXIQXZ0ggEwqE">
    </script>
    <script type="text/javascript"
            src="include/gmap.js">
    </script>
</head>

<body>

<?php include "header.php" ?>
<div class="row">

<div class="col-xs-4 sidebar-container panel panel-default">
    <div class="panel-heading text-center"><h4><u>Your Listings</u></h4></div>

    <ul id="nav-top" class="nav nav-sidebar sidebar">
        <?php
        include_once "backend/userManager.php";
        $userManager = new userManager();
        // we can call this method safely, cause this page will ony be displayed if the user is logged in
        $user = $userManager->getUserById($_SESSION['userid']);
        if ($user->role == "admin") {
            $result = $listingManager->getAllListings();
        } else {
            // get user listings
            $result = $listingManager->getListingsByUser($_SESSION['userid']);
            if ($user->role == "agent") {
                // add realtor listings to user listings
                $result += $listingManager->getListingsByRealtor($_SESSION['userid']);
                $result = array_unique($result, SORT_REGULAR);
            }
        }

        $count = 0;

        if (count($result) == 0) { // no data available
            //No results
            echo "<div>No Results Returned</div>";
        } else {
            echo "<li class=\"active\" onclick=\"clickListing(event)\">";
            foreach ($result as $listing) {

                $encoded_id = "navbarListing" . $listing->listing_id;
                if ($count == 0) {
                    echo "<li class=\"active\" id=\"" . $encoded_id . "\" onclick=\"clickListing(event, " . $listing->listing_id . ")\">";
                } else {
                    echo "<li id=\"" . $encoded_id . "\" onclick=\"clickListing(event, " . $listing->listing_id . ")\">";
                }

                $first_image = $listing->image_ids[0];
                $image_id = $first_image['image_id'];


                echo "    <a href=\" # \" " . ">";
                echo "        <div class=\"navbar-photo\">";
                echo "        <div id=\"marker_num\">" . ++$count . "</div>";
                echo "        <img src=\"getImage.php?id=" . $image_id . "\" alt=\"Image2\" class=\"img-thumbnail navbar-thumb\">";


                echo "                <h5>Price: " . "$" . htmlentities(number_format($listing->list_price)) . "</h5>";
                echo "                <h6>Address: " . htmlentities($listing->street) . "</h6>";
                echo "                <h6>City: " . htmlentities($listing->city) . "</h6>";

                echo "                <h6>Sq. Feet: " . htmlentities($listing->sq_feet) . "</h6>";
                echo "                <h6>Num. Beds: " . htmlentities($listing->num_beds) . "</h6>";
                echo "                <h6>Num. Baths: " . htmlentities($listing->num_baths) . "</h6>";


                echo "        </div>";
                echo "</a>";

                echo "</li>";
            }

        }
        ?>
    </ul>

</div>
<!--/span-->

<div id="main-content"
     class="col-xs-8 col-xs-offset-4">


    <div id="main_area">
        <div class="panel-heading text-center"><h4><u>Selected Listing</u></h4></div>


        <div class="main">
            <div id="valid-error" class="col-xs-12 col-sm-12 col-md-12"></div>
            <form class="form-editPage" role="form" enctype=multipart/form-data action="editListing.php"
                  method="POST">

                <div class="form-group col-lg-12">
                    <label for="address">Address: </label>
                    <input type="text" class="form-control" id="address" name="address" placeholder="Address">
                </div>

                <div class="form-group col-lg-12">
                    <input type="text" class="form-control" id="listing_id"
                           name="listing_id" style="display: none;">
                </div>

                <div class="form-group col-lg-5">
                    <label for="city">City: </label>
                    <input type="text" class="form-control" data-validation="custom"
                           data-validation-regexp="([A-Z]|[a-z])\w+"
                           data-validation-error-msg="City must be in form: San Francisco" id="city" name="city"
                           placeholder="City" readonly>
                </div>
                <div class="form-group col-lg-3">
                    <label for="state">State: </label>
                    <input type="text" class="form-control" data-validation="custom"
                           data-validation-regexp="([A-Z]|[a-z]){2}"
                           data-validation-error-msg="State must be in form: CA" id="state" name="state"
                           placeholder="State" readonly>

                </div>
                <div class="form-group col-lg-4">
                    <label for="zip">Zip: </label>
                    <input type="number" class="form-control" data-validation="custom"
                           data-validation-regexp="([0-9]){5}"
                           data-validation-error-msg="Zip code must be in form: 94109" id="zip" name="zip"
                           placeholder="Zip">
                </div>

                <div class="form-group col-lg-4">
                    <label for="list_price">List Price: </label>
                    <input type="number" class="form-control" data-validation="number"
                           data-validation-allowing="range[50000;9999999999]"
                           data-validation-error-msg="List price must be in form: 1250000" id="list_price"
                           name="list_price" placeholder="Enter Price">
                </div>
                <div class="form-group col-lg-4">
                    <label for="num_beds">Num. Beds: </label>
                    <input type="number" class="form-control" data-validation="number"
                           data-validation-allowing="range[1;100]"
                           data-validation-error-msg="Num. Beds must be between 1 and 100" id="num_beds" name="num_beds"
                           placeholder="Num Beds">
                </div>
                <div class="form-group col-lg-4">
                    <label for="num_baths">Num. Baths: </label>
                    <input type="number" class="form-control" data-validation="number"
                           data-validation-allowing="range[1;100]"
                           data-validation-error-msg="Num. Baths must be between 1 and 100" id="num_baths"
                           name="num_baths" placeholder="Num Baths">
                </div>
                <div class="form-group col-lg-4">
                    <label for="num_garages">Num. Garages: </label>
                    <input type="number" class="form-control" data-validation="number"
                           data-validation-allowing="range[0;100]"
                           data-validation-error-msg="Num. Garages must be between 0 and 100" id="num_garages"
                           name="num_garages" placeholder="Num Garages">
                </div>
                <div class="form-group col-lg-4">
                    <label for="sq_feet">Sq. Feet: </label>
                    <input type="number" class="form-control" data-validation="number"
                           data-validation-allowing="range[1;99999999]"
                           data-validation-error-msg="Sq. Feet must be of form: 2400" id="sq_feet" name="sq_feet"
                           placeholder="Sq. Feet">
                </div>
                <div class="form-group col-lg-12">
                    <label for="listing_desc">Listing Desc: </label>
                    <textarea class="form-control" name="listing_desc" id="listing_desc" rows="6" cols="30"></textarea>
                </div>
        </div>
    </div>
    <div id="image-label" class="col-xs-offset-1 col-sm-offset-1 col-md-offset-1 col-xs-11 col-sm-11 col-md-11">

    </div>

    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <div class="col-xs-12 col-sm-6 col-md-6">
                <label id="picture1" for="Inputcity">Picture 1: </label>
                <input type="file" class="form-control" name="picture1" placeholder="Pic">
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6">
                <label for="Inputstreet">Picture 1 - Caption: </label>
                <input type="text" class="form-control" name="picture1_caption" id="picture_caption1"
                       placeholder="Picture 1 Caption">
            </div>
        </div>
        <div class="form-group">
            <div class="col-xs-12 col-sm-6 col-md-6">
                <label id="picture2" for="Inputcity">Picture 2: </label>
                <input type="file" class="form-control" name="picture2" placeholder="Pic">
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6">
                <label for="Inputstreet">Picture 2 - Caption: </label>
                <input type="text" class="form-control" name="picture2_caption" placeholder="Picture 2 Caption">
            </div>
        </div>
        <div class="form-group">
            <div class="col-xs-12 col-sm-6 col-md-6">
                <label for="Inputcity">Picture 3: </label>
                <input type="file" class="form-control" name="picture3" placeholder="Pic">
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6">
                <label for="Inputstreet">Picture 3 - Caption: </label>
                <input type="text" class="form-control" name="picture3_caption" placeholder="Picture 3 Caption">
            </div>
        </div>
        <div class="form-group">
            <div class="col-xs-12 col-sm-6 col-md-6">
                <label for="Inputcity">Picture 4: </label>
                <input type="file" class="form-control" name="picture4" placeholder="Pic">
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6">
                <label for="Inputstreet">Picture 4 - Caption: </label>
                <input type="text" class="form-control" name="picture4_caption" placeholder="Picture 4 Caption">
            </div>
        </div>
        <div class="form-group">
            <div class="col-xs-12 col-sm-6 col-md-6">
                <label for="Inputcity">Picture 5: </label>
                <input type="file" class="form-control" name="picture5" placeholder="Pic">
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6">
                <label for="Inputstreet">Picture 5 - Caption: </label>
                <input type="text" class="form-control" name="picture5_caption" placeholder="Picture 5 Caption">
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <button id="submitButton" class="btn btn-lg btn-primary" type="submit" value='Upload'>Update Listing
            </button>
        </div>
    </div>

</form>

<form class="form-editPage" action="editListing.php" role="form" enctype=multipart/form-data method="POST">
    <div id="valid-error" class="form-group col-lg-5">
        <label for="Inputstreet">Delete Listing:</label>

        <div class="has-warning">
            <div class="checkbox">
                <label>
                    <input type="checkbox" id="checkboxWarning" value="option1"
                           name="terms_checkbox[]"
                           data-validation="checkbox_group" data-validation-qty="min1"
                           data-validation-error-msg="You must select the checkbox to acknowledge deleting the listing">
                    You Must Check to Acknowledge Deleting Listing
                </label>

                <div id="deleteListing-button"></div>
            </div>
        </div>
    </div>
</form>
</div>

</div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="dist/js/bootstrap.min.js"></script>

<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.1.47/jquery.form-validator.min.js"></script>

<script src="include/createListing.js"></script>
<!--/span-->


</body>
</html>
