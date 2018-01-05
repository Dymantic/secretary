<?php


namespace Dymantic\Secretary\Tests\Unit;


use Dymantic\Secretary\Secretary;
use Dymantic\Secretary\Tests\TestCase;

class SecretaryTest extends TestCase
{
    /**
     *@test
     */
    public function it_has_the_correct_email_property()
    {
        $this->app['config']->set('secretary.sends_email_to', 'test@example.test');

        $secretary = $this->app->make(Secretary::class);

        $this->assertEquals('test@example.test', $secretary->email);
    }

    /**
     *@test
     */
    public function it_provides_the_correct_slack_webhook()
    {
        $this->app['config']->set('secretary.slack_endpoint', 'https://test.test/endpoint');
        $this->app['config']->set('secretary.sends_email_to', 'test@example.test');

        $secretary = $this->app->make(Secretary::class);

        $this->assertEquals('https://test.test/endpoint', $secretary->routeNotificationForSlack());
    }

    /**
     *@test
     */
    public function the_secretary_can_have_a_slack_recipient()
    {
        $this->app['config']->set('secretary.slack_recipient', '#test_channel');
        $this->app['config']->set('secretary.slack_endpoint', 'https://test.test/endpoint');
        $this->app['config']->set('secretary.sends_email_to', 'test@example.test');

        $secretary = $this->app->make(Secretary::class);

        $this->assertEquals('#test_channel', $secretary->slack_recipient);
    }
}