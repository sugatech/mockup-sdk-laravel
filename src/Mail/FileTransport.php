<?php

namespace Mockup\SDK\Mail;

use Carbon\Carbon;
use Illuminate\Mail\Transport\Transport;
use Storage\SDK\StorageClient;
use Swift_Mime_SimpleMessage;

class FileTransport extends Transport
{
    /**
     * @var
     */
    protected $storageClient;

    /**
     * FileTransport constructor.
     * @param $settings
     */
    public function __construct($settings)
    {
        $this->storageClient = new StorageClient($settings['api_url'], $settings['access_token']);
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
            $path = sprintf(
                'mockup/mail/%s/%s_%s.txt',
                Carbon::today()->toDateString(),
                $to,
                uniqid()
            );

            $tmp = tmpfile();
            fwrite($tmp, $message->toString());
            $file = stream_get_meta_data($tmp)['uri'];
            $this->storageClient->createFile($file, $path);
            fclose($tmp);
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