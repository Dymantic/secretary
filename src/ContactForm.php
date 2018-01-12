<?php


namespace Dymantic\Secretary;


use Illuminate\Foundation\Http\FormRequest;

class ContactForm extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required',
            'email' => 'required|email',
            'message_body' => 'required',
            'contact_method' => 'max:50',
            'phone' => 'max:50'
        ];
    }

    public function contactMessage($note_fields = [])
    {
        $notes = collect($note_fields)->flatMap(function($field) {
            return [$field => $this->{$field}];
        })->all();
        $message_fields = array_merge($this->all(['name', 'email', 'message_body']), ['message_notes' => $notes]);
        return new ContactMessage($message_fields);
    }
}