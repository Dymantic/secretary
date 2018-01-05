<?php

namespace Dymantic\Secretary\Tests\Unit;

use Dymantic\Secretary\Secretary;
use Dymantic\Secretary\Tests\TestCase;

class ServiceProviderTest extends TestCase
{
    /**
     *@test
     */
    public function it_creates_an_instance_using_the_correct_config()
    {
        $this->app['config']->set('secretary.sends_email_to', 'test@example.test');

        $secretary = $this->app->make(Secretary::class);

        $this->assertEquals('test@example.test', $secretary->getSendsEmailTo());
    }
}