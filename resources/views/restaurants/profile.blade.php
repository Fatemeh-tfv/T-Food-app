@extends('template.layouts.app')

@section('title', 'Edit Restaurant Profile')

@section('content')

<div class="max-w-2xl mx-auto py-10 px-6">
    <h1 class="text-2xl font-bold mb-6">Edit Profile</h1>

@include('template.layouts.session')

    <form action="{{ route('restaurant.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4 bg-white p-6 rounded shadow">
        @csrf

        <div>
            <label class="block mb-1 font-medium">Restaurant Name</label>
            <input type="text" name="name" value="{{ old('name', $restaurant->name) }}" class="w-full border p-2 rounded">
            @error('name') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
        </div>

        <div>
            <label class="block mb-1 font-medium">Address</label>
            <input type="text" name="address" value="{{ old('address', $restaurant->address) }}" class="w-full border p-2 rounded">
            @error('address') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
        </div>

        <div>
            <label class="block mb-1 font-medium">Phone</label>
            <input type="text" name="phone" value="{{ old('phone', $restaurant->phone) }}" class="w-full border p-2 rounded">
            @error('phone') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
        </div>

        <div>
            <label class="block mb-1 font-medium">Description</label>
            <textarea name="description" class="w-full border p-2 rounded">{{ old('description', $restaurant->description) }}</textarea>
            @error('description') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
        </div>

        <div>
            <label class="block mb-1 font-medium">Logo</label>
            @if ($restaurant->logo)
                <img src="{{ asset('storage/' . $restaurant->logo) }}" class="h-20 mb-2" alt="Restaurant Logo">
            @endif
            <input type="file" name="logo" class="w-full border p-2 rounded">
            @error('logo') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
        </div>

        <div>
            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Save Changes</button>
        </div>
    </form>
</div>
@endsection