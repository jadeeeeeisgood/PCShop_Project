<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}

                    @if(auth()->user()->role === 'admin')
                        <div class="mt-6">
                            <h3 class="text-lg font-semibold mb-4">Admin Panel</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <a href="{{ route('admin.categories.index') }}"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-center">Manage
                                    Categories</a>
                                <a href="{{ route('admin.products.index') }}"
                                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-center">Manage
                                    Products</a>
                                <a href="{{ route('admin.orders.index') }}"
                                    class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded text-center">Manage
                                    Orders</a>
                            </div>
                        </div>
                    @endif

                    <div class="mt-6">
                        <a href="{{ route('products.index') }}"
                            class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Browse
                            Products</a>
                        <a href="{{ route('cart.index') }}"
                            class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded ml-4">View
                            Cart</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>