<div class="d-flex">
    @can('global.superuser')
    <form class="mr-2" method="post" action="{{ route('tracker.polling.create', ['character' => $row->character_id]) }}">
        {{ csrf_field() }}
        {{ method_field('post') }}
        <button class="btn btn-xs btn-info">
            <i class="fas fa-server"></i>
            Poll
        </button>
    </form>
    @endcan

    @include('web::character.partials.delete', compact('row'))
</div>