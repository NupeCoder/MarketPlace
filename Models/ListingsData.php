<?php


class ListingsData extends UserData
{

    protected $_listingID, $_listingName, $_description, $_price, $_confirmed, $_category, $_itemPhoto, $_userID;

    public function __construct($dbRow)
    {
        parent::__construct($dbRow);
        $this->_listingID = $dbRow['listingID'];
        $this->_listingName = $dbRow['name'];
        $this->_description = $dbRow['description'];
        $this->_price = $dbRow['price'];
        $this->_confirmed = $dbRow['confirmed'];
        $this->_category = $dbRow['category'];
        $this->_itemPhoto = $dbRow['itemPhoto'];
        $this->_ownerID = $dbRow['ownerID'];
    }

    /**
     * @return mixed
     */
    public function getListingID()
    {
        return $this->_listingID;
    }

    /**
     * @return mixed
     */
    public function getListingName()
    {
        return $this->_listingName;
    }



    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->_description;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->_price;
    }

    /**
     * @return mixed
     */
    public function getConfirmed()
    {
        return $this->_confirmed;
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->_category;
    }

    /**
     * @return mixed
     */
    public function getItemPhoto()
    {
        return $this->_itemPhoto;
    }

}

