{{-- resources/views/store_form.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Add Store') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ url('/store/add') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Store Name:</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="geo_coordinates" class="form-label">Geo Coordinates:</label>
                        <input type="text" class="form-control" id="geo_coordinates" name="geo_coordinates" required>
                    </div>
                    <div class="mb-3">
                        <label for="is_open" class="form-label">Is Open:</label>
                        <input type="checkbox" class="form-check-input" id="is_open" name="is_open" value="1">
                    </div>
                    <div class="mb-3">
                        <label for="store_type" class="form-label">Store Type:</label>
                        <select class="form-select" id="store_type" name="store_type" required>
                            <option value="takeaway">Takeaway</option>
                            <option value="shop">Shop</option>
                            <option value="restaurant">Restaurant</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="max_delivery_distance" class="form-label">Max Delivery Distance:</label>
                        <input type="number" class="form-control" id="max_delivery_distance" name="max_delivery_distance" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Store</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
