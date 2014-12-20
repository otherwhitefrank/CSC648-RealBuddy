<?php

include_once dirname(__FILE__) .'/../include/db.php';
include_once dirname(__FILE__) .'/user.php';
include_once dirname(__FILE__) .'/DBQueryHandler.php';

class userManager extends DBQueryHandler {
  
    public function create_user($user, $password) {
    if ($this->getUserByMail($user->email) == null) { // user does not exist 
      /* add user to db */
      $this->addUserToDb($user);
      $user->id = DBQueryHandler::getLastId("id", "users");

      /* add credentials to db */
      $this->addCredentialsToDb($user, $password);

      return $user->id;
    }
    return null;
  }

  public function changePassword($user, $old, $new)
  {
    // check if old password is correct
    if ($this->checkPasswordFromUserId($user->id, $old)){
      return $this->updateCredentials($user, $new); 
    } 
    return FALSE; 
  }

  public function updateCredentials($user, $pass) {
    $query = "UPDATE credentials SET user_password = '" . sha1($pass) 
      . "' WHERE user_id = " . $this->dbManager->real_escape_string($user->id);
    return DBQueryHandler::queryAndFree($query);
  }

  private function addCredentialsToDb($user, $password) {
    DBQueryHandler::queryAndFree("INSERT INTO credentials "
      . "(user_id, email, user_password) "
      . "VALUES ('"
      . $this->dbManager->real_escape_string($user->id) . "', '"
      . $this->dbManager->real_escape_string($user->email) . "', '"
      . $this->generatePasswordBlob($password) . "' )");
  }

  //need this later on for the forgotten Password 
  public function generateRandomPassword() {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $Length = strlen($characters) ;
    $randomPass = '';
    for ($i = 0; $i < 6; $i++) {
      $randomPass .= $characters[rand(0, $Length - 1)];
    }
    return $randomPass;
  }

  public function getAllUsers() {
    $query = "SELECT a.*, b.city, b.state from users a, zipcode b WHERE a.zip = b.zip";
    $sqlResult = DBQueryHandler::query($query);
    $ret = $this->generateArrayOfUsers($sqlResult);
    DBQueryHandler::free($sqlResult);
    return $ret;
  }

  private function generateArrayOfUsers($sqlResult) {
    $result = array();
    while ($row = mysqli_fetch_array($sqlResult)) {
      $user_id = $row["id"];
      $firstName = $row["first_name"];
      $lastName = $row["last_name"];
      $email = $row["email"];
      $phone = $row["phone"];
      $street = $row["street"];
      $city = $row["city"];
      $state = $row["state"];
      $zip = $row["zip"];
      $role = $row["role"];
      $user = new User($user_id, $firstName, $lastName, $email, $phone, $street, $city, $state, $zip, $role);
      array_push($result, $user);
    }
    return $result;
  }

  private function generatePasswordBlob($password) {
    // TODO: Implement correct password handling

    return $this->dbManager->real_escape_string(sha1($password));
  }


  private function addUserToDb($user) {
    $query = "INSERT INTO users "
      . "(first_name, last_name, email, phone, street, zip, role) "
      . "VALUES ('"
      . $this->dbManager->real_escape_string($user->first_name) . "', '"
      . $this->dbManager->real_escape_string($user->last_name) . "', '"
      . $this->dbManager->real_escape_string($user->email) . "', '"
      . $this->dbManager->real_escape_string($user->phone) . "', '"
      . $this->dbManager->real_escape_string($user->street) . "', '"
      . $this->dbManager->real_escape_string($user->zip) . "', '"
      . $this->dbManager->real_escape_string($user->role) . "')";

    $queryZip = "INSERT INTO zipcode "
      . "(city, state, zip) "
      . "VALUES ('"
      . $this->dbManager->real_escape_string($user->city) . "', '"
      . $this->dbManager->real_escape_string($user->state) . "', '"
      . $this->dbManager->real_escape_string($user->zip) . "')";

    DBQueryHandler::queryAndFree($queryZip);
    DBQueryHandler::queryAndFree($query);
  }

  public function updateUser($user)
  {
    $query = "UPDATE users "
      . "SET "
      . "first_name='".$this->dbManager->real_escape_string($user->first_name) . "', "
      . "last_name='".$this->dbManager->real_escape_string($user->last_name) . "', "
      . "email='".$this->dbManager->real_escape_string($user->email) . "', "
      . "phone='".$this->dbManager->real_escape_string($user->phone) . "', "
      . "street='".$this->dbManager->real_escape_string($user->street) . "', "
      . "role='".$this->dbManager->real_escape_string($user->role) . "' "
      . "WHERE id = " . $this->dbManager->real_escape_string($user->id);

    $queryZip =  "UPDATE zipcode "
      . "SET "
      . "city='".$this->dbManager->real_escape_string($user->city) . "', "
      . "state='".$this->dbManager->real_escape_string($user->state) . "', "
      . "zip='".$this->dbManager->real_escape_string($user->zip) . "' "
      . "WHERE zip = " . $this->dbManager->real_escape_string($user->zip);

    DBQueryHandler::queryAndFree($query);
    DBQueryHandler::queryAndFree($queryZip);
  }

  public function checkPasswordFromUserId($user_id, $pass) {
    $query = "select user_password from credentials where user_id=" . $this->dbManager->real_escape_string
      ($user_id);
    //var_dump($query, $user_id, $pass);

    $pwOfDb = DBQueryHandler::queryFirstAndFree($query);
    return $pwOfDb != null && $pwOfDb->user_password == sha1($pass);
  }

  function delete_user($userId) {

    $userIdEscaped =  $this->dbManager->real_escape_string($userId); 
    $res = DBQueryHandler::queryAndFree("DELETE FROM favorites WHERE user_id = " . $userIdEscaped);
    $res = DBQueryHandler::queryAndFree("DELETE FROM listings WHERE user_id = " . $userIdEscaped);
    $res = DBQueryHandler::queryAndFree("DELETE FROM credentials WHERE user_id = " . $userIdEscaped);

    if($res == FALSE)
    {
      echo "Fatal: Credentials of user ID not deleted: ". $userId;
      die();
    }

    $res = DBQueryHandler::queryAndFree("DELETE FROM users WHERE id = " . $userIdEscaped);
    if($res == FALSE){
      echo "Fatal: User ID not deleted: ". $userId;
      die();
    }

    return $userId;
  }

  private function getUserByX($key, $value)
  {
    $query = "SELECT DISTINCT users.*, zipcode.city, zipcode.state FROM users, zipcode join users as userAlias where users."
      . $this->dbManager->real_escape_string($key) . "='" 
      . $this->dbManager->real_escape_string($value) . "' AND zipcode.zip = users.zip";

    return DBQueryHandler::queryFirstAndFree($query);
  }
  
  public function getUserById($userId) {
    return $this->getUserByX("id", $userId);
  }

  public function getUserByMail($mail) {
    return $this->getUserByX("email", $mail);
  }



  public function user_logout() {
    unset($_SESSION['userid']);
    session_destroy();
  }
}
