<?php

Route::group([
    'namespace'  => 'Anon\Seat\Tracker\Http\Controllers',
    'middleware' => ['web', 'auth'],
], function (): void {
    Route::get('/tracker', [
        'as'   => 'tracker.index',
        'uses' => 'TrackerController@index'
    ]);

    Route::post('/tracker/polling/{character}', [
        'as'   => 'tracker.polling.create',
        'uses' => 'TrackerController@create'
    ]);

    Route::delete('/tracker/polling/{character}', [
        'as'   => 'tracker.polling.destroy',
        'uses' => 'TrackerController@destroy'
    ]);
});
