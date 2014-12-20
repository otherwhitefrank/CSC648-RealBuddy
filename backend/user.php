<?php

class User {

    public $userId;
    public $first_name;
    public $last_name;
    public $email;
    public $phone;
    public $street;
    public $city;
    public $state;
    public $zip;
    public $role;

    function __construct($userId, $first_name, $last_name, $email, $phone, $street, $city, $state, $zip, $role) {
        // TODO: Check data validity
        $this->userId = $userId;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->email = $email;
        $this->phone = $phone;
        $this->street = $street;
        $this->city = $city;
        $this->state = $state;
        $this->zip = $zip;
        $this->role = $role;
    }

}
?>
