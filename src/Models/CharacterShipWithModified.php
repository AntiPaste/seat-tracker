<?php

namespace Anon\Seat\Tracker\Models;

use Seat\Eveapi\Models\Assets\CharacterAsset;
use Seat\Eveapi\Models\Sde\InvGroup;
use Seat\Eveapi\Models\Sde\InvType;
use Seat\Services\Contracts\HasTypeID;
use Seat\Services\Models\ExtensibleModel;

class CharacterShipWithModified extends ExtensibleModel implements HasTypeID
{
    protected $table = 'character_ships';

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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function content()
    {
        return $this->hasMany(CharacterAsset::class, 'location_id', 'ship_item_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function type()
    {
        return $this->hasOne(InvType::class, 'typeID', 'ship_type_id')
            ->withDefault(function ($type) {
                $group = new InvGroup();
                $group->groupName = 'Unknown';

                $type->typeName = trans('web::seat.unknown');
                $type->group = $group;
            });
    }

    /**
     * @return int The eve type id of this object
     */
    public function getTypeID(): int
    {
        return $this->ship_type_id;
    }
}
