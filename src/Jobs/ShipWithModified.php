<?php

namespace Anon\Seat\Tracker\Jobs;

use Illuminate\Support\Carbon;
use Seat\Eveapi\Jobs\AbstractAuthCharacterJob;
use Seat\Eveapi\Models\Location\CharacterShip;

class ShipWithModified extends AbstractAuthCharacterJob
{
    /**
     * @var string
     */
    protected $method = 'get';

    /**
     * @var string
     */
    protected $endpoint = '/characters/{character_id}/ship/';

    /**
     * @var int
     */
    protected $version = 'v1';

    /**
     * @var string
     */
    protected $scope = 'esi-location.read_ship_type.v1';

    /**
     * @var array
     */
    protected $tags = ['character', 'meta'];

    /**
     * Execute the job.
     *
     * @throws \Throwable
     */
    public function handle()
    {
        parent::handle();

        $response = $this->retrieve([
            'character_id' => $this->getCharacterId(),
        ]);

        $ship = $response->getBody();

        CharacterShip::firstOrNew([
            'character_id' => $this->getCharacterId(),
        ])->fill([
            'ship_item_id' => $ship->ship_item_id,
            'ship_name' => $ship->ship_name,
            'ship_type_id' => $ship->ship_type_id,
            'last_modified' => $response->isFromCache() ? Carbon::now() : new Carbon($response->getHeaderLine('Last-Modified')),
        ])->save();
    }
}
