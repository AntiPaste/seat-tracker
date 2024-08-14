<?php

namespace Anon\Seat\Tracker\Models;

use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Eveapi\Models\Sde\SolarSystem;
use Seat\Eveapi\Models\Sde\StaStation;
use Seat\Eveapi\Models\Universe\UniverseStructure;
use Seat\Services\Models\ExtensibleModel;

class CharacterLocationWithModified extends ExtensibleModel
{
    protected $table = 'character_locations';

    /**
     * @var bool
     */
    protected static $unguarded = true;

    /**
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var string
     */
    protected $primaryKey = 'character_id';

    protected $casts = [
        'last_modified' => 'datetime:Y-m-d',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function character()
    {

        return $this->belongsTo(CharacterInfo::class, 'character_id', 'character_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function solar_system()
    {
        return $this->belongsTo(SolarSystem::class, 'solar_system_id', 'system_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function station()
    {

        return $this->belongsTo(StaStation::class, 'station_id', 'stationID');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function structure()
    {

        return $this->belongsTo(UniverseStructure::class, 'structure_id', 'structure_id');
    }
}
