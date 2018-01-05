<?php


namespace Dymantic\Secretary;


use Illuminate\Notifications\Notifiable;

class Secretary
{
    use Notifiable;

    public $email;

    public function __construct($config)
    {
        $this->email = $config['sends_email_to'] ?? '';
        $this->slack_endpoint = $config['slack_endpoint'] ?? '';
        $this->slack_recipient = $config['slack_recipient'] ?? '#general';
    }

    public function getSendsEmailTo()
    {
        return $this->email;
    }

    public function receive(SecretaryMessage $message)
    {
        Message::create($message->toDataArray());

        $this->notify(new MessageReceived($message));
    }

    public function routeNotificationForSlack()
    {
        return $this->slack_endpoint;
    }

    public function getKey()
    {
        return 1;
    }
}