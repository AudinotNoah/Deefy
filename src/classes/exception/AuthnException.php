<?php

namespace iutnc\deefy\exception;

use Exception;

class AuthnException extends Exception
{
    public function __construct(string $property)
    {
        parent::__construct("Erreur connection : $property");
    }

}