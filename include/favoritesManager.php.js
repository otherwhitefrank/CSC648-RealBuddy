/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function switchFavoriteStatus(userID, listingID, currentState) {

    if (userID == -1)
    {
        promptLogin();
    }
    else
    {
        var url = "backend/fav_ajax_backend.php";
        var paramArr = {
            action: "favoriteStateChange",
            userID: userID,
            listingID: listingID,
            currentState: currentState
        }

        $.ajax({
                url: url,
                type: "post",
                data: paramArr,
                dataType: "html",
                async: true,
                timeout: 150000,
                success: function (response) {


                    if (response == 0) {
                        $('#listFavID' + listingID).attr('class', 'favClass glyphicon glyphicon-star-empty');
                    }
                    else {
                        $('#listFavID' + listingID).attr('class', 'favClass glyphicon glyphicon-star');
                    }
                    $("#listFavID" + listingID).attr('onclick', 'switchFavoriteStatus('+ userID +', ' + listingID + ', ' + response + ')');


                }
            }
        );
    }

}

function deleteFavorite(userID, listingID) {

  var url = "backend/fav_ajax_backend.php";
  var paramArr = {
    action: "deleteFavorite",
    userid: userID,
    listingid: listingID
  }
  $.ajax({
    url: url,
    type: "post",
    data: paramArr,
    dataType: "html",
    async: true,
    timeout: 150000,
    success: function (response) {
        if($("favlink").click()){
      $("#listing_" + listingID).hide(1000);}
    }
  }
  );
}



