<?php

class Validator
{
    public function __construct()
    {

    }

    public function validateInput($input) : string
    {
        $input = trim($input);
        $input = stripslashes($input);
        $input = htmlspecialchars($input);
        return $input;
    }
}