<?php

class image {
	public $listing_id;
	public $caption;
	public $image_blob;

	function __construct($listing_id, $caption, $image_blob){
		$this->listing_id = $listing_id;
		$this->caption = $caption;
		$this->image_blob = $image_blob;
	}
}
