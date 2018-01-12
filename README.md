# Secretary

A simple way to handle contact forms and such. At the very least, it saves you from having to deal with handling that damn contact form for the millionth time. Simply pass the message to your Secretary and it will handle it accordingly, whether it is sending off an email, Slack message, etc. Database records are kept for each message. Only Email and Slack messages are included out of the box, but it is easy to add your own.


### Installation and setup

Step 1: Require with composer

``` php
composer require dymantic/secretary
```

Laravel should auto-discover the ServiceProvider and Facade. If you don't use auto-discovery you can add them yourself to your app config.

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

Step 2: Publish the config file:

```
php artisan vendor:publish --provider="Dymantic\Secretary\SecretaryServiceProvider"
```

Step 3: Run the migration

```
php artisan migrate
```

Step 4: Set your config accordingly in `config/secretary.php`. Below is an example:

```php
<?php

return [
  'sends_email_to' => 'hello@example.test',
  'slack_endpoint' => 'https://a-totoally-fake-slack-webhook.test/FAKE',
  'slack_recipient' => '#site_messages',
  'notification_channels' => ['mail', 'slack']
];
```

Step 5: Use it!

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

### Database messages

Each message received by your Secretary will be saved to the database. The model is `Dymantic\Secretary\Message` and is just an eloquent model to be used as such, so you may delete, etc at will. The model does include an `archive` method to archive a message, and a `reinstate` method which is just the opposite of archive.

The secretary itself has some convenience methods for retrieving messages

```php
//get all messages
$secretary->getMessages();

//get archived messages
$secretary->getArchivedMessages();

//get messages from the last week (does NOT include archived messages)
$secretary->lastWeeksMessages();

//get messages from the last month (does NOT include archived messages)
$secretary->lastMonthsMessages();
```