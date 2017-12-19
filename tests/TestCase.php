<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;


    protected function hasKeys($keys, $array)
    {
        foreach ($keys as $key){
            $this->assertArrayHasKey($key,$array);
        }
    }
}
