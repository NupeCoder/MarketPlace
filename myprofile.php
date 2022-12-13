<?php


require_once('Models/User.php');
require_once('Models/UserAPI.php');

$currentUser = new User(); // sets session id
$listingAPI = new ListingsAPI();
$userAPI = new userAPI();

require_once('Views/myprofile.phtml');

$listingAPI->populateTable($listingAPI->fetchUserListings());

if(isset($_POST['changeProfile']))
{
    $newName = validateInput(['changeName']);
    $newMobile =  validateInput($_POST['changeMobile']);
    $newPassword =  validateInput($_POST['changePass']);
    $confirmPassword =  validateInput($_POST['changePassConfirm']);

    function validateInput($input)
    {
        $input = trim($input);
        $input = stripslashes($input);
        $input = htmlspecialchars($input);
        return $input;
    }

    if($newPassword == $confirmPassword){
        if( (!empty($newName)) || (!empty($newMobile)) || (!empty($newPassword)) )
        {
            $userAPI->changeUserDetails($newName,$newMobile,$newPassword);
        }
    }
}