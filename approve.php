<?php


require_once('Models/User.php');
require_once('Models/ListingsAPI.php');



$view= new stdClass();
$view->pageTitle = "Approve Listings";

$userInstance = new User();
$listingAPI = new ListingsAPI();


if(isset($_POST["LOGOUT"]))
{
    $userInstance->logout();
}

if (isset($_POST["acceptID"])) {

    echo "yes";
    $listingAPI->acceptListings($_POST["acceptID"]);
}

if (isset($_POST["rejectID"])) {
    $listingAPI->rejectListings($_POST["rejectID"]);
}

require_once('Views/approve.phtml');

$listingAPI->populateApprovalTable($listingAPI->fetchAllUnconfirmedListings());

