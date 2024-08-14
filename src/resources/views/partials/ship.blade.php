{{ $ship->type->typeName }} called <i>{{ $ship->ship_name }}</i>
<i>({{ $ship->last_modified->shortRelativeToNowDiffForHumans() }})</i>