<?php

require_once('Models/User.php');
require_once('Models/ListingsAPI.php');

$view= new stdClass();
$view->pageTitle = "Dashboard";

$userInstance = new User();
$listingAPI = new ListingsAPI();

if(isset($_POST["LOGOUT"]))
{
    $userInstance->logout();
}

echo '<table class="table table-dark table-striped table-responsive">
                         <tr>
                             <th scope="col">Listing ID</th>
                             <th scope="col">Product Name</th>
                             <th scope="col">Description</th>
                             <th scope="col">Price</th>
                             <th scope="col">Category</th>
                             <th scope="col">Product Image</th>
                             <th scope="col">Owner Details</th>
                         </tr>';

$listingAPI->populateTable($listingAPI->fetchAllConfirmedListings());

require_once('Views/dashboard.phtml');
