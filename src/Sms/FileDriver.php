<?php

namespace Mockup\SDK\Sms;

use Tzsk\Sms\Abstracts\Driver;
use Zttp\Zttp;

class FileDriver extends Driver
{
    /**
     * Your Driver Config.
     *
     * @var array $settings
     */
    public function __construct($settings)
    {

    }

    /**
     * Send the message
     *
     * @return object
     */
    public function send()
    {
        foreach($this->recipients as $recipient) {
            $text = $recipient.'-'.$this->body;

            Zttp::post(
                'http://localhost:8000/api/client/v1/messages/send',
                [
                    'text' => $text,
                ]
            );
        }

        return null;
    }
}
