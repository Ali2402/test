@extends('layouts.app-auth')
@section('css')
<style>
    html,body{
        scroll-behavior: smooth;
    }
    #hashtag:before{
        content:'# ';
        color:#C4261F;
        cursor: pointer;
    }
</style>
@endsection

@section('content')
<h1 class="text-5xl text-gray-500 font-bold text-center m-8">Welcome to RyuJin App</h1>
@foreach($table_of_content as $p)
<a href="#{{ Str::snake($p) }}" ><h3 id="hashtag" class="ml-8 m-1 text-lg text-gray-600 font-bold">{{ $p }}</h3>
@endforeach
@php $n=0; @endphp
@foreach($content['link'] as $key=>$link)
<div id="{{ $link }}">
<div class="mt-10 bg-indigo-50 h-auto rounded-lg p-5">
    <h3 id="hashtag" class="text-lg text-gray-500 font-bold mb-3">{{ $table_of_content[$key] }}</h3>
    <div class="text-medium text-black ml-3"> {!! $content['desc'][$n++] !!}</div>
</div>
</div>

@endforeach
@endsection

