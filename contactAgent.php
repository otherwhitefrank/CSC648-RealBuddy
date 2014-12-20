<?php
session_start();
include_once "include/db.php";
include_once "backend/listing.php";
include_once "backend/listingManager.php";
include_once "checkPermission.php";
checkPermission("user");


$dbManager = DatabaseManager::getInstance();

$sendername = 'RealBuddy';
$content = 'User asking for Details';
$redirect_query = "results.php";
$mailtext="";

/** Check that the page was requested from itself via the POST method. */
if ($_SERVER['REQUEST_METHOD'] == "GET") {
  $id = $_GET['id'];
  $address = $_GET['address'];
  $city = $_GET['city'];
  $state = $_GET['state'];
  $zip = $_GET['zip'];
 
} if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $mailtext.="A user contacted you concerning the house in  "  .$_GET['address']. ", " .$_GET['city']. ", "  .$_GET['state']. ", "  .$_GET['zip'];
    $mailtext.="\n His name is " .$_POST['name'] ;
    $mailtext.= ". You can call him at this number: " .$_POST['phone'];
    $mailtext.= " or just write him an email at: " .$_POST['email'];
    $mailtext.= "\n Here is his Message: \n";
    $mailtext.= " ========================================= \n";
    $mailtext.=$_POST['message'];
    $sql_query = "SELECT * FROM listings WHERE listing_id = '". $_GET['id'] . "' ";
    $req1 = $dbManager->execute_query($sql_query);
    $req2 = mysqli_fetch_assoc($req1);
    $approver_id=$req2['approved'];
    //var_dump($approver_id) ;

    $sql_query2 = "SELECT * FROM credentials WHERE user_id = '". $approver_id . "' ";
    $req3 = $dbManager->execute_query($sql_query2);
    $req4 = mysqli_fetch_assoc($req3);
    $approver_email=$req4['email'];
    //var_dump($approver_email) ;
    
    $header = array();
                $header[] = "From: ".mb_encode_mimeheader($sendername, "utf-8", "Q")." ";
                $header[] = "MIME-Version: 1.0";
                $header[] = "Content-type: text/plain; charset=utf-8";
                $header[] = "Content-transfer-encoding: 8bit";
    
    $m=mail(    $approver_email, 
                mb_encode_mimeheader($content, "utf-8", "Q"), 
                $mailtext,
                implode("\n", $header)
                ) ;
    
    if($m)
                    {
                     
                    
                      header("Refresh: 1 ; URL=./results.php");
                      echo 'Your mail has been sent! You are being to the Results Page';
                    }
                    else
                    { ?>
                     <script language="javascript" type="text/javascript"> 

                    alert( "Your Mail has not been sent !" );

                    </script> 
                   <?php }
   
    
    
    exit ;
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

      <script src="include/contactAgent.js"></script>

      <div id="hidden-address" style="display: none;">
        <?php echo $address . ", " . $city . ", " . $state . ", " . $zip; ?>
      </div>

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
$listingManager = new ListingManager();
$listing = $listingManager->getListingById($id);
//var_dump($listing);
//Query for all images associated with listing, but only use the first image for the navbar results
$first_image = $listing[0]->image_ids[0];
$image_id = $first_image['image_id'];

echo "<div class=\"col-xs-6 col-md-6 col-lg-6\">";
echo "        <div id=\"current-listing\" class=\"main\">";
echo "            <img src= getImage.php?id=$image_id alt=\"Image2\" class=\"img-thumbnail\">";
echo "                <h4>Address: " . htmlentities($listing[0]->street) . "</h4>";
echo "                <h5>City: " . htmlentities($listing[0]->city) . "</h5>";
echo "                <h5>Price: " . "$" . htmlentities(number_format($listing[0]->list_price)) . "</h5>";
echo "                <h5>Sq. Feet: " . htmlentities($listing[0]->sq_feet) . "</h5>";
echo "                <h5>Num. Beds: " . htmlentities($listing[0]->num_beds) . "</h5>";
echo "                <h5>Num. Baths: " . htmlentities($listing[0]->num_baths) . "</h5>";
echo "        </div>";
echo " </div>";
echo "<div id=\"map-container\" class=\"col-xs-6 col-md-6 col-lg-6\">";
echo "    <div id=\"map-canvas\"></div>";
echo "</div>";
?>
        </div>


        <div class="col-xs-6 col-md-6 col-lg-6">

            <form class="form-editPage" role="form" enctype=multipart/form-data action=""
                                                                              method="POST">
            <div class="form-group col-lg-12">
                    <input type="text" class="form-control" id="listing_id"
                           name="listing_id" value="<?php $id; ?>" style="display: none;">
            </div>
            <div class="form-group">
              <label for="Inputlist_price">Your Name: </label>
              <input type="text" class="form-control" name="name" placeholder="Full Name">
            </div>
            <div class="form-group">
              <label for="Inputstreet">Email Address: </label>
              <input type="text" class="form-control" name="email" placeholder="Email">
            </div>
            <div class="form-group">
              <label for="Inputcity">Phone: </label>
              <input type="text" class="form-control" name="phone" placeholder="Phone Number">
            </div>
            <div class="form-group">
              <label for="Inputcity">Message: </label>

              <textarea class="form-control" name="message" rows="6" cols="30"></textarea>
            </div>

            <button class="btn btn-lg btn-primary" type="submit" name="submit" value='Upload'>Contact Agent</button>
          </form>
        </div>
        <!--/span-->
      </div>
      <!--/row-->

      <!--/span-->


      <!--/row-->
    </div>
<?php include "footer.php"?>
 </body>
</html>
