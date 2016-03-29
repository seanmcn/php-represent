<?php

/**
 * Created by PhpStorm.
 * User: Sean
 * Date: 2016-03-28
 * Time: 11:54 PM
 */
class APITest extends PHPUnit_Framework_TestCase
{

    public function testGet()
    {
        $represent = new \PHPRepresent\API();
        $represent->setInsecure();
        $response = $represent->get('representativez');
        $this->assertFalse($response, 'Failed: FALSE on 404');


        $response = $represent->get('representatives');
        $isJson = self::isJson($response);
        $this->assertTrue($isJson, 'Failed: get() not returning JSON');
    }

    static function isJson($string)
    {
        if ($string === false) {
            return false;
        }
        json_decode($string);

        return ( json_last_error() == JSON_ERROR_NONE );
    }
}
