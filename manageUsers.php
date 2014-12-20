<?php
session_start();
include_once 'backend/userManager.php';
include_once 'backend/user.php';
include_once 'checkPermission.php';
checkPermission("admin");

//Define refine_search variables
$userManager = new userManager();
if (isset($_POST['deleteUserButton'])) // delete user
{
    $userid = $_POST['deleteUserButton'];
    $userManager->delete_user($userid);
} else if (isset($_POST['userRoleSelection'])) { // update user
    $formvalues = explode("_", $_POST['userRoleSelection']);
    $user = $userManager->getUserById($formvalues[0]);
    if ($user->role != "admin") {
        $user->role = $formvalues[1];
        $userManager->updateUser($user);
    }
    $currentUserId = $user->id;
}
// check again if the current action took of admin rights
checkPermission("admin");
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

    <title>Software Engineering: Group 10 - Manage users</title>

    <!--Jquery-->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

    <link href="dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="include/approveListing.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <script src="include/manageUsers.js"></script>

</head>

<body>
<?php include "header.php" ?>
<div class="row">

    <div class="col-xs-4 sidebar-container panel panel-default">
        <div class="panel-heading text-center"><h4><u>Users</u></h4></div>

        <ul id="nav-top" class="nav nav-sidebar sidebar">
            <?php
            $allUsers = $userManager->getAllUsers();


            if (count($allUsers) <= 0) {
                //No results
                echo "<div>No Results Returned</div>";
            } else {
                $count = 0;
                foreach ($allUsers as $user) {
                    $activeUser = "<li class=\"active\" onclick=\"clickUser(event, $user->userId)\">";
                    $inactiveUser = "<li onclick=\"clickUser(event, $user->userId)\">";
                    if (isset($currentUserId)) {
                        if ($user->userId == $currentUserId) {
                            echo $activeUser;
                        } else {
                            echo $inactiveUser;
                        }
                    } else {
                        if ($count++ == 0) {
                            echo $activeUser;
                        } else {
                            echo $inactiveUser;
                        }
                    }
                    //Create a hidden div with listing_id so we can change the content-body
                    echo "    <div class=\"value_user_id\" style=\"display: none;\">" . $user->userId . "</div>";

                    echo "    <a href=\" # \" " . ">";
                    echo "        <div class=\"navbar-photo\">";

                    echo "                <h5>Name: " . htmlentities($user->first_name) . " " . htmlentities($user->last_name) . "</h5>";
                    echo "                <h6>Email: " . htmlentities($user->email) . "</h6>";
                    echo "                <h6>Phone: " . htmlentities($user->phone) . "</h6>";

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
            <div class="col-xs-12 col-sm-6 col-md-6">

                <div class="col-sm-6 col-md-8">
                    <h4>
                        <div id="main-input-name"></div>
                    </h4>
                </div>
                <div class="col-sm-6 col-md-8">
                    <small>
                        <div id="main-input-street"></div>
                    </small>
                </div>
                <div class="col-sm-6 col-md-8">
                    <small>
                        <div id="main-input-address"></div>
                    </small>
                </div>
            </div>


        </div>
        <div class="col-xs-12 col-sm-6 col-md-6">
            <form class="form-editPage" role="form" enctype=multipart/form-data name="managedUserRole"
                  action="manageUsers.php" method="POST">

                <div class="form-group col-lg-12">
                    <label for="UserRole">User Role:</label>

                    <div id="main-updateRole-options"></div>
                </div>

                <div class="form-group col-lg-12">
                    <button type="submit" class="btn btn-success">Update User</button>
                </div>
            </form>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-6 col-md-offset-6 col-sm-offset-6">
            <div class="col-xs-12 col-sm-6 col-md-6" id="valid-error"></div>
            <form class="form-editPage" role="form" enctype=multipart/form-data name="deleteUser"
                  action="manageUsers.php" method="POST">

                <div class="form-group col-lg-12">
                    <label for="DeleteUser">Delete User:</label>

                    <div class="has-warning">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" id="checkboxWarning" value="option1"
                                       name="terms_checkbox[]"
                                       data-validation="checkbox_group" data-validation-qty="min1"
                                       data-validation-error-msg="You must acknowledge deleting the user">
                                You Must Check to Acknowledge Deleting User
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-group col-lg-12">
                    <div id="main-deleteUser-button"></div>
                </div>
            </form>
        </div>
    </div>
</div>
</div>


<!--/span-->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="dist/js/bootstrap.min.js"></script>

<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.1.47/jquery.form-validator.min.js"></script>

<script src="include/createListing.js"></script>
</body>
</html>
