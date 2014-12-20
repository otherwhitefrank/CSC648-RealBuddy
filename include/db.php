<?php

include_once dirname(__FILE__) . '/../backend/listingManager.php';

class DatabaseManager extends mysqli {

	private static $instance = null;
// db connection config vars
	private $dbUser = "root";
	private $dbPass = "Temp1234";
	private $dbName = "student_f14g10";
	private $dbHost = "127.0.0.1";

	public static function getInstance() {
		if (!self::$instance instanceof self) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	public function __construct() {
		parent::__construct($this->dbHost, $this->dbUser, $this->dbPass, $this->dbName);
		if (mysqli_connect_error()) {
			exit('Connect Error (' . mysqli_connect_errno() . ') '
				. mysqli_connect_error());
		}
		parent::set_charset('utf-8');
	}

// The clone and wakeup methods prevents external instantiation of copies of the Singleton class,
// thus eliminating the possibility of duplicate objects.
	public function __clone() {
		trigger_error('Clone is not allowed.', E_USER_ERROR);
	}

	public function __wakeup() {
		trigger_error('Deserializing is not allowed.', E_USER_ERROR);
	}

	public function verify_credentials($name, $password) {
		$name = $this->real_escape_string($name);
		$password = $this->real_escape_string($password);

		$result = $this->query("SELECT 1 FROM users
 	           WHERE name = '" . $name . "' AND password = '" . $password . "'");
		return $result->data_seek(0);
	}

	function format_date_for_sql($date) {
		if ($date == "")
			return null;
		else {
			$dateParts = date_parse($date);
			return $dateParts["year"] * 10000 + $dateParts["month"] * 100 + $dateParts["day"];
		}
	}


	public function getGeoCoords($lat, $lon, $radius, $num_bedrooms, $num_bathrooms, $num_garages, $min_price, $max_price) {
		$query = "SELECT a.listing_id, a.lat, a.lon, a.alt, b.list_price, b.street, c.city, c.state, c.zip, b.num_beds, b.num_baths, b.num_garages," .
			" b.sq_feet, b.listing_desc, ( 3959 * acos( cos( radians(" . $lat . ") ) * cos( radians( lat ) ) * " .
			"cos( radians( lon ) - radians(" . $lon . ") ) + sin( radians(" . $lat . ") ) * sin( radians( lat ) ) ) ) AS" .
			" distance FROM geocoords a, listings b, zipcode c WHERE a.listing_id = b.listing_id AND b.zip = c.zip" .
			" HAVING distance < " . $radius .
			" AND b.num_beds " . ">= " . $num_bedrooms .
			" AND b.num_baths " . ">= " . $num_bathrooms .
			" AND b.num_garages " . ">= " . $num_garages .
			" AND b.list_price " . ">= " . $min_price .
			" AND b.list_price " . "< " . $max_price .
			" ORDER BY distance";

		$result = $this->execute_query($query);

// generate array of listings and their geoCoords
		$final_array = array();
		while ($row = mysqli_fetch_array($result)) {
			$element = array('listing_id' => $row['listing_id'],
			    'lat' => $row['lat'],
			    'lon' => $row['lon'],
			    'alt' => $row['alt']);

			array_push($final_array, $element);
		}
		return $final_array;
	}

	public function getGeoCoordsByListId($id) {
		$query = "SELECT lat, lon FROM geocoords WHERE listing_id=" . $id;
		$result = $this->execute_query($query);

		$final_array = array();
		$row = mysqli_fetch_array($result);
		$element = array('lat' => $row['lat'],
		    'lon' => $row['lon']);

		array_push($final_array, $element);

		return $final_array;
	}


	public function getImage($image_id) {
		$query = "SELECT image_blob FROM images WHERE id='{$image_id}'";

    $result = $this->execute_query($query);
		while ($row = mysqli_fetch_array($result)) {
			$image = $row['image_blob'];
		}
		return $image;
	}


	/**
	 *  This is a method to help switching to new databasemanager singleton. For 
	 * now a query can be executed from any other php file using this method.
	 * @param type $query
	 * @return type
	 */
	public function execute_query($query) {
		return $this->query($query);
	}

}

?>
