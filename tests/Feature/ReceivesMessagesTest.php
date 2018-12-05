<?php


namespace Dymantic\Secretary\Tests\Feature;


use Dymantic\Secretary\MessageReceived;
use Dymantic\Secretary\Secretary;
use Dymantic\Secretary\Tests\MakesContactMessages;
use Dymantic\Secretary\Tests\TestCase;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class ReceivesMessagesTest extends TestCase
{
    use MakesContactMessages;

    /**
     * @test
     */
    public function it_can_receive_a_contact_message_and_store_it_in_the_database()
    {
        $message = $this->makeMessage();
        $secretary = $this->app->make(Secretary::class);

        $secretary->receive($message);

        $this->assertDatabaseHas('secretary_messages', [
            'name'          => 'Test Name',
            'email'         => 'test@example.test',
            'message_body'  => 'Test message body',
            'message_notes' => json_encode([])
        ]);
    }

    /**
     * @test
     */
    public function it_receives_a_message_and_sends_the_correct_notifications()
    {
        Notification::fake();

        $this->app['config']->set('secretary.notification_channels', ['mail', 'slack']);
        $this->app['config']->set('secretary.sends_email_to', 'receiver@example.test');
        $message = $this->makeMessage();
        $secretary = $this->app->make(Secretary::class);

        $secretary->receive($message);

        Notification::assertSentTo($secretary, MessageReceived::class, function ($notification, $channels) {
            return in_array('mail', $channels) &&
                in_array('slack', $channels) &&
                $notification->message->sender === 'Test Name';
        });
    }

    /**
     *@test
     */
    public function multiple_email_addresses_are_acceptable_and_all_are_notified()
    {
        Notification::fake();

        $this->app['config']->set('secretary.notification_channels', ['mail', 'slack']);
        $this->app['config']->set('secretary.sends_email_to', ['receiver-one@example.test', 'receiver-two@example.test']);
        $message = $this->makeMessage();
        $secretary = $this->app->make(Secretary::class);

        $secretary->receive($message);

        Notification::assertSentTo($secretary, MessageReceived::class, function ($notification, $channels) {
            return in_array('mail', $channels) &&
                in_array('slack', $channels) &&
                $notification->message->sender === 'Test Name';
        });

        Notification::assertSentTo(new AnonymousNotifiable, MessageReceived::class, function($notification, $channels) {
            return in_array('mail', $channels) &&
                ! in_array('slack', $channels) &&
                $notification->message->sender === 'Test Name';
        });
    }

    /**
     *@test
     */
    public function it_wont_notify_channels_that_are_not_in_the_config()
    {
        Notification::fake();

        $this->app['config']->set('secretary.notification_channels', ['mail']);
        $this->app['config']->set('secretary.sends_email_to', 'receiver@example.test');
        $message = $this->makeMessage();
        $secretary = $this->app->make(Secretary::class);

        $secretary->receive($message);

        Notification::assertSentTo($secretary, MessageReceived::class, function ($notification, $channels) {
            return !in_array('slack', $channels);
        });
    }
}