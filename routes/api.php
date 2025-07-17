<?php

 use App\Http\Controllers\ControladorRegistroCooperativa;

Route::get('/backoffice', [ControladorRegistroCooperativa::class, 'index']);
Route::get('/persona', [ControladorRegistroCooperativa::class, 'index']);

?>
