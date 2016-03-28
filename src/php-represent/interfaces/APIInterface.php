<?php

namespace PHPRepresent\Interfaces;

/**
 * Interface APIInterface
 * @package PHPRepresent\Interfaces
 */
interface APIInterface {

	/**
	 * @return mixed
	 */
	public function setRateLimit();

	/**
	 * @param string $path
	 * @param array $params
	 * @param bool|true $throttle
	 *
	 * @return mixed
	 */
	public function get($path, array $params, $throttle = TRUE);

	/**
	 * @param $path
	 * @param array $params
	 *
	 * @return mixed
	 */
	public function getAll($path, array $params);

	/**
	 * @param null $postcode
	 *
	 * @return mixed
	 */
	public function postcodes($postcode = NULL);

	/**
	 * @param string|null $name
	 * @param array $params
	 *
	 * @return mixed
	 */
	public function boundarySets($name = NULL, array $params);


	/**
	 * @param string|null $boundarySet
	 * @param string|null $name
	 * @param bool|false $representatives
	 * @param array $params
	 *
	 * @return mixed
	 */
	public function boundaries($boundarySet = NULL, $name = NULL, $representatives = FALSE, array $params);

	/**
	 * @param null $set
	 *
	 * @return mixed
	 */
	public function representativeSets($set = NULL);

	/**
	 * @param null $set
	 * @param array $params
	 *
	 * @return mixed
	 */
	public function representatives($set = NULL, array $params);

	/**
	 * @param null $set
	 *
	 * @return mixed
	 */
	public function elections($set = NULL);

	/**
	 * @param null $set
	 * @param array $params
	 *
	 * @return mixed
	 */
	public function candidates($set = NULL, array $params);

}