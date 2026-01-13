<x-app-layout>
    <div class="flex flex-col lg:flex-row gap-6">
        <div class="lg:w-1/2">
            <livewire:petugas.input-setoran />
        </div>

        <div class="lg:w-1/2 bg-white rounded-xl shadow-sm p-6 h-fit">
            <h3 class="font-bold text-gray-700 mb-4 border-b pb-2">Setoran Terbaru Hari Ini</h3>
            <ul class="space-y-4">
                <li class="flex justify-between items-center p-3 bg-gray-50 rounded">
                    <div>
                        <p class="font-bold text-sm text-gray-800">Budi Santoso</p>
                        <p class="text-xs text-gray-500">Gabrukan: 10 Kg</p>
                    </div>
                    <span class="text-xs font-bold bg-yellow-100 text-yellow-700 px-2 py-1 rounded">PENDING</span>
                </li>
            </ul>
        </div>
    </div>
</x-app-layout>
