<?php

require_once('Models/User.php');

$view= new stdClass();
$view->pageTitle = "Login";

$userInstance = new User();

require_once('Views/index.phtml');

if(isset($_POST["LOGIN"]))
{
    if (($userInstance->authenticateUser($_POST['email'], $_POST['password'])))
    {
        header('location: dashboard.php'); //
        echo("Valid Login");
    }
    else
    {

        echo("Invalid Login");
    }
}