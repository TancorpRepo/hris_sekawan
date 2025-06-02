<x-filament-panels::page>

    {{-- @if (session('message'))
        <div class="alert alert-warning">
            {{ session('message') }}
        </div>
    @endif --}}

    <div class="mt-4">
        {{ $this->table }}
    </div>
</x-filament-panels::page>
