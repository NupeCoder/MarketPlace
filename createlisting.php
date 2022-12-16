
<?php


require_once('Models/User.php');
require_once('Models/ListingsAPI.php');
require_once('Models/ListingCharityAPI.php');

$view= new stdClass();
$view->pageTitle = "Create Listing";

$userInstance = new User();
$listingsData = new ListingsAPI();

if(isset($_POST["LOGOUT"]))
{
    $userInstance->logout();
}

require_once('Views/createlisting.phtml');


if (isset($_POST["registerListings"])) {
    $tempCategory = $_POST["selectedOption"];




    $ownerID = $_SESSION['userID'];

    $result = $listingsData->registerListing($_POST["listingname"], $_POST["description"], floatval($_POST["price"]), $tempCategory,'images/defaultItem.svg', $ownerID);




    if (isset($_POST['donate']) && $_POST['donate'] != "0") {


        echo $_POST['charity'];
        $charityID = $_POST['charity'];
        $listingID = $listingsData->fetchAddedListing();



        $listingCharityAPI = new ListingCharityAPI();
        $listingCharityAPI->insertRecord($listingID, $charityID);
    }





}
