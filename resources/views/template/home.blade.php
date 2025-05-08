@extends('template.layouts.app')

@section('title', 'Home - Yummy')

@section('content')
@extends('template.layouts.session')
<div class="bg-white">
  <div class="mx-auto max-w-2xl px-4 py-16 sm:px-6 sm:py-24 lg:max-w-7xl lg:px-8">
    <h2 class="text-2xl tracking-tight text-gray-900">Pick your favorite restaurant</h2>

    <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8">
      @foreach ($restaurants as $restaurant)
      
      <div class="group relative">
        <img src="{{ asset($restaurant->logo) }}" alt="{{ $restaurant->name }}" class="aspect-square w-full rounded-md bg-gray-200 object-cover group-hover:opacity-75 lg:aspect-auto lg:h-80">
        <div class="mt-4 flex justify-between">
          <div>
            <h3 class="text-sm text-gray-700">
              <a href="{{ route('restaurants.show',$restaurant->id) }}">
                <span aria-hidden="true" class="absolute inset-0"></span>
                {{ $restaurant->name }}
              </a>
            </h3>
            <p class="mt-1 text-sm text-gray-500">{{ $restaurant->description }}</p>
          </div>
          {{-- ⭐ Display average rating --}}
          @php
            $fullStars = floor($restaurant->average_rating);
            $emptyStars = 5 - $fullStars;
          @endphp

          <p class="text-sm font-medium text-gray-900">
              {!! str_repeat('⭐', $fullStars) !!}
              {!! str_repeat('☆', $emptyStars) !!}
          </p>

        </div>
      </div>
      @endforeach
    </div>
  </div>
</div>

@endsection