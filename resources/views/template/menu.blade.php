@extends('template.layouts.app')

@section('title', 'Categories - Yummy')

@section('content')
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold text-center mb-8"></h1>

        <div class="bg-white rounded-lg shadow-md p-6">
            <img src="{{ asset($restaurant->logo) }}" alt="{{ $restaurant->name }}" class="w-full h-64 object-cover rounded-lg mb-4">
            <p class="text-gray-600">{{ $restaurant->description }}</p>
            <p class="text-gray-600 mt-4"><strong>Address:</strong> {{ $restaurant->address }}</p>
            <p class="text-gray-600"><strong>Phone:</strong> {{ $restaurant->phone }}</p>
            <h2 class="text-2xl font-semibold mt-8">Menu</h2>
            <div class="mt-4 space-y-4">
                @foreach ($restaurant->menuCategories as $category)
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-xl font-semibold">{{ $category->name }}</h3>
                        <div class="mt-2 space-y-2">
                            <div class="mt-6 space-y-12 lg:grid lg:grid-cols-3 lg:gap-x-6 lg:space-y-0">
                                @foreach ($category->foodItems as $item)
                                <a href="{{ route('food-item.show', $item->id) }}">
                                    <div class="group relative flex flex-col space-y-2"> 
                                        <!-- <h3 class="text-lg font-semibold text-gray-7000 mb-4">
                                            {{ $item->name }}
                                        </h3> -->
                                        <img src="{{ asset($item->image) }}" alt="{{ $item->name }}" class="w-[200px] h-[200px] rounded-lg bg-white object-cover group-hover:opacity-75 sm:w-[250px] sm:h-[250px] lg:w-[300px] lg:h-[300px]">                                        
                                        <div class="flex flex-row items-center gap-x-6 mt-4">
                                        <h4 class="text-base font-semibold text-gray-900">{{ $item->name }}</h4>
                                        <p class="flex items-center justify-center px-8 py-3">${{ $item->price }}</p>
                                        </div>
                                    </div>
                                </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection