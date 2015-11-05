<?php

namespace Jetstream\Jetstream\Models;

use app\Interfaces\AccessLevelable;
use Illuminate\Database\Eloquent\Model;
use Wundership\Exceptions\UndefinedTransformerException;
use Wundership\Traits\Validateable;
use Wundership\Transformers\AccountTransformer;

/**
 * Class GlobalUniqueModel
 * TODO
 * @package Wundership
 */
abstract class GlobalUniqueModel extends Model {

	public $defaultFields = [];
	public $guardianPrependFields = ['id', 'entity'];
	public $guardianAppendFields = ['created_at', 'updated_at'];
	protected $transformer = null;

	/**
	 * Process and sanitize the given field array for saving
	 * @param array $data
	 * TODO
	 * @return array
	 */
	public static function sanitizeSave($data)
	{
		return [];
	}

	/**
	 * TODO
	 * @return \Illuminate\Database\Eloquent\Relations\MorphOne
	 */
	public function uuid()
	{
		return $this->morphOne('Wundership\UUID', 'entity');
	}

	public function idAccessor()
	{
		return $this->uuid->uuid;
	}

	public function entityAccessor()
	{
		return get_class($this);
	}

	public function getOwner()
	{
		return 'system';
	}

	public function getTransformer()
	{
		if(is_null($this->transformer)) throw new UndefinedTransformerException(get_class($this));
		return new $this->transformer;
	}

}