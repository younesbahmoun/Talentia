@extends('layouts.master')
@section('scripts')
    <script>
        console.log('Home page loaded');
    </script>
    <script>
        console.log('Additional script section');
    </script>
@endsection
@section('title', 'Home Page')
@section('nav')
    @include('partials.nav')
@endsection
@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Languages Section -->
        <section class="mb-8">
            <h2 class="text-2xl font-bold mb-4">Available Languages</h2>
            
            @if($languages && count($languages) > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full border-collapse border border-gray-300">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="border border-gray-300 px-4 py-2 text-left font-semibold">Language</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($languages as $language)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="border border-gray-300 px-4 py-2">{{ $language }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <p class="text-blue-800">No languages available.</p>
                </div>
            @endif
        </section>

        <!-- User Name Section -->
        <section class="mb-8">
            <h2 class="text-2xl font-bold mb-4">User Information</h2>
            
            @if(isset($name) && !empty($name))
                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <p class="text-green-800">
                        <span class="font-semibold">Name:</span> {{ $name }}
                    </p>
                </div>
            @else
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <p class="text-yellow-800">User name information is not available.</p>
                </div>
            @endif
        </section>
    </div>
@endsection