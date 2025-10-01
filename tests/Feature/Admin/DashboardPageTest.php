<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;

class DashboardPageTest extends TestCase
{
    public function test_renders_the_dashboard_page_successfully()
    {
        $response = $this->get('/admin/dashboard');
        
        $response->assertStatus(200);
    }

    public function test_contains_required_navigation_elements()
    {
        $response = $this->get('/admin/dashboard');
        
        // Check for navigation items
        $response->assertSee('Dashboard');
        $response->assertSee('Inventory');
        $response->assertSee('Sales');
        $response->assertSee('Staff');
        $response->assertSee('Customers');
        $response->assertSee('Reports');
        $response->assertSee('Settings');
    }

    public function test_contains_top_bar_elements()
    {
        $response = $this->get('/admin/dashboard');
        
        // Check for language switch
        $response->assertSee('EN');
        
        // Check for notification icon (badge with count)
        $response->assertSee('3'); // notification count
        
        // Check for user menu (avatar placeholder)
        $response->assertSee('Profile');
        $response->assertSee('Logout');
    }

    public function test_sidebar_collapse_functionality()
    {
        $response = $this->get('/admin/dashboard');
        
        // Check for sidebar collapse elements
        $response->assertSee('sidebarCollapsed');
        $response->assertSee('x-show="!sidebarCollapsed || sidebarOpen"', false);
    }

    public function test_contains_footer_text()
    {
        $response = $this->get('/admin/dashboard');
        
        $response->assertSee('© Geez Restaurant Management System — 2025');
    }

    public function test_has_proper_meta_tags()
    {
        $response = $this->get('/admin/dashboard');
        
        $response->assertSee('<meta charset="utf-8">', false);
        $response->assertSee('<meta name="viewport" content="width=device-width, initial-scale=1">', false);
        $response->assertSee('<meta name="csrf-token"', false);
    }

    public function test_loads_required_assets()
    {
        $response = $this->get('/admin/dashboard');
        
        // Check for Vite assets (compiled)
        $response->assertSee('/build/assets/', false);
        
        // Check for fonts
        $response->assertSee('inter', false);
        $response->assertSee('noto-sans-ethiopic', false);
    }
}