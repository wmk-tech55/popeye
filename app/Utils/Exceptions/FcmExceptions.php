<?php

namespace MixCode\Utils\Exceptions;

use Exception;

class FcmExceptions extends Exception
{
    /**
     * Get Exception
     *
     * @param int $success_messages
     * @param int $failed_messages
     * @param object $error_message
     * @return null
     * @throws \App\Utils\Exceptions\FcmExceptions
     */
    public static function getException($success_messages, $failed_messages, $error_message)
    {
        if ($success_messages == 0 && $failed_messages > 0) {
            throw new static($error_message);
        }
        
        return null;
    }
}