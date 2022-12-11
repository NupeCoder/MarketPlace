<?php


require_once('Models/User.php');




$currentUser = new User(); // sets session id


require_once('Views/myprofile.phtml');



/*
 *
 *
 *
 * $chargepointDataSet = new chargepointDataSet();

if (isset($_POST['submit'])) { //
    echo "yes"; //code gets to here //

    $view->chargepointDataSet = $chargepointDataSet->fetchFilteredChargepoints($_POST['search']); //
    require_once('Views/chargepoint.phtml');
} else {

    $view->chargepointDataSet = $chargepointDataSet->fetchAllUsers(); //
    require_once('Views/chargepoint.phtml'); //
}

 *
 *
 */
