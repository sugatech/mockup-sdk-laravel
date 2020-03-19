<?php

namespace Mockup\SDK\Sms;

use Carbon\Carbon;
use Tzsk\Sms\Abstracts\Driver;

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
            $path = sprintf(
                'mockup/sms/%s/%s_%s.txt',
                Carbon::today()->toDateString(),
                $recipient,
                uniqid()
            );
            $tmp = tmpfile();
            fwrite($tmp, $this->body);
            $file = stream_get_meta_data($tmp)['uri'];
            app('storage.client')->createFile($file, $path);
            fclose($tmp);
        }

        return null;
    }
}
