<?php


namespace Dymantic\Secretary;


class ContactMessage implements SecretaryMessage
{

    public $sender;
    public $sender_email;
    public $message_body;
    public $message_notes;

    public function __construct($message)
    {
        $this->sender = $message['name'];
        $this->sender_email = $message['email'];
        $this->message_body = $message['message_body'];
        $this->message_notes = $message['message_notes'];
    }

    public function body()
    {
        return $this->message_body;
    }

    public function toDataArray()
    {
        return [
            'name'          => $this->sender,
            'email'         => $this->sender_email,
            'message_body'  => $this->message_body,
            'message_notes' => json_encode($this->message_notes)
        ];
    }

    public function sender()
    {
        return $this->sender;
    }

    public function senderEmail()
    {
        return $this->sender_email;
    }

    public function messageNotes()
    {
        return $this->message_notes ?? [];
    }
}