<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;

class LoginPageTest extends TestCase
{
    public function test_login_page_renders_successfully(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertViewIs('auth.login');
    }

    public function test_login_page_contains_required_elements(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        
        // Check for form elements
        $response->assertSee('Sign in to your account');
        $response->assertSee('Email address');
        $response->assertSee('Password');
        $response->assertSee('Sign in');
        $response->assertSee('Forgot your password?');
        
        // Check for branding
        $response->assertSee('Geez Restaurant');
        $response->assertSee('ግዕዝ ሬስቶራንት');
        
        // Check for theme/language switches
        $response->assertSee('Switch language');
        $response->assertSee('English');
    }

    public function test_login_page_has_proper_meta_tags(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('<meta name="csrf-token"', false);
        $response->assertSee('<meta name="viewport"', false);
    }

    public function test_login_page_loads_required_assets(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        
        // Check for Vite assets (built assets have different paths)
        $response->assertSee('/build/assets/', false);
        
        // Check for fonts
        $response->assertSee('fonts.googleapis.com', false);
    }
}
