<?php


namespace Dymantic\Secretary\Tests;


use Dymantic\Secretary\ContactMessage;

trait MakesContactMessages
{
    private function makeMessage($message_details = [])
    {
        return new ContactMessage($this->defaultMessageDetails($message_details));
    }

    private function defaultMessageDetails($overrides)
    {
        $defaults = [
            'name'          => 'Test Name',
            'email'         => 'test@example.test',
            'message_body'  => 'Test message body',
            'message_notes' => []
        ];

        return array_merge($defaults, $overrides);
    }
}