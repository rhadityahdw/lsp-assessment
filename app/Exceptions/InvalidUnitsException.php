<?php

namespace App\Exceptions;

use Exception;

class InvalidUnitsException extends Exception
{
    protected $message = 'Invalid unit IDs provided';
}