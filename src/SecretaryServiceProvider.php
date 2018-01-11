<?php

namespace Dymantic\Secretary;

use Illuminate\Support\ServiceProvider;

class SecretaryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(Secretary::class, function () {
            return new Secretary(config('secretary'));
        });
    }

    public function boot()
    {
        if (!class_exists('CreateMessagesTable')) {
            $migration_stub = __DIR__ . '/../database/migrations/create_secretary_messages_table.php.stub';
            $migration_name = 'migrations/' . date('Y_m_d_His', time()) . '_create_secretary_messages_table.php';
            $published_migration_path = database_path($migration_name);

            $this->publishes([
                $migration_stub => $published_migration_path,
            ], 'migrations');
        }

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'secretary');

        $this->publishes([
            __DIR__ . '/../config/secretary.php' => config_path('secretary.php')
        ]);
    }
}