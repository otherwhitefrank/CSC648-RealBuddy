<?php
include_once 'include/db.php';
include_once "backend/userManager.php";
include_once "checkPermission.php";
checkPermission("guest");

$userManager = new userManager();

$sendername = 'RealBuddy';
$content = 'Password Reset';
$redirect_query = "login.php";

if ($_SERVER['REQUEST_METHOD'] === "POST") {
  if (empty($_POST['email'])) {
?>
    <script language="javascript" type="text/javascript">
    alert("Please enter your email address");
    </script>
<?php
  } else {
    $email = $_POST['email'];
    $user = $userManager->getUserByMail($email);

    if ($user == null) {
?>
      <script language="javascript" type="text/javascript">

      alert("No registered user with this email address!");

      </script>
<?php

    } else {
      // update credentials
      $randomPass = $userManager->generateRandomPassword();
      $updatepass = $userManager->updateCredentials($user, $randomPass);

      if ($updatepass != FALSE) { // on success
        $header = array();
        $header[] = "From: " . mb_encode_mimeheader($sendername, "utf-8", "Q") . " ";
        $header[] = "MIME-Version: 1.0";
        $header[] = "Content-type: text/plain; charset=utf-8";
        $header[] = "Content-transfer-encoding: 8bit";

        $mailtext = "Here is your new Password \n";
        $mailtext .= $randomPass;

        // send mail
        $m = mail(
          $user->email,
          mb_encode_mimeheader($content, "utf-8", "Q"),
          $mailtext,
          implode("\n", $header)
        );

        // check if mail got send
        if ($m) {
?>
          <script language="javascript" type="text/javascript">

          alert("We have sent you an email with your Password, please check your Mailbox! You will be redirected to login.");

          </script>
<?php
          header("Refresh: 1; URL=./login.php");
        } else {
          echo '<br><br>Mail has not been sent';
        }
        //header("Location: $redirect_query");
      }
    }
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


    <input type="hidden" name="source_page" value="results.html">
  </head>

  <body>
    <?php include "header.php"?>
    <div class="container-fluid">
      <div class="row">
        <div class="col-xs-10 col-md-6 col-lg-6 col-xs-offset-1 col-md-offset-3 col-lg-offset-3">
          <form class="form-horizontal main" role="form" action="" method="post">
            <div class="form-group">
              <div class="col-xs-10 col-md-6 col-lg-6 col-xs-offset-1 col-md-offset-3 col-lg-offset-3">
                <label for="email">Enter your Email address:</label>
                <input type="email" style="width: 100%;" class="form-control" name="email"
                                                                              placeholder="Email"><br>
                                                                              <button type="submit" name="submit" class="btn btn-lg btn-primary" value="Upload">Reset
                                                                                Password
                                                                              </button>
              </div>
            </div>
          </form>
        </div>
        <!-- span -->
      </div>
      <?php include "footer.php";?>
  </body>
</html>
