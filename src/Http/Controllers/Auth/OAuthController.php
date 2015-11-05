<?php

namespace Jetstream\Jetstream\Http\Controllers\Auth;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use LucaDegasperi\OAuth2Server\Facades\Authorizer;
use Wundership\Http\Requests;
use Wundership\Http\Controllers\Controller;
use Wundership\Scope;

class OAuthController extends Controller
{
	/*
	 * Issue an access token via one of the allowed methods
	 */
	public function issueAccessToken()
	{
		return response()->json(Authorizer::issueAccessToken());
	}

	/*
	 * Display a form where the user can authorize the client to use his data
	 */
	public function createAuthCode()
	{
		$authParams = Authorizer::getAuthCodeRequestParams();
		$formParams = array_except($authParams,'client');
		$formParams['client_id'] = $authParams['client']->getId();

		$scope_collection = new \Illuminate\Database\Eloquent\Collection();
		foreach($authParams['scopes'] as $scope)
		{
			$scope_collection->add(Scope::findOrFail($scope->getId()));
		}
		return view('oauth.authorization-form')
			->with('params', $formParams)
			->with('client', $authParams['client'])
			->with('scopes', $scope_collection);
	}

	/*
	 * Create auth code if the user accepted the request for access and redirect with auth code.
	 * Otherwise redirect with failed info
	 */
	public function storeAuthCode()
	{
		$params = Authorizer::getAuthCodeRequestParams();
		$params['user_id'] = Auth::user()->id;

		$redirectUri = '';

		// if the user has allowed the client to access its data, redirect back to the client with an auth code
		if (Input::get('approve') !== null) {
			$redirectUri = Authorizer::issueAuthCode('user', $params['user_id'], $params);
		}

		// if the user has denied the client to access its data, redirect back to the client with an error message
		if (Input::get('deny') !== null) {
			$redirectUri = Authorizer::authCodeRequestDeniedRedirectUri();
		}

		return Redirect::to($redirectUri);
	}
}
