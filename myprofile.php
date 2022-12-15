<?php

require_once('Models/User.php');
require_once('Models/UserAPI.php');
require_once('Models/helperClass/Validator.php');

$currentUser = new User(); // sets session id
$listingAPI = new ListingsAPI();
$userAPI = new userAPI();
$validator = new Validator();

if(isset($_POST['DeleteID']))
{
    $listingAPI->removeChosenListing($_POST['DeleteID']);
}

require_once('Views/myprofile.phtml');

$listingAPI->populateTable($listingAPI->fetchUserListings());

$phpSelf  = filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_URL);

if(isset($_POST['changeProfile']))
{
    $newPassword =  $validator->validateInput($_POST['changePass']);
    $confirmPassword =  $validator->validateInput($_POST['changePassConfirm']);

    if($newPassword == $confirmPassword){
        $userAPI->changeUserDetails($validator->validateInput($_POST['changeName']),$validator->validateInput($_POST['changeMobile']),$newPassword);
    }
}

