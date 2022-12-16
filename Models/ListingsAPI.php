<?php




require_once('Models/Database.php');
require_once('Models/ListingsData.php');



class ListingsAPI {

    protected ?Database $dbInstance;
    protected PDO $dbHandle;
    protected $DEFAULT_PROFILE_PICTURE;

    public function __construct()
    {
        $this->dbInstance = Database::getInstance();
        $this->dbHandle = $this->dbInstance->getDbConnection();
        $DEFAULT_PROFILE_PICTURE = "images/defaultItem.svg";
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

    /**
     * This method is made to echo the table with the appropriate information
     * @param array $input
     * @return void
     */
    public function populateTable(array $input): void
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
                            2px solid #328135;" alt="This is a profile photo">
                       </td>
                       <td><p>Owner's Name is: </p>{$listing->getName()}</td>
                    <tr>
                  EOT;
        }
    }


    public function registerListing($newListingname, $newDescription, $newPrice, $newCategory, $newItemPhoto, $newOwnerID)
    {

        $confirmedStatus = 0;
        $sqlQuery = "INSERT INTO Listings (listingName,description,price,confirmed,category,itemPhoto,ownerID) VALUES (?,?,?,?,?,?,?)";

        $statement = $this->dbHandle->prepare($sqlQuery);

        $statement->bindParam(1,$newListingname);
        $statement->bindParam(2,$newDescription);
        $statement->bindParam(3,$newPrice);
        $statement->bindParam(4,$confirmedStatus);
        $statement->bindParam(5,$newCategory);
        $statement->bindParam(6,$newItemPhoto);
        $statement->bindParam(7,$newOwnerID);

        return $statement->execute();
    }


    public function fetchAddedListing() {
        $sqlQuery ='SELECT listingID FROM Listings ORDER BY listingID DESC LIMIT 1';

        $statement = $this->dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(); // execute the PDO statement

        return $statement->fetch()[0];

    }


}
