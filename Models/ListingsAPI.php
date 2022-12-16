<?php


require_once('Models/Database.php');
require_once('Models/ListingsData.php');




class ListingsAPI {
    // global variables for the class
    protected ?Database $dbInstance;
    protected PDO $dbHandle;
    protected $DEFAULT_PROFILE_PICTURE;
    private $phpSelf;
    //constructor for ListingsAPI class
    public function __construct()
    {
        $this->dbInstance = Database::getInstance();
        $this->dbHandle = $this->dbInstance->getDbConnection();
        $DEFAULT_PROFILE_PICTURE = "images/defaultItem.svg";
        $phpSelf = filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_URL);
        $this->phpSelf = filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_URL);
    }
    // this function rejects listing that should not be approved by being passed the rejectedListind ID
    public function rejectListings($rejectedListing): void
    {
        // this sqlqury deletes the rejected listing from the database using the listing id
        $sqlQuery = "DELETE FROM Listings WHERE listingID = '$rejectedListing'";

        $statement = $this->dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(); // execute the PDO statement
    }
    //this function accepts the approved listings by being passed a listingid
    public function acceptListings($acceptedListing): void
    {
        // this sqlquery updates the listing table and sets the confirmed value
        $sqlQuery = "UPDATE Listings SET confirmed=1 WHERE listingID = '$acceptedListing'";
        $statement = $this->dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(); // execute the PDO statement
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

    public function removeChosenListing(int $removeID): void
    {
        $sqlQuery = 'DELETE FROM Listings WHERE listingID = ?';

        $statement = $this->dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->bindParam(1, $removeID);
        $statement->execute(); // execute the PDO statement
    }
    /**

     * @throws Exception
     */
    public function editListing($nameIn, $descIn, $priceIn, int $categoryIn): void
    {
        $sqlClauses = [];

        if(!empty($categoryIn))
        {
            $categoryIn = match ($categoryIn) {
                1 => "Baby & Children",
                2 => "Clothing & Footwear",
                3 => "DIY & Tools",
                4 => "Electronics & Computers",
                5 => "Home & Garden",
                6 => "Jewellery & Accessories",
                7 => "Sports & Outdoors",
                default => throw new \Exception('Unexpected match value'),
            };
        }

        if(!empty($nameIn))
        {
            $sqlClauses[] = "Listings.listingName = '$nameIn'";
        }
        if(!empty($descIn))
        {
            $sqlClauses[] = "Listings.description = '$descIn'";
        }
        if(!empty($priceIn))
        {
            $sqlClauses[] = "Listings.price = '$priceIn'";
        }
        if(!empty($categoryIn))
        {
            $sqlClauses[] = "Listings.category = '$categoryIn'";
        }

        if (!empty($sqlClauses))
        {
            $query = "UPDATE Listings SET " . implode(', ', $sqlClauses) . " WHERE listingID = ?";
            $statement = $this->dbHandle->prepare($query);
            $statement->bindParam(1, $_SESSION['EditID']);
            $statement->execute();
        } else
        {
            echo "Nothing has been input OR No new inputs have been detected";
        }
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

    public function fetchCurrentListing(int $editID): ListingsData
    {
        $sqlQuery = 'SELECT * FROM (Listings INNER JOIN Users ON Listings.ownerID = Users.userID) WHERE listingID = ?';

        $statement = $this->dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->bindParam(1, $editID);
        $statement->execute(); // execute the PDO statement

        $row = $statement->fetch();
        $dataObj = new ListingsData($row);

        return $dataObj;
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
                default => throw new \Exception('Unexpected match value'),
            };
        }

        if(!empty($searchOrderIn))
        {
            switch ($searchOrderIn)
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
         WHERE confirmed = 1 " . $sqlClauses[0];

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
                            <img src="{$listing->getItemPhoto()} " style="height:64px; width:64px; border-radius: 25%;" alt="This is a profile photo">
                       </td>
                       <td>
                       <form action="{$_SERVER['PHP_SELF']}" method="post">
                             <button type="submit" name="acceptID" value="{$listing->getListingID()}, {$listing->getListingName()}, {$listing->getPrice()}, {$listing->getCategory()}, {$listing->getDescription()}, {$listing->getItemPhoto()}" class="btn btn-success btn-approval rounded-0">Accept</button>
                       </form>
                       <form action="{$_SERVER['PHP_SELF']}" method="post">
                            <button type="submit" name="rejectID" value="{$listing->getListingID()}" class="btn btn-danger btn-approval rounded-0">Reject</button>
                       </form>
                       </td>
                    <tr>
                  EOT;
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
            $phpSelf = filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_URL);
            echo <<< EOT
                    <tr>
                       <th scope="row">{$listing->getListingID()}</th>
                       <td>{$listing->getListingName()}</td>
                       <td>{$listing->getDescription()}</td>
                       <td>{$listing->getPrice()}</td>
                       <td>{$listing->getCategory()}</td>
                       <td>
                            <img src="{$listing->getItemPhoto()}" style="height:64px; width:64px; border-radius: 25%; border: 
                            2px solid #c51010;" alt="This is a listing photo">
                       </td>
                       <td>
                       <form action="editlisting.php" method="post">
                            <button type="submit" name="EditID" value="{$listing->getListingID()}" class="btn btn-info">Edit</button>  
                       </form>
                       <form action="{$phpSelf}" method="post" class="was-validated">
                        <input class="form-check-input" type="checkbox" id="myCheck" name="remember" required>
                            <label class="form-check-label" for="myCheck">Confirm Deletion</label>
                            <div class="valid-feedback">You may delete listing.</div>
                            <div class="invalid-feedback">Confirm you want to delete this lsiting.</div>
                            <button type="submit" name="DeleteID" value="{$listing->getListingID()}" class="btn btn-danger">Delete</button>
                       </form>     
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
       //$id = 0;
        foreach ($input as $listing)
        {
            //$id +=1;
            echo <<< EOT
                  <div class="col d-inline-flex justify-content-center g-4">
                        <div class="card border-red listing-card mb-3 me-3" style="width: 18rem;">
                            <div class="text-center border-bottom py-3">
                                <img src="{$listing->getItemPhoto()}" class="card-img-top" alt="Product Image" >
                            </div>
                            <div class="card-body text-center">
                                    <h6 class="card-title">{$listing->getListingName()}</h6>
                                    <h6 class="card-text text-muted">{$listing->getCategory()}</h6>
                                    <h5 class="card-text">£{$listing->getPrice()}</h5>
                                    <p class="card-text">{$listing->getDescription()}</p>
                                    <p class="card-footer">Seller: {$listing->getName()}</p>
                                    <img src="{$listing->getProfilePhoto()}"  class="card-img-profile" alt="Seller Profile Picture">
                                <form method="post" action="">
                                    <button class="btn btn-outline-danger" name="teamsID" value="{$listing->getListingName()}, {$listing->getDescription()}" type="submit">Send a Message</button>
                                </form>
                            </div>
                        </div>
                   </div>
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

}


//<input type="hidden" class="card-title" name="name{$id}" value="{$listing->getListingName()}">
