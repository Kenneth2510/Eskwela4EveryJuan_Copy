<section class="relative hidden w-full lg:w-3/12 lg:block" id="profile">
            <div class="fixed top-0 right-0 z-50 w-full bg-white bg-opacity-50 lg:z-0 lg:relative" >
                <button class="absolute z-50 top-4 right-4" id="prof-btn">
                    <img src="{{url('/assets/close-icon.svg')}}" alt="">
                </button>

                <div class="float-right w-1/2 h-screen p-4 pt-24 bg-white md:w-2/5 lg:w-full">
                    <div class="">
                        <div class="flex flex-row items-center justify-between px-4">
                            <h1 class="text-2xl font-semibold">Profile</h1>
                            <button class="hidden w-8 h-8 rounded bg-seagreen lg:block">
                                <svg class="mx-auto fill-white" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M80 0v-160h800V0H80Zm80-240v-150l362-362 150 150-362 362H160Zm80-80h36l284-282-38-38-282 284v36Zm477-326L567-796l72-72q11-12 28-11.5t28 11.5l94 94q11 11 11 27.5T789-718l-72 72ZM240-320Z"/></svg>
                            </button>
                        </div>
                        

                        <div class="grid mb-10 place-items-center">
                            {{-- <img class="my-4 bg-green-500 rounded-full w-14 h-14 lg:w-20 lg:h-20" src="" alt=""> --}}
                            <img class="my-4 bg-green-500 rounded-full w-14 h-14 lg:w-20 lg:h-20" src="{{ asset('storage/' . $learner->profile_picture) }}
                                " alt="Profile Picture">
                            <h1 class="text-lg font-medium">{{ $learner->learner_fname }}  {{ $learner->learner_lname }}</h1>
                            <h3 class="text-sm opacity-50">Learner ID: {{ $learner->learner_id }}</h3>
                        </div>
                        
                        <div class="hidden lg:block">
                            @include('partials.calendar')
                        </div>


                        <div class="flex flex-col items-center justify-center my-8">
                            <a class="lg:hidden" href="">Edit Profile</a>
                            <a class="" href="">view activity logs</a>
                        </div>

                        <div class="mt-4 text-center text-mainwhitebg md:hidden">
                            <form  action="">
                                <button class="w-32 h-10 font-medium underline rounded-lg shadow-lg underline-offset-2 bg-darthmouthgreen">Logout</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>