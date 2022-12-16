<?php

require_once('Models/Database.php');

/**
 * The purpose of this class was to hash all passwords that I imported from the mockaroo query -> One-time script
 */
class PassHasher
{
    // sets two variables to access the database
    protected $dbInstance;
    protected $dbHandle;
    //constructor for the passhasher class
    public function __construct()
    {
        $this->dbInstance = Database::getInstance();
        $this->dbHandle = $this->dbInstance->getDbConnection();
    }
    // this function gets all user passwords from the database
    public function fetchAllPasswords()
    {
        // sets the query that needs to run on the database
        // the query gets all the userId's and password's from user table
        $query = 'SELECT userID, password FROM Users WHERE userID > 2';
        // this prepares the query to be executed
        $statement = $this->dbHandle->prepare($query);
        // this executes the query on the database
        $statement->execute();
        // makes two arrays named idSet and passSet
        $idSet = [];
        $passSet = [];
        // this while iterates through the results of the query and puts them in the array
        while ($row = $statement->fetch(PDO::FETCH_ASSOC))
        {
            $idSet[] = $row['userID'];
            $passSet[] = $row['password'];
        }
        // this returns both arrays with the results of the query
        return array($idSet, $passSet);
    }
    // this function is used to encrypt passwords when passed a passwordArray
    public function encryptPasswords(array $passwordArray)
    {
        // idSet is given the value of index 0 from passwordArray
        $idSet = $passwordArray[0];
        // passSet is given the value of index 1 from passwordArray
        $passSet = $passwordArray[1];

        $i = 0;

        // goes through the whole array
        foreach($passSet as $value)
        {
            // hashedPass stores the hashed password
            $hashedPass = password_hash($value, PASSWORD_DEFAULT);
            // this query updates the users table for all the passwords
            $query = 'UPDATE Users SET password = ? WHERE userID = ?';
            // this prepares the statement to be executed
            $statement = $this->dbHandle->prepare($query);
            // binds the hashedPass and the id to the statement
            $statement->bindParam(1, $hashedPass);
            $statement->bindParam(2, $idSet[$i]);
            // executes the statement to update the users table
            $statement->execute();
            //increments the value of i everytime it loops
            $i++;
        }
    }

}