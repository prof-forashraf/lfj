@extends('layouts.app')
@section('title', 'About Us')
    @section('meta_description', 'Learn more about LatestFashionJewellery.com and our passion for jewellery.')

    @section('content')
    <div class="container mx-auto px-4 py-12">
        <h1 class="text-4xl font-playfair text-center text-pink-600 mb-8">About LatestFashionJewellery.com</h1>
        <div class="prose prose-lg max-w-3xl mx-auto">
            <p>Welcome! We are passionate about...</p>
            {{-- Add your about content here --}}
        </div>
    </div>
    @endsection