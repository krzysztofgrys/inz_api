<?php
/**
 * Created by PhpStorm.
 * User: krzysztofgrys
 * Date: 03.12.2017
 * Time: 3:31 PM
 */

use \Tests\TestCase;
use Mockery as M;

class UsersControllerTest extends TestCase
{
    protected $mockedEntitiesGateway;
    protected $mockedEntitiesRatingGateway;

    public function setUp()
    {
        parent::setUp();
        $this->mockedEntitiesGateway       = M::mock('App\Entity\EntityGateway');
        $this->mockedEntitiesRatingGateway = M::mock('App\Ratings\RatingGateway');
        $this->mockedEntitiesGateway->shouldReceive('getEntities')->andReturn(json_decode(file_get_contents(base_path() . '/tests/Unit/stubs/entities.json')));
        $this->mockedEntitiesRatingGateway->shouldReceive('getEntitiesRating')->andReturn(json_decode('{"2":{"entity_id":2,"count":1}}'
            )
        );
        $this->app->instance('App\Entity\EntityGateway', $this->mockedEntitiesGateway);
        $this->app->instance('App\Ratings\RatingGateway', $this->mockedEntitiesRatingGateway);
    }


    public function testIndexAction()
    {
        $call = $this->call('GET', '/v1/entity', ['format' => 'application/json']);
        $this->assertTrue($call->isOk());
        $result = $call->getOriginalContent();

        $data = $result['data'];

        foreach ($data as $dat) {
            $this->hasKeys(['id', 'user_id'], $dat);
        }


    }

}
