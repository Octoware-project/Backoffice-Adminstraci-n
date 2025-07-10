<?php

  use App\Http\Controllers\RegistroCooperativaController;

  Route::post('/registro-cooperativa', [RegistroCooperativaController::class, 'recibir']);

?>
