<?php


require('vendor\sebbmeyer\php-microsoft-teams-connector\src\TeamsConnectorInterface.php');
require('vendor\sebbmeyer\php-microsoft-teams-connector\src\AbstractCard.php');
require('vendor\sebbmeyer\php-microsoft-teams-connector\src\TeamsConnector.php');
require('vendor\sebbmeyer\php-microsoft-teams-connector\src\Cards\SimpleCard.php');

$teamsurl = 'https://testlivesalfordac.webhook.office.com/webhookb2/239f714c-5d12-48b6-99b7-32c7bab59db1@65b52940-f4b6-41bd-833d-3033ecbcf6e1/IncomingWebhook/86c77cc816f54326b645352852d9da22/b5c3ae56-20df-476e-a296-eb50c262b730';


if (isset($_GET['1']))
{

    $connector = new \Sebbmyr\Teams\TeamsConnector($teamsurl);

    $card  = new \Sebbmyr\Teams\Cards\SimpleCard(['title' => $_GET['name1'], 'text' => 'Testing']);

    $connector->send($card);

}


