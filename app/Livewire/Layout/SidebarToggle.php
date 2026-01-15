<?php

namespace App\Livewire\Layout;

use Livewire\Component;

class SidebarToggle extends Component
{
    public $sidebarOpen = true;

    public function toggleSidebar()
    {
        $this->sidebarOpen = !$this->sidebarOpen;
        $this->dispatch('sidebar-toggled', open: $this->sidebarOpen);
    }

    public function render()
    {
        return view('livewire.layout.sidebar-toggle');
    }
}
