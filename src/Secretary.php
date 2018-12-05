<?php


namespace Dymantic\Secretary;


use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Notification;

class Secretary
{
    use Notifiable;

    public $email;
    public $additional_emails = [];
    public $slack_endpoint;
    public $slack_recipient;
    public $notification_channels;

    public function __construct($config)
    {
        $this->setEmails($config['sends_email_to'] ?? '');
        $this->slack_endpoint = $config['slack_endpoint'] ?? '';
        $this->slack_recipient = $config['slack_recipient'] ?? '#general';
        $this->notification_channels = $config['notification_channels'] ?? [];
    }

    private function setEmails($addresses)
    {
        if(is_array($addresses)) {
            $this->email = array_shift($addresses);
            $this->additional_emails = $addresses;
            return;
        }

        $this->email = $addresses;
    }

    public function getSendsEmailTo()
    {
        return $this->email;
    }

    public function receive(SecretaryMessage $message)
    {
        Message::create($message->toDataArray());
        $notification = new MessageReceived($message, $this->notification_channels);
        $this->notify(new MessageReceived($message, $this->notification_channels));

        foreach ($this->additional_emails as $address) {
            Notification::route('mail', $address)->notify(new MessageReceived($message, ['mail']));
        }
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