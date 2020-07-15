<?php

namespace Mockup\SDK\Mail;

use Illuminate\Mail\MailManager;

class MockupAddedMailManager extends MailManager
{
    protected function createMockupTransport()
    {
        return new MockupTransport(config('mail.mailers.mockup'));
    }
}
