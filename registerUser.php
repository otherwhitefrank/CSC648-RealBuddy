<?php
include_once "backend/userManager.php";
include_once "checkPermission.php";
checkPermission("guest");
$registerErrorMessage = "";
$userManager = new userManager();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST["firstname"];
    $last_name = $_POST["lastname"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $street = $_POST["street"];
    $city = $_POST["city"];
    $state = $_POST["state"];
    $zip = $_POST["zip"];
     if($_POST["password"] != $_POST["repassword"]) {
            ?>
                <script language="javascript" type="text/javascript"> 

                alert( "Passwords don't match" );
 
                </script> 
            <?php
            }
    elseif ($_POST["email"] != $_POST["remail"]) {
            ?>
                <script language="javascript" type="text/javascript"> 

                alert( "Emails don't match" );
 
                </script> 
             <?php    
             }
    else {
    $user = new User(0, $first_name, $last_name, $email, $phone, $street, $city, $state, $zip, "user");
    $userid = $userManager->create_user($user, $_POST['password']);
    if ($userid != null) {
        //Success-> Redirection to Login Page
        header("Location: login.php");
    } else {
        // Failure
        $registerErrorMessage = 'Registration failed. The email address you have chosen already exists! Please try again.';
    }
    }
}

function setField($fieldName){
  if(isset($_POST[$fieldName])){
    echo $_POST[$fieldName];
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
        <link href="include/registerUser.css" rel="stylesheet">

        <script src="http://cdn.jsdelivr.net/jquery.validation/1.13.1/jquery.validate.min.js"></script>
        <script src="http://cdn.jsdelivr.net/jquery.validation/1.13.1/additional-methods.min.js"></script>

        <script type="text/javascript"
                src="include/promptLogin.js">
        </script>

        <input type="hidden" name="source_page" value="results.html">
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
    <div  id="content" class="container-fluid">

        <form method="POST" action="registerUser.php"  id="register_form" name="reg">

            <div id="valid-error" class="col-xs-12 col-sm-12 col-md-12"></div>
            <div class="col-xs-12 col-sm-6 col-md-6">
                <h3 class="dark-grey">Registration</h3>


                <div class="form-group col-lg-6">
                    <label>First Name</label>
                    <input type="text" name="firstname"
                           data-validation="custom"
                           data-validation-regexp="([A-Z]|[a-z]|-)*"
                           data-validation-error-msg="Names must only be letters, and contain no spaces or hyphens"
                           class="form-control" id="firstname" value="<?php setField("firstname");?>" required="true"/>
                </div>

                <div class="form-group col-lg-6">
                    <label>Last Name</label>
                    <input type="text" name="lastname"
                           data-validation="custom"
                           data-validation-regexp="([A-Z]|[a-z]|-)*"
                           data-validation-error-msg="Names must only be letters, and contain no spaces or hyphens"
                           class="form-control" id="lastname" value="<?php setField("lastname")?>" required="true">
                </div>

                <div class="form-group col-lg-6">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" id="password" required="true">
                </div>

                <div class="form-group col-lg-6">
                    <label>Repeat Password</label>
                    <input type="password" name="repassword" class="form-control" id="repassword"  required="true">
                </div>

                <div class="form-group col-lg-6">
                    <label>Email Address</label>
                    <input type="email" name="email" data-validation="email" class="form-control" id="email" value="<?php setField("email")?>"  required="true">
                </div>

                <div class="form-group col-lg-6">
                    <label>Repeat Email Address</label>
                    <input type="email" name="remail" data-validation="email" class="form-control" id="remail" value="" required=true >
                </div>


                <div class="form-group col-lg-6">
                    <label>Phone number</label>
                    <input type="phone" name="phone"
                           data-validation="custom"
                           data-validation-regexp="[0-9/. \-]+ "
                           data-validation-error-msg="Phone must be in form: +1 12345"
                           class="form-control" id="phone" value="<?php setField("phone");?>" >
                </div>


                <div class="form-group col-lg-12">
                    <label>Address</label>
                    <input type="text" name="street" class="form-control" id="street" value="<?php setField("street");?>" >
                </div>

                <div class="form-group col-lg-6">
                    <label>City</label>
                    <input type="text" name="city"
                           data-validation="custom"
                           data-validation-regexp="([A-Z]|[a-z])\w+"
                           data-validation-error-msg="City must be in form: San Francisco" id="city" name="city"
                           class="form-control" id="city" value="<?php setField("city");?>" >
                </div>

                <div class="form-group col-lg-3">
                    <label>State</label>
                    <input type="text" name="state"
                           data-validation="custom"
                           data-validation-regexp="([A-Z]|[a-z]){2}"
                           data-validation-error-msg="State must be in form: CA" id="state" name="state"
                           class="form-control" id="state" value="<?php setField("state");?>" >
                </div>

                <div class="form-group col-lg-3">
                    <label>Zip</label>
                    <input type="text" name="zip"
                           data-validation="custom"
                           data-validation-regexp="([0-9]){5}"
                           data-validation-error-msg="Zip code must be in form: 94109" id="zip" name="zip"
                           class="form-control" id="zip" value="<?php setField("zip");?>" >
                </div>

            </div>

            <div class="col-xs-12 col-sm-6 col-md-6">
                <h2 class="dark-grey">Terms of Use</h2>

                <div class="col-sm-12" style="height:400px;overflow:auto">

                    <p>
                        Welcome to HomeBuddy! By utilizing of HomeBuddy’s products and services (web sites, apps),
                        you agree to be bound to the following terms of use.  Please read these terms in full as 
                        they govern your use of these services.
                    </p>

                    <h4 class="dark-grey">Agreement to Terms</h4>
                    <p>
                        By using our site, you agree to the following terms.  Failure to uphold these terms will result in 
                        termination and membership with HomeBuddy:
                    </p>
                    <ol style="list-style-type:lower-alpha">
                        <li>You agree to use our site and services for your personal use.</li>
                        <li>You may print and download material from the website providing you do not 
                            modify or reproduce any content without written permission.</li>
                        <li>You may not promote or link to any external websites, brands, listings, comments 
                            without the written permission of the owners of HomeBuddy.</li>
                        <li>You may not use the HomeBuddy services in any way that is unlawful, or harms 
                            HomeBuddy, its service providers, or any users.</li>
                        <li>You may not share your account with others.  You are responsible for all actions taken on your account.</li>
                    </ol>

                    <h4 class="dark-grey">Material Provided by You</h4>
                    <p>
                        For materials you post or provide to HomeBuddy in connection with our service, 
                        you grant HomeBuddy a non-exclusive, worldwide, perpetual, irrevocable, fully paid, 
                        royalty-free, and fully sublicensable and transferable license to:
                    </p>
                    <ol style="list-style-type:lower-alpha">
                        <li>Use, copy, distribute, transmit, publicly display, publicly perform, reproduce, 
                            edit, modify, prepare derivative works and translate your submissions in connection 
                            with HomeBuddy services with the exception of personal information, which is covered 
                            under our <a href="privacy.php" target="_blank">privacy policy</a>.</li>
                        <li>Sublicense these rights to the maximum extent permitted by applicable law.</li>
                    </ol>
                    <p>
                        When using HomeBuddy, you shall not post, send to or from this website any material:
                    </p>
                    <ol style="list-style-type:lower-alpha">
                        <li>That is discriminatory, obscene, pornographic, defamatory, in breach of confidentiality
                            or privacy, which constitutes conduct that would be deemed a criminal offense, or otherwise
                            is contrary to the law.</li>
                        <li>That falsifies any information related to you, your contact information, your listings, 
                            or any other users related to HomeBuddy.</li>
                    </ol>  

                    <h4 class="dark-grey">The Role of HomeBuddy</h4>
                    <p>
                        We at HomeBuddy, Inc. provides a service that allows consumers to buy and sell houses 
                        with a reputable real estate agent.  You will be able to search and find similar listings
                        with the information you provide.  With every listing comes a representative agent that 
                        will provide more information of a given listing.  While searching for listings is not 
                        restricted to only registered users of HomeBuddy, any other services such as buying, 
                        selling, contacting an agent are restricted to only registered users.
                    </p>

                    <h4 class="dark-grey">Privacy</h4>
                    <p>
                        Please refer to our <a href="privacy.php" target="_blank">Privacy Policy</a> 
                        for information on how we collect, use, and disclose information from our users.
                    </p>

                    <h4 class="dark-grey">Disclaimer and Liability</h4>
                    <p>
                        While we do take all reasonable steps to make sure that all information 
                        on this website is up to date and accurate at all times, we do not guarantee 
                        that all material will be accurate or up to date at your time of access.  All 
                        material on this website is provided without warranty of any kind.  Using the 
                        material on this website is at your own discretion.  We do not accept 
                        liability for any loss or damage that you suffer as a result of using this website.  
                    </p>

                    <h4 class="dark-grey">Changes to the Terms of Service</h4>
                    <p>
                        We reserve the right to change the Terms of Service at any point.  
                        In the event of a change in the Terms of Service, we shall notify you the change via email.  
                    </p>

                    <h4 class="dark-grey">Contact Information</h4>
                    <p>
                        For any enquiries, contact us via:
                    </p>
                    <ul>
                        <li>Email: HomeBuddyinfo@HomeBuddy.com</li>
                        <li>Phone: (321) 555 - 1421</li>
                        <li>Address: HomeBuddy Inc, 321 Kross Street, Somewhere, CA 92121</li>
                    </ul> 
                </div>

                <div class="col-sm-6">
                    <label id="checkbox-label" for="terms_checkbox">
                        <input id="terms_checkbox" type="checkbox" name="terms_checkbox[]"
                               data-validation="checkbox_group" data-validation-qty="min1"
                               data-validation-error-msg="You must agree to the terms of service" class="checkbox"/>
                        I agree to the Terms and Conditions</label>
                </div>

                <div class="col-sm-6">
                    <button type="submit" class="btn btn-primary">Register</button>
                </div>

                <div class="col-sm-12">
                  <font color="red"><?php echo $registerErrorMessage?></font>
                </div>
            </div>
        </form>

    </div>

    <?php include "footer.php" ?>



<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.1.47/jquery.form-validator.min.js"></script>

<script src="include/createListing.js"></script>

</body>
</html>