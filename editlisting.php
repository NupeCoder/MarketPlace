<?php

require_once('Models/User.php');
require_once('Models/ListingsAPI.php');

$view = new stdClass();
$view->pageTitle = "View Profile";

$userInstance = new User();
$listingAPI = new ListingsAPI();

if(isset($_POST['EditID']))
{
    $_SESSION['EditID'] = $_POST['EditID'];
}

$editObj = $listingAPI->fetchCurrentListing($_SESSION['EditID']);
$phpSelf = filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_URL);

if(isset($_POST['EDIT']))
{

    $listingAPI->editListing($_POST['editName'], $_POST['editDesc'], $_POST['editPrice'], $_POST['editCategory']);
}

require_once ('Views/editlisting.phtml');