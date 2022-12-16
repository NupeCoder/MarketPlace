<?php

require_once('Models/Database.php');
require_once('Models/ListingCharityData.php');


class ListingCharityAPI
{

    protected ?Database $dbInstance;
    protected PDO $dbHandle;

    public function __construct()
    {
        $this->dbInstance = Database::getInstance();
        $this->dbHandle = $this->dbInstance->getDbConnection();
    }


    public function insertRecord($listingID, $charityID) {
        $sql = "INSERT INTO ListingCharity (listingID, charityID) VALUES (? ,?)";

        $statement = $this->dbHandle->prepare($sql); // prepare a PDO statement

        $statement->bindParam(1, $listingID);
        $statement->bindParam(2, $charityID);


        $statement->execute(); // execute the PDO statement

    }







}