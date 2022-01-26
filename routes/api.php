<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;

/**
 * This route is called when user must first time confirm secret
 */
Route::post('register', 'Lifeonscreen\Google2fa\Google2fa@register');

/**
 * This route is called when user must first time confirm secret
 */
Route::post('confirm', 'Lifeonscreen\Google2fa\Google2fa@confirm');

/**
 * This route is called to verify users secret
 */
Route::post('authenticate', 'Lifeonscreen\Google2fa\Google2fa@authenticate');

Route::post('unlocked-recovery-codes', 'Lifeonscreen\Google2fa\Http\Controllers\UnlockedRecoveryCodesController@store');
