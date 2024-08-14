<?php

namespace Anon\Seat\Tracker\Http\Controllers;

use Anon\Seat\Tracker\Http\DataTables\Tracker\TrackerDataTable;
use Anon\Seat\Tracker\Models\HighFrequencyPoll;
use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Eveapi\Models\Sde\SolarSystem;
use Seat\Web\Http\Controllers\Controller;
use Seat\Web\Http\DataTables\Scopes\CharacterScope;

class TrackerController extends Controller
{
    public function index(TrackerDataTable $dataTable)
    {
        $oldestUpdatedAt = null;

        $systemCounts = [];

        $validCharacters = CharacterInfo::with('refresh_token')->with('location')->where(function ($subQuery) {
            $subQuery->whereHas('refresh_token', function ($query) {
                $query->where('expires_on', '>=', carbon());
            });
        })->whereHas('location')->get();

        foreach ($validCharacters as $character) {
            if (!$oldestUpdatedAt || $character->location->last_modified < $oldestUpdatedAt) {
                $oldestUpdatedAt = $character->location->last_modified;
            }

            $systemCounts[$character->location->solar_system_id] = ($systemCounts[$character->location->solar_system_id] ?? 0) + 1;
        }

        $systemCounts = array_filter($systemCounts, function ($count) {
            return $count >= 10;
        });

        arsort($systemCounts);

        $updatedAgo = $oldestUpdatedAt ? $oldestUpdatedAt->shortRelativeToNowDiffForHumans() : '-';

        $systems = [];
        foreach (SolarSystem::with('region')->get() as $system) {
            $systems[$system->system_id] = $system;
        }

        return $dataTable
            ->addScope(new CharacterScope)
            ->render('tracker::index', ['systems' => $systems, 'systemCounts' => $systemCounts, 'updatedAgo' => $updatedAgo]);
    }

    public function create(CharacterInfo $character)
    {
        HighFrequencyPoll::create([
            'character_id' => $character->character_id
        ]);

        return redirect()->back()
            ->with('success', sprintf('Character %s has been successfully added to high frequency polling.', $character->name));
    }

    public function destroy(CharacterInfo $character)
    {
        HighFrequencyPoll::where('character_id', $character->character_id)->delete();

        return redirect()->back()
            ->with('success', sprintf('Character %s has been successfully removed from high frequency polling.', $character->name));
    }
}
