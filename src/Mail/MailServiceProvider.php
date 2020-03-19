<?php

namespace Mockup\SDK\Mail;

use Illuminate\Support\ServiceProvider;

class MailServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function register()
    {
        $app = $this->app;
        $app->make('queue');
        $app->alias('mailer', \Illuminate\Mail\Mailer::class);
        $app->alias('mailer', \Illuminate\Contracts\Mail\Mailer::class);
        $app->alias('mailer', \Illuminate\Contracts\Mail\MailQueue::class);

        $app->loadComponent(
            'mail',
            FileAddedServiceProvider::class,
            'mailer'
        );
    }
}
