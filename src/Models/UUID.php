<?php

namespace Jetstream\Jetstream\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class UUID
 * TODO
 * @package Wundership
 */
class UUID extends Model
{

	/**
	 * @var string
	 * TODO
	 */
	protected $primaryKey = 'uuid';

	/**
	 * @var string
	 * TODO
	 */
	protected $table = 'uuid';

	protected $fillable = ['uuid', 'entity_id', 'entity_type'];

	/**
	 * @param $uuid
	 * TODO
	 * @return bool
	 */
	private static function uuidDoesNotExist($uuid)
	{
		return UUID::where('uuid', $uuid)->count() > 0;
	}

	/**
	 * @param GlobalUniqueModel $model
	 * @param $uuid
	 * TODO
	 * @return static
	 */
	private static function createUUIDForGlobalUniqueModel(GlobalUniqueModel $model, $uuid)
	{
		return UUID::create(['uuid' => $uuid, 'entity_id' => $model->getKey(), 'entity_type' => get_class($model)]);
	}

	/**
	 * TODO
	 * @return string
	 */
	private static function createNotExistingIdentifier()
	{
		do
		{
			$uuid = static::randomUUID(10);
			return $uuid;
		}
		while(self::uuidDoesNotExist($uuid));
	}

	/**
	 * TODO
	 * @return \Illuminate\Database\Eloquent\Relations\MorphTo
	 */
	public function entity()
	{
		return $this->morphTo();
	}

	public static function createRandomForModel(GlobalUniqueModel $model)
	{
		$uuid = self::createNotExistingIdentifier();

		self::createUUIDForGlobalUniqueModel($model, $uuid);
	}

	public static function randomUUID($numbers)
	{
		$uuid = '';
		for($i = 0; $i < $numbers; $i++)
		{
			$uuid .= rand(0, 9);
		}
		return $uuid;
	}
}
