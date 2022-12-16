<?php

require_once('Models/User.php');
require_once('Models/ListingsAPI.php');
require_once('Models/helperClass/Validator.php');

$view= new stdClass();
$view->pageTitle = "Create Listing";

$userInstance = new User();
$listingsData = new ListingsAPI();
$validator = new Validator();

if(isset($_POST["LOGOUT"]))
{
    $userInstance->logout();
}

require_once('Views/createlisting.phtml');


if (isset($_POST["registerListings"]))
{
    $tempCategory = $_POST["selectedOption"];
    $ownerID = $_SESSION['userID'];


    $result = $listingsData->registerListing($validator->validateInput($_POST["listingname"]), $validator->validateInput($_POST["description"]), $validator->validateInput(floatval($_POST["price"])), $tempCategory, 'images/defaultItem.svg', $ownerID);
}