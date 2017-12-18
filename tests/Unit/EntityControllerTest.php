<?php
/**
 * Created by PhpStorm.
 * User: krzysztofgrys
 * Date: 03.12.2017
 * Time: 3:31 PM
 */

use \Tests\TestCase;
use Mockery as M;

class EntityControllerTest extends TestCase
{
    protected $mockedEntitiesGateway;
    protected $mockedEntitiesRatingGateway;

    public function setUp()
    {
        parent::setUp();
        $this->mockedEntitiesGateway = M::mock('App\Entity\EntityGateway');
        $this->mockedEntitiesGateway = M::mock('App\Ratings\RatingGateway');
        $this->mockedEntitiesGateway->shouldReceive('getEntities')->andReturn(json_decode(file_get_contents(base_path() . '/tests/Unit/stubs/entities.json')));
        $this->app->instance('App\Entity\EntityGateway', $this->mockedEntitiesGateway);
        $this->app->instance('App\Ratings\RatingGateway', $this->mockedEntitiesRatingGateway);
    }


    public function testIndexAction()
    {
        $call = $this->call('GET', '/v1/entity', ['format' => 'application/json']);
        $this->assertTrue($call->isOk());
        $result = $call->getOriginalContent();
        dd($result);

    }

    public function testShowAction()
    {
        $this->assertTrue(true);


    }

    public function test1()
    {
        $this->assertTrue(true);
        $this->assertTrue(true);

    }

}
