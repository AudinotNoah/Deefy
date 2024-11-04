<?php

namespace iutnc\deefy\exception;

use Exception;

class InvalidPropertyNameException extends Exception
{
    public function __construct(string $property)
    {
        parent::__construct("Nom invalide : $property");
    }

}