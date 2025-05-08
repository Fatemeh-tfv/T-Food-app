@extends('template.layouts.app')

@section('title', 'Add Food Item')

@section('content')
<div class="max-w-3xl mx-auto py-10 px-6">
    <h2 class="text-2xl font-semibold mb-6">Add New Food Item</h2>

    @if ($errors->any())
        <div class="mb-4 text-red-600">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('foods.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4 bg-white p-6 rounded shadow">
        @csrf

        <div>
            <label class="block font-semibold">Category</label>
            <input type="text" name="category_name" class="w-full border rounded px-3 py-2" required>
        </div>

        <div>
            <label class="block font-semibold">Food's Name</label>
            <input type="text" name="name" class="w-full border rounded px-3 py-2" required>
        </div>

        <div>
            <label class="block font-semibold">Description</label>
            <textarea name="description" class="w-full border rounded px-3 py-2"></textarea>
        </div>

        <div>
            <label class="block font-semibold">Price ($)</label>
            <input type="number" step="0.01" name="price" class="w-full border rounded px-3 py-2" required>
        </div>

        <div>
            <label class="block font-semibold">Image (optional)</label>
            <input type="file" name="image" class="w-full border rounded px-3 py-2">
        </div>

        <div class="text-right">
            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700">
                Save
            </button>
        </div>
    </form>
</div>
@endsection
