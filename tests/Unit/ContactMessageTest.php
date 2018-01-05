<?php


namespace Dymantic\Secretary\Tests\Unit;


use Dymantic\Secretary\SecretaryMessage;
use Dymantic\Secretary\Tests\MakesContactMessages;
use Dymantic\Secretary\Tests\TestCase;

class ContactMessageTest extends TestCase
{
    use MakesContactMessages;
    /**
     * @test
     */
    public function it_implements_the_correct_interface()
    {
        $contact_message = $this->makeMessage();

        $this->assertInstanceOf(SecretaryMessage::class, $contact_message);
    }

    /**
     * @test
     */
    public function it_provides_the_name_of_sender()
    {
        $contact_message = $this->makeMessage();

        $this->assertEquals('Test Name', $contact_message->sender);
    }

    /**
     *@test
     */
    public function it_provides_the_message_body()
    {
        $contact_message = $this->makeMessage();

        $this->assertEquals('Test message body', $contact_message->body());
    }

    /**
     *@test
     */
    public function it_can_be_converted_to_a_data_array_for_model_creation()
    {
        $contact_message = $this->makeMessage();

        $expected = [
            'name'          => 'Test Name',
            'email'         => 'test@example.test',
            'message_body'  => 'Test message body',
            'message_notes' => json_encode([])
        ];

        $this->assertEquals($expected, $contact_message->toDataArray());
    }
}