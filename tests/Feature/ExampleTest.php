<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/en');

        $response->assertStatus(200);
    }

    public function test_root_redirects_to_en(): void
    {
        $response = $this->get('/');

        $response->assertRedirect('/en');
    }
}
