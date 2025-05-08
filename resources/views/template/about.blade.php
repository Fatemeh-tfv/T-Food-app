@extends('template.layouts.app')

@section('title', 'About Us')

@section('content')
<div class="container mx-auto py-12 px-4 md:px-8">
    <h2 class="text-3xl font-bold text-center mb-8">About Us</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div>
            <h3 class="text-xl font-semibold mb-4">Our Story</h3>
            <p class="text-gray-700 mb-4">We are a passionate team dedicated to bringing you the best food experience. Our journey started with a vision to create an online food ordering platform that not only offers convenience but also high-quality meals from trusted restaurants. We believe in making every meal an unforgettable experience.</p>
            <p class="text-gray-700 mb-4">Our team is committed to excellence, and we work closely with local restaurants to ensure that every dish meets the highest standards. Whether you're a foodie or just looking for a quick meal, we have something for everyone!</p>
        </div>

        <div>
            <h3 class="text-xl font-semibold mb-4">Our Mission</h3>
            <p class="text-gray-700 mb-4">Our mission is simple: to make food ordering easy, fast, and delicious. We aim to provide a seamless platform where customers can find great local restaurants, place orders easily, and enjoy a delightful dining experience from the comfort of their homes.</p>
            <p class="text-gray-700">We are constantly striving to improve and bring new features to enhance the user experience. Join us in our journey to change the way people order food online.</p>
        </div>
    </div>

    <div class="mt-12 text-center">
        <h3 class="text-xl font-semibold mb-4">Our Values</h3>
        <ul class="list-disc text-gray-700 space-y-2 mx-auto max-w-2xl">
            <li><strong>Quality:</strong> We ensure that every meal meets the highest standards of taste and quality.</li>
            <li><strong>Customer-Centric:</strong> Our users' satisfaction is our top priority. We always listen to feedback and strive for better.</li>
            <li><strong>Innovation:</strong> We embrace technology to create a seamless and enjoyable experience for both restaurants and customers.</li>
            <li><strong>Community:</strong> We are dedicated to supporting local restaurants and creating a strong food community.</li>
        </ul>
    </div>
</div>
@endsection
