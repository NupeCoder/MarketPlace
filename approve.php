<?php


require_once('Models/User.php');
require_once('Models/ListingsAPI.php');
require_once('Models/TeamsAPI.php');


$view= new stdClass();
$view->pageTitle = "Approve Listings";

$userInstance = new User();
$listingAPI = new ListingsAPI();
$teamsAPI = new TeamsAPI();

if(isset($_POST["LOGOUT"]))
{
    $userInstance->logout();
}

if (isset($_POST["acceptID"]))
{
    var_dump($_POST['acceptID']);
    $vals = explode(", ", $_POST['acceptID']);
    $listingAPI->acceptListings($vals[0]);
    $teamsAPI->sendCreationHeroCard($vals[1], $vals[2], $vals[3], $vals[5], $vals[4]);
}

if (isset($_POST["rejectID"])) {
    $listingAPI->rejectListings($_POST["rejectID"]);
}

require_once('Views/approve.phtml');

$listingAPI->populateApprovalTable($listingAPI->fetchAllUnconfirmedListings());

