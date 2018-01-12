<?php


namespace Dymantic\Secretary\Tests\Unit;


use Dymantic\Secretary\ContactForm;
use Dymantic\Secretary\ContactMessage;
use Dymantic\Secretary\Tests\TestCase;

class ContactFormRequestTest extends TestCase
{
    /**
     * @test
     */
    public function it_has_the_correct_validation_fields()
    {
        $form = new ContactForm();
        $expected = [
            'name'           => 'required',
            'email'          => 'required|email',
            'message_body'   => 'required',
            'contact_method' => 'max:50',
            'phone'          => 'max:50'
        ];

        $this->assertEquals($expected, $form->rules());
    }

    /**
     * @test
     */
    public function it_creates_the_basic_secretary_message()
    {
        $form = new ContactForm();
        $form['name'] = 'Test name';
        $form['email'] = 'test@example.test';
        $form['message_body'] = 'Test body';

        $message = $form->contactMessage();

        $this->assertInstanceOf(ContactMessage::class, $message);
        $this->assertEquals('Test name', $message->sender());
        $this->assertEquals('test@example.test', $message->senderEmail());
        $this->assertEquals('Test body', $message->body());
        $this->assertEquals([], $message->messageNotes());
    }

    /**
     * @test
     */
    public function it_can_create_the_contact_message_with_stipulated_fields_as_the_message_notes()
    {
        $form = new ContactForm();
        $form['name'] = 'Test name';
        $form['email'] = 'test@example.test';
        $form['message_body'] = 'Test body';
        $form['phone'] = '+110 091 925 6421';
        $form['contact_method'] = 'email';
        $form['company'] = 'Acme test company'; //does not get validated, user has to validate

        $expected_notes = [
            'phone'          => '+110 091 925 6421',
            'contact_method' => 'email',
            'company'        => 'Acme test company',
        ];

        $message = $form->contactMessage(['phone', 'contact_method', 'company']);

        $this->assertInstanceOf(ContactMessage::class, $message);
        $this->assertEquals('Test name', $message->sender());
        $this->assertEquals('test@example.test', $message->senderEmail());
        $this->assertEquals('Test body', $message->body());
        $this->assertEquals($expected_notes, $message->messageNotes());
    }
}