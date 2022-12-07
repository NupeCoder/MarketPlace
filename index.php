<?php

require_once('Models/User.php');

$view= new stdClass();
$view->pageTitle = "Login";

$userInstance = new User();

if(isset($_POST["LOGIN"]))
{
    if (($userInstance->authenticateUser($_POST['email'], $_POST['password'])))
    {
        echo("Valid Login");
    }
    else
    {
        echo("Invalid Login");
    }
}


require_once('Views/index.phtml');