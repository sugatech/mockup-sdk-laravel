<?php

namespace Mockup\SDK\Mail;

use Illuminate\Mail\MailServiceProvider;

class FileAddedServiceProvider extends MailServiceProvider
{
    protected function registerSwiftTransport()
    {
        $this->app->singleton('swift.transport', function ($app) {
            return new FileAddedTransportManager($app);
        });
    }
}
