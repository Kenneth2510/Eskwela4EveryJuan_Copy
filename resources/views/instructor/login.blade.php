@extends('layouts.instructor_login')

@section('content')
    {{-- MAIN --}}
    <div class="relative w-full h-screen text-black lg:overflow-auto md:flex md:justify-center md:items-center md:bg-mainwhitebg lg:w-1/2 lg:text-mainwhitebg lg:pt-24" id="loginForm">
        <div class="max-w-screen-xl pt-16 mx-auto rounded-lg md:shadow-xl md:w-3/4 md:mx-auto md:bg-mainwhitebg lg:bg-opacity-0 lg:shadow-transparent lg:pt-10">
            {{-- <x-header title="Instructor Login"> --}}
                {{-- <p class="text-sm md:text-base">Lorem ipsum dolor sit amet consectetur, adipisicing elit. Aperiam quidem nobis quasi porro odio! Iusto, aliquam.</p> --}}
            {{-- </x-header> --}}

            <h1 class="text-4xl font-bold text-center text-darthmouthgreen lg:text-left lg:text-6xl">Instructor Login</h1>
            <p class="mt-3 text-darthmouthgreen">Welcome, instructor! Please enter your credentials to access the teaching platform.</p>

            <form class="flex flex-col justify-center text-black rounded-lg md:mt-4 h-96 md:w-3/4 md:mx-auto lg:w-full" action="{{ url('/instructor/login') }}" method="POST">
                @csrf
                <div class="pb-4 mx-4 border-b-4">

                    <div class="flex flex-col my-4 lg:flex-row lg:justify-between lg:items-center">
                        <label class="font-medium text-darthmouthgreen lg:w-1/2" for="instructor_username">Username:</label>
                        {{-- <div class="relative lg:w-1/2">
                            <svg class="absolute w-8 h-8 mx-1 border-r-2 md:w-10 md:h-10 lg:my-1" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M160-160q-33 0-56.5-23.5T80-240v-480q0-33 23.5-56.5T160-800h640q33 0 56.5 23.5T880-720v480q0 33-23.5 56.5T800-160H160Zm320-280L160-640v400h640v-400L480-440Zm0-80 320-200H160l320 200ZM160-640v-80 480-400Z"/></svg>

                            <input class="w-full h-8 pl-10 rounded text-darthmouthgreen md:h-10 lg:h-12 md:pl-12 ring-seagreen ring-2" 
                                type="text" 
                                name="instructor_username" 
                                id="instructor_username" 
                                value="{{ old('instructor_username') }}" 
                                required 
                                style="border-color: #00693e;"
                                />
                        </div> --}}

                        <label class="flex items-center w-full gap-2 border-2 input input-bordered border-darthmouthgreen">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4 opacity-70"><path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM12.735 14c.618 0 1.093-.561.872-1.139a6.002 6.002 0 0 0-11.215 0c-.22.578.254 1.139.872 1.139h9.47Z" /></svg>
                            <input class="grow"
                            type="text"
                            name="instructor_username"
                            id="instructor_name"
                            value="{{ old('instructor_username') }}"
                            required
                            placeholder="Username" />
                        </label>
                        
                    </div>
                    
                    <div class="flex flex-col my-4 lg:flex-row lg:justify-between lg:items-center">
                        <label class="font-medium text-darthmouthgreen lg:w-1/2" for="instructor_password">Password:</label>
                        {{-- <div class="relative lg:w-1/2">
                            <svg class="absolute w-8 h-8 mx-1 border-r-2 md:w-10 md:h-10 lg:my-1" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M240-80q-33 0-56.5-23.5T160-160v-400q0-33 23.5-56.5T240-640h40v-80q0-83 58.5-141.5T480-920q83 0 141.5 58.5T680-720v80h40q33 0 56.5 23.5T800-560v400q0 33-23.5 56.5T720-80H240Zm0-80h480v-400H240v400Zm240-120q33 0 56.5-23.5T560-360q0-33-23.5-56.5T480-440q-33 0-56.5 23.5T400-360q0 33 23.5 56.5T480-280ZM360-640h240v-80q0-50-35-85t-85-35q-50 0-85 35t-35 85v80ZM240-160v-400 400Z"/></svg>
                            <svg class="absolute right-0 w-6 h-6 mx-1 top-1 lg:w-8 lg:h-8" id="showPwd" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M480-320q75 0 127.5-52.5T660-500q0-75-52.5-127.5T480-680q-75 0-127.5 52.5T300-500q0 75 52.5 127.5T480-320Zm0-72q-45 0-76.5-31.5T372-500q0-45 31.5-76.5T480-608q45 0 76.5 31.5T588-500q0 45-31.5 76.5T480-392Zm0 192q-146 0-266-81.5T40-500q54-137 174-218.5T480-800q146 0 266 81.5T920-500q-54 137-174 218.5T480-200Zm0-300Zm0 220q113 0 207.5-59.5T832-500q-50-101-144.5-160.5T480-720q-113 0-207.5 59.5T128-500q50 101 144.5 160.5T480-280Z"/></svg>
                            <svg class="absolute right-0 hidden w-6 h-6 mx-1 top-1 lg:my-1" id="hidePwd" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="m644-428-58-58q9-47-27-88t-93-32l-58-58q17-8 34.5-12t37.5-4q75 0 127.5 52.5T660-500q0 20-4 37.5T644-428Zm128 126-58-56q38-29 67.5-63.5T832-500q-50-101-143.5-160.5T480-720q-29 0-57 4t-55 12l-62-62q41-17 84-25.5t90-8.5q151 0 269 83.5T920-500q-23 59-60.5 109.5T772-302Zm20 246L624-222q-35 11-70.5 16.5T480-200q-151 0-269-83.5T40-500q21-53 53-98.5t73-81.5L56-792l56-56 736 736-56 56ZM222-624q-29 26-53 57t-41 67q50 101 143.5 160.5T480-280q20 0 39-2.5t39-5.5l-36-38q-11 3-21 4.5t-21 1.5q-75 0-127.5-52.5T300-500q0-11 1.5-21t4.5-21l-84-82Zm319 93Zm-151 75Z"/></svg>
                            
                            <input required class="w-full h-8 pl-10 rounded text-darthmouthgreen md:h-10 lg:h-12 md:pl-12 ring-seagreen ring-2" type="password" name="password" id="password">
                        </div> --}}

                        <label class="relative flex items-center w-full gap-2 border-2 input input-bordered border-darthmouthgreen">
                            <svg class="absolute hidden h-4 right-3" id="hidePwd" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M480-320q75 0 127.5-52.5T660-500q0-75-52.5-127.5T480-680q-75 0-127.5 52.5T300-500q0 75 52.5 127.5T480-320Zm0-72q-45 0-76.5-31.5T372-500q0-45 31.5-76.5T480-608q45 0 76.5 31.5T588-500q0 45-31.5 76.5T480-392Zm0 192q-146 0-266-81.5T40-500q54-137 174-218.5T480-800q146 0 266 81.5T920-500q-54 137-174 218.5T480-200Zm0-300Zm0 220q113 0 207.5-59.5T832-500q-50-101-144.5-160.5T480-720q-113 0-207.5 59.5T128-500q50 101 144.5 160.5T480-280Z"/></svg>
                            <svg class="absolute h-4 right-3" id="showPwd" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="m644-428-58-58q9-47-27-88t-93-32l-58-58q17-8 34.5-12t37.5-4q75 0 127.5 52.5T660-500q0 20-4 37.5T644-428Zm128 126-58-56q38-29 67.5-63.5T832-500q-50-101-143.5-160.5T480-720q-29 0-57 4t-55 12l-62-62q41-17 84-25.5t90-8.5q151 0 269 83.5T920-500q-23 59-60.5 109.5T772-302Zm20 246L624-222q-35 11-70.5 16.5T480-200q-151 0-269-83.5T40-500q21-53 53-98.5t73-81.5L56-792l56-56 736 736-56 56ZM222-624q-29 26-53 57t-41 67q50 101 143.5 160.5T480-280q20 0 39-2.5t39-5.5l-36-38q-11 3-21 4.5t-21 1.5q-75 0-127.5-52.5T300-500q0-11 1.5-21t4.5-21l-84-82Zm319 93Zm-151 75Z"/></svg>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="green" class="w-4 h-4 opacity-70"><path fill-rule="evenodd" d="M14 6a4 4 0 0 1-4.899 3.899l-1.955 1.955a.5.5 0 0 1-.353.146H5v1.5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5v-2.293a.5.5 0 0 1 .146-.353l3.955-3.955A4 4 0 1 1 14 6Zm-4-2a.75.75 0 0 0 0 1.5.5.5 0 0 1 .5.5.75.75 0 0 0 1.5 0 2 2 0 0 0-2-2Z" clip-rule="evenodd" /></svg>
                            <input class="grow"
                            required
                            type="password"
                            name="password"
                            id="password"/>
                        </label>
                    </div>
                    <div class="flex justify-end w-full">
                        <span class="font-bold text-right text-darthmouthgreen md:text-darthmouthgreen">
                            <a href="{{url('/instructor/forgot')}}">
                                Forgot Password?
                            </a>
                        </span>
                    </div>


                    @if ($errors->has('instructor_username') && $errors->has('password'))
                    <p class="p-1 mt-2 text-lg font-semibold text-red-500">
                        Both username and password are required.
                    </p>
                    @else
                        @error('instructor_username_login')
                        <p class="p-1 mt-2 text-lg font-semibold text-red-500">
                            {{$message}}
                        </p>
                        @enderror
                    @endif
                    
                    <div class="flex items-center justify-end px-4 my-4">
                        {{-- <div class="flex items-center">
                            <input class="w-4 h-4 mx-1 accent-darthmouthgreen" type="checkbox" name="remember" id="remember">
                            <label class="text-darthmouthgreen" for="remember">Remember me</label>
                        </div> --}}
    
                        <button type="submit" name="Login" class="btn btn-primary">Login</button>
                    </div>
                </div>
            </form>
            <div class="w-full text-center">
                {{--<p class="text-black lg:text-mainwhitebg">Don't have an account yet?
                    <span class="font-semibold text-darthmouthgreen lg:text-white">
                        <a href="{{url('/instructor/register1')}}">--}}
                <p class="text-darthmouthgreen md:text-darthmouthgreen">Don't have an account yet?
                    <span class="font-bold text-darthmouthgreen md:text-darthmouthgreen">

                        <a href="{{url('/instructor/register')}}">
                            Sign up
                        </a>
                    </span>
                </p>
            </div>
        </div>    
    </div>
    
    {{-- MAIN LEFT --}}
    <div class="relative hidden h-screen bg-seagreen md:w-1/2 lg:block">
        {{-- IMAGE HOLDER --}}
        <div class="relative w-full h-full overflow-hidden rounded-lg">
            {{-- img-1 --}}
            <div class="hidden slides" id="slide1">
                <img src="{{asset('/images/ins-login-img1.png')}}" class="absolute block -translate-x-1/2 -translate-y-1/2 top-1/3 left-1/2" alt="image-1">
                <div class="absolute block text-center -translate-x-1/2 top-3/4 left-1/2">
                    <h1 class="text-2xl font-bold text-white">Maintain your Business</h1>
                    <p class="text-base text-white">Lorem ipsum dolor sit amet consectetur. Tellus ultrices in nibh malesuada sit justo fermentum. Elit id in pulvinar eget amet.</p>
                </div>
                
            </div>
            {{-- img-2 --}}
            <div class="hidden slides" id="slide2">
                <img src="{{asset('/images/ins-login-img2.png')}}" class="absolute block -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2" alt="image-2">
                <div class="absolute block text-center -translate-x-1/2 top-3/4 left-1/2">
                    <h1 class="text-2xl font-bold text-white">Maintain your Business</h1>
                    <p class="text-base text-white">Lorem ipsum dolor sit amet consectetur. Tellus ultrices in nibh malesuada sit justo fermentum. Elit id in pulvinar eget amet.</p>
                </div>
            </div>
            {{-- img-3 --}}
            <div class="hidden slides" id="slide3">
                <img src="{{asset('/images/ins-login-img3.png')}}" class="absolute block -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2" alt="image-3">
                <div class="absolute block text-center -translate-x-1/2 top-3/4 left-1/2">
                    <h1 class="text-2xl font-bold text-white">Maintain your Business</h1>
                    <p class="text-base text-white">Lorem ipsum dolor sit amet consectetur. Tellus ultrices in nibh malesuada sit justo fermentum. Elit id in pulvinar eget amet.</p>
                </div>
            </div>
        </div>

        {{-- BOTTOM BUTTONS --}}
        <div class="absolute z-30 flex space-x-3 -translate-x-1/2 bottom-5 left-1/2" id="carouselBtn">
            <button type="button" class="w-2 h-2 rounded-full bg-slate-200" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" class="w-2 h-2 rounded-full bg-slate-200" aria-current="true" aria-label="Slide 2"></button>
            <button type="button" class="w-2 h-2 rounded-full bg-slate-200" aria-current="true" aria-label="Slide 3"></button>
        </div>
        
        <button type="button" class="absolute top-0 left-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none" id="l-prevBtn">
            <span class="inline-flex items-center justify-center w-10 h-10 bg-white rounded-full dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
                <svg class="w-4 h-4 text-white dark:text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 1 1 5l4 4"/>
                </svg>
                <span class="sr-only">Previous</span>
            </span>
        </button>

        <button type="button" class="absolute top-0 right-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none" id="l-nextBtn">
            <span class="inline-flex items-center justify-center w-10 h-10 bg-white rounded-full dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
                <svg class="w-4 h-4 text-white dark:text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                </svg>
                <span class="sr-only">Next</span>
            </span>
        </button>
    </div>
        
@endsection
