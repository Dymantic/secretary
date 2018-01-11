<?php


namespace Dymantic\Secretary;


use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class MessageReceived extends Notification
{
    use Queueable;

    public $message;
    private $notification_channels;

    public function __construct(SecretaryMessage $message, $notification_channels)
    {
        $this->message = $message;
        $this->notification_channels = $notification_channels;
    }


    public function via($notifiable)
    {
        return $this->notification_channels;
    }


    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject("New message from {$this->message->sender()}")
            ->from($this->message->senderEmail(), $this->message->sender())
            ->markdown('secretary::mail.contact_message', [
                'name'          => $this->message->sender(),
                'email'         => $this->message->senderEmail(),
                'message_body'  => $this->message->body(),
                'message_notes' => $this->message->messageNotes()
            ]);
    }

    public function toSlack($notifiable)
    {
        return (new SlackMessage)
            ->success()
            ->to($notifiable->slack_recipient)
            ->content($this->message->body())
            ->attachment(function ($attachment) {
                $attachment->title('The message above is from:')
                           ->fields(array_merge([
                               'Name'    => $this->message->sender(),
                               'Email'   => $this->message->senderEmail(),
                           ], $this->message->messageNotes()));
            });
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}

