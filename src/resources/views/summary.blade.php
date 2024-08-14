<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between">
            <h3 class="card-title">Location summary</h3>
            <span>{{ $updatedAgo }}</span>
        </div>
    </div>
    <div class="row col card-body">
        @foreach ($systemCounts as $systemID => $count)
        <div class="col-1">{{ $count }}</div>
        <div class="col-11">{{ $systems[$systemID]->name }} ({{ $systems[$systemID]->region->name }})</div>
        @endforeach
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">High frequency polling</h3>
    </div>
    <div class="card-body">
        {{ $dataTable->table() }}
    </div>
</div>

@push('javascript')
{{ $dataTable->scripts() }}
@endpush