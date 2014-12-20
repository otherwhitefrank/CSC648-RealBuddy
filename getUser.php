<?php

include_once "backend/userManager.php";
$userManager = new userManager();

$id = $_GET['user_id'];

$final_array = $userManager->getUserById($id);

echo json_encode($final_array);
?>
