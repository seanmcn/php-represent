<?php

/**
 * Created by PhpStorm.
 * User: Sean
 * Date: 2016-04-03
 * Time: 10:46 AM
 */
class DocsTest extends PHPUnit\Framework\TestCase
{
    /**
     * @var PHPRepresent\API()
     */
    protected $represent;

    protected function setUp(): void
    {
        $this->represent = new \PHPRepresent\API();
        $this->represent->setInsecure();
    }

    function testGet()
    {
        $path     = 'boundaries';
        $params   = ['sets' => ['toronto-wards', 'ottawa-wards']];
        $response = $this->represent->get($path, $params);
        $isJson   = self::isDataJson($response);
        $this->assertTrue($isJson, 'Failed: boundaries() not returning JSON');
        $decoded = json_decode($response, true);
        $this->assertArrayHasKey('meta', $decoded);
        $this->assertArrayHasKey('objects', $decoded);
        $this->assertIsInt($decoded['meta']['total_count']);
        $this->assertGreaterThan(1, $decoded['meta']['total_count']);
    }

    function testGetAll()
    {
        $path     = 'boundaries';
        $params   = ['sets' => 'toronto-wards,ottawa-wards'];
        $response = $this->represent->getAll($path, $params);

        $isJson = self::isDataJson($response);
        $this->assertTrue($isJson, 'Failed: boundaries() not returning JSON');
        $decoded = json_decode($response, true);

        $this->assertIsArray($decoded);
        $this->assertIsArray($decoded[0]);
    }

    function testPostcode()
    {
        $response = $this->represent->postcode('L5G4L3');
        $isJson   = self::isDataJson($response);
        $this->assertTrue($isJson, 'Failed: boundaries() not returning JSON');
        $decoded = json_decode($response, true);

        // It seems that the representatives_concordance key does not show up for this postcode any more
        // $this->assertArrayHasKey('representatives_concordance', $decoded);
        $this->assertArrayHasKey('boundaries_centroid', $decoded);
        $this->assertArrayHasKey('representatives_centroid', $decoded);
        $this->assertArrayHasKey('boundaries_concordance', $decoded);

    }

    function testBoundarySets()
    {
        $response = $this->represent->boundarySets();
        $isJson   = self::isDataJson($response);
        $this->assertTrue($isJson, 'Failed: boundaries() not returning JSON');
        $decoded = json_decode($response, true);
        $this->assertArrayHasKey(0, $decoded);
        $this->assertArrayHasKey('domain', $decoded[0]);
        $this->assertArrayHasKey('name', $decoded[0]);
    }

    function testBoundaries()
    {
        $response = $this->represent->boundaries('toronto-wards-2018');
        $isJson   = self::isDataJson($response);
        $this->assertTrue($isJson, 'Failed: boundaries() not returning JSON');
        $decoded = json_decode($response, true);
        $this->assertArrayHasKey(0, $decoded);
        $this->assertArrayHasKey('boundary_set_name', $decoded[0]);
        $this->assertArrayHasKey('name', $decoded[0]);

        $response = $this->represent->boundaries(null, null, false, ['sets' => ['toronto-wards-2018', 'ottawa-wards']]);
        $isJson   = self::isDataJson($response);
        $this->assertTrue($isJson, 'Failed: boundaries() not returning JSON');
        $decoded = json_decode($response, true);
        $this->assertArrayHasKey(0, $decoded);
        $this->assertArrayHasKey('boundary_set_name', $decoded[0]);
        $this->assertArrayHasKey('name', $decoded[0]);
    }

    function testRepresentativeSets(){
        $response = $this->represent->representativeSets('north-dumfries-township-council');
        $isJson   = self::isDataJson($response);
        $this->assertTrue($isJson, 'Failed: boundaries() not returning JSON');
        $decoded = json_decode($response, true);
        $this->assertArrayHasKey('url', $decoded);
        $this->assertArrayHasKey('name', $decoded);
    }

    function testRepresentatives() {
        $response = $this->represent->representatives('house-of-commons');
        $isJson   = self::isDataJson($response);
        $this->assertTrue($isJson, 'Failed: boundaries() not returning JSON');
        $decoded = json_decode($response, true);
        $this->assertArrayHasKey(0, $decoded);
        $this->assertArrayHasKey('district_name', $decoded[0]);
        $this->assertArrayHasKey('party_name', $decoded[0]);
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
