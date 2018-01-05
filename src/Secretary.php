<?php


namespace Dymantic\Secretary;


class Secretary
{
    private $sends_email_to;

    public function __construct($config)
    {
        $this->sends_email_to = $config['sends_email_to'];
    }

    public function getSendsEmailTo()
    {
        return $this->sends_email_to;
    }

    public function receive(SecretaryMessage $message)
    {
        Message::create($message->toDataArray());
    }
}