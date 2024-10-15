<?php

namespace MixCode\Utils\Exceptions;

use Exception;

class MshastraSmsExceptions extends Exception
{
    /**
     * Get Exception
     *
     * @return null
     * @throws \MixCode\Utils\Exceptions\MshastraSmsExceptions
     */
    public static function getException($message)
    {
        throw new static($message);
        
        return null;
    }
}