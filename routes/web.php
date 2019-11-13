<?php

$route = config('motor-docs.route', 'documentation');
Route::get($route.'/{package?}/{page?}', '\Motor\Docs\Http\Controllers\DocumentationController@index')
    ->name('documentation.index')
    ->where('page', '[0-9a-zA-Z\/\-]+')
    ->defaults('page', '')->defaults('package', '');
