<?php


namespace Dymantic\Secretary\Tests\Feature;


use Dymantic\Secretary\Secretary;
use Dymantic\Secretary\Tests\MakesContactMessages;
use Dymantic\Secretary\Tests\TestCase;

class ReceivesMessagesTest extends TestCase
{
    use MakesContactMessages;

    /**
     *@test
     */
    public function it_can_receive_a_contact_message_and_store_it_in_the_database()
    {
        $message = $this->makeMessage();
        $secretary = $this->app->make(Secretary::class);

        $secretary->receive($message);

        $this->assertDatabaseHas('secretary_messages', [
            'name' => 'Test Name',
            'email' => 'test@example.test',
            'message_body' => 'Test message body',
            'message_notes' => json_encode([])
        ]);
    }
}