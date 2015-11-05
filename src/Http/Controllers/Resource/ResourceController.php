<?php

namespace Jetstream\Jetstream\Http\Controllers\Resource;

use Dingo\Api\Exception\StoreResourceFailedException;
use Dingo\Api\Routing\Helpers;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;
use Jetstream\Jetstream\Http\Controllers\Controller;

/**
 * Class ResourceController
 * TODO
 * @package Wundership\Http\Controllers\Resource
 */
abstract class ResourceController extends Controller
{
	use Helpers;

	/**
	 * @var array
	 * Bind models to controllers
	 */
	private static $controllerBindings = [];

	private $request;

	public function __construct(Request $request)
	{
		$this->request = $request;
	}

	/**
	 * @param mixed $entity
	 * TODO
	 * @return \Jetstream\Jetstream\Http\Controllers\Resource\ResourceController
	 */
	public static function resolveController($entity)
	{
		return app(static::getControllerClass($entity));
	}

	/**
	 * @param mixed $entity
	 * TODO
	 * @return mixed
	 */
	private static function getControllerClass($entity)
	{
		if ($entity instanceof GlobalUniqueModel)
		{
			return static::$controllerBindings[get_class($entity)];
		} else
		{
			return static::$controllerBindings[$entity];
		}
	}

	public function requestedFields()
	{
		if($this->request->has('fields') && $this->request->input('fields') != '')
		{
			return $this->parseRequestedFields($this->request->input('fields'));
		}
		else
		{
			return false;
		}
	}

	/**
	 * TODO
	 * @return array
	 */
	private function parseRequestedFields($fieldString)
	{
		$raw = explode(',', $fieldString);
		$results = $this->parseRequestedFieldsArray($raw);
		return $results;
	}

	private function parseRequestedFieldsArray(&$raw)
	{
		$results = [];
		while(($part = array_shift($raw)) != null)
		{
			if($this->rawStringOpensNewLevel($part) && $this->rawStringClosesLevel($part))
			{
				$results[$this->getKeyFromRawStringWithOpeningCurlyBracket($part)][$this->getKeyFromRawStringWithOpeningAndClosingCurlyBrackets($part)] = null;
			}
			elseif($this->rawStringOpensNewLevel($part))
			{
				$key = $this->getKeyFromRawStringWithOpeningCurlyBracket($part);
				$results[$key] = $this->parseRequestedFieldsArray($raw);
				$results[$key][substr($part, strpos($part, '{') + 1)] = null;
			}
			elseif($this->rawStringClosesLevel($part))
			{
				$results[$this->getKeyFromRawStringWithClosingCurlyBracket($part)] = null;
				break;
			}
			else
			{
				$results[$part] = null;
			}
		}
		return $results;
	}

	/**
	 * @param $part
	 * TODO
	 * @return string
	 */
	private function getKeyFromRawStringWithOpeningAndClosingCurlyBrackets($part)
	{
		$start = strpos($part, '{') + 1;
		$length = strpos($part, '}') - $start;
		$result = substr($part, $start, $length);
		return $result;
	}

	/**
	 * @param $part
	 * TODO
	 * @return string
	 */
	private function getKeyFromRawStringWithOpeningCurlyBracket($part)
	{
		return substr($part, 0, strpos($part, '{'));
	}

	/**
	 * @param $part
	 * TODO
	 * @return bool
	 */
	private function rawStringOpensNewLevel($part)
	{
		return str_contains($part, '{');
	}

	/**
	 * @param $part
	 * TODO
	 * @return bool
	 */
	private function rawStringClosesLevel($part)
	{
		return str_contains($part, '}');
	}

	/**
	 * @param $part
	 * TODO
	 * @return string
	 */
	private function getKeyFromRawStringWithClosingCurlyBracket($part)
	{
		return substr($part, 0, strpos($part, '}'));
	}

	/**
	 * {@inheritdoc}
	 */
	protected function formatValidationErrors(Validator $validator)
	{
		throw new StoreResourceFailedException("Unable to create a new Resource.", $validator->errors());
	}
}