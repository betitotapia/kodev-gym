<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/admin/credencial-download', function (\Illuminate\Http\Request $request) {
    $ruta = $request->query('path');
    $path = storage_path('app/private/' . $ruta);

    if (!file_exists($path)) {
        abort(404, 'Credencial no encontrada');
    }

    $ext  = pathinfo($path, PATHINFO_EXTENSION);
    $mime = $ext === 'jpg' ? 'image/jpeg' : 'image/png';

    return response()->download($path, 'credencial.' . $ext, ['Content-Type' => $mime]);
})->middleware('auth');