<?php

namespace Anon\Seat\Tracker\Models;

use Illuminate\Database\Eloquent\Model;
use Seat\Eveapi\Models\Character\CharacterInfo;

class HighFrequencyPoll extends Model
{
    protected static $unguarded = true;
    public $incrementing = false;
    protected $primaryKey = 'character_id';

    public function character()
    {
        return $this->belongsTo(CharacterInfo::class, 'character_id', 'character_id');
    }
}
