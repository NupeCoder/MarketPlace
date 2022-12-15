<?php

require_once('Models/User.php');
require_once('Models/ListingsAPI.php');
require_once('Models/helperClass/Validator.php');

$view = new stdClass();
$view->pageTitle = "View Profile";

$userInstance = new User();
$listingAPI = new ListingsAPI();
$validator = new Validator();

if(isset($_POST['EditID']))
{
    $_SESSION['EditID'] = $_POST['EditID'];
}

$editObj = $listingAPI->fetchCurrentListing($_SESSION['EditID']);
$phpSelf = filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_URL);

if(isset($_POST['EDIT']))
{

    $listingAPI->editListing($validator->validateInput($_POST['editName']), $validator->validateInput($_POST['editDesc']),
        $validator->validateInput($_POST['editPrice']), $validator->validateInput($_POST['editCategory']));
}

require_once ('Views/editlisting.phtml');