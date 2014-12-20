<?php


class favorites {
    public $user_id;
    public $listing_id;
    
    function __construct($user_id, $listing_id) {
        $this->user_id = $user_id;
        $this->listing_id = $listing_id;
     }
}

