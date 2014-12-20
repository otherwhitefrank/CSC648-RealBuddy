<?php
include_once "backend/userManager.php";
$user = null;
if (isset($_SESSION['userid'])) {
    $userManager = new UserManager();
    $user = $userManager->getUserById($_SESSION['userid']);
}
?>



<div class="navbar navbar-fixed-top navbar-inverse" role="navigation">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.php">HomeBuddy</a>
        </div>
        <ul class="nav navbar-nav">

        </ul>


        <ul class="nav navbar-nav">
            <li id="sell-house">

                <?php
                if ($user != null) {
                    echo "<button id=\"sell-house-button\" onclick=\"window.location.href='createListing.php'\" type=\"button\"" .
                        "class=\"btn btn-danger\" href=\"createListing.php\">Sell Your House Today!" .
                        "</button>";

                } else {
                    echo "<button id=\"sell-house-button\" onclick=\"promptSellLogin();\" type=\"button\"" .
                        "class=\"btn btn-danger\" href=\"createListing.php\">Sell Your House Today!" .
                        "</button>";
                }

                ?>

            </li>
            <!--
            <li id="buy-house">
                <?php
                echo "<button id=\"buy-house-button\" onclick=\"window.location.href='results.php'\" type=\"button\"" .
                    "class=\"btn btn-primary\" href=\"results.php\">Buy A House Today!" .
                    "</button>";

                ?>
            </li>
            -->

        </ul>
        <div class="collapse navbar-collapse ">


            <ul class="nav navbar-nav pull-right">
                <!-- guests shall be able to search-->
                <li><a href="results.php">Search</a></li>
                <?php
                if ($user != null) {
                    switch ($user->role) {
                        case "admin":
                            echo "<li><a href=\"manageUsers.php\">Manage users</a></li>";
                            echo "<li><a href=\"approveListing.php\">Approve listings</a></li>";
                        case "agent":
                            echo "<li><a href=\"editListing.php\">Edit listings</a></li>";
                        case  "user":
                            echo "<li><a href=\"createListing.php\">Create listings</a></li>";
                            echo "<li><a href=\"favoriteListings.php\">Favorites</a></li>";
                            echo "<li><a href=\"userProfile.php\">Your Profile</a></li>";
                        default:
                            echo "<li><a href=\"logout.php\">Logout</a></li>";

                    }
                } else { // no valid user
                    echo "<li><a href=\"login.php\">Login</a></li>";
                    echo "<li><a href=\"registerUser.php\">Register</a></li>";
                }
                ?>
            </ul>
        </div>
        <!-- /.nav-collapse -->
    </div>
    <!-- /.container -->
</div>
<!-- /.navbar -->


