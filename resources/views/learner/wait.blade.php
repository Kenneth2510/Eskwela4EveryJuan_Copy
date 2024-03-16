@include('partials.header')

{{-- SECURITY CODE --}}
<section class="items-center justify-center w-full h-screen bg-mainwhitebg md:bg-mainwhitebg md:flex">
    <div class="w-full p-2 py-8 mt-16 rounded-lg md:bg-mainwhitebg text-darthmouthgreen md:w-3/4 md:shadow-lg lg:w-1/4 md:h-3/4 lg:h-96" id="securityForm">
        
        <div class="w-full h-full m-auto">
            <div class="flex flex-col items-center justify-center h-full text-center">
                <i class="fa-regular fa-hourglass-half text-[100px] mt-5 " style="color: #025c26;"></i>

                <h2 class="mt-10 text-2xl font-bold text-gray-800">Account Approval</h2>
                <p class="mb-4 text-gray-600">Your account is not yet approved. Please wait for the admin to approve your registration.</p>
                <!-- You can add additional information or links here if needed -->
                <div class="mt-4">
                    <form class="" action="{{ url('/learner/logout') }}" method="POST"> 
                        @csrf
                        <button class="px-5 py-3 text-white bg-darthmouthgreen rounded-xl hover:bg-white hover:text-darthmouthgreen hover:border-2 hover:border-darthmouthgreen" type="submit">Go Back</button>
                    </form>
                </div>

            </div>
        </div>

    </div>

</section>

@include('partials.footer')
