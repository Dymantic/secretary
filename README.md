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

The general flow is to create a new message that implements `Dymantic\Secretary\SecretaryMessage` and then pas it to Secretary's `receive` method. This package contains `Dymantic\Secretary\ContactMessage` which should be fine for most cases.

```php
//ContactFormController.php // or whatever controller you use

public function handleContactForm(\Dymantic\Secretary\Secretary $secretary) {
    //validate your request

    //new up the message
    $message = new \Dymantic\Secretary\ContactMessage([
        'name' => request('name'),
        'email' => request('email'),
        'message_body' => request('message_body')
    ]);

    $secretary->receive($message);

    //you are done now
}
```

The above is the most common use case, so this package includes a form request to simplify the process.

```php
//ContactFormController.php // or whatever controller you use

public function handleContactForm(\Dymantic\Secretary\Secretary $secretary, \Dymantic\Secretary\ContactForm $form) {
    $secretary->receive($form->contactMessage());
    //you are done now
}
```

The above will handle basic validation for the name, email address and message body.

### Additional message data

Aside from the name. email and message_body fields of a message, there is the message_notes field that holds additional data that can be included as part of the message. You may pass these fields as an associative array with the key 'message_notes' when creating a new message, or you can pass the fields to be plucked from the request if using the ContactForm form request object.

```php
//creating a message manually
$message = new \Dymantic\Secretary\ContactMessage([
        'name' => request('name'),
        'email' => request('email'),
        'message_body' => request('message_body'),
        'message_notes' => [
            'phone' => request('phone'),
            'company' => request('company')
        ]
    ]);

//or if using the form request object, just pass the fields you would like to take from the request as an array to the contactMessage method
$form->createMessage(['phone', 'company']);
```

** Note: ** You are responsible for validating the extra fields.