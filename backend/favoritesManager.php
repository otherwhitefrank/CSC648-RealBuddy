<?php

include_once dirname(__FILE__) . '/../include/db.php';
include_once dirname(__FILE__) . '/listingManager.php';
include_once dirname(__FILE__) . '/listing.php';


class favoritesManager {

    private $dbManager;
    private $listManager;

    function __construct() {
        //$this->dbManager = DatabaseManager::getInstance();
        $this->dbManager = new DatabaseManager();
        $this->listManager = new listingManager();
    }


      /*
     * checkIfPropertyisFavoritForUser
     *
     * @param $userID Int value of User ID
     * @param $listingID Int value of Property ID
     *
     * @return int Status if property is favorit for user
     */

    public function checkUserPropertyFavorit($userID, $listingID) {
       if ($userID != 0 AND $listingID != 0) {
           $query = "SELECT * FROM favorites WHERE user_id =" . $userID . " AND listing_id = " . $listingID;
           $result = $this->dbManager->execute_query($query);
           if (!mysqli_fetch_row($result)) {     //No mapping
               return 0;
           }
           else {
               return 1;
           }
       }
    }

    public function getFavoriteProperFromListing($listingID) {
        if ($listingID != 0) {
            $query = "SELECT * FROM favorites WHERE listing_id = " . $listingID;
            $result = $this->dbManager->execute_query($query);
            if (!mysqli_fetch_row($result)) {     //No mapping
                return 0;
            }
            else {
                return 1;
            }
        }
    }

    public function create_favorite($userID, $listingid) {
        $this->dbManager->query("INSERT INTO favorites "
                . "(user_id, listing_id) "
                . "VALUES ('"
                . $userID . "', '"
                . $listingid
                . "')");
    }


    public function delete_favorite($userID, $listingid) {
       return $this->dbManager->query("DELETE FROM favorites WHERE user_id =". $userID . " AND listing_id =". $listingid);
    }


    public function get_listID($userID)
    {
        $query = "SELECT DISTINCT listings.*, zipcode.city, zipcode.state, favorites.listing_id, geocoords.* FROM favorites, zipcode, geocoords " .
                 "join listings where favorites.user_id =" . $userID .
                " AND favorites.listing_id = listings.listing_id AND listings.zip = zipcode.zip and listings.listing_id = geocoords.listing_id";

        $sqlresult = $this->dbManager->query($query);

        $result = $this->generateArrayOfListings($sqlresult);
		mysqli_free_result($sqlresult);
		return $result;
    }



	private function generateArrayOfListings($sqlResult) {
		$result = array();


		while ($row = mysqli_fetch_array($sqlResult)) {
			$listing_id = $row["listing_id"];
//			$user_id = $row["user_id"]; TODO: undefined index
			$user_id = $row["user_id"];
			$list_price = $row["list_price"];
			$street = $row["street"];
			$city = $row["city"];
			$state = $row["state"];
			$zip = $row["zip"];
			$num_beds = $row["num_beds"];
			$num_baths = $row["num_baths"];
			$num_garages = $row["num_garages"];
			$sq_feet = $row["sq_feet"];
			$listing_desc = $row["listing_desc"];
            $approved = $row["approved"];
            $lat = $row["lat"];
            $lon = $row["lon"];
			$listing = new Listing($user_id, $list_price, $street, $city, $state, $zip, $num_beds, $num_baths, $num_garages, $sq_feet, $listing_desc, $approved, $lat, $lon);
			$listing->listing_id = $listing_id;
			$listing->image_ids = $this -> listManager->getImageIdsFromListing($listing_id);
			array_push($result, $listing);
		}
		return $result;
}
}

