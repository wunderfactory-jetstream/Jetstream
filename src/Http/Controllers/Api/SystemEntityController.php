<?php

namespace Jetstream\Jetstream\Http\Controllers\Api;

use Illuminate\Contracts\Validation\ValidationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Wundership\Http\Controllers\Resource\AccountController;
use Wundership\Http\Controllers\Resource\EmailAuthenticationController;
use Wundership\Http\Controllers\Resource\FacebookAuthenticationController;
use Wundership\Http\Exceptions\ModelValidationException;
use Wundership\Http\Requests;
use Wundership\Http\Controllers\Controller;

/**
 * Class SystemEntityController
 * TODO
 * @package Wundership\Http\Controllers\Api
 */
class SystemEntityController extends Controller
{
    public static $bindings = [
        'accounts' => AccountController::class,
        'facebook-authentication' => FacebookAuthenticationController::class,
        'email-authentication' => EmailAuthenticationController::class
    ];
}
