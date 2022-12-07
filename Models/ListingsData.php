<?php


class ListingsData extends UserData {

    protected $_listingID, $_name, $_description, $_price, $_confirmed, $_category, $_itemPhoto, $_userID;

    public function __construct($dbRow) {

        parent::__construct($dbRow);
        $this->_listingID = $dbRow['listingID'];
        $this->_name = $dbRow['name'];
        $this->_description = $dbRow['description'];
        $this->_price = $dbRow['price'];
        $this->_confirmed = $dbRow['confirmed'];
        $this->_category = $dbRow['category'];
        $this->_itemPhoto = $dbRow['itemPhoto'];
        $this->_userID = $dbRow['userID'];
    }

}

