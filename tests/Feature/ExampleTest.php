<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic test example.
     */
    public function test_public_pages_return_successful_responses(): void
    {
        $pages = [
            '/',
            '/nosotros',
            '/cursos',
            '/contacto',
            '/login',
            '/register',
        ];

        foreach ($pages as $page) {
            $this->get($page)->assertStatus(200);
        }

        $this->get('/checkout')->assertRedirect('/login');
    }
}
