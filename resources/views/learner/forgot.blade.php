@include('partials.header')

<section class="items-center justify-center w-screen h-screen bg-mainwhitebg md:bg-mainwhitebg md:flex">
    <div class="w-2/5 p-2 py-8 mt-16 rounded-lg shadow-lg md:bg-mainwhitebg text-darthmouthgreen" id="securityForm">
        
        <div class="w-full h-full m-auto">
            <div class="flex flex-col items-center justify-center h-full p-20">
                <h1 class="text-6xl font-bold text-darthmouthgreen">Forgot Password</h1>
                <p class="mt-5 text-xs text-darthmouthgreen md:text-base">Hello! If you've forgotten your password, don't worry. We'll help you reset it. Please enter your email address associated with your instructor account below, and we'll send you a link to reset your password.</p>
                
                <div class="w-full mt-16">
                    <form class="" action="{{ url('/learner/reset') }}" method="POST"> 
                        @csrf
                        <label for="email" class="sr-only">Email</label>
                        <input type="email" id="email" name="email" class="w-full p-3 border-b-2 border-darthmouthgreen focus:outline-none focus:border-darthmouthgreen" placeholder="Enter your email" required>
                        
                        <button class="px-5 py-3 mt-4 text-white bg-darthmouthgreen rounded-xl hover:bg-white hover:text-darthmouthgreen hover:border-2 hover:border-darthmouthgreen" type="submit">Reset Password</button>
                    </form>
                </div>

            </div>
        </div>

    </div>
</section>


@include('partials.footer')
