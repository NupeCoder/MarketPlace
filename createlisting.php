<?php

session_start();


require_once('Views/createlisting.phtml');
require_once('Models/UserData.php');
require_once('Models/ListingsAPI.php');







if (isset($_POST["registerListings"])) {
    $tempCategory = $_POST["selectedOption"];


    $listingsData = new ListingsAPI();




    $result = $listingsData->registerListing($_POST["listingname"], $_POST["description"], $_POST["price"], $tempCategory, $_POST["itemphoto"]);
}
