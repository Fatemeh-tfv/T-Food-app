@extends('template.layouts.app')

@section('title', 'Contact Us')

@section('content')
<div class="container mx-auto py-12 px-4 md:px-8">
    <h2 class="text-3xl font-bold text-center mb-8">Contact Us</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
        <div>
            <h3 class="text-xl font-semibold mb-4">Get in Touch</h3>
            <p class="mb-4">Have any questions, feedback, or just want to say hi? We'd love to hear from you!</p>
            <ul class="space-y-2 text-gray-700">
                <li><strong>Address:</strong> 123 Main Street, City, Country</li>
                <li><strong>Phone:</strong> +1 123 456 7890</li>
                <li><strong>Email:</strong> support@example.com</li>
                <li><strong>Hours:</strong> Mon - Sat: 9AM - 6PM</li>
            </ul>
        </div>

        <div>
            <form class="space-y-4 bg-white p-6 shadow-md rounded-md">
                <div>
                    <label class="block mb-1 font-medium">Your Name</label>
                    <input type="text" class="w-full border rounded p-2" placeholder="John Doe" required>
                </div>
                <div>
                    <label class="block mb-1 font-medium">Your Email</label>
                    <input type="email" class="w-full border rounded p-2" placeholder="you@example.com" required>
                </div>
                <div>
                    <label class="block mb-1 font-medium">Subject</label>
                    <input type="text" class="w-full border rounded p-2" placeholder="Subject" required>
                </div>
                <div>
                    <label class="block mb-1 font-medium">Message</label>
                    <textarea class="w-full border rounded p-2 h-32" placeholder="Write your message here..." required></textarea>
                </div>
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Send Message</button>
            </form>
        </div>
    </div>
</div>
@endsection