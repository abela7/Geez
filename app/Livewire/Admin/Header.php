<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class Header extends Component
{
    public $pageTitle;
    public $notificationCount = 3;

    public function mount($pageTitle = null)
    {
        $this->pageTitle = $pageTitle ?? __('dashboard.title');
    }

    public function render()
    {
        return view('livewire.admin.header');
    }

    public function markNotificationsAsRead()
    {
        $this->notificationCount = 0;
        $this->dispatch('notifications-read');
    }
}