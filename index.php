<?php

require_once('Models/User.php');
require_once('Models/helperClass/Validator.php');

$view= new stdClass();
$view->pageTitle = "Login";

$userInstance = new User();
$validator = new Validator();


if(isset($_POST["LOGIN"]))
{
    if (($userInstance->authenticateUser($validator->validateInput(($_POST['email'])), $validator->validateInput($_POST['password']))))
    {
        header('location: dashboard.php'); //
        echo("Valid Login");
    }
    else
    {

        echo("Invalid Login");
    }
}

require_once('Views/index.phtml');