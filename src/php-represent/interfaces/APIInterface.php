<?php

namespace PHPRepresent\Interfaces;

/**
 * Interface APIInterface
 * @package PHPRepresent\Interfaces
 */
interface APIInterface
{

    /**
     * @return mixed
     */
    public function setInsecure();

    /**
     * @param $limit
     *
     * @return mixed
     */
    public function setRateLimit($limit);

    /**
     * @return mixed
     */
    public function rateLimit();

    /**
     * @param string $path
     * @param array $params
     * @param bool|true $throttle
     *
     * @return mixed
     */
    public function get($path, array $params = [], $throttle = true);

    /**
     * @param $path
     * @param array $params
     *
     * @return mixed
     */
    public function getAll($path, array $params = []);

    /**
     * @param string $postcode
     *
     * @return mixed
     */
    public function postcode($postcode);

    /**
     * @param string|null $name
     * @param array $params
     *
     * @return mixed
     */
    public function boundarySets($name = null, array $params = []);


    /**
     * @param string|null $boundarySet
     * @param string|null $name
     * @param bool|false $representatives
     * @param array $params
     *
     * @return mixed
     */
    public function boundaries($boundarySet = null, $name = null, $representatives = false, array $params = []);

    /**
     * @param null $set
     *
     * @return mixed
     */
    public function representativeSets($set = null);

    /**
     * @param null $set
     * @param array $params
     *
     * @return mixed
     */
    public function representatives($set = null, array $params = []);

    /**
     * @param null $set
     *
     * @return mixed
     */
    public function elections($set = null);

    /**
     * @param null $set
     * @param array $params
     *
     * @return mixed
     */
    public function candidates($set = null, array $params = []);

}