<?php

use App\Http\Controllers\DocumentationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/docs/roles-permissions', [DocumentationController::class, 'rolesPermissions'])
    ->name('docs.roles-permissions');
