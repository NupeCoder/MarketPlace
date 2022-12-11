<?php

//session_start();

require_once('Models/Database.php');


//require_once('Models/ListingsData.php');
require_once('Models/UserData.php');


class UserAPI
{

    protected ?Database $dbInstance;
    protected PDO $dbHandle;
    protected $DEFAULT_PROFILE_PICTURE;

    public function __construct()
    {
        $this->dbInstance = Database::getInstance();
        $this->dbHandle = $this->dbInstance->getDbConnection();
        $DEFAULT_PROFILE_PICTURE = "images/default.png";
    }

    /**
     * Interfaces with the database to gather all the users stored and make them into UserData objects
     * @return array $dataSet
     */
    public function getAllUsers()
    {
        $sqlQuery = 'SELECT * FROM Users';

        $statement = $this->dbHandle->prepare($sqlQuery); //Prep the PDO statement
        $statement->execute(); //Attempts to execute said statement

        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new UserData($row);
        }
        return $dataSet;
    }




    public function getUserDetails() {

        $userID = $_SESSION["userID"];

        $sqlQuery = 'SELECT * FROM Users WHERE userID = ?';

        $statement = $this->dbHandle->prepare($sqlQuery); //Prep the PDO statement
        $statement->bindParam(1, $userID); //bindParam $user to the first question mark
        $statement->execute(); //Attempts to execute prepped statement

        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new UserData($row);
        }
        return $dataSet;
    }


    /**
     * Takes in user input and validates it against the database's stored credentials
     * @param $user String username
     * @param $pass String password
     * @return array $dataSet
     */
    public function userValidation(string $user, string $pass): array
    {
        $queryOne = 'SELECT userID, password FROM Users WHERE email = ?';

        $statementOne = $this->dbHandle->prepare($queryOne); //Prep the PDO statement
        $statementOne->bindParam(1, $user); //bindParam $user to the first question mark
        $statementOne->execute(); //Attempts to execute prepped statement

        $dataSet = [];

        while ($row = $statementOne->fetch()) {
            if (password_verify($pass, $row['password'])) {
                $queryTwo = 'SELECT * FROM Users WHERE userID = ?';

                $statementTwo = $this->dbHandle->prepare($queryTwo); //Prep the PDO statement
                $statementTwo->bindParam(1, $row['userID']); //bindParam $user to the first question mark
                $statementTwo->execute(); //Attempts to execute prepped statement

                while ($userRow = $statementTwo->fetch()) {
                    $dataSet[] = new UserData($userRow);
                }
                return $dataSet;
            }
        }
        return $dataSet;
    }

}