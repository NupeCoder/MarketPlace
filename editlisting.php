<?php

require_once('Models/User.php');
require_once('Models/ListingsAPI.php');

$view= new stdClass();
$view->pageTitle = "Dashboard";

$userInstance = new User();
$listingAPI = new ListingsAPI();
$editObj = $listingAPI->fetchCurrentListing();

if(isset($_POST['EditID']))
{
    $_SESSION['EditID'] = $_POST['EditID'];
}

if(isset($_POST['EDIT']))
{
    $listingAPI->editListing($_POST['editName'], $_POST['editDesc'], $_POST['editPrice'], $_POST['editCategory']);
}

require_once ('Views/editlisting.phtml');