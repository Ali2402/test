@extends('layouts.app-auth')

@section('content')

@if(session('msg'))
    <div class="col-span-1 md:col-span-2 relative py-3 pl-4 pr-10 leading-normal mb-5 @if(preg_match('/Oops/', session('msg'))) bg-red-100 text-red-700 @else bg-green-100 text-green-700 @endif rounded-lg" role="alert">
        <p>{{ session()->get('msg') }}</p>
    </div>
@endif

<div>
    <table class="table-fixed w-full text-left">
        <tr class="border-b-2 border-t">
            <th class="w-1/2">Username</th>
            <th class="w-1/4">Email</th>
            <th class="w-1/4">Balance</th>
        </tr>
        <tr>
            <td>{{ $data['username'] }}</td>
            <td>{{ $data['email'] }}</td>
            <td>{{ $data['balance'] }}</td>
        </tr>
    </table>
    <p class="text-xl font-bold mt-3">Change Password</p>
    <form action="/account/update" method="POST" class="flex flex-col gap-y-5">
        @csrf
        <div>
            <p>Old Password</p>
            <div class="relative w-3/4">
                <input id="old_password" type="password" name="old_password" class="w-full rounded appearance-none border border-gray-200 rounded p-2 text-gray-900 label-floating" required autocomplete="old-password">
                <div class="absolute inset-y-0 right-0 flex items-center px-2 cursor-pointer">
                    <svg id="eye-open-old" class="w-5 h-5 stroke-current text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    <svg id="eye-close-old" class="w-5 h-5 stroke-current text-yellow-500" style="display: none" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                </div>
            </div>
        </div>
        <div>
            <p>New Password</p>
            <div class="relative w-3/4">
                <input id="new_password" type="password" name="new_password" class="w-full rounded appearance-none border border-gray-200 rounded p-2 text-gray-900 label-floating" required autocomplete="new-password">
                <div class="absolute inset-y-0 right-0 flex items-center px-2 cursor-pointer">
                    <svg id="eye-open" class="w-5 h-5 stroke-current text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    <svg id="eye-close" class="w-5 h-5 stroke-current text-yellow-500" style="display: none" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                </div>
            </div>
        </div>
        <button type="submit" class="px-5 py-2 w-max rounded-lg bg-green-500 text-white font-semibold">Submit</button>
    </form>
</div>
@endsection

@section('js')
<script>
    var eyeOpen = document.getElementById("eye-open")
    var eyeClose = document.getElementById("eye-close")
    var password = document.getElementById("new_password")

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

    var eyeOpenConfirm = document.getElementById("eye-open-old")
    var eyeCloseConfirm = document.getElementById("eye-close-old")
    var passwordConfirm = document.getElementById("old_password")

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