@extends('layouts.app-auth')

@section('content')
<div>
    <div class="flex flex-col justify-center items-center w-full gap-y-3">
        <div class="flex flex-row gap-x-2 items-center">
            <p class="text-5xl font-bold">{{ $data['title'] }}</p>
        </div>
        @if($data['status'] == 1)
            <p>Pending</p>
        @elseif($data['status'] == 2)
            <p>Proccess</p>
        @else 
            <p>Fixed</p>
        @endif
        <p class="font-semibold text-lg">Author {{ $data['author'] }}</p>
        <div class="bg-white shadow rounded-lg w-full p-10">
            {{ $data['content'] }}
            <p class="mt-10 font-semibold">Date: {{ $data['date'] }}</p>
        </div>
    </div>
</div>
@endsection