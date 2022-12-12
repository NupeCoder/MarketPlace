<?php

require_once('Models/User.php');
require_once('Models/ListingsAPI.php');

$view= new stdClass();
$view->pageTitle = "Dashboard";

$userInstance = new User();
$listingAPI = new ListingsAPI();

if(isset($_POST["LOGOUT"]))
{
    $userInstance->logout();
}

if(isset($_POST['SEARCH'])){

}

require_once('Views/dashboard.phtml');


