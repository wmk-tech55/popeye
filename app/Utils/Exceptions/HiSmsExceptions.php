<?php

namespace MixCode\Utils\Exceptions;

use Exception;

class HiSmsExceptions extends Exception
{
    /**
     * According to HiSms Documentation Following Codes and Messages are Exceptions
     */
    const EXCEPTIONS = [
        1   => "Username is wrong",
        2   => "Password is wrong",
        4   => "No Numbers are Provided",
        5   => "No Message Provided",
        6   => "Sender is Wrong",
        7   => "Sender is not Activated",
        8   => "Message Contains disallowed word",
        9   => "No Balance",
        10  => "Date format is invalid",
        11  => "Time format is invalid",
        404 => "Missing Required Parameters",
        403 => "The number of allowed attempts exceeded",
        504 => "Account Disabled",
    ];


    /**
     * Get Exception
     *
     * @param int|string $exception_index
     * @return null
     * @throws \MixCode\Utils\Exceptions\HiSmsExceptions
     */
    public static function getException($exception_index)
    {
        if (isset(static::EXCEPTIONS[$exception_index])) {
            throw new static(static::EXCEPTIONS[$exception_index]);
        }
        
        return null;
    }
}