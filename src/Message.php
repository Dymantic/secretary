<?php


namespace Dymantic\Secretary;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Message extends Model
{
    protected $table = 'secretary_messages';

    protected $fillable = ['name', 'email', 'message_body', 'message_notes'];

    protected $casts = ['archived' => 'boolean'];

    public function scopeArchived($query)
    {
        return $query->where('archived', true);
    }

    public function scopeUnarchived($query)
    {
        return $query->where('archived', false);
    }

    public function scopeFromTheLastWeek($query)
    {
        return $query->where('created_at', '>', Carbon::today()->subWeek());
    }

    public function scopeFromTheLastMonth($query)
    {
        return $query->where('created_at', '>', Carbon::today()->subMonth());
    }

    public function archive()
    {
        $this->archived = true;
        $this->save();
    }

    public function reinstate()
    {
        $this->archived = false;
        $this->save();
    }
}