<?php

 require_once('UserAPI.php');
require_once('ListingsAPI.php');

class User
{
    private ?int $_userID;
    private ?string $_name;
    private bool $_loggedIn;
    private ?string $_profilePicURL;
    private ?string $_location;
    private ?string $_email;
    private ?array $_listingIDs;
    private ?string $_number;

    public function __construct()
    {
        //--This line is for testing purposes whilst on uni PCs  - should still work on home PCs tho-------
        session_save_path("sessions");
        //-----------------------------------------------------------------------------------------------
        session_start();
        $this->_userID = 0;
        $this->_name = "User";
        $this->_loggedIn = false;
        $this->_profilePicURL = "images/default.png";
        $this->_location = "N/A";
        $this->_email = "none";
        $this->_number = "none";

        if(isset($_SESSION["loginStatus"]))
        {
            if($_SESSION["loginStatus"])
            {
                $this->_userID = $_SESSION["userID"];
                $this->_loggedIn = true;
                $this->_name = $_SESSION["fullName"];
                $this->_location = $_SESSION['location'];
                $this->_email = $_SESSION['email'];
                $this->_profilePicURL = $_SESSION["profilePhoto"];
                $this->_number = $_SESSION['number'];
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
     * @param int $userID
     */
    public function setUserID(int $userID): void
    {
        $this->_userID = $userID;
    }

    /**
     * @return int
     */
    public function getUserID(): int
    {
        return $this->_userID;
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
        $_validatedUsers = $userSet->userValidation($user, $pass);

        if (count($_validatedUsers) >= 1)
        {
            $listingsAPI = new ListingsAPI();

            $_SESSION["loginStatus"] = true;
            $_SESSION["fullName"] = $_validatedUsers[0]->getName();
            $_SESSION["profilePhoto"] = $_validatedUsers[0]->getProfilePhoto();
            $_SESSION["userID"] = $_validatedUsers[0]->getUserID(); // we will use this for getting user details
            $_SESSION['location'] = $_validatedUsers[0]->getLocation();
            $_SESSION['email'] = $_validatedUsers[0]->getEmail();
            $_SESSION['number'] = $_validatedUsers[0]->getPhoneNumber();

            $this->_loggedIn = true;
            $this->_name = $_validatedUsers[0]->getName();
            $this->_profilePicURL = $_validatedUsers[0]->getProfilePhoto();
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
        unset($_SESSION["profilePhoto"]);
        unset($_SESSION["userID"]);
        $this->_name = "User";
        $this->_loggedIn = false;
        $this->_profilePicURL = "images/default.png";
        session_destroy();
    }
}