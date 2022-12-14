<?php

require_once('Models/Database.php');
require_once('Models/ListingsData.php');


class ListingsAPI {

    protected ?Database $dbInstance;
    protected PDO $dbHandle;
    protected $DEFAULT_PROFILE_PICTURE;
    private $phpSelf;

    public function __construct()
    {
        $this->dbInstance = Database::getInstance();
        $this->dbHandle = $this->dbInstance->getDbConnection();
        $DEFAULT_PROFILE_PICTURE = "images/defaultItem.svg";
        $this->phpSelf = filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_URL);
    }

    public function rejectListings($rejectedListing) {
        $sqlQuery = "DELETE FROM Listings WHERE listingID = '$rejectedListing'";

        $statement = $this->dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(); // execute the PDO statement
    }

    public function acceptListings($acceptedListing) {
        $sqlQuery = "UPDATE Listings SET confirmed=1 WHERE listingID = '$acceptedListing'";
        $statement = $this->dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(); // execute the PDO statement
    }





    public function fetchAllConfirmedListings(): array
    {
        $sqlQuery = 'SELECT * FROM (Listings INNER JOIN Users ON Listings.ownerID = Users.userID) WHERE confirmed=1';

        $statement = $this->dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(); // execute the PDO statement

        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new ListingsData($row);
        }
        return $dataSet;
    }


    public function fetchAllUnconfirmedListings(): array
    {
        $sqlQuery = 'SELECT * FROM (Listings INNER JOIN Users ON Listings.ownerID = Users.userID) WHERE confirmed=0';

        $statement = $this->dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(); // execute the PDO statement

        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new ListingsData($row);
        }
        return $dataSet;
    }


    public function getUserListingDetails() {
        $userID = $_SESSION["userID"];

        $sqlQuery = 'SELECT * FROM (Listings INNER JOIN Users ON Listings.ownerID = Users.userID) WHERE ownerID=?';

        $statement = $this->dbHandle->prepare($sqlQuery); //Prep the PDO statement
        $statement->bindParam(1, $userID); //bindParam $user to the first question mark
        $statement->execute(); //Attempts to execute prepped statement

        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new ListingsData($row);
        }
        return $dataSet;
    }

    /**
     * This method is made to echo the table with the appropriate information
     * @param array $input
     * @return void
     */
    public function populateApprovalTable(array $input): void
    {
        foreach ($input as $listing)
        {
            echo <<< EOT
                    <tr>   
                       <th scope="row">{$listing->getListingID()}</th>
                       <td>{$listing->getListingName()}</td>
                       <td>{$listing->getDescription()}</td>
                       <td>{$listing->getPrice()}</td>
                       <td>{$listing->getCategory()}</td>
                       <td>
                            <img src="{$listing->getItemPhoto()} " style="height:64px; width:64px; border-radius: 25%; border: 
                            2px solid crimson;" alt="This is a profile photo">
                       </td>
                       <td>
                       <form action="{$_SERVER['PHP_SELF']}" method="post">
                             <button type="submit" name="acceptID" value="{$listing->getListingID()}" class="btn btn-success">Accept</button>
                       </form>
                       <form action="{$_SERVER['PHP_SELF']}" method="post">
                            <button type="submit" name="rejectID" value="{$listing->getListingID()}" class="btn btn-danger">Reject</button>
                       </form>
                       </td>
                    <tr>
                  EOT;
        }
    }




}
