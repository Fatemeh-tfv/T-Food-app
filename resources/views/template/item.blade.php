@extends('template.layouts.app')

@section('title', 'Food Item - Yummy')

@section('content')

@extends('template.layouts.session')
<div class="bg-white">
  <div class="pt-6">
    <nav aria-label="Breadcrumb">
      <ol role="list" class="mx-auto flex max-w-2xl items-center space-x-2 px-4 sm:px-6 lg:max-w-7xl lg:px-8">
        <li>
          <div class="flex items-center">
            <a href="#" class="mr-2 text-sm font-medium text-gray-900">{{ $food->category && $food->category->restaurant ? $food->category->restaurant->name : 'No Restaurant' }}</a>
            <svg width="16" height="20" viewBox="0 0 16 20" fill="currentColor" aria-hidden="true" class="h-5 w-4 text-gray-300">
              <path d="M5.697 4.34L8.98 16.532h1.327L7.025 4.341H5.697z" />
            </svg>
          </div>
        </li>
        <li>
          <div class="flex items-center">
            <a href="#" class="mr-2 text-sm font-medium text-gray-900">{{ $food->category ? $food->category->name : 'No Category' }}</a>
            <svg width="16" height="20" viewBox="0 0 16 20" fill="currentColor" aria-hidden="true" class="h-5 w-4 text-gray-300">
              <path d="M5.697 4.34L8.98 16.532h1.327L7.025 4.341H5.697z" />
            </svg>
          </div>
        </li>

        <li class="text-sm">
          <a href="#" aria-current="page" class="font-medium text-gray-500 hover:text-gray-600">{{ $food->name }}</a>
        </li>
      </ol>
    </nav>

    <!-- Image gallery -->
    <div class="mx-auto mt-6 max-w-2xl sm:px-6 lg:grid lg:max-w-7xl lg:grid-cols-3 lg:gap-x-8 lg:px-8">
      <!-- <img src="https://tailwindcss.com/plus-assets/img/ecommerce-images/product-page-02-secondary-product-shot.jpg" alt="Two each of gray, white, and black shirts laying flat." class="hidden size-full rounded-lg object-cover lg:block"> -->
      <div class="hidden lg:grid lg:grid-cols-1 lg:gap-y-8">
        <!-- <img src="{{ asset($food->image) }}" alt="{{ $food->name }}" class="aspect-3/2 w-full rounded-lg object-cover"> -->
        <img src="{{ asset($food->image) }}" alt="{{ $food->name }}" class="aspect-3/2 w-full rounded-lg object-cover">
      </div>
      <!-- <img src="https://tailwindcss.com/plus-assets/img/ecommerce-images/product-page-02-featured-product-shot.jpg" alt="Model wearing plain white basic tee." class="aspect-4/5 size-full object-cover sm:rounded-lg lg:aspect-auto"> -->
    </div>

    <!-- Product info -->
    <div class="mx-auto max-w-2xl px-4 pt-10 pb-16 sm:px-6 lg:grid lg:max-w-7xl lg:grid-cols-3 lg:grid-rows-[auto_auto_1fr] lg:gap-x-8 lg:px-8 lg:pt-16 lg:pb-24">
      <div class="lg:col-span-2 lg:border-r lg:border-gray-200 lg:pr-8">
        <h1 class="text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl">{{ $food->name }}</h1>
      </div>

      <!-- Options -->
      <div class="mt-4 lg:row-span-3 lg:mt-0">
        <h2 class="sr-only">{{ $food->name }}</h2>
        <p class="text-3xl tracking-tight text-gray-900">${{ $food->price }}</p>

        <!-- Reviews -->
          <div class="mt-6">
              <h3 class="sr-only">Reviews</h3>
              <div class="flex items-center">
                  <div class="flex items-center">
                      @php
                          $averageRating = round($food->averageRating());
                      @endphp
                      @for ($i = 1; $i <= 5; $i++)
                          <svg class="size-5 shrink-0 {{ $i <= $averageRating ? 'text-gray-900' : 'text-gray-200' }}" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                              <path fill-rule="evenodd" d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401Z" clip-rule="evenodd" />
                          </svg>
                      @endfor
                  </div>
                  <p class="ml-2 text-sm text-gray-600">{{ $food->review->count() }} reviews</p>
              </div>
          </div>
        <!-- Add to order item's form -->
        <form method="POST" action="{{ route('order.add') }}" id="cart-update-form-{{ $food->id }}" class="mt-4 flex items-center" data-food-id="{{ $food->id }}">
            @csrf
            <input type="hidden" name="food_item_id" value="{{ $food->id }}">
            <button type="submit" id="buy-{{ $food->id }}" class="bg-red-500 text-white px-4 py-2 rounded buy-button">
            Add to basket
            </button>
            <div id="quantity-controls-{{ $food->id }}" class="hidden flex items-center">
            <button type="button" class="bg-gray-300 px-2 py-1 rounded" onclick="decreaseQuantity({{ $food->id }})">−</button>
            <input type="number" name="quantity" value="1" min="1" id="quantity-{{ $food->id }}" class="w-12 text-center border rounded mx-2" />
            <button type="button" class="bg-gray-300 px-2 py-1 rounded" onclick="increaseQuantity({{ $food->id }})">+</button>
            </div>
        </form>
      </div>

      <div class="py-10 lg:col-span-2 lg:col-start-1 lg:border-r lg:border-gray-200 lg:pt-6 lg:pr-8 lg:pb-16">
        <!-- Description and details -->
        <div>
          <h3 class="sr-only">About the {{ $food->name }}:</h3>

          <div class="space-y-6">
            <p class="text-base text-gray-900">{{ $food->description }}</p>
          </div>
        </div>

        <div class="mt-10">
          <h3 class="text-lg font-semibold">Customer Reviews</h3>
          <div class="mt-4 space-y-4">
              @foreach($food->review as $review)
                  <div class="border-b pb-4">
                      <p class="font-semibold">{{ $review->user->name }}</p>
                      <p class="text-sm text-gray-500">{{ $review->created_at->diffForHumans() }}</p>
                      <p class="text-sm mt-1">{{ $review->review }}</p>
                      <div class="flex">
                          @for ($i = 1; $i <= 5; $i++)
                              <svg class="size-4 {{ $i <= $review->rating ? 'text-yellow-500' : 'text-gray-200' }}" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                  <path fill-rule="evenodd" d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401Z" clip-rule="evenodd" />
                              </svg>
                          @endfor
                      </div>  
                  </div>
              @endforeach
          </div>
        </div>

        <!-- Button to trigger modal -->
        @auth
        <button onclick="openModal()" class="bg-blue-500 text-white px-4 py-2 rounded-lg mt-4">
            Add a Review
        </button>

        <!-- Modal Background -->
         
        <div id="reviewModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
            <div class="bg-white p-6 rounded-lg w-1/3">
                <h3 class="text-lg font-semibold mb-4">Add a Review</h3>
                
                <!-- Review Form -->
                <form action="{{ route('reviews.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="food_item_id" value="{{ $food->id }}">

                    <!-- Comment -->
                    <label class="block mb-2">Comment:</label>
                    <textarea name="review" class="w-full border rounded-lg p-2 mb-4" required></textarea>
                    <!-- Rating -->
                    <label class="block mb-2">Rating:</label>
                    <div class="flex space-x-1 mb-4">
                        @for ($i = 1; $i <= 5; $i++)
                            <label>
                                <input type="radio" name="rating" value="{{ $i }}" class="hidden" required>
                                <svg class="size-6 cursor-pointer star" data-value="{{ $i }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                                </svg>
                            </label>
                        @endfor
                    </div>
                    <!-- Submit & Close Buttons -->
                    <div class="flex justify-between">
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg">
                            Submit Review
                        </button>
                        <button type="button" onclick="closeModal()" class="bg-gray-500 text-white px-4 py-2 rounded-lg">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
      @endauth

      </div>
    </div>
  </div>
</div>
<script>
    function openModal() {
        document.getElementById('reviewModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('reviewModal').classList.add('hidden');
    }

    // Star rating functionality
    document.querySelectorAll('.star').forEach(star => {
        star.addEventListener('click', function() {
            let value = this.getAttribute('data-value');
            document.querySelectorAll('.star').forEach(s => s.classList.remove('text-yellow-500'));
            for (let i = 1; i <= value; i++) {
                document.querySelector(`.star[data-value="${i}"]`).classList.add('text-yellow-500');
            }
            document.querySelector(`input[name="rating"][value="${value}"]`).checked = true;
        });
    });
</script>

<!-- <script>
   document.addEventListener('DOMContentLoaded', function () {
    const buyButton = document.getElementById('buy-{{ $food->id }}');
    const quantityControls = document.getElementById('quantity-controls-{{ $food->id }}');
    const quantityInput = document.getElementById('quantity-{{ $food->id }}');
    const cartForm = document.getElementById('cart-update-form-{{ $food->id }}');

    // Flag to prevent switching back
    let isItemAddedToCart = false;

    buyButton.addEventListener('click', function (event) {
        // Prevent the default form submit action (page reload)
        event.preventDefault();

        if (!isItemAddedToCart) {
            buyButton.classList.add('hidden');    // Hide the "Add to basket" button
            quantityControls.classList.remove('hidden');    // Show the quantity controls
            quantityInput.focus();    // Focus the input

            // Automatically submit the form when first adding the item
            submitForm();

            // Set the flag to true to prevent the button from showing again
            isItemAddedToCart = true;
        }
    });

    window.decreaseQuantity = function (foodId) {
        const input = document.getElementById('quantity-' + foodId);
        let current = parseInt(input.value) || 1;

        if (current > 1) {
            input.value = current - 1;
            submitForm();
        } else if (current === 1) {
            // Hide quantity controls and show "Add to basket"
            const buyButton = document.getElementById('buy-' + foodId);
            const quantityControls = document.getElementById('quantity-controls-' + foodId);

            input.value = 0; // optionally show 0
            submitForm();

            quantityControls.classList.add('hidden');
            buyButton.classList.remove('hidden');

            // Reset flag if you're using one to prevent toggling back
            isItemAddedToCart = false;
        }
    }

    window.increaseQuantity = function (foodId) {
        const input = document.getElementById('quantity-' + foodId);
        let current = parseInt(input.value) || 1;
        input.value = current + 1;
        submitForm(true);
    };


    function submitForm(isUpdate = false) {
    const foodId = cartForm.dataset.foodId;
    const quantity = parseInt(quantityInput.value);

    const url = isUpdate
        ? `/order/update/${foodId}`  // Your update route
        : cartForm.action;           // Original add route

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            food_item_id: foodId,
            quantity: quantity
        })
    })
    .then(response => response.json())
    .then(data => {
        console.log('Cart updated:', data);

        // Only update visuals if this was an initial add
        if (!isUpdate) {
            buyButton.classList.add('hidden');
            quantityControls.classList.remove('hidden');
            isItemAddedToCart = true;
        }
    })
    .catch(error => console.error('Error updating cart:', error));
}

});

</script> -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const buyButton = document.getElementById('buy-{{ $food->id }}');
    const quantityControls = document.getElementById('quantity-controls-{{ $food->id }}');
    const quantityInput = document.getElementById('quantity-{{ $food->id }}');
    const cartForm = document.getElementById('cart-update-form-{{ $food->id }}');

    let isItemAddedToCart = false;

    buyButton.addEventListener('click', function (event) {
        event.preventDefault();

        if (!isItemAddedToCart) {
            buyButton.classList.add('hidden');
            quantityControls.classList.remove('hidden');
            quantityInput.focus();

            submitForm();

            isItemAddedToCart = true;
        }
    });

    window.decreaseQuantity = function (foodId) {
        const input = document.getElementById('quantity-' + foodId);
        let current = parseInt(input.value) || 1;

        if (current > 1) {
            input.value = current - 1;
            submitForm(true);
        } else if (current === 1) {
            const buyButton = document.getElementById('buy-' + foodId);
            const quantityControls = document.getElementById('quantity-controls-' + foodId);

            input.value = 0;
            submitForm(true);

            quantityControls.classList.add('hidden');
            buyButton.classList.remove('hidden');

            isItemAddedToCart = false;
        }
    }

    window.increaseQuantity = function (foodId) {
        const input = document.getElementById('quantity-' + foodId);
        let current = parseInt(input.value) || 1;
        input.value = current + 1;
        submitForm(true);
    };

    function submitForm(isUpdate = false) {
        const foodId = cartForm.dataset.foodId;
        const quantity = parseInt(quantityInput.value);

        fetch('/order/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                food_item_id: foodId,
                quantity: quantity
            })
        })
        .then(response => response.json())
        .then(data => {
            console.log('Cart updated in session:', data);

            if (!isUpdate) {
                buyButton.classList.add('hidden');
                quantityControls.classList.remove('hidden');
                isItemAddedToCart = true;
            }
        })
        .catch(error => console.error('Error updating cart:', error));
    }
});
</script>

@endsection