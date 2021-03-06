<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * Class PingTest
 * @package Tests\Feature
 */
class PingTest extends TestCase
{

    /**
     *
     */
    public function test_it_returns_ping()
    {
        $response = $this->json('GET', 'api/ping');
        $response->assertStatus(200);
        $response->assertJson(['status' => 'ok']);
    }

    /**
     *
     */
    public function test_it_returns_404()
    {
        $response = $this->json('GET', 'api/non-existing-resource');
        $response->assertStatus(404);
        $response->assertJson(['message' => '404 Not Found', 'status_code' => 404]);
    }
}
