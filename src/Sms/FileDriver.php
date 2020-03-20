<?php

namespace Mockup\SDK\Sms;

use Carbon\Carbon;
use Storage\SDK\StorageClient;
use Tzsk\Sms\Abstracts\Driver;

class FileDriver extends Driver
{
    /**
     * @var StorageClient
     */
    private $storageClient;

    /**
     * Your Driver Config.
     *
     * @var array $settings
     */
    public function __construct($settings)
    {
        $this->storageClient = new StorageClient($settings['api_url'], $settings['access_token']);
    }

    /**
     * Send the message
     *
     * @return object
     */
    public function send()
    {
        foreach($this->recipients as $recipient) {
            $path = sprintf(
                'sms/%s/%s_%s.txt',
                Carbon::today()->toDateString(),
                $recipient,
                uniqid()
            );
            $tmp = tmpfile();
            fwrite($tmp, $this->body);
            $file = stream_get_meta_data($tmp)['uri'];
            $this->storageClient->createFile($file, $path);
            fclose($tmp);
        }

        return null;
    }
}
