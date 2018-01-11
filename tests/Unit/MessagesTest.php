<?php


namespace Dymantic\Secretary\Tests\Unit;


use Dymantic\Secretary\Message;
use Dymantic\Secretary\Tests\CreatesMessages;
use Dymantic\Secretary\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;

class MessagesTest extends TestCase
{
//    use RefreshDatabase;
    use CreatesMessages;

    /**
     *@test
     */
    public function a_message_can_be_archived()
    {
        $message = Message::create([
            'name' => 'Test name',
            'email' => 'test@example.test',
            'message_body' => 'Test body',
            'message_notes' => json_encode([]),
        ])->fresh();

        $this->assertFalse($message->archived);

        $message->archive();

        $this->assertTrue($message->fresh()->archived);
    }

    /**
     *@test
     */
    public function an_archived_message_can_be_reinstated()
    {
        $message = Message::create([
            'name' => 'Test name',
            'email' => 'test@example.test',
            'message_body' => 'Test body',
            'message_notes' => json_encode([]),
        ]);
        $message->archive();
        $this->assertTrue($message->fresh()->archived);

        $message->reinstate();

        $this->assertFalse($message->fresh()->archived);
    }

    /**
     *@test
     */
    public function there_is_an_archived_scope_for_messages()
    {
        $archived_message = $this->createMessage($archive = true);
        $not_archived = $this->createMessage();

        $retrieved = Message::archived()->get();

        $this->assertCount(1, $retrieved);
        $this->assertTrue($retrieved->first()->is($archived_message));
    }

    /**
     *@test
     */
    public function there_is_a_scope_for_non_archived_messages()
    {
        $archived_message = $this->createMessage($archive = true);
        $not_archived = $this->createMessage();

        $retrieved = Message::unarchived()->get();

        $this->assertCount(1, $retrieved);
        $this->assertTrue($retrieved->first()->is($not_archived));
    }

    /**
     *@test
     */
    public function there_is_a_scope_for_messages_from_the_last_week()
    {
        $recent = $this->createTimedMessage(Carbon::today()->subDays(2));
        $old = $this->createTimedMessage(Carbon::today()->subDays(8));

        $retrieved = Message::fromTheLastWeek()->get();

        $this->assertCount(1, $retrieved);
        $this->assertTrue($retrieved->first()->is($recent));
    }

    /**
     *@test
     */
    public function there_is_a_scope_for_messages_from_the_last_month()
    {
        $recent = $this->createTimedMessage(Carbon::today()->subDays(20));
        $old = $this->createTimedMessage(Carbon::today()->subDays(40));

        $retrieved = Message::fromTheLastMonth()->get();

        $this->assertCount(1, $retrieved);
        $this->assertTrue($retrieved->first()->is($recent));
    }

}