<x-filament::page>
    <form wire:submit.prevent="authenticate" class="space-y-8">
        <div>
            <label for="PersonnelNo" class="block text-sm font-medium text-gray-700">
                {{ __('NIK') }}
            </label>
            <input id="PersonnelNo" wire:model="PersonnelNo" type="text" required
                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            @error('PersonnelNo') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="Password" class="block text-sm font-medium text-gray-700">
                {{ __('Password') }}
            </label>
            <input id="Password" wire:model="password" type="password" required
                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            @error('Password') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
        </div>

        <x-filament::button type="submit" class="w-full">
            {{ __('Login') }}
        </x-filament::button>
    </form>
</x-filament::page>
