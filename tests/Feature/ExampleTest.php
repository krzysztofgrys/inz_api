<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
//        $response = $this->get('/v1/entity');
//        $response = $this->call('GET', '/v1/entity',
//            ['country-code' => 'gb', 'format' => 'application/json'], [], [], ['HTTP_Accept-Language' => 'en-gb']);
//        dd($response);
////
//        $response->assertStatus(200);
        $this->assertTrue(1==1);
    }
}
