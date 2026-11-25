<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('data-academic-notice', false);
        $response->assertSee('Website Simulasi Tugas Kuliah');
        $response->assertSee('Saya Mengerti, Lanjutkan');
    }

    public function test_login_page_includes_academic_notice_popup(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('data-academic-notice', false);
        $response->assertSee('Website Simulasi Tugas Kuliah');
    }
}
