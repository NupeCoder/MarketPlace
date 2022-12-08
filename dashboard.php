<?php

require_once('Models/User.php');

$view= new stdClass();
$view->pageTitle = "Dashboard";

require_once('Views/dashboard.phtml');
