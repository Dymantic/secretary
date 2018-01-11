<?php


namespace Dymantic\Secretary\Tests;


use Dymantic\Secretary\Message;

trait CreatesMessages
{
    private function createMessage($archive = false)
    {
        $message = Message::create([
            'name'          => 'Test name',
            'email'         => 'test@example.test',
            'message_body'  => 'Test body',
            'message_notes' => json_encode([]),
        ]);
        $message->archived = $archive;
        $message->save();

        return $message->fresh();
    }

    private function createTimedMessage($time)
    {
        $message = Message::create([
            'name'          => 'Test name',
            'email'         => 'test@example.test',
            'message_body'  => 'Test body',
            'message_notes' => json_encode([]),
        ]);
        $message->created_at = $time;
        $message->save();

        return $message->fresh();

    }
}