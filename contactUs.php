<?php
session_start();
include_once "include/db.php";
include_once "backend/listing.php";
include_once "backend/listingManager.php";
include_once "checkPermission.php";

$dbManager = DatabaseManager::getInstance();

$sendername = 'RealBuddy';
$content = 'User is contacting us with a general question';
$redirect_query = "results.php";
$mailtext = "";

/** Check that the page was requested from itself via the POST method. */
if ($_SERVER['REQUEST_METHOD'] == "GET") {
    if (isset($_GET['address'])) {
        $address = $_GET['address'];
    }
    if (isset($_GET['city'])) {
        $city = $_GET['city'];
    }
    if (isset($_GET['state'])) {
        $state = $_GET['state'];
    }
    if (isset($_GET['zip'])) {
        $zip = $_GET['zip'];
    }

}
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $mailtext .= "A user contacted you concerning a general question";
    $mailtext .= "\n His name is " . $_POST['name'] . "\n";
    $mailtext .= ". You can call him at this number: " . $_POST['phone'];
    $mailtext .= " or just write him an email at: " . $_POST['email'];
    $mailtext .= "\n\n Here is his Message: \n";
    $mailtext .= " ========================================= \n";
    $mailtext .= $_POST['message'];


    $header = array();
    $header[] = "From: " . mb_encode_mimeheader($sendername, "utf-8", "Q") . " ";
    $header[] = "MIME-Version: 1.0";
    $header[] = "Content-type: text/plain; charset=utf-8";
    $header[] = "Content-transfer-encoding: 8bit";

    $m = mail('fdye@sfsuswe.com',
        mb_encode_mimeheader($content, "utf-8", "Q"),
        $mailtext,
        implode("\n", $header)
    );

    if ($m) {


        header("Refresh: 1 ; URL=./results.php");
        echo 'Your mail has been sent! You are being to the Results Page';
    } else {
        ?>
        <script language="javascript" type="text/javascript">

            alert("Your Mail has not been sent !");

        </script>
    <?php
    }


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

    <script type="text/javascript"
            src="include/promptLogin.js">
    </script>

</head>

<body>

<?php include "header.php" ?>

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

<div class="container-fluid">
    <div class="row">
        <div id="main-content" class="col-xs-6 col-md-6 col-lg-6">

            <div class="col-xs-6 col-md-6 col-lg-6">

                <form class="form-editPage" role="form" enctype=multipart/form-data action=""
                      method="POST">

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

                    <button class="btn btn-lg btn-primary" type="submit" name="submit" value='Upload'>Contact Us
                    </button>
                </form>
            </div>
            <!--/span-->
        </div>
        <!--/row-->

        <!--/span-->


        <!--/row-->
    </div>
    <?php include "footer.php" ?>
</body>
</html>
