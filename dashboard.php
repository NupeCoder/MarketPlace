<?php

require_once('Models/User.php');

$view= new stdClass();
$view->pageTitle = "Dashboard";

$userInstance = new User();

if(isset($_POST["LOGOUT"]))
{
    $userInstance->logout();
}


require_once('Views/dashboard.phtml');
