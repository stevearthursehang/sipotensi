<x-filament-panels::page>
    <div class="max-w-2xl mx-auto">
        <form wire:submit="save" class="space-y-6">
            {{ $this->form }}

            <x-filament::button type="submit" class="w-full">
                Upload & Proses GeoJSON
            </x-filament::button>
        </form>
    </div>
</x-filament-panels::page>
