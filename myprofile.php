<?php

require_once('Models/User.php');
require_once('Models/UserAPI.php');

$currentUser = new User(); // sets session id
$listingAPI = new ListingsAPI();

if(isset($_POST['DeleteID']))
{
    $listingAPI->removeChosenListing($_POST['DeleteID']);
}

require_once('Views/myprofile.phtml');

$listingAPI->populateTable($listingAPI->fetchUserListings());