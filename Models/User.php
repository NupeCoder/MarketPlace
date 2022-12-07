<?php

require_once('UserAPI.php');

class User
{
    private int $_ownerID;
    private string $_name;
    private bool $_loggedIn;
    private string $_profilePicURL;


    public function __construct()
    {
        session_save_path("sessions");
        session_start();
        $this->_ownerID = 0;
        $this->_name = "User";
        $this->_loggedIn = false;
        $this->_profilePicURL = "images/default.png";

        if(isset($_SESSION["loginStatus"]))
        {
            if($_SESSION["loginStatus"])
            {
                $this->_ownerID = $_SESSION['ownerID'];
                $this->_loggedIn = true;
                $this->_name = $_SESSION["fullName"];
                $this->_profilePicURL = $_SESSION["profilePhoto"];
            }
        } else
        {
            $_SESSION["loginStatus"] = false;
        }
    }

    /**
     * @param mixed|null $fullName
     */
    public function setName(string $fullName): void
    {
        $this->_name = $fullName;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->_name;
    }

    /**
     * @param bool $loggedIn
     */
    public function setLoggedIn(bool $loggedIn): void
    {
        $this->_loggedIn = $loggedIn;
    }

    /**
     * @return bool
     */
    public function getLoggedIn(): bool
    {
        return $this->_loggedIn;
    }

    /**
     * @param int $ownerID
     */
    public function setOwnerID(int $ownerID): void
    {
        $this->_ownerID = $ownerID;
    }

    /**
     * @return int
     */
    public function getOwnerID(): int
    {
        return $this->_ownerID;
    }


    /**
     * Validates the user credentials and sets key SESSION variables that will allow other methods to access said data
     * @param $user
     * @param $pass
     * @return bool
     */
    public function authenticateUser($user, $pass): bool
    {
        $userSet = new UserAPI();

        $validatedUsers = $userSet->userValidation($user, $pass);

        if (count($validatedUsers) >= 1)
        {
            $_SESSION["loginStatus"] = true;
            $_SESSION["fullName"] = $validatedUsers[0]->getFullName();
            $_SESSION["profilePhoto"] = $validatedUsers[0]->getProfilePhoto();
            $_SESSION["ownerID"] = $validatedUsers[0]->getID();
            $this->_loggedIn = true;
            $this->_name = $validatedUsers[0]->getFullName();
            $this->_profilePicURL = $validatedUsers[0]->getProfilePhoto();
            var_dump($_SESSION["fullName"]);
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * Allows users the log-out; undoes the main SESSION variables and resets stored user info
     * @return void
     */
    public function logOut(): void
    {
        $_SESSION["loginStatus"] = false;
        unset($_SESSION['ownerID']);
        unset($_SESSION["profilePhoto"]);
        unset($_SESSION["userID"]);
        $this->_name = "User";
        $this->_loggedIn = false;
        $this->_profilePicURL = "images/default.png";
        session_destroy();
    }
}