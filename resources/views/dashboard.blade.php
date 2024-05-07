<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">@if(auth()->check() && (auth()->user()->role == 'admin' || auth()->user()->role == 'moderator'))
            <form action="{{ url('/store/add') }}" method="POST">
                @csrf
                <div>
                    <label for="name">Store Name:</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div>
                    <label for="geo_coordinates">Geo Coordinates:</label>
                    <input type="text" id="geo_coordinates" name="geo_coordinates" required>
                </div>
                <div>
                    <label for="is_open">Is Open:</label>
                    <input type="checkbox" id="is_open" name="is_open" value="1">
                </div>
                <div>
                    <label for="store_type">Store Type:</label>
                    <select name="store_type" id="store_type" required>
                        <option value="takeaway">Takeaway</option>
                        <option value="shop">Shop</option>
                        <option value="restaurant">Restaurant</option>
                    </select>
                </div>
                <div>
                    <label for="max_delivery_distance">Max Delivery Distance:</label>
                    <input type="number" id="max_delivery_distance" name="max_delivery_distance" required>
                </div>
                <button type="submit">Add Store</button>
            </form>
        @else
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        {{ __("You're logged in!") }}
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
