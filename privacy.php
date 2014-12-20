<?php
include_once "backend/userManager.php";
include_once "checkPermission.php";
checkPermission("guest");
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

        <script type="text/javascript"
                src="include/promptLogin.js">
        </script>


    <input type="hidden" name="source_page" value="results.html">
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
    <div  id="content" class="container-fluid">

        <form method="POST" action="registerUser.php"  id="register_form" name="reg">


            <div class="col-xs-12 col-sm-6 col-md-6">
                <h2 class="dark-grey">Privacy Policy</h2>
                <p>
                    Welcome!  We at Realbuddy are providing this Privacy Policy to explain our 
                    practices regarding collecting and using information from users of the Realbuddy service.  
                    Realbuddy respects the privacy of all of its users, and the following 
                    details how we use the information we collect from you. <br><br>
                </p>

                <h4 class="dark-grey">To Whom Does This Privacy Policy Apply?</h4>
                <p>
                    This Privacy Policy applies to anyone who uses our services, 
                    particularly to users who register to our site.<br><br>
                </p>

                <h4 class="dark-grey">What Do We Collect?</h4>
                <p> 
                    We collect any information you provide when using our services.  
                    This occurs when creating an account, submitting a listing to sell, 
                    or searching for houses via the search button.  In regards to registering
                    for our service, we ask you to provide us with certain personal identifiable
                    information such as your name, email, home address and phone number. 
                    We also keep a record of your favorite listings as well as any listings you submit.<br><br>
                </p>                

                <h4 class="dark-grey">How Do We Use Your Information? </h4>
                <p>
                    We use your information to provide a service to you.  When you request 
                    contact with a real estate agent, we provide personal information to 
                    the agent as a means of telling the agent who you are.  When searching 
                    for houses, we use the housing information you provide to list all of 
                    the possible listings for sale.  We may also use housing information you 
                    provide when displaying featured houses on Realbuddy’s home page.
                </p> 
                <h4 class="dark-grey">General Matters</h4>
                <p>
                    In the event of a change in your personal information, you can access and 
                    modify your information through your account settings or by contacting 
                    customer service.  We implore that you keep your information up to date 
                    as to keep providing a quality service to you.
                    <br><br>
                    We will retain your information for as long as your account is active or 
                    needed to provide you services.  If you no longer want to keep your account, 
                    you may contact customer service.  In the event of an account termination,
                    we will delete your information as soon as is practicable, although some 
                    information may remain archived for our records or as required by law.   
                    <br><br>
                    Our services are directed toward adults over the age of 18.  We do not 
                    knowingly collect or store any personal information about persons under the age of 13.
                    <br><br>
                    We may update our Privacy Policy to reflect changes to our information 
                    practices.  We will notify you of any changes via email.<br><br>
                </p>
                
                <h4 class="dark-grey">Contact Information</h4>
                <p>
                    For any enquiries, contact us via:
                </p>
                <ul>
                    <li>Email: HomeBuddyinfo@HomeBuddy.com</li>
                    <li>Phone: (321) 555 - 1421</li>
                    <li>Address: Homebuddy Inc, 321 Kross Street, Somewhere, CA 92121</li>
                </ul> 
            </div>
        </form>

    </div>

    <?php include "footer.php" ?>
</body>
</html>
