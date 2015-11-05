<?php

namespace Jetstream\Jetstream\Models;

use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Wundership\Transformers\AccountTransformer;

/**
 * Account Model
 * Used for authentication and is the central endpoint for end users
 * TODO
 * @package Wundership
 */
class Account extends GlobalUniqueModel implements AuthorizableContract {
	use Authorizable;

	public static $rules = [
		'email' => 'required|unique:accounts|email|min:1',
		'password' => 'required|min:6'
	];

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'accounts';

	protected $transformer = AccountTransformer::class;

	public $defaultFields = [];

	protected $fillable = ['email'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = [
		'password'
	];

	public static function sanitizeSave($data)
	{
		$data['password'] = Hash::make($data['password']);
		return $data;
	}

	/**
	 * Return the relation for the authentication method of a user
	 * @return \Illuminate\Database\Eloquent\Relations\MorphTo
	 */
	public function authentication()
	{
		return $this->morphTo();
	}

	public function getValidationRules()
	{
		return $this->rules;
	}
}
