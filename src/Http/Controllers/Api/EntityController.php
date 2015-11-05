<?php

namespace Jetstream\Jetstream\Http\Controllers\Api;

use Illuminate\Http\Request;
use Wundership\Http\Controllers\Controller;
use Wundership\Http\Controllers\Resource\ResourceController;
use Wundership\Http\Requests;
use Wundership\Account;
use Wundership\UUID;
use LucaDegasperi\OAuth2Server\Facades\Authorizer;

/**
 * Class EntityController
 * TODO
 * @package Wundership\Http\Controllers
 */
class EntityController extends Controller
{
	/**
	 * Display the requested entity with all the specified fields
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		// If 'me' is specified as resource, try to find account id by the request token bearer
		if($id == 'me') $id = Account::findOrFail(Authorizer::getResourceOwnerId())->uuid->uuid;
		//try to retrieve an entity by it's uuid
		$entity = UUID::findOrFail($id)->entity;
		//find resource controller - invoke show method
		return ResourceController::resolveController($entity)->show($entity);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Request $request, $id)
	{
		$entity = UUID::findOrFail($id)->entity;
		return ResourceController::resolveController($entity)->update($entity);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$entity = UUID::findOrFail($id)->entity;
		return ResourceController::resolveController($entity)->destroy($entity);
	}
}