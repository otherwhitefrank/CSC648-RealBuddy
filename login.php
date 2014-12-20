<?php
session_start();
include_once 'backend/userManager.php';
include_once "checkPermission.php";
checkPermission("guest");
$userManager = new userManager();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $userManager->getUserByMail($_POST['username']);
    $password = $_POST['password'];

    if ($user != null && $userManager->checkPasswordFromUserId($user->id, $password)) {
        $_SESSION['userid'] = $user->id;

        header("location:results.php");

    } else {
        // Failure
        echo 'You entered a wrong Email. Please try again!';
    }
}
?>
<!DOCTYPE HTML>
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
    <link href="include/login.css" rel="stylesheet">


    <script src="http://cdn.jsdelivr.net/jquery.validation/1.13.1/jquery.validate.min.js"></script>
    <script src="http://cdn.jsdelivr.net/jquery.validation/1.13.1/additional-methods.min.js"></script>

    <script type="text/javascript"
            src="include/promptLogin.js">
    </script>


    <input type="hidden" name="source_page" value="results.php">
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


<?php include "header.php" ?>
<div class="container-fluid">
    <div id="content" class="container-fluid">

        <div class="col-xs-12 col-lg-offset-4 col-md-offset-4 col-sm-4 col-md-4 col-lg-4">
            <h3 class="dark-grey">Login</h3>

            <form id="main-search" class="form-horizontal" role="form" action="login.php" method="POST">
                <div class="form-group col-lg-12">
                    <label>Email: </label>
                    <input type="text" name="username" class="form-control" id="" value="">
                </div>

                <div class="form-group col-lg-12">
                    <label>Password: </label>
                    <input type="password" name="password" class="form-control" id="" value="">
                </div>
                <div class="form-group col-lg-12">
                    <button type="submit" class="btn btn-default">Login</button>
                </div>
                <div>
                    <a class="form-group col-lg-12" href="forgetPassword.php">Password forgotten?</a>
                </div>
            </form>
            <!-- row -->
        </div>

        <?php include "footer.php" ?>
</body>
</html>
