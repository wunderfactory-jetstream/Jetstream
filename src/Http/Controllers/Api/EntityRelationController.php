<?php namespace Jetstream\Jetstream\Http\Controllers\Api;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\App;
use Wundership\Exceptions\NotImplementedException;
use Wundership\GlobalUniqueModel;
use Wundership\Http\Controllers\Resource\ResourceController;
use Wundership\Http\Requests;
use Wundership\Account;
use Wundership\UUID;
use LucaDegasperi\OAuth2Server\Facades\Authorizer;

/**
 * Class EntityRelationController.php
 * TODO
 * @package Wundership\Http\Controllers
 */
class EntityRelationController extends Controller
{
	public function show($id, $relation)
	{
		if($id == 'me') $id = Account::findOrFail(Authorizer::getResourceOwnerId())->uuid->uuid;
		$entity = UUID::findOrFail($id)->entity;
		$relation = $entity->$relation();
		if($relation instanceof HasOne && $relation->getRelated() instanceof GlobalUniqueModel)
		{
			return ResourceController::resolveController($relation->getRelated())->show($relation->getRelated());
		}
		else
		{
			throw new NotImplementedException;
		}
	}

	public function store(Request $request, $id, $relation)
	{
		if($id == 'me') $id = Account::findOrFail(Authorizer::getResourceOwnerId())->uuid->uuid;
		$entity = UUID::findOrFail($id)->entity;
		$relation = $entity->$relation();
		if($relation instanceof HasOne && $relation->getRelated() instanceof GlobalUniqueModel)
		{
			return ResourceController::resolveController($relation->getRelated())->store();
		}
		else
		{
			throw new NotImplementedException;
		}
	}

	public function update(Request $request, $id, $relation)
	{
		if($id == 'me') $id = Account::findOrFail(Authorizer::getResourceOwnerId())->uuid->uuid;
		$entity = UUID::findOrFail($id)->entity;
		$relation = $entity->$relation();
		if($relation instanceof HasOne && $relation->getRelated() instanceof GlobalUniqueModel)
		{
			return ResourceController::resolveController($relation->getRelated())->update($relation->getRelated());
		}
		else
		{
			throw new NotImplementedException;
		}
	}

	public function destroy($id, $relation)
	{
		if($id == 'me') $id = Account::findOrFail(Authorizer::getResourceOwnerId())->uuid->uuid;
		$entity = UUID::findOrFail($id)->entity;
		$relation = $entity->$relation();
		if($relation instanceof HasOne && $relation->getRelated() instanceof GlobalUniqueModel)
		{
			return ResourceController::resolveController($relation->getRelated())->destroy($relation->getRelated());
		}
		else
		{
			throw new NotImplementedException;
		}
	}
}
