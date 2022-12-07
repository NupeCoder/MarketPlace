<?php

class UserData
{
    protected $_userID, $_name, $_email, $_password, $_location, $_phoneNumber, $_profilePhoto, $_position;



    public function __construct($dbRow) {
        $this->_userID = $dbRow['userID'];
        $this->_name = $dbRow['name'];
        $this->_email = $dbRow['email'];
        $this->_password = $dbRow['password'];
        $this->_location = $dbRow['location'];
        $this->_phoneNumber = $dbRow['phoneNumber'];
        $this->_profilePhoto = $dbRow['profilePhoto'];
        $this->_position = $dbRow['position'];

    }

    public function getUserID() {
        return $this->_userID;
    }

    public function getName() {
        return $this->_name;
    }

    public function getEmail() {
        return $this->_email;
    }

    public function getPassword() {
        return $this->_password;
    }


    public function getLocation() {
        return $this->_location;
    }

    public function getPhoneNumber() {
        return $this->_phoneNumber;
    }

    public function getProfilePhoto() {
        return $this->_profilePhoto;
    }

    public function getPosition() {
        return $this->_position;
    }


}