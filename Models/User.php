<?php


require_once('UserAPI.php');
require_once('ListingsAPI.php');

class User
{
    private ?int $_userID;
    private string $_name;
    private bool $_loggedIn;
    private string $_profilePicURL;

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





        if(isset($_SESSION["loginStatus"]))
        {
            if($_SESSION["loginStatus"])
            {
                //$this->_userID = $_SESSION["userID"];
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

    public function getUserData() {
        return $this->_validatedUsers;
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

        //$validatedUsers = $userSet->userValidation($user, $pass);

        $_validatedUsers = $userSet->userValidation($user, $pass);

        if (count($_validatedUsers) >= 1)
        {



            /*
            $this->_userData->setUserID($validatedUsers[0]->getUserID());
            $this->_userData->setName($validatedUsers[0]->getName());
            $this->_userData->setEmail($validatedUsers[0]->getEmail());
            $this->_userData->setPassword($validatedUsers[0]->getPassword());
            $this->_userData->setLocation($validatedUsers[0]->getLocation());
            $this->_userData->setPhoneNumber($validatedUsers[0]->getPhoneNumber());
            $this->_userData->setProfilePhoto($validatedUsers[0]->getProfilePhoto());
            $this->_userData->setRole($validatedUsers[0]->getRole());
*/



            $_SESSION["loginStatus"] = true;
            $_SESSION["fullName"] = $_validatedUsers[0]->getName();
            $_SESSION["profilePhoto"] = $_validatedUsers[0]->getProfilePhoto();
            $_SESSION["userID"] = $_validatedUsers[0]->getUserID(); // we will use this for getting user details


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