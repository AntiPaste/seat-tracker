@can('global.superuser')
<form method="post" action="{{ route('tracker.polling.destroy', ['character' => $row->character_id]) }}">
    {{ csrf_field() }}
    {{ method_field('delete') }}
    <button class="btn btn-xs btn-danger">
        <i class="fas fa-trash-alt"></i>
        {{ trans('web::seat.delete') }}
    </button>
</form>
@endcan