@if (session('success'))
    <div
        x-data="{ show: true }"
        x-show="show"
        x-init="setTimeout(() => show = false, 3500)"
        class="mb-6 flex items-center justify-between rounded-lg border border-green-600 bg-green-900/40 px-4 py-3 text-green-300 text-sm"
    >
        <span>{{ session('success') }}</span>
        <button x-on:click="show = false" class="ml-4 text-green-400 hover:text-white">&times;</button>
    </div>
@endif

@if (session('error'))
    <div
        x-data="{ show: true }"
        x-show="show"
        x-init="setTimeout(() => show = false, 3500)"
        class="mb-6 flex items-center justify-between rounded-lg border border-red-600 bg-red-900/40 px-4 py-3 text-red-300 text-sm"
    >
        <span>{{ session('error') }}</span>
        <button x-on:click="show = false" class="ml-4 text-green-400 hover:text-white">&times;</button>
    </div>
@endif