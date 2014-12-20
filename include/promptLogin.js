function promptLogin() {

    $("#dismissPopupButton").on("click", function (e) {
        $("#loginPrompt").modal('hide'); // dismiss the dialog
    });
    $("#loginPopupButton").on("click", function (e) {
        window.location.href = "login.php";
    });
    $("#registerPopupButton").on("click", function (e) {
        window.location.href = "registerUser.php";
    });


    $("#loginPrompt").on("hide", function () { // remove the event listeners when the dialog is dismissed
        $("#dismissPopupButton").off("click");
        $("#loginPopupButton").off("click");
        $("#registerPopupButton").off("click");
    });

    $("#loginPrompt").on("hidden", function () { // remove the actual elements from the DOM when fully hidden
        $("#loginPrompt").remove();
    });

    $("#loginPrompt").modal({ // wire up the actual modal functionality and show the dialog
        "backdrop": "static",
        "keyboard": true,
        "show": true // ensure the modal is shown immediately
    });
};

function promptContactLogin() {

    $("#dismissContactPopupButton").on("click", function (e) {
        $("#loginPrompt").modal('hide'); // dismiss the dialog
    });
    $("#loginContactPopupButton").on("click", function (e) {
        window.location.href = "login.php";
    });
    $("#registerContactPopupButton").on("click", function (e) {
        window.location.href = "registerUser.php";
    });


    $("#loginPrompt").on("hide", function () { // remove the event listeners when the dialog is dismissed
        $("#dismissContactPopupButton").off("click");
        $("#loginContactPopupButton").off("click");
        $("#registerContactPopupButton").off("click");
    });

    $("#loginContactPrompt").on("hidden", function () { // remove the actual elements from the DOM when fully hidden
        $("#loginContactPrompt").remove();
    });

    $("#loginContactPrompt").modal({ // wire up the actual modal functionality and show the dialog
        "backdrop": "static",
        "keyboard": true,
        "show": true // ensure the modal is shown immediately
    });
};


function promptSellLogin() {

    $("#dismissSellPopupButton").on("click", function (e) {
        $("#loginPrompt").modal('hide'); // dismiss the dialog
    });
    $("#loginSellPopupButton").on("click", function (e) {
        window.location.href = "login.php";
    });
    $("#registerSellPopupButton").on("click", function (e) {
        window.location.href = "registerUser.php";
    });


    $("#loginPrompt").on("hide", function () { // remove the event listeners when the dialog is dismissed
        $("#dismissSellPopupButton").off("click");
        $("#loginSellPopupButton").off("click");
        $("#registerSellPopupButton").off("click");
    });

    $("#loginContactPrompt").on("hidden", function () { // remove the actual elements from the DOM when fully hidden
        $("#loginSellPrompt").remove();
    });

    $("#loginSellPrompt").modal({ // wire up the actual modal functionality and show the dialog
        "backdrop": "static",
        "keyboard": true,
        "show": true // ensure the modal is shown immediately
    });
};