<?php

namespace Anon\Seat\Tracker\Commands\Tracker\Poll;

use Anon\Seat\Tracker\Jobs\LocationWithModified;
use Anon\Seat\Tracker\Jobs\ShipWithModified;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Seat\Eveapi\Models\Character\CharacterInfo;

class Normal extends Command
{
    use DispatchesJobs;

    protected $signature = 'tracker:poll:normal';

    protected $description = 'Poll location tracker at a normal frequency.';

    public function handle()
    {
        $targets = CharacterInfo::with('refresh_token')->where(function ($subQuery) {
            $subQuery->whereHas('refresh_token', function ($query) {
                $query->where('expires_on', '>=', carbon());
            });
        })->get();

        $this->line('Polling ' . $targets->count() . ' targets');

        foreach ($targets as $target) {
            $this->dispatch(new LocationWithModified($target->refresh_token));
            $this->dispatch(new ShipWithModified($target->refresh_token));
        }
    }
}
