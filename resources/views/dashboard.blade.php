<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Administrador Suplementos App') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <livewire:producto-crud />
            </div>
            <div class="mt-4">
                <a href=" {{ route('carrito') }}" class="text-lg text-bold text-blue-500 hover:underline">
                    {{ __('Ir al carrito') }}
                </a>
            </div>
        </div>
    </div>
</x-app-layout>