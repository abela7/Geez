<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class Navigation extends Component
{
    public $currentRoute;
    public $sidebarCollapsed = false;
    public $sidebarOpen = false;

    public function mount()
    {
        $this->currentRoute = request()->route()->getName();
    }

    public function render()
    {
        return view('livewire.admin.navigation');
    }

    public function isActive($route)
    {
        return $this->currentRoute === $route;
    }

    public function toggleTheme()
    {
        $this->dispatch('theme-toggled');
    }
}