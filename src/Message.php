<?php


namespace Dymantic\Secretary;


use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = 'secretary_messages';

    protected $fillable = ['name', 'email', 'message_body', 'message_notes'];
}