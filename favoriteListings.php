<?php
session_start();
include_once "checkPermission.php";
checkPermission("user");

if (isset($_SESSION['userid'])) {
    $userid = $_SESSION['userid'];
}

?>

<?php

include_once "include/db.php";
include_once 'backend/favoritesManager.php';

$favManager = new favoritesManager();


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
    <link href="include/favoriteListing.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
      <![endif]-->


      <script src="include/favoriteListing.js"></script>

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
  </head>

  <body>

    <?php include "header.php"?> 
    <div class="row">

      <div class="col-xs-4 sidebar-container panel panel-default">
        <div class="panel-heading text-center"><h4><u>Your Listings</u></h4></div>

        <ul id="nav-top" class="nav nav-sidebar sidebar">
<?php

$userid = $_SESSION['userid'];
$result = $favManager -> get_listID($userid);


//            $url_address = str_replace(" ", "+", $address);
//
//
//            $gmaps_api_url = "https://maps.googleapis.com/maps/api/geocode/json?address=" . $url_address . "?key=AIzaSyAtzwBM6mqjLD5HOk4NlGKR1R0uJ_jyF-U";
//
//            //var_dump($gmaps_api_url);
//            $du = file_get_contents($gmaps_api_url);
//
//
//            $djd = json_decode(utf8_encode($du), true);
//
//            if ($djd['status'] == 'ZERO_RESULTS') {
//                //No results found from google, invalid address
//                $result = array();
//            } else {
//                $search_lat = $djd['results'][0]['geometry']['location']['lat'];
//                $search_lon = $djd['results'][0]['geometry']['location']['lng'];
//                $search_radius = $distance;
//                $result = $listingManager->getListingsByAttr($search_lat, $search_lon, $search_radius, $num_bedrooms, $num_bathrooms, $num_garages, $min_price, $max_price);
//            }


if (count($result) == 0) { // no data available
  //No results
  echo "<div>You haven't favored any listings yet</div>";
} else {
  echo "<li class=\"active\" >";
  foreach ($result as $listing) {

    $id = $listing->listing_id;
    echo "<li class=\"active\" id=\"" . $id . "\" onclick=\"clickListing(event, " . $listing->listing_id . ")\">";
 
   
    //echo "<li onclick=\"deleteFavorite(" .$userid.", " . $id . ")\" id=\"listing_" . $id . "\">";



    $first_image = $listing->image_ids[0];
    $image_id = $first_image['image_id'];

    //var_dump($images_info, $first_image, $image_id);
    //Create a hidden div with listing_id so we can change the content-body
    echo "    <div class=\"value_listing_id\" style=\"display: none;\">" . $listing->listing_id . "</div>";

    echo "    <a href=\"#\" >";
    echo "        <div class=\"navbar-photo\">";
    echo "        <img src=\"getImage.php?id=" . $image_id . "\" alt=\"Image2\" class=\"img-thumbnail navbar-thumb\">";


    echo "                <h5>Price: " . "$" . htmlentities(number_format($listing->list_price)) . "</h5>";
    echo "                <h6>Address: " . htmlentities($listing->street) . "</h6>";
    echo "                <h6>City: " . htmlentities($listing->city) . "</h6>";

    echo "                <h6>Sq. Feet: " . htmlentities($listing->sq_feet) . "</h6>";
    echo "                <h6>Num. Beds: " . htmlentities($listing->num_beds) . "</h6>";
    echo "                <h6>Num. Baths: " . htmlentities($listing->num_baths) . "</h6>";

    echo "        </div>";

  
    if ($favManager->checkUserPropertyFavorit($userid, $listing->listing_id) == 0) {
          echo "<div id=\"listFavID" . $listing->listing_id . "\" onClick=\"switchFavoriteStatus(" .$userid . "," . $listing->listing_id . ", 0);\" class=\"favClass glyphicon glyphicon-star-empty\"  aria-hidden=\"true\"></div>";
        } else {
          echo "<div id=\"listFavID" . $listing->listing_id . "\" onClick=\"switchFavoriteStatus(" .$userid . "," . $listing->listing_id . ", 1);\"class=\"favClass glyphicon glyphicon-star\" aria-hidden=\"true\"></div>";
        }
        
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

                <?php
                if (count($result) > 0) {

                echo "<div class=\"col-xs-6\" id=\"slider\">";
                echo "<div class=\"row\">";
                echo "<div class=\"col-xs-7\" id=\"carousel-bounding-box\">";
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

                echo "<div class=\"col-xs-12\" id=\"carousel-text\"></div>";

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

        echo "<div class=\"row hidden-xs\" id = \"slider-thumbs\" >";

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


    echo "<div id=\"main-meta-data2\" class=\"col-xs-6\">";
    echo "</div>";
    } else {
        echo "<div>No favored listings yet!</div>";
    }
    ?>



    </div>
    </div>
    </div>
    </div>
    <!--/Slider-->

    <div id="main-meta-data" class="col-xs-12">
        <!--A place holder for ajax code-->
    </div>
    <div id="main-meta-data2" class="col-xs-12">
        <!--A place holder for ajax code-->
    </div>
    </div>

    <!--/span-->

        <!--/span-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="dist/js/bootstrap.min.js"></script>
 </body>
</html>
