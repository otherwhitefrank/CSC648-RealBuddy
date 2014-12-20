<?php
session_start();
include_once "checkPermission.php";
checkPermission("user");

$userManager = new userManager();
$passwordChangeMessage = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userid = $_SESSION['userid'];
    $password = $_POST['password'];
    $repassword = $_POST['repassword'];
    $old = $_POST['current_password'];

    if ($password != $repassword){
      $passwordChangeMessage = "New password and repeated password differ! Please try again.";
    } else {
      $user = $userManager->getUserById($userid);
      if ($user != null && $userManager->changePassword($user, $old, $password) != FALSE) {
        $passwordChangeMessage = "Password updated properly!";
      } else {
        // Failure
        $passwordChangeMessage = 'Old password not correct!';
      }
    }
}

?>
<html>
  <head>
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


  </head>
  <body>
<?php 
include "header.php";
include_once 'backend/user.php';
include_once 'backend/userManager.php';
if (isset($_SESSION['userid'])) :
  // prepare user data 
  $usrMgr = new userManager();
$user = $usrMgr->getUserById($_SESSION['userid']);
?>
              <div class="container">
                <div class="row">
                  <div class="col-xs-12 col-sm-6 col-md-6">
                    <div class="well well-sm">
                      <div class="row">

                        <div class="col-sm-6 col-md-8">
                          <h4><?php print $user->first_name . " " . $user->last_name ?> </h4>
                          <small>
                            <cite title=
                              <?php
                             $city = $user->city;
                             $state = $user->state;
                             print "\"$city, $state\">$city, $state"
                             ?>

                            </cite>
                          </small>
                          <p>
                          <i class="glyphicon glyphicon-envelope"></i>
                          <?php print $user->email; ?>


                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div  id="content" class="container-fluid">
                <h3> Change password </h3>
                <form method="POST" action="userProfile.php"  id="change_password_form" name="change_password">
                  <div class="col-xs-12 col-sm-6 col-md-6">

                    <div class="form-group col-lg-12">
                      <label>Current Password</label>
                      <input type="password" name="current_password" class="form-control" id="current_password">
                    </div>


                    <div class="form-group col-lg-6">
                      <label>New Password</label>
                      <input type="password" name="password" class="form-control" id="password">
                    </div>

                    <div class="form-group col-lg-6">
                      <label>Repeat New Password</label>
                      <input type="password" name="repassword" class="form-control" id="repassword" >
                    </div>
                      <div class="form-group col-lg-12">
                        <font color="blue"><?php echo $passwordChangeMessage ?></font>
                      </div> 

                      <div class="btn-group vertical">
                        <button type="submit" class="btn btn-primary">Change password</button>
                      </div>
                  </div>

                </form>
                <?php else : ?>
                <font color="red"> You're not logged in! </font>
                <?php endif; ?>
  </body>
  <?php include "footer.php"?>
</html>
