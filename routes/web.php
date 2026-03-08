<?php

use Motor\Docs\Http\Controllers\DocumentationController;

$route = config('motor-docs.route', 'documentation');
Route::get($route.'/{package?}/{page?}', [DocumentationController::class, 'index'])
    ->name('documentation.index')
    ->where('page', '[0-9a-zA-Z\/\-]+')
    ->defaults('page', '')->defaults('package', '');
