<?php
include_once "backend/userManager.php";
function checkPermission($allowedFor)
{
  if ($allowedFor == "guest") 
  {
    // do not check for further rights
    return;
  }
  if (isset($_SESSION['userid'])){
    $userManager = new userManager();
    $user = $userManager->getUserById($_SESSION['userid']);
    if ($user != null)
    {
        $userRole = $user->role;
        if ($allowedFor == $userRole)
        {
          return;
        } else if ($allowedFor == "agent" && $userRole == "admin")
        {
          return;
        } else if ($allowedFor == "user" && ($userRole == "admin" || $userRole == "agent"))
        {
          return;
        }
    }
  } 
  header("Location: notGranted.php"); 
}
?>
