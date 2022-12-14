<?php

require_once('Models/User.php');
require_once('Models/UserAPI.php');

$currentUser = new User(); // sets session id
$listingAPI = new ListingsAPI();
$userAPI = new userAPI();

if(isset($_POST['DeleteID']))
{
    $listingAPI->removeChosenListing($_POST['DeleteID']);
}

require_once('Views/myprofile.phtml');

$listingAPI->populateTable($listingAPI->fetchUserListings());

$phpSelf  = filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_URL);

if(isset($_POST['changeProfile']))
{
    $newName = validateInput($_POST['changeName']);
    $newMobile =  validateInput($_POST['changeMobile']);
    $newPassword =  validateInput($_POST['changePass']);
    $confirmPassword =  validateInput($_POST['changePassConfirm']);

    if($newPassword == $confirmPassword){
        $userAPI->changeUserDetails($newName,$newMobile,$newPassword);
    }
}

function validateInput($input)
{
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input);
    return $input;
}