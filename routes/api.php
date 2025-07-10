<?php

 use App\Http\Controllers\ControladorRegistroCooperativa;

Route::get('/backoffice', [ControladorRegistroCooperativa::class, 'index']);
Route::post('/backoffice/{id}/aceptar', [ControladorRegistroCooperativa::class, 'aceptar']);
Route::post('/backoffice/{id}/rechazar', [ControladorRegistroCooperativa::class, 'rechazar']);

?>
