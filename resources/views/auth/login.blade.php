@extends('layouts.app')

@section('content')
<div class="flex flex-row items-center justify-center">
    <div class="w-3/4 my-5">
        <div class="grid grid-cols-1 md:grid-cols-2 bg-gray-50 border rounded shadow-md px-10 md:px-20 py-5 items-center justify-center">
            @if(session('msg') == 'maximum_device')
                <div class="col-span-1 md:col-span-2 relative py-3 pl-4 pr-10 leading-normal text-red-700 bg-red-100 rounded-lg" role="alert">
                    <p>Maximum device is 3 , logout in another device.</p>
                </div>
            @elseif(session('msg') == 'failed_password')
                <div class="col-span-1 md:col-span-2 relative py-3 pl-4 pr-10 leading-normal text-red-700 bg-red-100 rounded-lg" role="alert">
                    <p>Failed Password</p>
                </div>
            @elseif(session('msg') == 'success')
            <div class="col-span-1 md:col-span-2 relative py-3 pl-4 pr-10 leading-normal text-green-700 bg-green-100 rounded-lg" role="alert">
                <p>Successfully register, please do login here.</p>
            </div>
            @endif

            @if(date('dmY') != '20082021')
            <div class="col-span-1 md:col-span-2 mt-3 relative py-3 pl-4 pr-10 leading-normal text-blue-700 bg-blue-100 rounded-lg" role="alert">
                <p>For RyuJin Old Member! You can login using your <b><i>username as a new password.</i></b> and keep using old email</p>
                <p>After login you must change ur password.</p>
            </div>
            @endif
          
            <img src="/img/ryu-logo.png" class="w-96 h-96" alt="">
            <form method="POST" action="/auth/login">
                @csrf
                <div class="flex flex-col gap-y-5">
                    <p class="text-3xl font-semibold">Login</p>
                    <div>
                        <p>E-mail Address</p>
                        <input id="email" type="email" name="email" class="w-full rounded appearance-none border border-gray-200 rounded p-2 text-gray-900 label-floating @error('email') border-red-500 @enderror" required autocomplete="email">
                        @error('email')
                            <p class="text-md font-bold text-red-500" role="alert">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    <div>
                        <p>Password</p>
                        <div class="relative">
                            <input id="password" type="password" name="password" class="w-full rounded appearance-none border border-gray-200 rounded p-2 text-gray-900 label-floating @error('password') border-red-500 @enderror" required autocomplete="current-password">
                            <div class="absolute inset-y-0 right-0 flex items-center px-2 cursor-pointer">
                                <svg id="eye-open" class="w-5 h-5 stroke-current text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                <svg id="eye-close" class="w-5 h-5 stroke-current text-yellow-500" style="display: none" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                            </div>
                        </div>
                        @error('password')
                            <p class="text-md font-bold text-red-500" role="alert">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    {{-- <div class="relative text-gray-700">
                        <input id="password" type="password" name="password" class="w-full h-10 pl-3 pr-8 text-base placeholder-gray-600 border rounded-lg focus:shadow-outline" type="text" placeholder="Regular input"/>
                        <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                          <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M14.243 5.757a6 6 0 10-.986 9.284 1 1 0 111.087 1.678A8 8 0 1118 10a3 3 0 01-4.8 2.401A4 4 0 1114 10a1 1 0 102 0c0-1.537-.586-3.07-1.757-4.243zM12 10a2 2 0 10-4 0 2 2 0 004 0z" clip-rule="evenodd" fill-rule="evenodd"></path></svg>
                        </div>
                    </div> --}}
                    <div>
                        <button class="bg-yellow-500 rounded text-white px-7 py-2 font-semibold">Login</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    var eyeOpen = document.getElementById("eye-open")
    var eyeClose = document.getElementById("eye-close")
    var password = document.getElementById("password")

    eyeOpen.onclick = function () {
        if(password.type == 'password') {
            password.setAttribute('type', 'text');
            eyeOpen.style.display = 'none'
            eyeClose.style.display = ''
        } else {
            password.setAttribute('type', 'password');
            eyeOpen.style.display = ''
            eyeClose.style.display = 'none'
        }
    }

    eyeClose.onclick = function () {
        if(password.type == 'password') {
            password.setAttribute('type', 'text');
            eyeOpen.style.display = 'none'
            eyeClose.style.display = ''
        } else {
            password.setAttribute('type', 'password');
            eyeOpen.style.display = ''
            eyeClose.style.display = 'none'
        }
    }
</script>
@endsection