<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Category Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold">{{ $category->name }}</h3>
                    <p>Slug: {{ $category->slug }}</p>
                    <p>Created: {{ $category->created_at }}</p>
                    <a href="{{ route('admin.categories.index') }}" class="text-blue-500">Back to list</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
