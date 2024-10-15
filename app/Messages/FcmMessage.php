<?php

namespace MixCode\Messages;

class FcmMessage
{
    /**
     * @var string
     */
    protected $title;
    
    /**
     * @var string
     */
    protected $body;

    /**
     * @var string
     */
    protected $icon;
    
    /**
     * @var string
     */
    protected $sound = 'default';
    
    /**
     * @var array
     */
    protected $data = [];

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return \MixCode\Messages\FcmMessage
     */
    public function setTitle(string $title)
    {
        $this->title            = $title;
        $this->data['title']    = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param string $body
     * @return \MixCode\Messages\FcmMessage
     */
    public function setBody(?string $body = null)
    {
        $this->body             = $body;
        $this->data['body']     = $body;

        return $this;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param array $data
     * @return \MixCode\Messages\FcmMessage
     */
    public function setData(array $data = [])
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @param array $data
     * @return \MixCode\Messages\FcmMessage
     */
    public function appendData(array $data = [])
    {
        $this->data = array_merge($this->data, $data);

        return $this;
    }

    /**
     * @return string
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * @param string $icon
     * @return \MixCode\Messages\FcmMessage
     */
    public function setIcon(?string $icon = null)
    {
        $this->icon         = $icon;
        $this->data['icon'] = $icon;

        return $this;
    }

    /**
     * @return string
     */
    public function getSound()
    {
        return $this->sound;
    }

    /**
     * @param string $sound
     * @return \MixCode\Messages\FcmMessage
     */
    public function setSound(?string $sound = 'default')
    {
        $this->sound            = $sound;
        $this->data['sound']    = $sound;

        return $this;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'notification' => [
                'title' => $this->getTitle(),
                'body'  => $this->getBody(),
                'sound' => $this->getSound(),
            ],
            'data'  => $this->getData(),
        ];
    }

}
