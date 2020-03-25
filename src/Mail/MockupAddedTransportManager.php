<?php

namespace Mockup\SDK\Mail;

use Illuminate\Mail\TransportManager;

class MockupAddedTransportManager extends TransportManager
{
    protected function createMockupDriver()
    {
        return new MockupTransport();
    }
}
