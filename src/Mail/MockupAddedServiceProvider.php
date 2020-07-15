<?php

namespace Mockup\SDK\Mail;

use Illuminate\Mail\MailServiceProvider;

class MockupAddedServiceProvider extends MailServiceProvider
{
    protected function registerIlluminateMailer()
    {
        $this->app->singleton('mail.manager', function ($app) {
            return new MockupAddedMailManager($app);
        });

        $this->app->bind('mailer', function ($app) {
            return $app->make('mail.manager')->mailer();
        });
    }
}
