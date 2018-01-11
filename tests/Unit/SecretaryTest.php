<?php


namespace Dymantic\Secretary\Tests\Unit;


use Dymantic\Secretary\Message;
use Dymantic\Secretary\Secretary;
use Dymantic\Secretary\Tests\CreatesMessages;
use Dymantic\Secretary\Tests\TestCase;
use Illuminate\Support\Carbon;

class SecretaryTest extends TestCase
{
    use CreatesMessages;

    /**
     * @test
     */
    public function it_has_the_correct_email_property()
    {
        $this->app['config']->set('secretary.sends_email_to', 'test@example.test');

        $secretary = $this->app->make(Secretary::class);

        $this->assertEquals('test@example.test', $secretary->email);
    }

    /**
     * @test
     */
    public function it_provides_the_correct_slack_webhook()
    {
        $this->app['config']->set('secretary.slack_endpoint', 'https://test.test/endpoint');
        $this->app['config']->set('secretary.sends_email_to', 'test@example.test');

        $secretary = $this->app->make(Secretary::class);

        $this->assertEquals('https://test.test/endpoint', $secretary->routeNotificationForSlack());
    }

    /**
     * @test
     */
    public function the_secretary_can_have_a_slack_recipient()
    {
        $this->app['config']->set('secretary.slack_recipient', '#test_channel');
        $this->app['config']->set('secretary.slack_endpoint', 'https://test.test/endpoint');
        $this->app['config']->set('secretary.sends_email_to', 'test@example.test');

        $secretary = $this->app->make(Secretary::class);

        $this->assertEquals('#test_channel', $secretary->slack_recipient);
    }

    /**
     * @test
     */
    public function all_messages_can_retrieved_not_including_archived()
    {
        $messageA = $this->createMessage();
        $messageB = $this->createMessage();
        $messageC = $this->createMessage($archive = true);
        $messageD = $this->createMessage();

        $secretary = $secretary = $this->app->make(Secretary::class);
        $all_messages = $secretary->getMessages();

        $this->assertCount(3, $all_messages);
        $this->assertTrue($all_messages->contains($messageA));
        $this->assertTrue($all_messages->contains($messageB));
        $this->assertTrue($all_messages->contains($messageD));
        $this->assertFalse($all_messages->contains($messageC));

    }

    /**
     * @test
     */
    public function all_archived_messages_can_be_retrieved()
    {
        $messageA = $this->createMessage();
        $messageB = $this->createMessage($archive = true);
        $messageC = $this->createMessage($archive = true);
        $messageD = $this->createMessage();

        $secretary = $secretary = $this->app->make(Secretary::class);
        $all_messages = $secretary->getArchivedMessages();

        $this->assertCount(2, $all_messages);
        $this->assertTrue($all_messages->contains($messageB));
        $this->assertTrue($all_messages->contains($messageC));
        $this->assertFalse($all_messages->contains($messageA));
        $this->assertFalse($all_messages->contains($messageD));
    }

    /**
     * @test
     */
    public function the_secretary_can_retrieve_the_messages_from_the_last_week()
    {
        $messageA = $this->createTimedMessage(Carbon::today()->subDays(2));
        $messageB = $this->createTimedMessage(Carbon::today()->subDays(4));
        $messageC = $this->createTimedMessage(Carbon::today()->subDays(6));
        $messageD = $this->createTimedMessage(Carbon::today()->subDays(8));

        $secretary = $this->app->make(Secretary::class);

        $recent_messages = $secretary->lastWeeksMessages();

        $this->assertCount(3, $recent_messages);
        $this->assertTrue($recent_messages->contains($messageA));
        $this->assertTrue($recent_messages->contains($messageB));
        $this->assertTrue($recent_messages->contains($messageC));
        $this->assertFalse($recent_messages->contains($messageD));
    }

    /**
     * @test
     */
    public function the_secretary_can_retrieve_the_messages_from_the_last_month()
    {
        $messageA = $this->createTimedMessage(Carbon::today()->subDays(2));
        $messageB = $this->createTimedMessage(Carbon::today()->subDays(10));
        $messageC = $this->createTimedMessage(Carbon::today()->subDays(40));
        $messageD = $this->createTimedMessage(Carbon::today()->subDays(50));

        $secretary = $this->app->make(Secretary::class);

        $recent_messages = $secretary->lastMonthsMessages();

        $this->assertCount(2, $recent_messages);
        $this->assertTrue($recent_messages->contains($messageA));
        $this->assertTrue($recent_messages->contains($messageB));
        $this->assertFalse($recent_messages->contains($messageC));
        $this->assertFalse($recent_messages->contains($messageD));
    }


}