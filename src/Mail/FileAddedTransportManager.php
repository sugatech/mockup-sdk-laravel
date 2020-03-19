<?php

namespace Mockup\SDK\Mail;

use Illuminate\Mail\TransportManager;

class FileAddedTransportManager extends TransportManager
{
    protected function createFileDriver()
    {
        return new FileTransport();
    }
}
