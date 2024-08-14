<?php

namespace Anon\Seat\Tracker\Commands\Tracker\Poll;

use Anon\Seat\Tracker\Jobs\LocationWithModified;
use Anon\Seat\Tracker\Jobs\ShipWithModified;
use Anon\Seat\Tracker\Models\HighFrequencyPoll;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Seat\Eveapi\Models\RefreshToken;

class HighFrequency extends Command
{
    use DispatchesJobs;

    public $queue = 'high';

    protected $signature = 'tracker:poll:highfrequency';

    protected $description = 'Poll location tracker at a high frequency.';

    public function handle()
    {
        $targets = HighFrequencyPoll::all();

        $this->line('Polling ' . $targets->count() . ' targets with high frequency');

        $start = microtime(true);
        foreach (HighFrequencyPoll::all() as $target) {
            $refreshToken = RefreshToken::find($target->character_id);
            if (!$refreshToken) {
                $this->line('Skipping character ' . $target->character_id);
                continue;
            }

            $this->dispatchSync(new LocationWithModified($refreshToken));
            $this->dispatchSync(new ShipWithModified($refreshToken));
        }

        $end = microtime(true);

        $this->line('Polled ' . $targets->count() . ' target with high frequency in ' . ($end - $start) . ' seconds');
    }
}
