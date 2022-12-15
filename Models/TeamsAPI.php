<?php

require('vendor\sebbmeyer\php-microsoft-teams-connector\src\TeamsConnectorInterface.php');
require('vendor\sebbmeyer\php-microsoft-teams-connector\src\AbstractCard.php');
require('vendor\sebbmeyer\php-microsoft-teams-connector\src\TeamsConnector.php');
require('vendor\sebbmeyer\php-microsoft-teams-connector\src\Cards\SimpleCard.php');
require('vendor\sebbmeyer\php-microsoft-teams-connector\src\Cards\HeroCard.php');

class TeamsAPI
{

    private string $teamsURL;

    public function __construct()
    {
       $this->teamsURL = "https://testlivesalfordac.webhook.office.com/webhookb2/239f714c-5d12-48b6-99b7-32c7bab59db1@65b52940-f4b6-41bd-833d-3033ecbcf6e1/IncomingWebhook/86c77cc816f54326b645352852d9da22/b5c3ae56-20df-476e-a296-eb50c262b730";
    }

    public function sendSimpleOverviewCard(): void
    {
        $URL = $this->teamsURL;
        $listingInfo = explode(", ", $_POST['teamsID']);
        $connector = new \Sebbmyr\Teams\TeamsConnector($URL);

        $card  = new \Sebbmyr\Teams\Cards\SimpleCard(['title' => $listingInfo[0], 'text' => $listingInfo[1]]);

        $connector->send($card);
    }

    public function sendCreationHeroCard($listingName, $listingPrice, $listingCategory, $listingImage, $listingDescription): void
    {
        $URL = $this->teamsURL;
        // create connector instance
        $connector = new \Sebbmyr\Teams\TeamsConnector($URL);
        // create card
        $card = new \Sebbmyr\Teams\Cards\HeroCard();
        $card->setTitle($listingName . " - £" . $listingPrice)
            ->setSubtitle($listingCategory)
            //->addImage("http://hc2x-2.poseidon.salford.ac.uk/ajbell/" . $listingImage)
            ->setText($listingDescription)
            ->addButton("openUrl", "Wikipedia page", "https://en.wikipedia.org/wiki/Mouse")
        ;
        // send card via connector
        $connector->send($card);
    }
}