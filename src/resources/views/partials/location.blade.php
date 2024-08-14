@include('web::partials.location', ['location' => $location])
<i>({{ $location->last_modified->shortRelativeToNowDiffForHumans() }})</i>