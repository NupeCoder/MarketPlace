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
        $sqlQuery = 'SELECT * FROM (Listings INNER JOIN Users ON Listings.ownerID = Users.userID) WHERE confirmed = 1;';

        $statement = $this->dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(); // execute the PDO statement

        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new ListingsData($row);
        }
        return $dataSet;
    }

    public function fetchUserListings(): array
    {
        $sqlQuery = 'SELECT * FROM (Listings INNER JOIN Users ON Listings.ownerID = Users.userID) WHERE userID = ?';

        $statement = $this->dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->bindParam(1, $_SESSION['userID']);
        $statement->execute(); // execute the PDO statement

        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new ListingsData($row);
        }
        return $dataSet;
    }


    public function fetchAllUnconfirmedListings(): array
    {
        $sqlQuery = 'SELECT * FROM (Listings INNER JOIN Users ON Listings.ownerID = Users.userID) WHERE confirmed = 0';

        $statement = $this->dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(); // execute the PDO statement

        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new ListingsData($row);
        }
        return $dataSet;
    }

    public function fetchSearchedListings(?String $searchItemName, ?String $searchItemSeller, ?String $searchMinPrice, String $searchMaxPrice, int $searchOrderIn, int $searchCategoryIn, int $searchLocationIn): array
    {
        $sqlClauses = [];
        $dataSet = [];

        $searchOrder = "";
        $searchDirection = "";


        if(!empty($searchCategoryIn))
        {
            $searchCategory = match ($searchCategoryIn) {
                1 => "Baby & Children",
                2 => "Clothing & Footwear",
                3 => "DIY & Tools",
                4 => "Electronics & Computers",
                5 => "Home & Garden",
                6 => "Jewellery & Accessories",
                7 => "Sports & Outdoors",
                0 => "",
                default => throw new \Exception('Unexpected match value'),
            };
        }

        if(!empty($searchLocationIn))
        {
            $searchLocation = match ($searchLocationIn) {
                1 => "Bristol",
                2 => "London",
                3 => "Manchester",
                default => "",
            };
        }

        if(!empty($searchOrderIn))
        {
            switch ($searchOrder)
            {
                case 1:
                    $searchOrder = "listingName";
                    $searchDirection = "ASC";
                    break;

                case 2:
                    $searchOrder = "listingName";
                    $searchDirection = "DESC";
                    break;

                case 3:
                    $searchOrder = "price";
                    $searchDirection = "ASC";
                    break;

                case 4:
                    $searchOrder = "price";
                    $searchDirection = "DESC";
                    break;

                case 5:
                    $searchOrder = "listingID";
                    $searchDirection = "DESC";
                    break;

                case 6:
                    $searchOrder = "listingID";
                    $searchDirection = "ASC";
                    break;

                default:
                    $searchOrder = "";
                    $searchDirection = "";
            }

        }
        if(!empty($searchItemName))
        {
            $sqlClauses[] = "Listings.listingName LIKE '%$searchItemName%'";
        }
        if(!empty($searchItemSeller))
        {
            $sqlClauses[] = "Users.name LIKE '%$searchItemSeller%'";
        }
        if(!empty($searchCategory))
        {
            $sqlClauses[] = "Listings.category LIKE '$searchCategory'";
        }
        if(!empty($searchLocation))
        {
            $sqlClauses[] = "Users.location LIKE '$searchLocation'";
        }
        if(!empty($searchMinPrice))
        {
            $sqlClauses[] = "Listings.price > $searchMinPrice";
        }
        if(!empty($searchMaxPrice))
        {
            $sqlClauses[] = "Listings.price < $searchMaxPrice";
        }
        if(!empty($searchOrder))
        {
            $sqlClauses[] = "ORDER BY $searchOrder $searchDirection";
        }

        if((!empty($sqlClauses)) && (!empty($searchOrder)) && (count($sqlClauses) == 1))
        {
            $query = "SELECT * FROM (Listings INNER JOIN Users ON Listings.ownerID = Users.userID) 
         WHERE confirmed = 1 " . implode(' AND ', $sqlClauses);

            $statement = $this->dbHandle->prepare($query);
            $statement->execute();

            while ($row = $statement->fetch()) {
                $dataSet[] = new ListingsData($row);
            }

            return $dataSet;

        } elseif (!empty($sqlClauses))
        {
            $query = "SELECT * FROM (Listings INNER JOIN Users ON Listings.ownerID = Users.userID) 
         WHERE confirmed = 1 AND " . implode(' AND ',$sqlClauses) ;

            $statement = $this->dbHandle->prepare($query);
            $statement->execute();

            while ($row = $statement->fetch()) {
                $dataSet[] = new ListingsData($row);
            }

            return $dataSet;
        }
        else
        {
            return $this->fetchAllConfirmedListings();
        }
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
                            2px solid #328135;" alt="This is a listing photo">
                       </td>
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
        $id = 0;
        foreach ($input as $listing)
        {
            $id +=1;
            echo <<< EOT
                  <div class="col d-inline-flex justify-content-center g-4">
                        <div class="card border-red mb-3 me-3" style="width: 18rem;">
                            <div class="text-center border-bottom py-3">
                                <img src="{$listing->getItemPhoto()}" class="card-img-top" alt="Product Image" >
                            </div>
                            <div class="card-body text-center">
                                <h5 class="card-title" id=name{$id}>{$listing->getListingName()}</h5>
                                <h6 class="card-text text-muted">{$listing->getCategory()}</h6>
                                <h5 class="card-text" id="price{$id}">£{$listing->getPrice()}</h5>
                                <p class="card-text">{$listing->getDescription()}</p>
                                <p class="card-footer">Seller: {$listing->getName()}</p>
                                <img src="{$listing->getProfilePhoto()}"  class="card-img-profile" alt="Seller Profile Picture">
                                <form method="post" action="">
                                    <button onclick="scanDiv({$id})" name={$id} type="submit" class="btn btn-outline-danger" >Send a Message</button>
                                </form>
                                
                            </div>
                        </div>
                   </div>
                  EOT;
        }
    }

}



