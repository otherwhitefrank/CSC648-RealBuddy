<?php
session_start();
include_once "backend/listing.php";
include_once "backend/user.php";
include_once "backend/image.php";
include_once "backend/listingManager.php";
include_once "backend/userManager.php";
include_once "checkPermission.php";
checkPermission("user");

// test setup! [[TEST_SETUP]]
include_once "include/db.php";
$dbManager = DatabaseManager::getInstance();
// end test setup!


/** Check that the page was requested from itself via the POST method. */
if ($_SERVER['REQUEST_METHOD'] == "POST") {
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

    $listingManager = new ListingManager();
    $userManager = new userManager();


    // FIXME: add map handler
    $new_address = $address . ", " . $city . ", " . $state . ", " . $zip;
    $url_address = str_replace(" ", "+", $new_address);
    //var_dump($url_address);
    $gmaps_api_url = "https://maps.googleapis.com/maps/api/geocode/json?address=" . $url_address . "?key=AIzaSyB-ssGXLniMzVEATITsDPKXIQXZ0ggEwqE";

    $du = file_get_contents($gmaps_api_url);
    $djd = json_decode(utf8_encode($du), true);

    //echo var_dump($djd);
    if (count($djd['results']) == 0) // no results
    {
      $search_lat = 0;
      $search_lon = 0;
    } else {

      $search_lat = $djd['results'][0]['geometry']['location']['lat'];
      $search_lon = $djd['results'][0]['geometry']['location']['lng'];
    }

    $listing = new Listing($user_id, $list_price, $address, $city, $state, $zip, $num_beds, $num_baths, $num_garages, $sq_feet, $listing_desc, 0, $search_lat, $search_lon);

//Save the images to the DB if they are present
    $arrImages = array();
    for ($i = 1; $i <= 5; $i++) {
        $pic_name = "picture{$i}";
        //Stop the entry if any kind of error happened when uploading
        if (isset($_FILES[$pic_name]['tmp_name'])) {
            if (is_uploaded_file($_FILES[$pic_name]['tmp_name'])) {
                $pic = $_FILES[$pic_name]['tmp_name'];
                $pic_caption = "picture{$i}_caption";
                $pic_caption = $_POST[$pic_caption];
                //var_dump($pic, $pic_Caption);
                $imgData = addslashes(file_get_contents($pic));
                $newImage = new image($listing->listing_id, $pic_caption, $imgData);
                $arrImages[] = $newImage;
            }
        }
    }
    $listing->images = $arrImages;

    //var_dump($listing);

    // if realtor, then approve it right now
    $user = $userManager->getUserById($user_id);

    /*if ($user->role == "agent") {
      $listing->approved = $user->id;
    }*/

    $id = $listingManager->addListing($listing);


    /* Handle file upload */


    //1500 Sullivan Ave, Daly City, CA 94015
    $redirect_query = "results.php?address=" . $address . ",+" . $city . ",+" . $state . ",+" . $zip;

    //Save new address in session so results.php refocuses on the inserted address
    $_SESSION['address'] = $address . ", " . $city . ", " . $state . ", " . $zip;

    /* Redirect browser */
    header("Location: " . $redirect_query);

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

    <!-- Bootstrap core CSS -->
    <link href="dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="include/createListing.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <input type="hidden" name="source_page" value="results.html">
</head>

<body>

<?php include "header.php" ?>
<div class="container-fluid">
    <div id="valid-error" class="col-xs-12 col-sm-12 col-md-12"></div>
    <div class="col-xs-12 col-sm-6 col-md-6">
        <div class="main">

            <h4 style="text-align: left">Please enter your houses listing information. All information requires approval
                from site moderators prior to posting on RealBuddy.</h4>


            <form id="createForm" class="form-editPage" role="form" enctype=multipart/form-data action="createListing.php"
                  method="POST">

                <div class="form-group col-lg-12">
                    <label for="address">Address: </label>
                    <input type="text" class="form-control"  id="address" name="address" placeholder="Address">
                </div>

                <div class="form-group col-lg-5">
                    <label for="city">City: </label>
                    <input type="text" class="form-control" data-validation="custom" data-validation-regexp="([A-Z]|[a-z])\w+"
                           data-validation-error-msg="City must be in form: San Francisco" id="city" name="city" placeholder="City">
                </div>
                <div class="form-group col-lg-3">
                    <label for="state">State: </label>
                    <input type="text" class="form-control" data-validation="custom" data-validation-regexp="([A-Z]|[a-z]){2}"
                           data-validation-error-msg="State must be in form: CA" id="state" name="state" placeholder="State">

                </div>
                <div class="form-group col-lg-4">
                    <label for="zip">Zip: </label>
                    <input type="number" class="form-control"  data-validation="custom" data-validation-regexp="([0-9]){5}"
                           data-validation-error-msg="Zip code must be in form: 94109" id="zip" name="zip" placeholder="Zip">
                </div>

                <div class="form-group col-lg-4">
                    <label for="list_price">List Price: </label>
                    <input type="number" class="form-control" data-validation="number" data-validation-allowing="range[50000;9999999999]"
                    data-validation-error-msg="List price must be in form: 1250000" id="list_price" name="list_price" placeholder="Enter Price">
                </div>
                <div class="form-group col-lg-4">
                    <label for="num_beds">Num. Beds: </label>
                    <input type="number" class="form-control" data-validation="number" data-validation-allowing="range[1;100]"
                    data-validation-error-msg="Num. Beds must be between 1 and 100" id="num_beds" name="num_beds" placeholder="Num Beds">
                </div>
                <div class="form-group col-lg-4">
                    <label for="num_baths">Num. Baths: </label>
                    <input type="number" class="form-control" data-validation="number" data-validation-allowing="range[1;100]"
                    data-validation-error-msg="Num. Baths must be between 1 and 100" id="num_baths" name="num_baths" placeholder="Num Baths">
                </div>
                <div class="form-group col-lg-4">
                    <label for="num_garages">Num. Garages: </label>
                    <input type="number" class="form-control" data-validation="number" data-validation-allowing="range[0;100]"
                           data-validation-error-msg="Num. Garages must be between 0 and 100" id="num_garages" name="num_garages" placeholder="Num Garages">
                </div>
                <div class="form-group col-lg-4">
                    <label for="sq_feet">Sq. Feet: </label>
                    <input type="number" class="form-control" data-validation="number" data-validation-allowing="range[1;99999999]"
                           data-validation-error-msg="Sq. Feet must be of form: 2400" id="sq_feet" name="sq_feet" placeholder="Sq. Feet">
                </div>
                <div class="form-group col-lg-12">
                    <label for="listing_desc">Listing Desc: </label>
                    <textarea class="form-control" name="listing_desc" id="listing_desc" rows="6" cols="30"></textarea>
                </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-6">
        <h5>Terms of Service: All information entered becomes the express property of RealBuddy Inc. and will be subject
            to approval. Please no offensive language or inappropriate pictures.</h5>
        <h5><b>You may enter up to five images per listing, to enter less simply leave the image slot empty</b></h5>

        <div class="form-group">
            <div class="col-xs-12 col-sm-6 col-md-6">
                <label for="Inputcity">Picture 1: </label>
                <input type="file" class="form-control" name="picture1" placeholder="Pic"
                       data-validation="mime size"
                       data-validation-allowing="jpg, png, gif"
                       data-validation-max-size="1024kb"
                       data-validation-error-msg="Images must be jpg, png, or gif and below 1024KB">
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6">
                <label for="Inputstreet">Picture 1 - Caption: </label>
                <input type="text" class="form-control" name="picture1_caption" placeholder="Picture 1 Caption">
            </div>
        </div>
        <div class="form-group">
            <div class="col-xs-12 col-sm-6 col-md-6">
                <label for="Inputcity">Picture 2: </label>
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


        <div class="col-xs-12 col-sm-offset-3 col-md-offset-3 col-sm-6 col-md-6">
            <button class="btn btn-lg btn-primary" id="submit-button" type="submit" value='Upload'>Create Listing</button>
        </div>
        </form>
    </div>
</div>
<!--/span-->

<!--/row-->

<!--/row-->

<!--Jquery-->
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.1.47/jquery.form-validator.min.js"></script>

<script src="include/createListing.js"></script>


<?php include "footer.php" ?>
</body>
</html>
