<?php

class Validator
{
    // constructor for validator
    public function __construct()
    {

    }
    //this function validates the input
    //this function is passed an input variable
    public function validateInput($input) : string
    {
        // trims the input
        $input = trim($input);
        // this unquotes the quotes string
        $input = stripslashes($input);
        // converts special characters to html entities
        $input = htmlspecialchars($input);
        // returns the input
        return $input;
    }
}