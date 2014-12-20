<?php
session_start();
include_once "backend/listing.php";
include_once "backend/listingManager.php";
include_once "checkPermission.php";
checkPermission("guest");


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $_SESSION['address'] = mysql_real_escape_string($_POST['address']);
  $_SESSION['min_price'] = "50000";
  $_SESSION['max_price'] = "250000000";
  $_SESSION['distance'] = "25";
  $_SESSION['num_bedrooms'] = "0";
  $_SESSION['num_bathrooms'] = "0";
  $_SESSION['num_garages'] = "0";

  $redirect_query = "results.php";

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

    <title>Software Engineering: Group 10</title>

    <!-- Bootstrap core CSS -->
    <link href="dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="include/home.css" rel="stylesheet">


    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>




      <![endif]-->

      <script src="include/promptLogin.js"></script>

  </head>

  <body>
  <!--Hidden message box that prompts to login -->
  <div id="loginSellPrompt" class="modal fade">
      <div class="modal-dialog">
          <div class="modal-content">
              <!-- dialog body -->
              <div class="modal-body">
                  <button id="dismissSellPopupButton" type="button" class="close" data-dismiss="modal">&times;</button>
                  Please register or login to your account to create a listing for your house
              </div>
              <!-- dialog buttons -->
              <div class="modal-footer">
                  <button id="loginSellPopupButton" type="buton" class="btn btn-primary prompt">Login</button>
                  <button id="registerSellPopupButton" type="button" class="btn btn-primary prompt">Register</button>
              </div>

          </div>
      </div>
  </div>


  <?php include "header.php"?>
    <div id="bg-image-holder" class="container-fluid">

      <div class="row row-offcanvas row-offcanvas-right">

        <div class="col-xs-12 col-md-12 col-lg-12">
          <p class="pull-right visible-xs">
          <button type="button" class="btn btn-primary btn-xs" data-toggle="offcanvas">Toggle nav</button>
          </p>


          <form id="main-search" class="form-horizontal" role="form" action="results.php" method="POST">
            <div id="address-form" class="form-group">

              <div class="col-sm-1 col-md-1 col-lg-1 col-sm-offset-3 col-md-offset-3 col-lg-offset-3">
                <label for="address" class="control-label">Search Houses:</label>
              </div>

              <div class="col-sm-6 col-md-6 col-lg-6">
                <div class="col-sm-9 col-md-9 col-lg-9">
                  <input type="text" class="form-control" id="address" name="address"
                                                                       placeholder="City, State, Address, or Zip">
                  <input type="hidden" name="source_page" value="index.html">
                </div>

                <div id="search-icon" class="col-sm-3 col-md-3 col-lg-3">
                  <button id="search-button" type="submit" class="btn"><span
                                                           class="glyphicon glyphicon-search"></span></button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>


      <div id="features">


<?php
$listingManager = new ListingManager();

$indexListings = $listingManager->getAllFeaturedListings();
//$indexListings = $listingManager->getAllListings();

// display up to 6 random listings
$randomKeys = array_rand($indexListings, 3);
//var_dump($randomKeys, $indexListings);
foreach ($randomKeys as $key) {
  $listing = $indexListings[$key];
  if ($listing->image_ids != NULL) {
    $first_image = $listing->image_ids[0];
    $image_id = $first_image['image_id'];

    $post_address = $listing->street . ", " . $listing->city . ", " . $listing->state;
    echo "<div class=\"col-md-1\"></div>";
    echo "<div class=\"col-md-3 no-padding lib-item\" data-category=\"ui\">";
    echo "<a href=\"results.php?address=" . $post_address . "\" class=\"features-link\">";
    echo "<div class=\"lib-panel\">";
    echo "<div class=\"row box-shadow\">";
    echo "<div class=\"col-md-6\">";
    echo "<img class=\"lib-img\" src=\"getImage.php?id=" . $image_id . "\">";
    echo "</div>";
    echo "<div class=\"col-md-6\">";
    echo "<div class=\"lib-row lib-header\">";
    echo "$" . number_format($listing->list_price);
    echo "<div class=\"lib-header-seperator\"></div>";
    echo "</div>";
    echo "<div class=\"lib-row lib-desc\">";
    echo "<h5>" . $listing->street . ", " . $listing->city . ", " . $listing->state . "</h5>";

    echo "</div>";
    echo "</div>";
    echo "</div>";
    echo "</a>";
    echo "</div>";
    echo "</div>";
  }
}
?>

      </div>


    </div>
  <?php include "footer.php"?>
  <div class="col-lg-12">
      <div class="col-lg-8 col-lg-offset-2">"Rows of Houses" &copy; Tory Byrne - Permission granted under fair use - freeimages.com</div>
  </div>
 </body>
</html>
