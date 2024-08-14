<?php

namespace Anon\Seat\Tracker\Http\DataTables\Tracker;

use Anon\Seat\Tracker\Models\HighFrequencyPoll;
use Illuminate\Http\JsonResponse;
use Seat\Eveapi\Models\Character\CharacterInfo;
use Yajra\DataTables\Services\DataTable;

class TrackerDataTable extends DataTable
{
    public function ajax(): JsonResponse
    {
        return datatables()
            ->eloquent($this->applyScopes($this->query()))
            ->editColumn('character.name', function ($row) {
                return view('web::partials.character', ['character' => $row->character])->render();
            })
            ->editColumn('character.affiliation.corporation.name', function ($row) {
                return view('web::partials.corporation', ['corporation' => $row->character->affiliation->corporation])->render();
            })
            ->editColumn('character.affiliation.alliance.name', function ($row) {
                if (! is_null($row->character->affiliation->alliance_id))
                    return view('web::partials.alliance', ['alliance' => $row->character->affiliation->alliance])->render();

                return '';
            })
            ->editColumn('character.location', function ($row) {
                return view('tracker::partials.location', ['location' => $row->character->location])->render();
            })
            ->editColumn('character.ship', function ($row) {
                return view('tracker::partials.ship', ['ship' => $row->character->ship])->render();
            })
            ->editColumn('action', function ($row) {
                return view('tracker::partials.delete', ['row' => $row->character])->render();
            })
            ->rawColumns([
                'character.name',
                'character.affiliation.corporation.name',
                'character.affiliation.alliance.name',
                'character.location',
                'character.ship',
                'action'
            ])
            ->make(true);
    }

    public function html()
    {
        return $this->builder()
            ->postAjax()
            ->columns($this->getColumns())
            ->addAction()
            ->orderBy(0, 'asc');
    }

    public function query()
    {
        return HighFrequencyPoll::with([
            'character.affiliation',
            'character.affiliation.corporation',
            'character.affiliation.alliance',
            'character.location',
            'character.ship',
        ])->select('character_infos.*');
    }

    public function getColumns()
    {
        return [
            ['data' => 'character.name', 'title' => trans_choice('web::seat.name', 1)],
            ['data' => 'character.affiliation.corporation.name', 'title' => trans_choice('web::seat.corporation', 1)],
            ['data' => 'character.affiliation.alliance.name', 'title' => trans_choice('web::seat.alliance', 1)],
            ['data' => 'character.location', 'title' => 'Location'],
            ['data' => 'character.ship', 'title' => 'Ship'],
        ];
    }
}
