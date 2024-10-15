<?php

namespace MixCode\Messages;

class HiSmsMessage
{
    /**
     * @var string
     */
    protected $phone_number;

    /**
     * @var string
     */
    protected $message;

    /**
     * @return string
     */
    public function getPhoneNumber()
    {
        return $this->phone_number;
    }

    /**
     * @param string $phone_number
     * @return \MixCode\Messages\HiSmsMessage
     */
    public function setPhoneNumber(string $phone_number)
    {
        $this->phone_number = $phone_number;

        return $this;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     * @return \MixCode\Messages\HiSmsMessage
     */
    public function setMessage(string $message)
    {
        $this->message = $message;

        return $this;
    }
}