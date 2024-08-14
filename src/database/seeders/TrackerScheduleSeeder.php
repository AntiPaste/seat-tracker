<?php

namespace Anon\Seat\Tracker\database\seeders;

use Seat\Services\Seeding\AbstractScheduleSeeder;

class TrackerScheduleSeeder extends AbstractScheduleSeeder
{
    public function getSchedules(): array
    {
        return [
            [
                'command' => 'tracker:poll:normal',
                'expression' => '*/15 * * * *',
                'allow_overlap' => false,
                'allow_maintenance' => false,
                'ping_before' => null,
                'ping_after' => null,
            ],
            [
                'command' => 'tracker:poll:highfrequency',
                'expression' => '* * * * *',
                'allow_overlap' => false,
                'allow_maintenance' => false,
                'ping_before' => null,
                'ping_after' => null,
            ],
        ];
    }

    public function getDeprecatedSchedules(): array
    {
        return [];
    }
}
