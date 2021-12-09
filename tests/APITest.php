<?php

/**
 * Created by PhpStorm.
 * User: Sean
 * Date: 2016-03-28
 * Time: 11:54 PM
 */
class APITest extends PHPUnit_Framework_TestCase
{

    /**
     * @var PHPRepresent\API()
     */
    protected $represent;

    function setUp()
    {
        $this->represent = new \PHPRepresent\API();
        $this->represent->setInsecure();
    }

    public function testGet()
    {
        $response = $this->represent->get('representativez');
        $this->assertFalse($response, 'Failed: FALSE on 404');


        $response = $this->represent->get('representatives');
        $isJson   = self::isDataJson($response);
        $this->assertTrue($isJson, 'Failed: get() not returning JSON');
    }

    public function testGetAll()
    {
        $response = $this->represent->getAll('boundariez');
        $this->assertFalse($response, 'Failed: FALSE on 404');

        $response = $this->represent->getAll('boundaries', ['limit' => 1000]);
        $isJson   = self::isDataJson($response);
        $this->assertTrue($isJson, 'Failed: getAll() not returning JSON');
    }

    public function testPostcode()
    {
        $response = $this->represent->postcode('L5G4L3');
        $isJson   = self::isDataJson($response);
        $this->assertTrue($isJson, 'Failed: postcode() not returning JSON');
    }

    public function testBoundarySets()
    {
        $response = $this->represent->boundarySets();
        $isJson   = self::isDataJson($response);
        $this->assertTrue($isJson, 'Failed: boundarySets() not returning JSON');

        $response = $this->represent->boundarySets('federal-electoral-districts');
        $isJson   = self::isDataJson($response);
        $this->assertTrue($isJson, "Failed: boundarySets('federal-electoral-districts') not returning JSON");

        $response = $this->represent->boundarySets(null, ['domain' => 'Canada']);
        $isJson   = self::isDataJson($response);
        $this->assertTrue($isJson, "Failed: boundarySets(null, params) not returning JSON");
    }

    public function testBoundaries() {
        $response = $this->represent->boundaries();
        $isJson   = self::isDataJson($response);
        $this->assertTrue($isJson, 'Failed: boundaries() not returning JSON');

        $response = $this->represent->boundaries('toronto-wards');
        $isJson   = self::isDataJson($response);
        $this->assertTrue($isJson, 'Failed: boundaries(set) not returning JSON');

        $response = $this->represent->boundaries('nova-scotia-electoral-districts', 'cape-breton-centre');
        $isJson   = self::isDataJson($response);
        $this->assertTrue($isJson, 'Failed: boundaries(set, name) not returning JSON');

        $response = $this->represent->boundaries('nova-scotia-electoral-districts', 'cape-breton-centre', TRUE);
        $isJson   = self::isDataJson($response);
        $this->assertTrue($isJson, 'Failed: boundaries(representatives) not returning JSON');

        $response = $this->represent->boundaries('census-subdivisions', null, FALSE, ['name' => 'Niagara+Falls']);
        $isJson   = self::isDataJson($response);
        $this->assertTrue($isJson, 'Failed: boundaries(params) not returning JSON');
    }

    public function testRepresentativeSets() {
        $response = $this->represent->representativeSets();
        $isJson   = self::isDataJson($response);
        $this->assertTrue($isJson, 'Failed: representativeSets() not returning JSON');

        $response = $this->represent->representativeSets('ontario-legislature');
        $isJson   = self::isDataJson($response);
        $this->assertTrue($isJson, "Failed: representativeSets('ontario-legislature') not returning JSON");
    }

    public function testRepresentatives() {
        $response = $this->represent->representatives();
        $isJson   = self::isDataJson($response);
        $this->assertTrue($isJson, 'Failed: representatives() not returning JSON');

        $response = $this->represent->representatives('house-of-commons');
        $isJson   = self::isDataJson($response);
        $this->assertTrue($isJson, "Failed: representatives('house-of-commons') not returning JSON");

        $response = $this->represent->representatives('house-of-commons', ['point' => '45.524,-73.596']);
        $isJson   = self::isDataJson($response);
        $this->assertTrue($isJson, "Failed: representatives('house-of-commons') not returning JSON");
    }

    /**
     * @param $string
     *
     * @return bool
     */
    static function isDataJson($string)
    {
        if ($string === false) {
            return false;
        }
        json_decode($string);

        return ( json_last_error() == JSON_ERROR_NONE );
    }
}
