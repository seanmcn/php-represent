<?php

namespace PHPRepresent;

use PHPRepresent\Interfaces\APIInterface;

/**
 * Class API
 * @package PHPRepresent
 */
class API implements APIInterface
{

    /**
     * @var string
     */
    protected $apiUrl = 'https://represent.opennorth.ca/';
    /**
     * @var bool
     */
    protected $secure = true;
    /**
     * @var int
     */
    protected $ratePeriod = 60;
    /**
     * @var int
     */
    protected $rateLimit = 60;
    /**
     * @var array
     */
    protected $rateHistory = [];


    /**
     * @return void
     */
    public function setInsecure()
    {
        $this->apiUrl = 'http://represent.opennorth.ca/';
        $this->secure = false;
    }

    /**
     * @param $limit
     * @return void
     */
    public function setRateLimit($limit)
    {
        $this->rateLimit = $limit;
    }

    /**
     *
     */
    public function rateLimit()
    {
        $begin     = new \DateTime(date("Y-m-d H:i:s", strtotime("1 minute ago")));
        $end       = new \DateTime(date("Y-m-d H:i:s"));
        $interval  = new \DateInterval('PT1S');
        $dateRange = new \DatePeriod($begin, $interval, $end);

        $requestCount = 0;
        foreach ($dateRange as $date) {
            if (array_search($date->format("Y-m-d H:i:s"), $this->rateHistory)) {
                $requestCount ++;
            }
        }

        if ($requestCount >= $this->rateLimit) {
            error_log('Throttling Open North API calls');
            sleep($this->ratePeriod);
        }

        $rateHistory[] = date("Y-m-d H:i:s");
    }

    /**
     * @param string $path
     * @param array $params
     * @param bool|true $throttle
     *
     * @return bool|mixed
     */
    public function get($path, array $params = [], $throttle = true)
    {
        if ($throttle) {
            $this->rateLimit();
        }

        $response = $this->simpleCurl($path, $params);

        return $response;
    }

    /**
     * @param $path
     * @param array $params
     *
     * @return string
     */
    public function getAll($path, array $params = [])
    {
        $objects = [];

        $params['limit']  = ! array_key_exists('limit', $params) ? 20 : $params['limit'];
        $params['offset'] = ! array_key_exists('offset', $params) ? 0 : $params['offset'];

        do {
            $this->rateLimit();
            $resultSet = json_decode($this->simpleCurl($path, $params));
            $more      = $resultSet->meta->next;
            $objects   = array_merge($objects, $resultSet->objects);
            $params['offset'] += $params['limit'];
        } while ($more !== null);

        return json_encode($objects);
    }

    /**
     * @param string $postcode
     *
     * @return bool|mixed
     */
    public function postcode($postcode)
    {
        $path = 'postcodes/' . strtoupper($postcode);

        return $this->get($path);
    }

    /**
     * @param null $name
     * @param array $params
     *
     * @return bool|mixed|string
     */
    public function boundarySets($name = null, array $params = [])
    {
        $path = 'boundary-sets';
        if ($name !== null) {
            $path .= '/' . $name;

            return $this->get($path, $params);
        }

        return $this->getAll($path, $params);
    }

    /**
     * @param null $boundarySet
     * @param null $name
     * @param bool|false $representatives
     * @param array $params
     *
     * @return mixed|string
     */
    public function boundaries($boundarySet = null, $name = null, $representatives = false, array $params = [])
    {
        $path = 'boundaries';

        if ($boundarySet !== null) {
            $path .= '/' . $boundarySet;
            if ($name !== null) {
                $path .= '/' . $name;
                if ($representatives) {
                    $path .= '/representatives';
                }
            }
        } else if ( ! array_key_exists('sets', $params) && ! array_key_exists('limit', $params)) {
            // Note: To get all boundaries we need to amp up the limit to not hit max execution time.
            $params['limit'] = 1000;
        }

        return $this->getAll($path, $params);
    }

    /**
     * @param null $set
     *
     * @return bool|mixed|string
     */
    public function representativeSets($set = null)
    {
        $path = 'representative-sets';
        if ($set !== null) {
            $path .= '/' . $set;

            return $this->get($path);
        }

        return $this->getAll($path);
    }

    /**
     * @param null $set
     * @param array $params
     *
     * @return string
     */
    public function representatives($set = null, array $params = [])
    {
        $path = 'representatives';

        if($set !== null) {
            $path .= '/'.$set;
        } else if ( ! array_key_exists('sets', $params) && ! array_key_exists('limit', $params)) {
            // Note: To get all representatives  we need to amp up the limit to not hit max execution time.
            $params['limit'] = 1000;
        }

        return $this->getAll($path, $params);
    }

    /**
     * @param null $set
     *
     * @return bool|mixed|string
     */
    public function elections($set = null)
    {
        //Todo: Test when has data
        $path = 'elections';
        if ($set !== null) {
            $path .= '/' . $set;
            return $this->get($path);
        }

        return $this->getAll($path);
    }

    /**
     * @param null $set
     * @param array $params
     *
     * @return mixed|string
     */
    public function candidates($set = null, array $params = [])
    {
        //Todo: Test when has data
        $path = 'candidates';

        if($set !== null) {
            $path .= '/'.$set;
        } else if ( ! array_key_exists('sets', $params) && ! array_key_exists('limit', $params)) {
            // Note: To get all candidates I'm guessing we need to amp up the limit to not hit max execution time.
            $params['limit'] = 1000;
        }

        return $this->getAll($path, $params);
    }

    /**
     * @param $path
     * @param array $params
     * @param array $options
     *
     * @return bool|mixed
     */
    protected function simpleCurl($path, $params = [], $options = [])
    {

        $paramUrl = '';
        $c        = 0;

        foreach ($params as $key => $param) {
            if (is_array($param)) {
                $param = implode(",", $param);
            }
            if ($c == 0) {
                $paramUrl .= '?' . $key . '=' . rawurlencode($param);
            } else {
                $paramUrl .= '&' . $key . '=' . rawurlencode($param);
            }
            $c ++;
        }

        $url = $this->apiUrl . $path . '/' . $paramUrl;

        $defaults = [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_CONNECTTIMEOUT => 15,
        ];

        if ($this->secure === false) {
            $defaults[CURLOPT_SSL_VERIFYPEER] = false;
            $defaults[CURLOPT_SSL_VERIFYHOST] = false;
        }

        $ch = curl_init();
        curl_setopt_array($ch, ( $options + $defaults ));



        if ( ! $result = curl_exec($ch)) {
            error_log(curl_error($ch));
        }
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($httpCode !== 200) {
            return false;
        }

        curl_close($ch);

        return $result;
    }
}