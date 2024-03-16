@include('partials.header')

<section class="flex flex-row w-full h-auto text-sm bg-mainwhitebg md:text-base lg:h-screen">
    <header class="fixed top-0 left-0 z-40 flex flex-row items-center w-full px-4 py-4 bg-seagreen">
        <a href="#">
            <span class="self-center text-lg font-semibold font-semibbold whitespace-nowrap md:text-2xl text-mainwhitebg">
                Eskwela4EveryJuan
            </span>
        </a>
    </header>  
    
    {{-- MAIN --}}
    <section class="w-full overflow-auto pt-14 md:bg-seagreen md:flex md:flex-col md:justify-center md:items-center lg:block lg:w-1/2 lg:text-mainwhitebg">
        <div class="md:shadow-lg md:w-3/4 md:mt-10 rounded-xl md:bg-mainwhitebg lg:w-full lg:bg-transparent lg:shadow-none" id="personinfo">
            <div class="px-4 pt-4 md:mx-auto md:w-3/4 md:pt-8 lg:w-full lg:pt-0" id="ins-head">
                <h1 class="my-2 text-3xl font-bold md:text-4xl">Create an Instructor account</h1>
                <p class="text-sm md:text-base">Lorem ipsum dolor sit amet consectetur, adipisicing elit. Aperiam quidem nobis quasi porro odio! Iusto, aliquam.</p>
            </div>

            <form class="pb-4 mx-4 mt-10 lg:px-8" action="{{ url('/learner/register') }}" method="POST">
                @csrf
                <div class="flex flex-col flex-wrap lg:flex-row">
                    <div class="lg:w-1/2 lg:pr-4 FORM-CTNR">
                        <label for="">Firstname:</label>
                        <input class="IN-V-INP" type="text" name="" id="" value="{{old('')}}">
                        @error('')
                        <p class="p-1 mt-2 text-xs text-red-500">
                            {{$message}}
                        </p>
                        @enderror
                    </div>
                    <div class="lg:w-1/2 lg:pr-4 FORM-CTNR">
                        <label for="">Lastname:</label>
                        <input class="IN-V-INP" type="text" name="" id="" value="{{old('')}}">
                        @error('')
                        <p class="p-1 mt-2 text-xs text-red-500">
                            {{$message}}
                        </p>
                        @enderror
                    </div>
                </div>

                <div class="flex flex-col flex-wrap lg:flex-row">
                    <div class="lg:w-1/2 lg:pr-4 FORM-CTNR">
                        <label for="">Birthday:</label>
                        <input class="IN-V-INP" type="date" name="" id="" value="{{old('')}}">
                        @error('')
                        <p class="p-1 mt-2 text-xs text-red-500">
                            {{$message}}
                        </p>
                        @enderror
                    </div>
                    
                    <div class=" lg:w-1/2 lg:pr-4 FORM-CTNR">
                        <label for="">Gender</label>
                        <select name="" id="gender" class="IN-V-INP">
                            <option value="" {{old('') == "" ? 'selected' : ''}} class=""></option>
                            <option value="Male" {{old('') == "Male" ? 'selected' : ''}} class="">Male</option>
                            <option value="Female" {{old('') == "Female" ? 'selected' : ''}} class="">Female</option>
                            <option value="Others" {{old('') == "Others" ? 'selected' : ''}} class="">Preferred not to say</option>
                        </select>
                        @error('')
                        <p class="p-1 mt-2 text-xs text-red-500">
                            {{$message}}
                        </p>
                        @enderror
                    </div>
                </div>
                
                <div class="lg:pr-4 FORM-CTNR">
                    <label for="">Email:</label>
                    <input class="IN-V-INP" type="email" name="" id="" value="{{old('')}}">
                    @error('')
                        <p class="p-1 mt-2 text-xs text-red-500">
                            {{$message}}
                        </p>
                        @enderror
                </div>
                <div class="lg:pr-4 FORM-CTNR">
                    <label for="instructor_contactno">Contact Number:</label>
                    {{-- <input class="IN-V-INP" type="text" name="instructor_contactno" id="instructor_contactno"> --}}
                    <input type="tel" id="" maxlength="11" pattern="[0-9]{11}" name="" class="IN-V-INP" placeholder="09" value="{{old('')}}">
                    @error('')
                        <p class="p-1 mt-2 text-xs text-red-500">
                            {{$message}}
                        </p>
                        @enderror
                </div>
                <div class="lg:pr-4 FORM-CTNR">
                    <label for="">Username:</label>
                    <input class="IN-V-INP" type="text" name="" id="" value="{{old('')}}">
                    @error('')
                        <p class="p-1 mt-2 text-xs text-red-500">
                            {{$message}}
                        </p>
                        @enderror
                </div>
                <div class="lg:pr-4 FORM-CTNR">
                    <label for="password">Password:</label>
                    <input class="IN-V-INP" type="password" name="password" id="">
                    @error('password')
                        <p class="p-1 mt-2 text-xs text-red-500">
                            {{$message}}
                        </p>
                        @enderror
                </div>
                <div class="lg:pr-4 FORM-CTNR">
                    <label for="password_confirmation">Confirm Password:</label>
                    <input class="IN-V-INP" type="password" name="password_confirmation" id="">
                </div>

                <div class="grid h-auto my-4 text-black place-items-end lg:pr-4" >
                    <button class="flex flex-row items-center justify-center w-24 h-10 rounded-lg bg-amber-400 hover:bg-amber-500 lg:h-12 lg:w-32" id="nextBtn">
                        <h1>Next</h1>
                        <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M647-440H160v-80h487L423-744l57-56 320 320-320 320-57-56 224-224Z"/></svg>
                    </button>
                </div>
            </form>
        </div>

        {{-- SECOND MAIN --}}
        <div class="hidden px-4 md:shadow-lg md:w-3/4 md:mt-10 rounded-xl md:bg-mainwhitebg lg:w-full lg:bg-transparent lg:shadow-none" id="learnerBusinessInfo">
            <button class="py-4" id="learnerBackBtn">
                <svg class="bg-gray-300 rounded-full" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="m313-440 224 224-57 56-320-320 320-320 57 56-224 224h487v80H313Z"/></svg>
            </button>
            <div class="pt-4 md:mx-auto md:w-3/4 md:pt-8 lg:w-full lg:pt-0" id="ins-head">
                <h1 class="my-2 text-3xl font-bold md:text-4xl">About your Business</h1>
                <p class="text-sm md:text-base">Lorem ipsum dolor sit amet consectetur, adipisicing elit. Aperiam quidem nobis quasi porro odio! Iusto, aliquam.</p>
            </div>
            
            <form class="pb-4 mx-auto mt-10 border-b-2 border-darthmouthgreen lg:w-3/4" action="">
                @csrf
                <div class="FORM-CTNR">
                    <label for="">Business Name</label>
                    <input class="IN-V-INP" type="text">
                </div>
                <div class="FORM-CTNR">
                    <label for="">Business Address</label>
                    <input class="IN-V-INP" type="text">
                </div>
                <div class="FORM-CTNR">
                    <label for="">Business Owner Name</label>
                    <input class="IN-V-INP" type="text">
                </div>
                <div class="FORM-CTNR">
                    <label for="">BPLO Account Number</label>
                    <input class="IN-V-INP" type="text">
                </div>

                <div class="my-4">
                    <div class="flex flex-row items-center">
                        <input class="mx-2" type="checkbox" name="" id="">
                        <p>I've read and Accept <span class="font-semibold text-darthmouthgreen">Terms & Conditions</span></p>
                    </div>
                    <div class="flex flex-row items-center">
                        <input class="mx-2" type="checkbox" name="" id="">
                        <p>Remember me</p>
                    </div>
                </div>
                
                <div class="grid h-auto my-4 text-black place-items-end" >
                    <button class="flex flex-row items-center justify-center w-24 h-10 rounded-lg bg-amber-400 hover:bg-amber-500 lg:h-12 lg:w-32" id="">
                        <h1>Confirm</h1>
                        <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M647-440H160v-80h487L423-744l57-56 320 320-320 320-57-56 224-224Z"/></svg>
                    </button>
                </div>
            </form> 
        </div>
        
        <div class="flex flex-row items-center justify-center py-4 text-center md:h-14 lg:h-auto" id="ins-foot ">
                <h1 class="text-black md:text-mainwhitebg">Already have an account?
                    <a class="font-semibold text-darthmouthgreen md:text-mainwhitebg" href="/instructor/login">Log in</a>
                </h1>
            </div>
    </section>

    <section class="relative hidden h-auto bg-ashgray md:w-1/2 lg:block">
        {{-- IMAGE HOLDER --}}
        <div class="relative w-full h-full overflow-hidden rounded-lg">
            {{-- img-1 --}}
            <div class="hidden slides" id="slide1">
                <img src="{{asset('/images/ins-login-img1.png')}}" class="absolute block -translate-x-1/2 -translate-y-1/2 top-1/3 left-1/2" alt="image-1">
                <div class="absolute block text-center -translate-x-1/2 top-3/4 left-1/2">
                    <h1 class="text-2xl font-bold">Maintain your Business</h1>
                    <p class="text-base">Lorem ipsum dolor sit amet consectetur. Tellus ultrices in nibh malesuada sit justo fermentum. Elit id in pulvinar eget amet.</p>
                </div>
                
            </div>
            {{-- img-2 --}}
            <div class="hidden slides" id="slide2">
                <img src="{{asset('/images/ins-login-img2.png')}}" class="absolute block -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2" alt="image-2">
                <div class="absolute block text-center -translate-x-1/2 top-3/4 left-1/2">
                    <h1 class="text-2xl font-bold">Maintain your Business</h1>
                    <p class="text-base">Lorem ipsum dolor sit amet consectetur. Tellus ultrices in nibh malesuada sit justo fermentum. Elit id in pulvinar eget amet.</p>
                </div>
            </div>
            {{-- img-3 --}}
            <div class="hidden slides" id="slide3">
                <img src="{{asset('/images/ins-login-img3.png')}}" class="absolute block -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2" alt="image-3">
                <div class="absolute block text-center -translate-x-1/2 top-3/4 left-1/2">
                    <h1 class="text-2xl font-bold">Maintain your Business</h1>
                    <p class="text-base">Lorem ipsum dolor sit amet consectetur. Tellus ultrices in nibh malesuada sit justo fermentum. Elit id in pulvinar eget amet.</p>
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
            <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
                <svg class="w-4 h-4 text-white dark:text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 1 1 5l4 4"/>
                </svg>
                <span class="sr-only">Previous</span>
            </span>
        </button>

        <button type="button" class="absolute top-0 right-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none" id="l-nextBtn">
            <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
                <svg class="w-4 h-4 text-white dark:text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                </svg>
                <span class="sr-only">Next</span>
            </span>
        </button>
    </section>
</section>
@include('partials.footer')