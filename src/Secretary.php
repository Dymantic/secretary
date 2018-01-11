<?php


namespace Dymantic\Secretary;


use Illuminate\Notifications\Notifiable;

class Secretary
{
    use Notifiable;

    public $email;
    public $slack_endpoint;
    public $slack_recipient;
    public $notification_channels;

    public function __construct($config)
    {
        $this->email = $config['sends_email_to'] ?? '';
        $this->slack_endpoint = $config['slack_endpoint'] ?? '';
        $this->slack_recipient = $config['slack_recipient'] ?? '#general';
        $this->notification_channels = $config['notification_channels'] ?? [];
    }

    public function getSendsEmailTo()
    {
        return $this->email;
    }

    public function receive(SecretaryMessage $message)
    {
        Message::create($message->toDataArray());

        $this->notify(new MessageReceived($message, $this->notification_channels));
    }

    public function routeNotificationForSlack()
    {
        return $this->slack_endpoint;
    }

    public function getKey()
    {
        return 1;
    }

    public function getMessages()
    {
        return Message::unarchived()->get();
    }

    public function getArchivedMessages()
    {
        return Message::archived()->get();
    }

    public function lastWeeksMessages()
    {
        return Message::fromTheLastWeek()->get();
    }

    public function lastMonthsMessages()
    {
        return Message::fromTheLastMonth()->get();
    }
}