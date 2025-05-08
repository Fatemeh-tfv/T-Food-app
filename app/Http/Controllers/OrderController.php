<?php

namespace App\Http\Controllers;

use App\Models\DiscountCode;
use App\Models\FoodItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Restaurant;

class OrderController extends Controller
{
    public function cart()
    {
        // Clear any previous order-related redirects
        session()->forget('last_order_id');
        session()->forget('last_visited_checkout');
    
        $cart = session()->get('cart', []);
    
        if (empty($cart)) {
            return view('template.shopping', [
                'cartItems' => [],
                'total' => 0
            ])->with('message', 'Your cart is empty.');
        }
    
        $foodItemIds = array_keys($cart);
        $foodItems = FoodItem::whereIn('id', $foodItemIds)->get()->keyBy('id');
    
        $cartItems = [];
        $total = 0;
    
        foreach ($cart as $foodItemId => $item) {
            $foodItem = $foodItems[$foodItemId] ?? null;
    
            if ($foodItem) {
                $subtotal = $foodItem->price * $item['quantity'];
                $total += $subtotal;
    
                $cartItems[] = [
                    'id' => $foodItem->id,
                    'name' => $foodItem->name,
                    'price' => $foodItem->price,
                    'quantity' => $item['quantity'],
                    'subtotal' => $subtotal,
                    'image' => $foodItem->image, // if applicable
                ];
            }
        }
    
        return view('template.shopping', [
            'cartItems' => $cartItems,
            'total' => $total
        ]);
    }        

    public function checkout()
    {
        $order = $this->getUserPendingOrder();

        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart')->with('error', 'Your cart is empty.');
        }

        session(['last_visited_checkout' => true]);

        return view('template.checkout', compact('order','cart'));
    }

    public function applyDiscount(Request $request)
    {
        $request->validate([
            'discount_code'=>'required|string',
        ]);

        $order= $this->getUserPendingOrder();
        if(!$order || $order->orderItems->isEmpty()){
            return back()->with('error','No active order to apply discount.');
        }

        $code= DiscountCode::where('code',$request->discount_code)->where('active', true)->firstOrFail();

        if(!$code){
            return back()->with('error','Invalid or expired discount code.');
        }

        //Calculate discount
        $discountAmount=$code->type === 'precentage' ? ($order->total_price * ($code->amount/ 100))
        : $code->amount;

        $discountAmount= min($discountAmount, $order->total_price);

        $order->update([
            'discount'=> $discountAmount,
        ]);

        return back()->with('success','Discount code applied: -$'.number_format($discountAmount,2));
    }

    public function showOrderStatus($orderId)
    {
        // Fetch the order along with the related items and user
        $order = Order::with('orderItems.foodItem', 'user')->find($orderId);

        // If order is not found, show an error
        if (!$order) {
            return redirect()->route('home')->with('error', 'Order not found');
        }

        return view('template.status', compact('order'));
    }

    public function finalizeOrder(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|in:cash,card,paypal,other',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'notes' => 'nullable|string',
            'city' => 'string|max:100',
            'state' => 'string|max:100',
            'postal_code' => 'string|max:20',
            'recipient_name' => 'required|string|max:100',
        ]);
    
        // Get cart from session
        $cartItems = session('cart', []);
        if (empty($cartItems)) {
            return redirect()->route('cart')->with('error', 'Your cart is empty.');
        }

        $firstItem = reset($cartItems);

        if (!isset($firstItem['restaurant_id'])) {
            dd('Missing restaurant_id in cart item:', $firstItem);
        }
    
        $restaurant = Restaurant::find($firstItem['restaurant_id']);
        $shippingFee = $restaurant ? $restaurant->shipping_fee : 10.0;
    
        $subtotal = array_sum(array_column($cartItems, 'subtotal'));
        $totalWithShipping = $subtotal + $shippingFee;

        $order = Order::create([
            'restaurant_id' => $firstItem['restaurant_id'],
            'user_id' => auth()->id(),
            'payment_method' => $request->payment_method,
            'payment_status' => 'unpaid',
            'status' => 'confirmed',
            'address' => $request->address,
            'phone' => $request->phone,
            'notes' => $request->notes,
            'city' => $request->city,
            'state' => $request->state,
            'postal_code' => $request->postal_code,
            'recipient_name' => $request->recipient_name,
            'shipping_fee' => $shippingFee,
            'total_price' => $totalWithShipping,
        ]);
    
        foreach ($cartItems as $item) {
            $order->orderItems()->create([
                'food_item_id' => $item['food_item_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }
    
        session()->forget(['cart', 'last_visited_checkout']);
        session(['last_order_id' => $order->id]);
    
        return redirect()->route('order.status', ['orderId' => $order->id])
            ->with('success', 'Your order has been placed successfully!');
    }    

    public function addToOrder(Request $request)
    {
        $request->validate([
            'food_item_id' => 'required|exists:food_items,id',
            'quantity' => 'nullable|integer|min:1',
        ]);
    
        $foodItem = FoodItem::findOrFail($request->food_item_id);
        $quantity = $request->input('quantity', 1);
    
        // Retrieve current cart from session, or initialize as empty array
        $cart = session()->get('cart', []);
    
        if (isset($cart[$foodItem->id])) {
            $cart[$foodItem->id]['quantity'] += 1;
            $cart[$foodItem->id]['subtotal'] = $cart[$foodItem->id]['price'] * $cart[$foodItem->id]['quantity'];
            $cart[$foodItem->id]['restaurant_id'] = $foodItem->category->restaurant_id;
        } else {
            // Add new item to cart
           $cart[$foodItem->id] = [
            'food_item_id' => $foodItem->id,
            'name' => $foodItem->name,
            'price' => $foodItem->price,
            'quantity' => $quantity,
            'image' => $foodItem->image,
            'restaurant_id' => $foodItem->category->restaurant_id,
        ];
        }
    
        session()->put('cart', $cart);
    
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Item added to cart']);
        }
    
        return redirect()->back()->with('success', 'Item added to cart successfully!');
    }

    public function update(Request $request, $foodItemId)
    {
        $cart = session()->get('cart', []);

        if (!isset($cart[$foodItemId])) {
            return redirect()->back()->with('error', 'Item not found in cart.');
        }

        $action = $request->input('action');
        if ($action === 'increase') {
            $cart[$foodItemId]['quantity'] += 1;
        } elseif ($action === 'decrease') {
            if ($cart[$foodItemId]['quantity'] > 1) {
                $cart[$foodItemId]['quantity'] -= 1;
            } else {
                unset($cart[$foodItemId]); // Remove if quantity reaches zero
            }
        }

        session()->put('cart', $cart);
    }

    public function removeItem($foodItemId)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$foodItemId])) {
            unset($cart[$foodItemId]);
            session()->put('cart', $cart);

            return redirect()->back()->with('warning', 'Item removed from cart');
        }

        return redirect()->back()->with('error', 'Item not found in cart');
    }

    private function getUserPendingOrder($lock = false)
    {
        $query = Order::where('user_id', auth()->id())
            ->where('status', 'pending')
            ->with('orderItems.foodItem');

        return $lock ? $query->lockForUpdate()->first() : $query->first();
    }

    private function getOrderItem($orderId, $foodItemId)
    {
        return OrderItem::where('order_id', $orderId)
            ->where('food_item_id', $foodItemId)
            ->first();
    }

    private function updateOrderTotalPrice(Order $order)
    {
        $order->update(['total_price' => $order->orderItems()->sum('price')]);
    }

}
