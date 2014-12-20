<?php
session_start();
include_once "checkPermission.php";
checkPermission("guest");

if (isset($_SESSION['userid'])) {
  $userid = $_SESSION['userid'];
}

?>
<?php

include_once 'backend/favoritesManager.php';
include_once "backend/listingManager.php";
$listingManager = new ListingManager();
$favMan = new favoritesManager();


//Define refine_search variables
$address = refine_search_variable('address', "94109");
$min_price = refine_search_variable('min_price', 50000);
$max_price = refine_search_variable('max_price', 250000000);
$distance = refine_search_variable('distance', 25);
$num_bedrooms = refine_search_variable('num_bedrooms', 0);
$num_bathrooms = refine_search_variable('num_bathrooms', 0);
$num_garages = refine_search_variable("num_garages", 0);

function refine_search_variable($in, $default)
{
  $result = null;
  if (isset($_POST[$in])) {
    $result = $_POST[$in];
  } else if (isset($_GET[$in])) {
    $result = $_GET[$in];
  } else if (isset($_SESSION[$in])) {
    $result = $_SESSION[$in];
  } else {
    $result = $default;
  }
  if ($result == "") {
    //Check for empty set
    $result = $default;
  }
  $_SESSION[$in] = $result;
  return $result;
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
    <link href="include/results.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
      <![endif]-->

      <script src="include/results.js"></script>
      <!-- Load Favorite Manager -->
      <script src="include/favoritesManager.php.js"></script>

      <div id="hidden-address" style="display: none;">
        <?php echo $address; ?>
      </div>
      <div id="hidden-min-price" style="display: none;">
        <?php echo $min_price; ?>
      </div>
      <div id="hidden-max-price" style="display: none;">
        <?php echo $max_price; ?>
      </div>
      <div id="hidden-distance" style="display: none;">
        <?php echo $distance; ?>
      </div>
      <div id="hidden-num-bedrooms" style="display: none;">
        <?php echo $num_bedrooms; ?>
      </div>
      <div id="hidden-num-bathrooms" style="display: none;">
        <?php echo $num_bathrooms; ?>
      </div>
      <div id="hidden-num-garages" style="display: none;">
        <?php echo $num_garages; ?>
      </div>
      <div id="hidden-user-id" style="display: none;">
          <?php
          if (isset($userid)) {
            $temp_user_id = $userid;
          }
          else
          {
            $temp_user_id = -1;
          }
          echo $temp_user_id;
          ?>
      </div>
      <script type="text/javascript"
              src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB-ssGXLniMzVEATITsDPKXIQXZ0ggEwqE">
      </script>
      <script type="text/javascript"
              src="include/gmap.js">
      </script>
      <script type="text/javascript"
              src="include/promptLogin.js">
      </script>
  </head>

  <body>

  <!--Hidden message box that prompts to login -->
  <div id="loginPrompt" class="modal fade">
      <div class="modal-dialog">
          <div class="modal-content">
              <!-- dialog body -->
              <div class="modal-body">
                  <button id="dismissPopupButton" type="button" class="close" data-dismiss="modal">&times;</button>
                  Please register or login to your account to save your favorite listings
              </div>
              <!-- dialog buttons -->
              <div class="modal-footer">
                  <button id="loginPopupButton" type="buton" class="btn btn-primary">Login</button>
                  <button id="registerPopupButton" type="button" class="btn btn-primary">Register</button>
              </div>

          </div>
      </div>
  </div>

  <!--Hidden message box that prompts to login -->
  <div id="loginContactPrompt" class="modal fade">
      <div class="modal-dialog">
          <div class="modal-content">
              <!-- dialog body -->
              <div class="modal-body">
                  <button id="dismissContactPopupButton" type="button" class="close" data-dismiss="modal">&times;</button>
                  Please register or login to your account to contact an agent about this listing
              </div>
              <!-- dialog buttons -->
              <div class="modal-footer">
                  <button id="loginContactPopupButton" type="buton" class="btn btn-primary">Login</button>
                  <button id="registerContactPopupButton" type="button" class="btn btn-primary">Register</button>
              </div>

          </div>
      </div>
  </div>

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
                  <button id="loginSellPopupButton" type="buton" class="btn btn-primary">Login</button>
                  <button id="registerSellPopupButton" type="button" class="btn btn-primary">Register</button>
              </div>

          </div>
      </div>
  </div>

  <?php include "header.php"?> 
        <div class="container-fluid body">
      <div id="main-row" class="row">
        <div id="map-container"
             class="col-lg-4">
          <div class="row">
            <div id="map-canvas"></div>
          </div>
        </div>

        <div id="search-box" class="col-lg-8 ">
          <div class="row col-sm-12">

            <form class="navbar-form navbar-left" name="refine_search" method="post" action="results.php"
                                                                                     role="form" enctype="multipart/form-data">

              <div class="row row-search">


                <div class="col-lg-12">
                  <label class="control-label">Search Houses:</label>

                  <div class="form-group">
                    <input id="input-address" type="text" style="" name="address"
                                                                   class="form-control"
                                                                   placeholder="Search">
                  </div>
                  <div class="form-group">
                    <input type="hidden" name="source_page" value="results.html">
                    <button type="submit" id="search-button" class="btn btn-default"><span
                                                             class="glyphicon glyphicon-search"></span></button>
                  </div>


                </div>




                <div class="col-lg-12">

                  <div class="form-group">
                    <label class="control-label">Min. Price</label>
                    <select id="input-min-price" class="form-control" name="min_price">
                      <option value="50000" selected="selected">$50,000+</option>
                      <option value="100000">$100,000+</option>
                      <option value="150000">$150,000+</option>
                      <option value="200000">$200,000+</option>
                      <option value="250000">$250,000+</option>
                      <option value="300000">$300,000+</option>
                      <option value="350000">$350,000+</option>
                      <option value="400000">$400,000+</option>
                      <option value="450000">$450,000+</option>
                      <option value="500000">$500,000+</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label class="control-label">Max. Price</label>
                    <select id="input-max-price" class="form-control" name="max_price">
                      <option value="550000">$550,000+</option>
                      <option value="600000">$600,000+</option>
                      <option value="650000">$650,000+</option>
                      <option value="700000">$700,000+</option>
                      <option value="750000">$750,000+</option>
                      <option value="800000">$800,000+</option>
                      <option value="850000">$850,000+</option>
                      <option value="900000">$900,000+</option>
                      <option value="950000">$950,000+</option>
                      <option value="250000000" selected="selected">Any Price+</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label class="control-label">Distance</label>
                    <select id="input-distance" class="form-control" name="distance">
                      <option value="5">5 mi.</option>
                      <option value="10">10 mi.</option>
                      <option value="15">15 mi.</option>
                      <option value="20">20 mi.</option>
                      <option selected="selected" value="25">25 mi.</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label class="control-label"># Beds</label>
                    <select id="input-num-bedrooms" class="form-control" name="num_bedrooms">
                      <option value="0" selected="selected">0+</option>
                      <option value="1">1+</option>
                      <option value="2">2+</option>
                      <option value="3">3+</option>
                      <option value="4">4+</option>
                      <option value="5">5+</option>
                      <option value="6">6+</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label class="control-label"># Baths</label>
                    <select id="input-num-bathrooms" class="form-control" name="num_bathrooms">
                      <option value="0" selected="selected">0+</option>
                      <option value="1">1+</option>
                      <option value="2">2+</option>
                      <option value="3">3+</option>
                      <option value="4">4+</option>
                      <option value="5">5+</option>
                      <option value="6">6+</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label class="control-label"># Car Garage</label>
                    <select id="input-num-garages" class="form-control" name="num_garages">
                      <option value="0" selected="selected">0+</option>
                      <option value="1">1+</option>
                      <option value="2">2+</option>
                      <option value="3">3+</option>
                      <option value="4">4+</option>
                      <option value="5">5+</option>
                      <option value="6">6+</option>
                    </select>
                  </div>

                </div>


              </div>

            </form>
          </div>
        </div>
      </div>
    </div>


    <div class="row">

      <div class="col-lg-4 sidebar-container panel panel-default">
        <div class="panel-heading text-center"><h4><u>Results</u></h4></div>

        <ul id="nav-top" class="nav nav-sidebar sidebar">
<?php
$url_address = str_replace(" ", "+", $address);


$gmaps_api_url = "https://maps.googleapis.com/maps/api/geocode/json?address=" . $url_address . "?key=AIzaSyB-ssGXLniMzVEATITsDPKXIQXZ0ggEwqE";

//var_dump($gmaps_api_url);
$du = file_get_contents($gmaps_api_url);


$djd = json_decode(utf8_encode($du), true);


if ($djd['status'] == 'ZERO_RESULTS') {
  //No results found from google, invalid address
  $result = array();
   
 // $result = $listingManager->getListingsByAddr($address);

    
} else {
  $search_lat = $djd['results'][0]['geometry']['location']['lat'];
  $search_lon = $djd['results'][0]['geometry']['location']['lng'];
  $search_radius = $distance;
    //var_dump($min_price, $max_price, $search_radius, $num_bedrooms, $num_bathrooms, $num_garages);
  $result = $listingManager->getListingsByAttr($search_lat, $search_lon, $search_radius, $num_bedrooms, $num_bathrooms, $num_garages, $min_price, $max_price);
    //var_dump($result);
}


if (count($result) == 0) { // no data available
  //No results
  echo "<div>No Listings Near This Address</div>";
  
} else {

  $count = 0;

  foreach ($result as $listing) {
    if ($listing->approved >= 0){
      $encoded_id = "navbarListing" . $listing->listing_id;
      if ($count == 0) {
        echo "<li class=\"active\" id=\"" . $encoded_id . "\" onclick=\"clickListing(event, " . $listing->listing_id . ")\">";
      } else {
        echo "<li id=\"" . $encoded_id . "\" onclick=\"clickListing(event, " . $listing->listing_id . ")\">";
      }


      $first_image = $listing->image_ids[0];
      $image_id = $first_image['image_id'];

      //Create a hidden div with listing_id so we can change the content-body
      //echo "    <div class=\"value_listing_id\" style=\"display: none;\">" . $listing->listing_id . "</div>";

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


      if (isset($userid)) {
            $temp_user_id = $userid;
        }
        else
        {
            $temp_user_id = -1;
        }


        if ($favMan->checkUserPropertyFavorit($temp_user_id, $listing->listing_id) == 0) {
          echo "<div id=\"listFavID" . $listing->listing_id . "\" onClick=\"switchFavoriteStatus(" .$temp_user_id . "," . $listing->listing_id . ", 0);\" class=\"favClass glyphicon glyphicon-star-empty\"  aria-hidden=\"true\"></div>";
        } else {
          echo "<div id=\"listFavID" . $listing->listing_id . "\" onClick=\"switchFavoriteStatus(" .$temp_user_id . "," . $listing->listing_id . ", 1);\"class=\"favClass glyphicon glyphicon-star\" aria-hidden=\"true\"></div>";
        }


      echo "</li>";
    }
  }
}
?>
        </ul>

      </div>
      <!--/span-->

      <div id="main-content"
           class="col-lg-8 col-lg-offset-4">


        <div id="main_area">

<?php
if (count($result) > 0) {

  echo "<div class=\"col-lg-6\" id=\"slider\">";
  echo "<div class=\"row\">";
  echo "<div class=\"col-lg-7\" id=\"carousel-bounding-box\">";
  echo "<div class=\"carousel slide\" id=\"myCarousel\">";

  echo "<div id=\"carousel-inner\" class=\"carousel-inner\">";

  //Query for all images associated with listing, but only use the first image for the navbar results


  $counter = 0;
  echo "<div class=\"active item\" data-slide-number=\"" . $counter . "\">";
  foreach ($listing->image_ids as $image_id_entry) {

    $image_id = $image_id_entry['image_id'];
    $image_caption = $image_id_entry['caption'];
    echo "<div class=\"item\" data-slide-number=\"" . ++$counter . "\">";
    echo "<img src=\"getImage.php?id=" . $image_id . "\"></div>";
  }


  echo "</div>";
  echo "</div>";


  echo "<a class=\"left carousel-control\" href=\"#myCarousel\" role=\"button\" data-slide=\"prev\">";
  echo "<span class=\"glyphicon glyphicon-chevron-left\"></span>";
  echo "</a>";
  echo "<a class=\"right carousel-control\" href=\"#myCarousel\" role=\"button\" data-slide=\"next\">";
  echo "<span class=\"glyphicon glyphicon-chevron-right\"></span>";
  echo "</a>";

  echo "<div class=\"col-lg-12\" id=\"carousel-text\"></div>";

  echo "<div id=\"slide-content\" style=\"display: none;\">";

  //Query for all images associated with listing, but only use the first image for the navbar results

  $counter = 0;
  foreach ($listing->image_ids as $value) {
    $image_id = $value['image_id'];
    $image_caption = $value['caption'];

    echo "<div id=\"slide-content-" . $counter++ . "\">";
    echo "<h2>" . $image_caption . "</h2>";
    echo "</div>";
  }
?>

             </div>
                   </div>
                 </div>
               </div>
               <!--/Slider-->


<?php
  if (count($result) > 0) {

    echo "<div class=\"row hidden-lg\" id = \"slider-thumbs\" >";

    echo "<ul id = \"hide-bullets\" class=\"hide-bullets\" >";

    $counter = 0;
    foreach ($listing->image_ids as $value) {
      $image_id = $value['image_id'];
      $image_caption = $value['caption'];

      echo "<li class=\"thumbnail-container col-sm-1\">";
      echo "<a class=\"thumbnail\" id=\"carousel-selector-" . $counter++ . "\"><img src=\"getImage.php?id=" . $image_id . "\"></a>";
      echo "</li>";
    }


    echo "</ul>";
    echo "</div >";
  }

  echo "</div>";


  echo "<div id=\"main-meta-data2\" class=\"col-lg-6\">";
  echo "</div>";
} else {
  echo "<div>No Listings Near This Address</div>";
}
?>



        </div>
      </div>
    </div>
    </div>
    <!--/Slider-->

    <div id="main-meta-data" class="col-lg-12">
      <!--A place holder for ajax code-->
    </div>
    <div id="main-meta-data2" class="col-lg-12">
      <!--A place holder for ajax code-->
    </div>
    </div>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  <script src="dist/js/bootstrap.min.js"></script>


  <!--/span-->
 </body>
</html>
