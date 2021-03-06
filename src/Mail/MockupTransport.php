<?php

namespace Mockup\SDK\Mail;

use Illuminate\Mail\Transport\Transport;
use Illuminate\Support\Facades\Http;
use Swift_Mime_SimpleMessage;

class MockupTransport extends Transport
{
    const channelKey = 'email';
    
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
     * Send the given Message.
     *
     * Recipient/sender data will be retrieved from the Message API.
     * The return value is the number of recipients who were accepted for delivery.
     *
     * This is the responsibility of the send method to start the transport if needed.
     *
     * @param Swift_Mime_SimpleMessage $message
     * @param string[] $failedRecipients An array of failures by-reference
     *
     * @return int
     */
    public function send(Swift_Mime_SimpleMessage $message, &$failedRecipients = null)
    {
        $this->beforeSendPerformed($message);

        foreach ($this->getTo($message) as $to) {
            $text = '['.self::channelKey.'] '.$to.PHP_EOL.PHP_EOL.$message->getHeaders().PHP_EOL.PHP_EOL.$message->getBody();

            Http::post(
                $this->apiUrl.'/api/client/v1/messages/send',
                [
                    'text' => $text,
                ]
            );
        }

        $this->sendPerformed($message);

        return $this->numberOfRecipients($message);
    }

    /**
     * Get all the addresses this message should be sent to.
     *
     * Note that Mandrill still respects CC, BCC headers in raw message itself.
     *
     * @param  \Swift_Mime_SimpleMessage $message
     * @return array
     */
    protected function getTo(Swift_Mime_SimpleMessage $message)
    {
        $to = [];

        if ($message->getTo()) {
            $to = array_merge($to, array_keys($message->getTo()));
        }

        if ($message->getCc()) {
            $to = array_merge($to, array_keys($message->getCc()));
        }

        if ($message->getBcc()) {
            $to = array_merge($to, array_keys($message->getBcc()));
        }

        return $to;
    }
}