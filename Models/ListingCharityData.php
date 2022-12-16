<?php

class ListingCharityData
{
    protected $_listingID, $_charityID;

    public function __construct($dbRow)
    {
        $this->_listingID = $dbRow['listingID'];
        $this->_charityID = $dbRow['charityID'];
    }

}


