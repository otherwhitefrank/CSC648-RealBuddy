<?php

include_once dirname(__FILE__) . '/favoritesManager.php';
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$action = $_POST['action'];

switch ($action) {
    case 'favoriteStateChange':
        favoriteStateChange();
        break;
    case 'deleteFavorite':
        deleteFavorite($_POST['listingid'], $_POST['userid']);
        break;
    default:
        echo "Wrong Action name.";
}

function favoriteStateChange() {
    $favMan = new favoritesManager();
    $userID = $_POST['userID'];
    $listingID = $_POST['listingID'];
    $currentState = $_POST['currentState'];

    // decide if create or delete fav belongs to currentstate

    if ($currentState == 0) {
        //create fav
        $favMan->create_favorite($userID, $listingID);
        echo "1";
    }

    if ($currentState == 1) {
        //delete fav
        $favMan->delete_favorite($userID, $listingID);
        echo "0";
    }
}

function deleteFavorite($listing_ID, $user_ID) {
    $favMan = new FavoritesManager();
    $result = $favMan->delete_favorite($user_ID, $listing_ID);
    echo $result;
}

?>


