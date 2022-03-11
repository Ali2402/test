@extends('layouts.app')

@section('content')


<div class="flex flex-row items-center justify-center">
    <div class="w-3/4 my-5">
        <div class="grid grid-cols-1 md:grid-cols-2 bg-gray-50 border rounded shadow-md px-10 md:px-20 py-12 md:py-24 items-center justify-center">
            @if(session('msg') == 'db_error')
            <div class="col-span-1 md:col-span-2 relative py-3 pl-4 pr-10 leading-normal text-red-700 bg-red-100 rounded-lg" role="alert">
                <p>Oops ! database error.</p>
            </div>
        @elseif(session('msg') == 'invalid_invite')
            <div class="col-span-1 md:col-span-2 relative py-3 pl-4 pr-10 leading-normal text-red-700 bg-red-100 rounded-lg" role="alert">
                <p>Wrong invite code !</p>
            </div>
        @elseif(session('msg') == 'invalid_form')
        <div class="col-span-1 md:col-span-2 relative py-3 pl-4 pr-10 leading-normal text-red-700 bg-red-100 rounded-lg" role="alert">
            <p>Please fill the form.</p>
        </div>
        @endif
           
            <form method="POST" action="/auth/register">
                @csrf
                <div class="flex flex-col gap-y-5">
                    <p class="text-3xl font-semibold">Register</p>
                    <div>
                        <p>Username</p>
                        <input id="username" type="text" name="username" class="w-full rounded appearance-none border border-gray-200 rounded p-2 text-gray-900 label-floating @error('username') border-red-500 @enderror" required autocomplete="username">
                        @error('username')
                            <p class="text-md font-bold text-red-500" role="alert">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
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
                            <input id="password" type="password" name="password" class="w-full rounded appearance-none border border-gray-200 rounded p-2 text-gray-900 label-floating @error('password') border-red-500 @enderror" required autocomplete="new-password">
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
                    <div>
                        <p>Invite Code</p>
                        <div class="relative">
                            <input id="invite_code" required="required" type="text" name="invite_code" class="w-full rounded appearance-none border border-gray-200 rounded p-2 text-gray-900 label-floating">
                        </div>
                    </div>

                    <div>
                        <p>Referral code ( optional )</p>
                        <div class="relative">
                            <input id="reff" type="text" name="reff" class="w-full rounded appearance-none border border-gray-200 rounded p-2 text-gray-900 label-floating">
                        </div>
                    </div>
                    <div>
                        <button class="bg-yellow-500 rounded text-white px-7 py-2 font-semibold">Register</button>
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

    var eyeOpenConfirm = document.getElementById("eye-open-confirm")
    var eyeCloseConfirm = document.getElementById("eye-close-confirm")
    var passwordConfirm = document.getElementById("password_confirm")

    eyeOpenConfirm.onclick = function () {
        if(passwordConfirm.type == 'password') {
            passwordConfirm.setAttribute('type', 'text');
            eyeOpenConfirm.style.display = 'none'
            eyeCloseConfirm.style.display = ''
        } else {
            passwordConfirm.setAttribute('type', 'password');
            eyeOpenConfirm.style.display = ''
            eyeCloseConfirm.style.display = 'none'
        }
    }

    eyeCloseConfirm.onclick = function () {
        if(passwordConfirm.type == 'password') {
            passwordConfirm.setAttribute('type', 'text');
            eyeOpenConfirm.style.display = 'none'
            eyeCloseConfirm.style.display = ''
        } else {
            passwordConfirm.setAttribute('type', 'password');
            eyeOpenConfirm.style.display = ''
            eyeCloseConfirm.style.display = 'none'
        }
    }
</script>
@endsection