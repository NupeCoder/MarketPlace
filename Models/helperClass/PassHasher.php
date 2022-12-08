<?php

require_once('Models/Database.php');

/**
 * The purpose of this class was to hash all passwords that I imported from the mockaroo query -> One-time script
 */
class PassHasher
{
    protected $dbInstance;
    protected $dbHandle;

    public function __construct()
    {
        $this->dbInstance = Database::getInstance();
        $this->dbHandle = $this->dbInstance->getDbConnection();
    }

    public function fetchAllPasswords()
    {
        $query = 'SELECT userID, password FROM Users WHERE userID > 2';

        $statement = $this->dbHandle->prepare($query);
        $statement->execute();

        $idSet = [];
        $passSet = [];

        while ($row = $statement->fetch(PDO::FETCH_ASSOC))
        {
            $idSet[] = $row['userID'];
            $passSet[] = $row['password'];
        }

        return array($idSet, $passSet);
    }

    public function encryptPasswords(array $passwordArray)
    {
        $idSet = $passwordArray[0];
        $passSet = $passwordArray[1];

        $i = 0;

        foreach($passSet as $value)
        {
            $hashedPass = password_hash($value, PASSWORD_DEFAULT);

            $query = 'UPDATE Users SET password = ? WHERE userID = ?';

            $statement = $this->dbHandle->prepare($query);
            $statement->bindParam(1, $hashedPass);
            $statement->bindParam(2, $idSet[$i]);

            $statement->execute();

            $i++;
        }
    }

}