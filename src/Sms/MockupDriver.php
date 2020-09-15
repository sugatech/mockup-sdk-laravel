<?php

namespace Mockup\SDK\Sms;

use Illuminate\Support\Facades\Http;
use Tzsk\Sms\Abstracts\Driver;

class MockupDriver extends Driver
{
    const channelKey = 'sms';

    /**
     * @var string
     */
    private $apiUrl;

    /**
     * Your Driver Config.
     *
     * @var array $settings
     */
    public function __construct($settings)
    {
        $this->apiUrl = $settings['api_url'];
    }

    /**
     * Send the message
     *
     * @return object
     */
    public function send()
    {
        foreach($this->recipients as $recipient) {
            $text = '['.self::channelKey.'] '.$recipient.PHP_EOL.PHP_EOL.$this->body;

            Http::post(
                $this->apiUrl.'/api/client/v1/messages/send',
                [
                    'text' => $text,
                ]
            );
        }

        return null;
    }
}
