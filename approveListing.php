<?php
session_start();
include_once "backend/listingManager.php";
include_once "checkPermission.php";
checkPermission("guest");

if (isset($_SESSION['userid'])) {
    $userid = $_SESSION['userid'];
}


checkPermission("agent");
$listingManager = new ListingManager();
if (isset($_POST['deleteListingButton'])) // delete user
{
    $listingid = $_POST['deleteListingButton'];
    $listingManager->deleteListing($listingid);
} else if (isset($_POST['approveListingButton'])) {
    $listingid = $_POST['approveListingButton'];
    $listingManager->approveListing($listingid, $userid);
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
    <link href="include/approveListing.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>

    <![endif]-->


    <script src="include/approveListing.js"></script>
    <script src="include/results.js"></script>

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
        } else {
            $temp_user_id = -1;
        }
        echo $temp_user_id;
        ?>
    </div>

    <script type="text/javascript"
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB-ssGXLniMzVEATITsDPKXIQXZ0ggEwqE">
    </script>
</head>

<body>

<?php include "header.php" ?>

<div class="row">

    <div class="col-xs-4 sidebar-container panel panel-default">
        <div class="panel-heading text-center"><h4><u>Pending Listings</u></h4></div>

        <ul id="nav-top" class="nav nav-sidebar sidebar">
            <?php
            $allListings = $listingManager->getAllNotApprovedListings();

            if (count($allListings) <= 0) {
                //No results
                echo "<div>No Listings Pending Approval</div>";
            } else {

                $count = 0;

                foreach ($allListings as $listing) {
                    $encoded_id = "navbarListing" . $listing->listing_id;
                    if ($count == 0) {
                        echo "<li class=\"active\" id=\"" . $encoded_id . "\" onclick=\"clickListing(event, " . $listing->listing_id . ")\">";
                    } else {
                        echo "<li id=\"" . $encoded_id . "\" onclick=\"clickListing(event, " . $listing->listing_id . ")\">";
                    }


                    $first_image = $listing->image_ids[0];
                    $image_id = $first_image['image_id'];

                    //var_dump($images_info, $first_image, $image_id);
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
                    echo "    </a>";
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
            if (count($allListings) > 0) {

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
if (count($allListings) > 0) {

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

echo "<div class=\"col-xs-12 col-sm-12 col-md-12\">";
echo "    <form class=\"form-editPage\" role=\"form\" enctype=multipart/form-data method=\"POST\">";
echo "        <div class=\"form-group col-lg-12\">";
echo "            <label for=\"Inputstreet\">Approve Listing:</label>";

echo "        </div>";
echo "        <div class=\"form-group col-lg-12\">";
echo "            <div id=\"approveListing-button\"></div>";
echo "        </div>";
echo "    </form>";

echo "    <form class=\"form-editPage\" role=\"form\" enctype=multipart/form-data method=\"POST\">";
echo "        <div id=\"valid-error\" class=\"form-group col-lg-5\">";
echo "            <label for=\"Inputstreet\">Delete Listing:</label>";

echo "            <div class=\"has-warning\">";
echo "                <div class=\"checkbox\">";
echo "                    <label>";
echo "                        <input type=\"checkbox\" id=\"checkboxWarning\" value=\"option1\"" .
                            " name=\"terms_checkbox[]\" " .
                            "data-validation=\"checkbox_group\" data-validation-qty=\"min1\" ".
                             "  data-validation-error-msg=\"You must select the checkbox to acknowledge deleting the listing\">";
echo "    You Must Check to Acknowledge Deleting Listing";
echo "</label>";

echo "                    <div id=\"deleteListing-button\"></div>";
echo "                </div>";
echo "            </div>";
echo "        </div>";
echo "    </form>";
echo "</div>";

} else {
    echo "<div>No Listings Pending Approval</div>";
}
?>




</div>
</div>
</div>

<!--/Slider-->



</div>
<!--/span-->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="dist/js/bootstrap.min.js"></script>


<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.1.47/jquery.form-validator.min.js"></script>

<script src="include/createListing.js"></script>

</body>
</html>
