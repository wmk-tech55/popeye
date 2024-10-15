<?php

namespace MixCode\Utils\Exceptions;

use Exception;

class WhatsLoopExceptions extends Exception
{
    /**
     * Get Exception
     *
     * @param int $code
     * @param string $error_message
     * @return null
     * @throws \MixCode\Utils\Exceptions\WhatsLoopExceptions
     */
    public static function getException($code, $error_message)
    {
        if ($code !== 200) {
            throw new static($error_message);
        }
        
        return null;
    }
}