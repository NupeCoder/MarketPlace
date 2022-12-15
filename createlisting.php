<?php

require_once('Models/User.php');
require_once('Models/ListingsAPI.php');

$view= new stdClass();
$view->pageTitle = "Create Listing";

$userInstance = new User();
$listingsData = new ListingsAPI();

if(isset($_POST["LOGOUT"]))
{
    $userInstance->logout();
}

require_once('Views/createlisting.phtml');


if (isset($_POST["registerListings"]))
{
    $tempCategory = $_POST["selectedOption"];
    $ownerID = $_SESSION['userID'];


    $result = $listingsData->registerListing($_POST["listingname"], $_POST["description"], floatval($_POST["price"]), $tempCategory, 'images/defaultItem.svg', $ownerID);
}