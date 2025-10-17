<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Order - #') . $order->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-4">
                            <label for="status" class="block text-gray-700">Order Status</label>
                            <select name="status" id="status" class="w-full px-3 py-2 border rounded" required>
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending
                                </option>
                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>
                                    Processing</option>
                                <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped
                                </option>
                                <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered
                                </option>
                                <option value="canceled" {{ $order->status == 'canceled' ? 'selected' : '' }}>Canceled
                                </option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="payment_status" class="block text-gray-700">Payment Status</label>
                            <select name="payment_status" id="payment_status" class="w-full px-3 py-2 border rounded"
                                required>
                                <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>
                                    Pending</option>
                                <option value="completed" {{ $order->payment_status == 'completed' ? 'selected' : '' }}>
                                    Completed</option>
                                <option value="failed" {{ $order->payment_status == 'failed' ? 'selected' : '' }}>Failed
                                </option>
                            </select>
                        </div>
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Update
                            Order</button>
                        <a href="{{ route('admin.orders.index') }}" class="ml-4 text-gray-500">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
