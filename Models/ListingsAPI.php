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

    /**
     * This method is made to echo the card with the appropriate information
     * @param array $input
     * @return void
     */
    public function createCards(array $input): void
    {

        foreach ($input as $listing)
        {
            echo <<< EOT
                    <div class="card" style="width: 18rem;">
                        <img src="{$listing->getItemPhoto()}" class="card-img-top alt="Product Image">
                        <div class="card-body">
                            <h5 class="card-title">{$listing->getListingName()}</h5>
                            <h6 class="card-text">{$listing->getCategory()}</h6>
                            <p class="card-text">{$listing->getDescription()} - The price is £{$listing->getPrice()}</p>
                            <p class="card-text">Seller: {$listing->getName()}</p><img src="{$listing->getProfilePhoto()}" alt="Seller Profile Picture">
                            <a href="#" class="btn btn-outline-danger">Send a Message</a>
                        </div>
                    </div>
                  EOT;
        }
    }

}
