<?php

namespace iutnc\deefy\exception;

use Exception;

class InvalidPropertyValueException extends Exception
{
    public function __construct($value)
    {
        parent::__construct("Nom invalide : $value");
    }

}