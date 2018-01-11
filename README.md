# Secretary
## Still under construction

A simple way to handle contact forms and such. At the very least, it saves you from having to deal with handling that damn contact form for the millionth time. Simply pass the message to your Secretary and it will handle it accordingly, whether it is sending off an email, Slack message, etc. Database records are kept for each message. Only Email and Slack messages are included out of the box, but it is easy to add your own.


### Installation and setup

``` php
composer require dymantic/secretary
```

Laravel 5.5 and up should auto-discover the ServiceProvider and Facade, otherwise you can add it yourself to your app config.

``` php
//in config/app.php
...
'providers' => [
    //...
    Dymantic\Secretary\SecretaryServiceProvider::class,
    //...
];

...

'aliases' => [
    //...
    'Secretary' => Dymantic\Secretary\Facades\Secretary::class,
];
```

Then publish the config file:

```
php artisan vendor:publish --provider="Dymantic\Secretary\SecretaryServiceProvider"
```

Then add your config accordingly to `config/secretary.php`. Below is an example:

```php
<?php

return [
  'sends_email_to' => '', //the email address you want messages sent to
  'slack_endpoint' => '', //the slack webhook url for slack notifications
  'slack_recipient' => '#general', //either a slack channel or user
  'notification_channels' => ['email']  //these will be passed to the Laravel Notification's via method
];
```

## Usage

Just go wild


