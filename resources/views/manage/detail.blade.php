@extends('layouts.app-auth')

@section('content')
<div>
    <div class="flex flex-col justify-center items-center w-full gap-y-3">
        <div class="flex flex-row gap-x-2 items-center">
            <p class="text-5xl font-bold">{{ $data['name'] }}</p>
            <sup>v{{ $data['version'] }}</sup>
        </div>
        <p class="font-semibold text-lg">$ {{ $data['price'] }}</p>
        <div class="bg-white shadow rounded-lg w-full p-10">
            {{ $data['desc'] }}
            <p class="mt-2 font-semibold">Date: {{ $data['date'] }}</p>
        </div>
    </div>
</div>
@endsection