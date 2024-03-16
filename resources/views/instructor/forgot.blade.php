@extends('layouts.instructor_login')

@section('content')
    <div class="w-full h-screen text-darthmouthgreen" id="securityForm">
        <div class="grid w-full h-full max-w-screen-xl px-4 mx-auto place-items-center ">
            <div class="flex flex-col items-center justify-center w-full px-4 space-y-8 rounded-lg md:h-3/4 md:shadow-lg md:w-3/4">
                <h1 class="text-4xl font-bold text-darthmouthgreen lg:text-6xl">Forgot Password</h1>
                <p class="text-xs text-darthmouthgreen md:text-base">Hello! If you've forgotten your password, don't worry. We'll help you reset it. Please enter your email address associated with your instructor account below, and we'll send you a link to reset your password.</p>
            
                
                <form class="" action="{{ url('/instructor/reset') }}" method="POST"> 
                        @csrf
                    <div class="flex flex-col items-center justify-center w-full">
                        <label for="email" class="sr-only">Email</label>
                        <input type="email" id="email" name="email" class="w-full p-2 border-b-2 border-darthmouthgreen focus:outline-none focus:border-darthmouthgreen" placeholder="Enter your email" required>
                        
                        <button class="w-full px-5 py-3 mt-4 text-white bg-darthmouthgreen rounded-xl hover:bg-white hover:text-darthmouthgreen hover:ring-2 hover:ring-darthmouthgreen" type="submit">Reset Password</button>
                    </div>
                </form>
                

            </div>
        </div>
    </div>
@endsection

