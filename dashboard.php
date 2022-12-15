<?php



require_once('Models/User.php');
require_once('Models/ListingsAPI.php');
require_once('Models/TeamsAPI.php');

$view= new stdClass();
$view->pageTitle = "Dashboard";

$userInstance = new User();
$listingAPI = new ListingsAPI();
$teamsAPI = new TeamsAPI();

if(isset($_POST["LOGOUT"]))
{
    $userInstance->logout();
}

if(isset($_POST['teamsID']))
{
    $teamsAPI->sendSimpleOverviewCard();
}


require_once('Views/dashboard.phtml');
