<?php

namespace Anon\Seat\Tracker\Http\DataTables\Tracker;

use Illuminate\Http\JsonResponse;
use Seat\Eveapi\Models\Character\CharacterInfo;
use Yajra\DataTables\Services\DataTable;

class CharacterDataTableOverride extends DataTable
{
    public function ajax(): JsonResponse
    {
        return datatables()
            ->eloquent($this->applyScopes($this->query()))
            ->editColumn('name', function ($row) {
                return view('web::partials.character', ['character' => $row])->render();
            })
            ->editColumn('affiliation.corporation.name', function ($row) {
                return view('web::partials.corporation', ['corporation' => $row->affiliation->corporation])->render();
            })
            ->editColumn('affiliation.alliance.name', function ($row) {
                if (! is_null($row->affiliation->alliance_id))
                    return view('web::partials.alliance', ['alliance' => $row->affiliation->alliance])->render();

                return '';
            })
            ->editColumn('affiliation.faction.name', function ($row) {
                if (! is_null($row->affiliation->faction_id))
                    return view('web::partials.faction', ['faction' => $row->affiliation->faction])->render();

                return '';
            })
            ->editColumn('refresh_token.expires_on', function ($row) {
                return view('web::character.partials.token_status', ['refresh_token' => $row->refresh_token])->render();
            })
            ->editColumn('action', function ($row) {
                return view('tracker::partials.create', compact('row'));
            })
            ->rawColumns([
                'name',
                'affiliation.corporation.name',
                'affiliation.alliance.name',
                'affiliation.faction.name',
                'refresh_token.expires_on',
            ])
            ->make(true);
    }

    /**
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->postAjax()
            ->columns($this->getColumns())
            ->addAction()
            ->orderBy(0, 'asc')
            ->parameters([
                'drawCallback' => 'function() { ids_to_names(); $("[data-toggle=tooltip]").tooltip(); }',
            ]);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        return CharacterInfo::with([
            'affiliation',
            'affiliation.corporation',
            'affiliation.alliance',
            'affiliation.faction',
            'refresh_token',
        ])->select('character_infos.*');
    }

    /**
     * @return array
     */
    public function getColumns()
    {
        return [
            ['data' => 'name', 'title' => trans_choice('web::seat.name', 1)],
            ['data' => 'affiliation.corporation.name', 'title' => trans_choice('web::seat.corporation', 1)],
            ['data' => 'affiliation.alliance.name', 'title' => trans_choice('web::seat.alliance', 1)],
            ['data' => 'affiliation.faction.name', 'title' => trans('web::seat.faction')],
            ['data' => 'security_status', 'title' => trans('web::seat.security_status')],
            ['data' => 'refresh_token.expires_on', 'title' => trans('web::seat.token_status'), 'sortable' => false, 'searchable' => false],
        ];
    }
}
