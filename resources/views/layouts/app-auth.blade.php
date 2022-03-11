<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>RYU-DASHBOARD</title>

    @yield('css')
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200;0,300;0,400;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Nunito', sans-serif !important;
        }
        body{
            background-color: #F9FAFB !important;
        }
        .loader-dots div {
            animation-timing-function: cubic-bezier(0, 1, 1, 0);
        }
        .loader-dots div:nth-child(1) {
            left: 8px;
            animation: loader-dots1 0.6s infinite;
        }
        .loader-dots div:nth-child(2) {
            left: 8px;
            animation: loader-dots2 0.6s infinite;
        }
        .loader-dots div:nth-child(3) {
            left: 32px;
            animation: loader-dots2 0.6s infinite;
        }
        .loader-dots div:nth-child(4) {
            left: 56px;
            animation: loader-dots3 0.6s infinite;
        }
        @keyframes loader-dots1 {
            0% {
                transform: scale(0);
            }
            100% {
                transform: scale(1);
            }
        }
        @keyframes loader-dots3 {
            0% {
                transform: scale(1);
            }
            100% {
                transform: scale(0);
            }
        }
        @keyframes loader-dots2 {
            0% {
                transform: translate(0, 0);
            }
            100% {
                transform: translate(24px, 0);
            }
        }
        .dialogku {
			display:    none;
			position:   fixed;
			z-index:    1000;
			top:        0;
			left:       0;
			height:     100%;
			width:      100%;
			/* background: rgba( 255, 255, 255, .8 ); */
		}
		/* body {
			overflow: hidden;
		}
		body .dialogku {
			display: block;
		} */
    </style>
</head>
<body>
    <div id="app" data-app>
        @yield('loader')
        <div class="leading-normal tracking-normal">
            <div class="flex flex-wrap">
                <div id="overlay-nav" class="absolute md:hidden inset-0 bg-black opacity-50 z-30" onclick="onClickButton()"></div>
                {{-- {/* start of navigation drawer */} --}}
                <div id="main-nav" class="w-1/2 md:w-1/3 lg:w-64 fixed mt-20 md:mt-0 md:top-0 md:left-0 h-screen bg-gray-50 z-30 overlay transform transition duration-500 translate-x-0 lg:block">
                    <div class="w-full h-20 flex px-4 items-center mb-8">
                        <p class="font-semibold text-3xl text-yellow-500 pl-4">RYUJIN</p>
                    </div>
                    <div class="flex flex-col mb-8 px-4 gap-y-2">
                        <a href="/home" class="w-full flex items-center text-yellow-500 h-10 pl-4 hover:bg-gray-200 rounded-lg cursor-pointer {{ Request::is('home') ? 'bg-gray-200' : '' }}">
                            <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                            </svg>
                            <span class="text-gray-700">Dashboard</span>
                        </a>
                        @if(session()->get('role') == 1)
                            <a href="/admin" class="w-full flex items-center text-yellow-500 h-10 pl-4 hover:bg-gray-200 rounded-lg cursor-pointer {{ Request::is('admin') ? 'bg-gray-200' : '' }}">
                                <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 8a6 6 0 01-7.743 5.743L10 14l-1 1-1 1H6v2H2v-4l4.257-4.257A6 6 0 1118 8zm-6-4a1 1 0 100 2 2 2 0 012 2 1 1 0 102 0 4 4 0 00-4-4z" clip-rule="evenodd"></path></svg>

                                <span class="text-gray-700">Administrator</span>
                            </a>
                        @endif
                            <a href="/balance" class="w-full flex items-center text-yellow-500 h-10 pl-4 hover:bg-gray-200 rounded-lg cursor-pointer {{ Request::is('balance') ? 'bg-gray-200' : '' }}">
                                <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path></svg>
                                <span class="text-gray-700">Balance</span>
                            </a>
                            <a href="/products" class="w-full flex items-center text-yellow-500 h-10 pl-4 hover:bg-gray-200 rounded-lg cursor-pointer {{ Request::is('products') ? 'bg-gray-200' : '' }}">
                                <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"></path></svg>
                                
                                <span class="text-gray-700">Products</span>
                            </a>
                            <a href="/manage" class="w-full flex items-center text-yellow-500 h-10 pl-4 hover:bg-gray-200 rounded-lg cursor-pointer {{ Request::is('manage') ? 'bg-gray-200' : '' }}">
                                <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M5 4a1 1 0 00-2 0v7.268a2 2 0 000 3.464V16a1 1 0 102 0v-1.268a2 2 0 000-3.464V4zM11 4a1 1 0 10-2 0v1.268a2 2 0 000 3.464V16a1 1 0 102 0V8.732a2 2 0 000-3.464V4zM16 3a1 1 0 011 1v7.268a2 2 0 010 3.464V16a1 1 0 11-2 0v-1.268a2 2 0 010-3.464V4a1 1 0 011-1z"></path></svg>

                                <span class="text-gray-700">Manage</span>
                            </a>
                        {{-- @else --}}
                            <a href="/issues" class="w-full flex items-center text-yellow-500 h-10 pl-4 hover:bg-gray-200 rounded-lg cursor-pointer {{ Request::is('issues') ? 'bg-gray-200' : '' }}">
                                <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>

                                <span class="text-gray-700">Issues</span>
                            </a>
                            <a href="/docs" class="w-full flex items-center text-yellow-500 h-10 pl-4 hover:bg-gray-200 rounded-lg cursor-pointer {{ Request::is('docs') ? 'bg-gray-200' : '' }}">
                                <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path></svg>
                                <span class="text-gray-700">Documentation</span>
                            </a>
                            <a href="/account" class="w-full flex items-center text-yellow-500 h-10 pl-4 hover:bg-gray-200 rounded-lg cursor-pointer {{ Request::is('account') ? 'bg-gray-200' : '' }}">
                                <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
                                <span class="text-gray-700">Account</span>
                            </a>
                        {{-- @endif --}}
                    </div>
                    <div class="mb-4 px-4">
                        <div class="w-full flex items-center text-yellow-500 h-10 pl-4 hover:bg-gray-200 rounded-lg cursor-pointer">
                            <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd"></path>
                            </svg>
                            <form id="logout-form" action="/logout" method="POST">
                                <button type="submit" class="text-gray-700 no-underline">
                                    Logout
                                </button>
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>
                {{-- {/* end of navigation drawer */} --}}

                {{-- {/* start of app bar */} --}}
                <div id="app-bar" class="w-full bg-gray-50 min-h-screen md:pl-64">
                    <div class="sticky top-0 z-40">
                        <div class="w-full h-20 px-6 bg-gray-50 flex items-center justify-between">
                            <div class="flex flex-row justify-between w-full">
                                <div class="inline-block flex items-center mr-4 cursor-pointer" onclick="onClickButton()">
                                    <svg class="w-6 h-6 stroke-current text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path strokeLinecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                                </div>
                                <div class="flex flex-row items-center justif-center text-yellow-500 gap-x-2">
                                    <svg class="w-5 h-5 stroke-current" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    {{-- <p class="font-semibold capitalize">{{ Auth::user()->name }}</p> --}}
                                    <p class="font-semibold capitalize">

                                        @php
                                            $username = DB::table('users')->where('id' , session()->get('user_id'))->first();

                                            echo $username->username;
                                        @endphp
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="p-6 bg-gray-50">
                        @yield('content')
                    </div>
                </div>

                {{-- {/* end of app bar */} --}}

            </div>
        </div>
        <div class="dialogku bg-gray-900 bg-opacity-50" id="modal_loading">
			<div class="absolute flex w-full h-screen">
				<div class="m-auto">
					<div class="bg-yellow-500 py-2 px-14 rounded-lg flex items-center flex-col shadow-lg">
						<div class="flex flex-row justify-center items-center loader-dots block relative w-full h-6 mt-2">
							<div class="absolute top-0 mt-1 w-4 h-4 rounded-full bg-white"></div>
							<div class="absolute top-0 mt-1 w-4 h-4 rounded-full bg-white"></div>
							<div class="absolute top-0 mt-1 w-4 h-4 rounded-full bg-white"></div>
							<div class="absolute top-0 mt-1 w-4 h-4 rounded-full bg-white"></div>
						</div>
						<div class="text-white font-light mt-2 text-center">
							Please, Wait ...
						</div>
					</div>
				</div>
			</div>
		</div>
        @yield('bottom-app')
    </div>
    @yield('js')
    <script>
        var navOpen = true;
        function onClickButton(){
            if(navOpen){
                navOpen = false;
                document.getElementById("overlay-nav").classList.remove("absolute");
                document.getElementById("overlay-nav").classList.remove("md:hidden");
                document.getElementById("main-nav").classList.remove("transition");
                document.getElementById("main-nav").classList.remove("duration-1000");
                document.getElementById("main-nav").classList.remove("translate-x-0");
                document.getElementById("main-nav").classList.remove("lg:block");
                document.getElementById("main-nav").classList.add("transition");
                document.getElementById("main-nav").classList.add("duration-500");
                document.getElementById("main-nav").classList.add("-translate-x-full");
            } else {
                navOpen = true;
                document.getElementById("overlay-nav").classList.add("absolute");
                document.getElementById("overlay-nav").classList.add("md:hidden");
                document.getElementById("main-nav").classList.remove("transition");
                document.getElementById("main-nav").classList.remove("duration-500");
                document.getElementById("main-nav").classList.remove("-translate-x-full");
                document.getElementById("main-nav").classList.add("transition");
                document.getElementById("main-nav").classList.add("duration-1000");
                document.getElementById("main-nav").classList.add("translate-x-0");
                document.getElementById("main-nav").classList.add("lg:block");
            }
            document.getElementById("app-bar").classList.toggle("md:pl-64");
        }
    </script>
</body>
</html>
