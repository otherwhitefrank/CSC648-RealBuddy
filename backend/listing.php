<?php

class Listing {

    public $listing_id;
    public $currency_list_price;
    public $city;
    public $state;
    public $listing_desc;
    public $list_price;
    public $zip;
    public $num_beds;
    public $num_baths;
    public $num_garages;
    public $sq_feet;
    public $user_id;
    public $street;
    public $images;
    public $image_ids = array();
    public $approved = 0; // initially not approved
    public $lat;
    public $lon;

    function __construct($user_id, $list_price, $street, $city, $state, $zip, $num_beds, $num_baths, $num_garages, $sq_feet, $listing_desc, $approved, $lat, $lon) {
        $this->user_id = $user_id;
        $this->list_price = $list_price;
        $this->street = $street;
        $this->city = $city;
        $this->state = $state;
        $this->zip = $zip;
        $this->num_beds = $num_beds;
        $this->num_baths = $num_baths;
        $this->num_garages = $num_garages;
        $this->sq_feet = $sq_feet;
        $this->listing_desc = $listing_desc;
        $this->currency_list_price = $list_price; // FIXME
        $this->approved = $approved;
        $this->lat = $lat;
        $this->lon = $lon;
    }

}

class IndexListing extends Listing {

    //  private static $DEFAULT_STRING_ENTRY = "not provided";
    //private static $DEFAULT_STREET = self::$DEFAULT_STRING_ENTRY;
    private static $DEFAULT_STREET = "not provided";
    private static $DEFAULT_ZIP = 0;
    private static $DEFAULT_NUM_BEDS = 0;
    private static $DEFAULT_NUM_BATHS = 0;
    private static $DEFAULT_NUM_GARAGES = 0;
    private static $DEFAULT_SQ_FEET = 0;
    private static $DEFAULT_LAT = 0;
    private static $DEFAULT_LON = 0;

    function __construct($listingId, $userId, $list_price, $city, $state, $listing_desc) {
        parent::__construct($listingId, $userId, $list_price, self::$DEFAULT_STREET, $city, $state, self::$DEFAULT_ZIP, self::$DEFAULT_NUM_BEDS, self::$DEFAULT_NUM_BATHS, self::$DEFAULT_NUM_GARAGES, self::$DEFAULT_SQ_FEET, $listing_desc, 1, self::$DEFAULT_LAT, self::$DEFAULT_LON);
    }

}

?>
