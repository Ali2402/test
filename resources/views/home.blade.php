@extends('layouts.app-auth')

@section('content')
<div class="grid grid-cols-2 lg:grid-cols-4 gap-5">
    <div class="col-span-2 lg:col-span-4">
      <p class="text-4xl text-gray-700 font-bold">Dashboard</p>
      @if(session('msg'))
<div class="col-span-1 md:col-span-2 relative py-3 pl-4 pr-10 leading-normal @if(preg_match('/Oops/', session('msg'))) bg-red-100 text-red-700 @else bg-green-100 text-green-700 @endif rounded-lg" role="alert">
    <p>{{ session()->get('msg') }}</p>
</div>
@endif
    </div>

    <div class="flex flex-row bg-white rounded-lg shadow-md p-10 gap-x-5 items-center">
      <svg class="w-10 h-10 p-2 bg-gray-200 stroke-current text-blue-500 rounded-full" fill="none" stroke="currentColor" view-box="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
      <div>
        <p class="font-semibold text-xl">{{ $data['users'] }}</p>
        <p class="text-xs text-gray-600">Total Users</p>
      </div>
    </div>
    <div class="flex flex-row bg-white rounded-lg shadow-md p-10 gap-x-5 items-center">
      <svg class="w-10 h-10 p-2 bg-gray-200 stroke-current text-red-500 rounded-full" fill="none" stroke="currentColor" view-box="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
      <div>
        <p class="font-semibold text-xl">{{ $data['products'] }}</p>
        <p class="text-xs text-gray-600">Total Products</p>
      </div>
    </div>
    <div class="flex flex-row bg-white rounded-lg shadow-md p-10 gap-x-5 items-center">
      <svg class="w-10 h-10 p-2 bg-gray-200 stroke-current text-green-500 rounded-full w-6 h-6" fill="none" stroke="currentColor" view-box="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
      <div>
        <p class="font-semibold text-xl">{{ $data['purchases'] }}</p>
        <p class="text-xs text-gray-600">Total Purchases</p>
      </div>
    </div>
    <div class="flex flex-row bg-white rounded-lg shadow-md p-10 gap-x-5 items-center">
      <svg class="w-10 h-10 p-2 bg-gray-200 stroke-current text-purple-500 rounded-full" fill="none" stroke="currentColor" view-box="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
      <div>
        <p class="font-semibold text-xl">$ {{ $data['balance'] }}</p>
        <p class="text-xs text-gray-600">Total Balance</p>
      </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 w-full mt-14 gap-x-5">
  <div class="bg-white rounded-lg shadow-md p-10">
    <p class="text-xl font-bold">User Activity</p>
    <table class="w-full table-fixed">
        <thead>
          <tr class="border-b">
            <th class="w-1/4 text-left">Date</th>
            <th class="w-1/4 text-left">Description</th>
           
          </tr>
        </thead>
        <tbody>
            @foreach($data['user_activity'] as $key => $value)
              <tr @if($key/2 != 0) class="bg-emerald-200" @endif>
                  <td class="text-sm">{{ $value->created_at }}</td>
                  <td class="text-sm overflow-ellipsis overflow-hidden">{{ $value->desc }} - <span class="text-red-500">{{ $value->ip_address }}</span> - <span class="text-yellow-700">{{ $value->user_agent }}</span> - <span class="text-green-700">{{ $value->user_agent }} </span> </td>
                  {{-- <td>{{ $value->ip_address }}</td>
                  <td class="overflow-ellipsis overflow-hidden whitespace-nowrap">{{ $value->user_agent }}</td> --}}
              </tr>
            @endforeach
        </tbody>
      </table>
  </div>
  <div class="bg-white rounded-lg shadow-md p-10">
    <p class="text-xl font-bold">Bugs Report</p>
    <table class="w-full table-fixed">
      <thead>
        <tr class="border-b">
          <th class="w-1/4 text-left">Date</th>
          <th class="w-1/2 text-left">Title</th>
          <th class="w-1/4 text-left">Status</th>
          <th class="w-1/4 text-left">Author</th>
        </tr>
      </thead>
      <tbody>
          @foreach($data['bugs'] as $key => $item)
            <tr @if($key/2 != 0) class="bg-emerald-200" @endif>
                <td class="text-sm">{{ $item->created_at }}</td>
                <td class="overflow-ellipsis overflow-hidden whitespace-nowrap">{{ $item->title }}</td>
                <td>@if($item->status == 1) <b> Pending </b> @elseif($item->status == 2) <font color=orange>Proccess</font> @else <font color=green> Fixed </font> @endif</td>
                <td>{{ $item->author }}</td>
            </tr>
          @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection
