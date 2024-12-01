<?php

use App\Livewire\DeviceTable;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/devicetable', DeviceTable::class);
