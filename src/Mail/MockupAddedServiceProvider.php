<?php

namespace Mockup\SDK\Mail;

use Illuminate\Mail\MailServiceProvider;

class MockupAddedServiceProvider extends MailServiceProvider
{
    protected function registerSwiftTransport()
    {
        $this->app->singleton('swift.transport', function ($app) {
            return new MockupAddedTransportManager($app);
        });
    }
}
