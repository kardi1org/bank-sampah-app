<button wire:click="toggleSidebar" class="mr-4 text-gray-600 hover:text-gray-900">
    @if ($sidebarOpen)
        <i class="fas fa-bars text-xl"></i>
    @else
        <i class="fas fa-bars text-xl"></i>
    @endif
</button>
