@extends('layouts.client')

@section('content')
    @foreach($homeContents as $content)
        <section class="h-[80vh] flex flex-col md:flex-row justify-center items-center">
            <div class="container mx-auto px-4 py-8 flex justify-center">
                <img src="{{ asset($content['logo']) }}" alt="logo">
            </div>
            <div class="container mx-auto px-4 py-8">
                <h1 class="text-3xl font-bold mb-4">{{ $content['title'] }}</h1>
                <p class="text-gray-700 mb-6">{{ $content['description'] }}</p>
            </div>
        </section>
    @endforeach
@endsection
