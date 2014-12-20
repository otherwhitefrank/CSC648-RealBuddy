<?php

include_once dirname(__FILE__) . '/../include/db.php';
include_once dirname(__FILE__) . '/listing.php';
include_once dirname(__FILE__) . "/DBQueryHandler.php";

class ListingManager extends DBQueryHandler
{

    public function addListing($listing)
    {
        /* add user to db */
        $this->addListingToDb($listing);
        $listing->listing_id = DBQueryHandler::getLastId("listing_id", "listings");

        //Update each image with listing_id
        foreach ($listing->images as $image) {
            $image->listing_id = $listing->listing_id;
        }

        $this->addImagesToDb($listing->images);
        $this->addGeoCoordToDB($listing);
        return $listing->listing_id;
    }

    private function deleteImagesFromListing($listing)
    {
        //Erase all previous images from this listing
        $query = "SET FOREIGN_KEY_CHECKS=0";
        DBQueryHandler::queryAndFree($query);

        $query = "DELETE FROM images where listing_id="
            . $this->dbManager->real_escape_string($listing->listing_id);
        DBQueryHandler::queryAndFree($query);

        $query = "SET FOREIGN KEY CHECKS=1";

        error_log($query);
        DBQueryHandler::queryAndFree($query);
    }

    public function updateListing($listing, $imagesChanged)
    {
        //update listing
        $this->updateListingToDb($listing);
        $this->updateGeoCoordToDB($listing);

        if ($imagesChanged) {
            $this->deleteImagesFromListing($listing);
            $this->addImagesToDb($listing->images);
        }
        return $listing->listing_id;
    }


    private function addImagesToDb($arrImages)
    {
        foreach ($arrImages as $image) {
            $query = "INSERT INTO images "
                . "(listing_id, caption, image_blob) "
                . "VALUES ('"
                . $this->dbManager->real_escape_string($image->listing_id) . "', '"
                . $this->dbManager->real_escape_string($image->caption) . "', '"
                . $image->image_blob . "')"; //Don't escape blobs

            error_log($query);
            DBQueryHandler::queryAndFree($query);
        }
    }

    private function addGeoCoordToDB($listing)
    {
        $query = "INSERT INTO geocoords(listing_id, lat, lon, alt) VALUES ("
            . $this->dbManager->real_escape_string($listing->listing_id) . ", "
            . $this->dbManager->real_escape_string($listing->lat) . ", "
            . $this->dbManager->real_escape_string($listing->lon) . ", "
            . "0)";

        DBQueryHandler::queryAndFree($query);
    }

    private function updateGeoCoordToDB($listing)
    {
        $query = "UPDATE geocoords "
            . "SET lat=" . $this->dbManager->real_escape_string($listing->lat) . ", "
            . "lon=" . $this->dbManager->real_escape_string($listing->lon) .
            " WHERE listing_id=" . $this->dbManager->real_escape_string($listing->listing_id);

        DBQueryHandler::queryAndFree($query);
    }

    private function addListingToDb($listing)
    {
        $queryZip = "INSERT INTO zipcode"
            . "(city, state, "//city, state,
            . "zip) "
            . "VALUES ('"
            . $this->dbManager->real_escape_string($listing->city) . "', '"
            . $this->dbManager->real_escape_string($listing->state) . "', '"
            . $this->dbManager->real_escape_string($listing->zip)
            . "')";


        $queryListing = "INSERT INTO listings "
            . "(user_id, list_price, street, "//city, state,
            . "zip, num_beds, num_baths, num_garages, sq_feet, listing_desc, approved) "
            . "VALUES ('"
            . $this->dbManager->real_escape_string($listing->user_id) . "', '"
            . $this->dbManager->real_escape_string($listing->list_price) . "', '"
            . $this->dbManager->real_escape_string($listing->street) . "', '"
            . $this->dbManager->real_escape_string($listing->zip) . "', '"
            . $this->dbManager->real_escape_string($listing->num_beds) . "', '"
            . $this->dbManager->real_escape_string($listing->num_baths) . "', '"
            . $this->dbManager->real_escape_string($listing->num_garages) . "', '"
            . $this->dbManager->real_escape_string($listing->sq_feet) . "', '"
            . $this->dbManager->real_escape_string($listing->listing_desc) . "', '"
            . $this->dbManager->real_escape_string($listing->approved)  
            . "')";

        DBQueryHandler::queryAndFree($queryZip);
        DBQueryHandler::queryAndFree($queryListing);
    }

    private function updateListingToDb($listing)
    {
        $query = "UPDATE listings SET "
            . "list_price=" . $this->dbManager->real_escape_string($listing->list_price) . ", "
            . "street='" . $this->dbManager->real_escape_string($listing->street) . "', "
            . "zip=" . $this->dbManager->real_escape_string($listing->zip) . ", "
            . "num_beds=" . $this->dbManager->real_escape_string($listing->num_beds) . ", "
            . "num_baths=" . $this->dbManager->real_escape_string($listing->num_baths) . ", "
            . "num_garages=" . $this->dbManager->real_escape_string($listing->num_garages) . ", "
            . "sq_feet=" . $this->dbManager->real_escape_string($listing->sq_feet) . ", "
            . "listing_desc='" . $this->dbManager->real_escape_string($listing->listing_desc) . "', "
            . "approved=" . $this->dbManager->real_escape_string($listing->approved) . " where listing_id=" . $listing->listing_id;

        DBQueryHandler::queryAndFree($query);
    }

    public function deleteListing($listing_id)
    {
        $query = "DELETE FROM geocoords WHERE listing_id = " . $this->dbManager->real_escape_string($listing_id);
        $query2 = "DELETE FROM listings WHERE listing_id = " . $this->dbManager->real_escape_string($listing_id);
        DBQueryHandler::queryAndFree($query);
        DBQueryHandler::queryAndFree($query2);
        // TODO: cleanup favorites as well
        return $listing_id;
    }

    public function getListingById($listing_id)
    {

        $query = "SELECT a.*, b.*, c.* FROM listings a, zipcode b, geocoords c WHERE a.listing_id="
            . $this->dbManager->real_escape_string($listing_id) . " AND a.zip = b.zip AND a.listing_id = c.listing_id";

        return $this->getListingsByX($query);
    }

    public function approveListing($listing_id, $approver)
    {
        $query = "UPDATE listings "
            . "SET "
            . "approved='" . $this->dbManager->real_escape_string($approver) . "' "
            . "WHERE listing_id = " . $this->dbManager->real_escape_string($listing_id);

        DBQueryHandler::queryAndFree($query);
    }

    public function getImageIdsFromListing($listing_id)
    {
        $query = "select id, caption from images where listing_id=" . $this->dbManager->real_escape_string
            ($listing_id);

        $result = DBQueryHandler::query($query);

        $final_array = array();

        while ($row = mysqli_fetch_array($result)) {
            $element = array('image_id' => $row['id'],
                'caption' => $row['caption']);

            array_push($final_array, $element);
        }

        if (count($final_array) <= 0) {
            return null;
        }
        DBQueryHandler::free($result);
        return $final_array;
    }

    public function getAllListings()
    {
        $query = "SELECT a.*, b.*, c.* FROM listings a, zipcode b, geocoords c WHERE a.listing_id=c.listing_id AND a.zip = b.zip";

        $sqlResult = DBQueryHandler::query($query);
        $ret = $this->generateArrayOfListings($sqlResult);
        DBQueryHandler::free($sqlResult);
        return $ret;
    }

    public function getAllFeaturedListings()
    {
        $query = "SELECT a.*, b.*, c.*, d.* FROM features a, listings b, zipcode c, geocoords d WHERE a.listing_id=b.listing_id AND b.zip = c.zip AND a.listing_id = d.listing_id";

        $sqlResult = DBQueryHandler::query($query);
        $ret = $this->generateArrayOfListings($sqlResult);
        DBQueryHandler::free($sqlResult);
        return $ret;
    }

    public function getAllNotApprovedListings()
    {
        $result = array();
        $listings = $this->getAllListings();
        // not a good idea to get all data and then delete not wanted ones, but in our case this doesnt matter ;)
        foreach ($listings as $listing) {
            if ($listing->approved == 0) {
                array_push($result, $listing);
            }
        }
        return $result;
    }

    public function getAllApprovedListings()
    {
        $result = array();
        $listings = $this->getAllListings();
        // not a good idea to get all data and then delete not wanted ones, but in our case this doesnt matter ;)
        foreach ($listings as $listing) {
            if ($listing->approved != 0) {
                array_push($result, $listing);
            }
        }
        return $result;
    }

    public function getListingsByAttr($lat, $lon, $radius, $num_bedrooms, $num_bathrooms, $num_garages, $min_price, $max_price)
    {
        $query = "SELECT b.approved, a.listing_id, a.lat, a.lon, b.list_price, b.street, c.city, c.state, c.zip, b.num_beds, b.user_id, " .
            "b.num_baths, b.num_garages, " .
            "b.sq_feet, b.listing_desc, " .
            "( 3959 * acos( cos( radians(" . $lat . ") ) " .
            "* cos( radians( lat ) ) * " .
            "cos( radians( lon ) - radians(" . $lon . ") ) + " .
            "sin( radians(" . $lat . ") ) * " .
            "sin( radians( lat ) ) ) ) AS" .
            " distance FROM geocoords a, listings b, zipcode c " .
            "WHERE a.listing_id = b.listing_id AND b.zip = c.zip" .
            " HAVING distance < " . $radius .
            " AND b.num_beds " . ">= " . $num_bedrooms .
            " AND b.num_baths " . ">= " . $num_bathrooms .
            " AND b.num_garages " . ">= " . $num_garages .
            " AND b.list_price " . ">= " . $min_price .
            " AND b.list_price " . "< " . $max_price .
            " AND b.approved!=0";
        
        

        $query .= " ORDER BY distance";

        return $this->getListingsByX($query);
    }
    
    
    public function getListingsByAddr($address)
    {
        $query = "SELECT b.approved, a.listing_id, a.lat, a.lon, b.list_price, b.street, c.city, c.state, c.zip, b.num_beds, b.user_id, " .
            "b.num_baths, b.num_garages, " .
            "b.sq_feet, b.listing_desc " .
            "FROM geocoords a, listings b, zipcode c " .
            "WHERE a.listing_id = b.listing_id AND b.zip = c.zip".
            " AND b.approved!='0'".
            "AND c.state ='" . $this->dbManager->real_escape_string($address)."'  OR b.street = '" . $this->dbManager->real_escape_string($address)."'";   
       
        return $this->getListingsByX($query);
    }
    
    
                
    
   

    private function getListingsByX($query)
    {
        $sqlResult = DBQueryHandler::query($query);
        // generate list of results
        $result = $this->generateArrayOfListings($sqlResult);
        DBQueryHandler::free($sqlResult);
        return $result;
    }

    public function getListingsByUser($userid)
    {
        $query = "SELECT a.*, b.*, c.* from listings a, zipcode b, geocoords c WHERE a.zip = b.zip AND a.listing_id = c.listing_id" .
            " and user_id=" . $this->dbManager->real_escape_string($userid);

        return $this->getListingsByX($query);
    }
    
    public function getListingsByRealtor($realtorid)
    {
        $query = "SELECT a.*, b.*, c.* from listings a, zipcode b, geocoords c WHERE a.zip = b.zip AND a.listing_id = c.listing_id" .
            " and approved =" . $this->dbManager->real_escape_string($realtorid);

        return $this->getListingsByX($query);
    }
    private function generateArrayOfListings($sqlResult)
    {
        $result = array();
        while ($row = mysqli_fetch_array($sqlResult)) {
            $listing_id = $row["listing_id"];
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
            $listing->image_ids = $this->getImageIdsFromListing($listing_id);
            array_push($result, $listing);
        }
        return $result;
    }
}
