@extends('layouts.landing_layout')

@section('content')
    <section class="relative w-full text-sm scroll-smooth">
        {{-- <nav class="fixed top-0 z-50 w-full border-b-2 bg-mainwhitebg start-0 text-seagreen border-darthmouthgreen">
            <div class="flex flex-wrap items-center justify-between max-w-screen-xl p-4 mx-auto">
                <a href="">
                    <h1 class="self-center text-xl font-semibold whitespace-nowrap">Eskwela4EveryJuan</h1>
                </a>
                <ul class="flex flex-row items-center justify-center font-medium divide-seagreen">
                    <li><a class="px-3 py-2 hover:font-semibold hover:text-base" href="#home">Home</a></li>
                    <li><a class="px-3 py-2 hover:font-semibold hover:text-base" href="#about">About</a></li>
                    <li><a class="px-3 py-2 hover:font-semibold hover:text-base" href="#bplo">BPLO</a></li>
                    <li><a class="px-3 py-2 hover:font-semibold hover:text-base" href="#services">BPLO</a></li>
                    <li><a class="px-3 py-2 hover:font-semibold hover:text-base" href="#contact">Contacts</a></li>
                </ul>
                <div class="flex flex-row items-center divide-x divide-seagreen">
                    <h3><a class="px-2 hover:font-medium hover:text-base" href="{{ url('/learner') }}">Learner</a></h3>
                    <h3><a class="px-2 hover:font-medium hover:text-base" href="{{ url('/instructor') }}">Instructor</a></h3>
                </div>                
            </div>
        </nav> --}}

        <nav class="fixed z-50 text-black border-b-2 border-gray-300 navbar bg-base-100">
            <div class="navbar-start">
                <div class="dropdown">
                <div tabindex="0" role="button" class="btn btn-ghost lg:hidden">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16" /></svg>
                </div>
                <ul tabindex="0" class="menu menu-sm dropdown-content mt-3 z-[1] p-2 shadow bg-base-100 rounded-box w-52">
                    <li><a href="#home">Home</a></li>
                    <li><a href="#about">About</a></li>
                    <li><a href="#bplo">BPLO</a></li>
                    <li><a href="#contact">Contacts</a></li>
                </ul>
                </div>
                <a class="text-xl btn btn-ghost">Eskwela4EveryJuan</a>
            </div>
            <div class="hidden navbar-center lg:flex text-primary">
                <ul class="px-1 menu menu-horizontal">
                    <li><a href="#home">Home</a></li>
                    <li><a href="#about">About</a></li>
                    <li><a href="#bplo">BPLO</a></li>
                    <li><a href="#contact">Contacts</a></li>
                </ul>
            </div>
            <div class="navbar-end">
                {{-- <a class="btn">Button</a> --}}
            </div>
        </nav>

        {{-- <section class="relative w-full h-screen px-2 py-4 pt-16 text-black" id="home">
            <div class="flex flex-wrap justify-between h-full max-w-screen-xl mx-auto">
                <div id="left" class="flex flex-col justify-center w-1/2">
                    <div class="space-y-4">
                        <h1 class="w-3/4 text-5xl font-bold text-darthmouthgreen">Next-Level Learning, Made Easy.</h1>
                        <p class="text-sm text-justify">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Vel aliquid nisi ea veniam minima, aut provident quas laboriosam veritatis fugit mollitia quia pariatur iusto natus vitae quasi deserunt at rerum.</p>
                    </div>

                    <div class="flex flex-row items-center justify-end my-4 text-sm">
                        <a href="{{ url('/learner') }}">
                            <x-forms.primary-button
                            color="ashgray"
                            name="Sign in as Learner"
                            type="button"
                            class="text-black ">
                            </x-forms.primary-button>
                        </a>
                        
                        <a href="{{ url('/instructor') }}">
                            <x-forms.primary-button
                            color="darthmouthgreen"
                            name="Sign in as Instructor"
                            type="button"
                            class="text-white">
                            </x-forms.primary-button>
                        </a>

                    </div>
                </div>

                <div id="right" class="w-1/2">
                    <div class="flex items-center justify-end w-full h-full">
                        <div class="w-3/5 bg-seagreen h-4/5"></div>
                    </div>
                </div>            
            </div>
        </section> --}}
        {{-- hero section --}}
        <section class="min-h-screen hero" style="background-image: url('{{ asset('assets/CityHall.jpg') }}')" id="home">
            <div class="hero-overlay bg-opacity-80"></div>
            <div class="text-center hero-content text-neutral-content">
                <div class="max-w-md">
                    <h1 class="text-xl text-darthmouthgreen font-semibold">Eskwela4EveryJuan</h1>
                <h1 class="mb-5 text-7xl font-bold">Next-Level Learning, Made Easy.</h1>
                <p class="mb-5 text-lg">Provident cupiditate voluptatem et in. Quaerat fugiat ut assumenda excepturi exercitationem quasi. In deleniti eaque aut repudiandae et a id nisi.</p>
                <div class="space-y-4">
                    <a href="{{  url('/learner') }}" class="btn btn-sm md:btn-md lg:btn-lg btn-primary btn-wide">Student</a>
                    <a href="{{  url('/instructor') }}" class="btn btn-sm md:btn-md lg:btn-lg btn-wide">Instructor</a>
                                 
                </div>

                </div>
            </div>
        </section>

        <section class="relative w-full h-screen text-black lg:py-4 lg:px-2 lg:pt-16" id="about">
            <div class="flex-wrap justify-between h-full max-w-screen-xl mx-auto lg:flex">
                <div id="left" class="absolute w-full h-full lg:relative lg:w-1/2">
                    <div class="flex items-center justify-center w-full h-full ">
                        <div class="w-full h-full lg:w-3/5 opacity-30 lg:opacity-100 bg-seagreen lg:h-4/5"></div>
                    </div>
                </div>                       
                <div id="right" class="flex flex-col justify-center p-3 lg:w-1/2">
                    <div class="space-y-4">
                        <div class="flex flex-row w-4/5">
                            <span class="mx-2 text-4xl">&#8212;</span>
                            <h1 class="text-3xl font-bold">About our Learning Management System</h1>
                        </div>
                        
                        <p class="text-sm text-justify">Lorem ipsum dolor sit amet consectetur adipisicing elit. Et sint aliquam nemo labore. Omnis quam ab cumque vero nihil reiciendis itaque illo, veritatis tempore dolor necessitatibus quaerat tenetur quod natus.
                        Dolor, dolorum? Quae neque at molestiae incidunt corrupti, impedit harum veritatis placeat, consectetur nemo quidem dolorum temporibus, quo tempore! In nisi quo dolores quas, culpa autem eligendi harum optio eum.
                        Doloribus sint consequatur illum unde exercitationem recusandae maiores. Ullam, ea totam accusamus architecto reiciendis consequuntur, consectetur nisi fugit non, ut deserunt harum nesciunt dolore nobis natus animi pariatur vel. Asperiores.
                        Magnam nobis ullam hic voluptatibus quasi sint officiis suscipit rem ea placeat odio dolor possimus labore itaque nesciunt obcaecati, fuga quia amet, recusandae impedit. Nam itaque officia ex optio a!</p>
                    
                        <div>
                            <span><a class="font-medium text-darthmouthgreen hover:text-seagreen hover:underline" href="">Read more <i class="mx-1 fa-solid fa-arrow-right"></i></a></span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- <section class="relative w-full px-2 py-4 text-black bg-opacity-50 h-72 bg-seagreen">
            <div class="flex flex-wrap items-center justify-between h-full max-w-screen-xl mx-auto">
                <div class="flex items-center">
                    <div class="mx-2">
                        <i class="fa-solid fa-user fa-4x"></i>
                    </div>
                    <div class="flex flex-col items-center">
                        <span class="text-3xl font-bold">36</span>
                        <p>Students Enrolled</p>
                    </div>
                </div>
                
                <div class="flex items-center">
                    <div class="mx-2">
                        <i class="fa-solid fa-user-tie fa-4x"></i>
                    </div>
                    <div class="flex flex-col items-center">
                        <span class="text-3xl font-bold">36</span>
                        <p>Instructor Teachings</p>
                    </div>
                </div>
                
                <div class="flex items-center">
                    <div class="mx-2">
                        <i class="fa-solid fa-book fa-4x"></i>
                    </div>
                    <div class="flex flex-col items-center">
                        <span class="text-3xl font-bold">36</span>
                        <p>Courses Available</p>
                    </div>
                </div>
                
                <div class="flex items-center">
                    <div class="mx-2">
                        <i class="fa-solid fa-award fa-4x"></i>
                    </div>
                    <div class="flex flex-col items-center">
                        <span class="text-3xl font-bold">36</span>
                        <p>Certificates</p>
                    </div>
                </div>
            </div>
        </section> --}}
        <section class="relative w-full px-4">
            <div class="w-full mx-auto shadow stats stats-vertical lg:stats-horizontal">
    
                <div class="stat">
                    <div class="stat-figure text-primary">
                        <i class="fa-solid fa-user fa-2xl"></i>
                    </div>
                    <div class="stat-title">Students Enrolled</div>
                    <div class="stat-value text-primary">25.6K</div>
                    <div class="stat-desc">21% more than last month</div>
                </div>
                
                <div class="stat">
                    <div class="stat-figure text-primary">
                        <i class="fa-solid fa-user-tie fa-2xl"></i>
                    </div>
                    <div class="stat-title">Instructor Teaching</div>
                    <div class="stat-value text-primary">2.6M</div>
                    <div class="stat-desc">21% more than last month</div>
                </div>

                <div class="stat">
                    <div class="stat-figure text-secondary">
                        <i class="fa-solid fa-book fa-2xl"></i>
                    </div>
                    <div class="stat-title">Courses Available</div>
                    <div class="stat-value text-secondary">2.6M</div>
                    <div class="stat-desc">21% more than last month</div>
                </div>
                
                <div class="stat">
                    <div class="stat-figure text-secondary">
                        <i class="fa-solid fa-award fa-2xl"></i>
                    </div>
                    <div class="stat-value">86%</div>
                    <div class="stat-title">Tasks done</div>
                    <div class="stat-desc text-secondary">31 tasks remaining</div>
                </div>
                
            </div>            
        </section>

        
        <section class="relative lg:top-[100px] w-full px-2 py-4 text-black lg:h-screen lg:pt-16" id="bplo">
            <div class="h-full lg:pt-[150px] max-w-screen-xl mx-auto space-y-10">

                <div class="flex flex-col items-center w-full lg:flex-row">
                    <div id="left" class="leading-loose lg:w-1/2 mx-5">
                        <div class="flex flex-row w-4/5 my-2">
                            <span class="text-4xl ">&#8212;</span>
                            <h1 class="text-3xl font-bold">Business Permits and Licensing Office</h1>
                        </div>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Harum beatae optio, assumenda sint est deserunt voluptatum ut commodi autem accusamus maiores consequatur rerum illum saepe culpa laborum corrupti itaque. Similique.
                        Neque dolor vitae voluptatem incidunt numquam, temporibus ipsum, veritatis, dicta ut aliquam minus nostrum obcaecati consequuntur magnam aut quos vel deleniti aliquid repellendus nesciunt totam at necessitatibus similique commodi. Recusandae!</p>
                    </div>
                    
                    <div id="right" class="lg:w-1/2">
                        <div class="flex flex-col items-center justify-center space-y-1">
                            <div class="flex items-center justify-center space-x-1">
                                <img class="w-1/2 rounded-lg" src="https://tse4.mm.bing.net/th?id=OIP.1d6tBbNiJTFQNEK_k0sSjQHaFj&pid=Api" alt="">
                                <img class="w-1/2 rounded-lg" src="https://tse4.mm.bing.net/th?id=OIP.1d6tBbNiJTFQNEK_k0sSjQHaFj&pid=Api" alt="">
                            </div>
                            <div class="w-full">
                                <img class="object-cover w-full h-48 rounded-lg" src="https://tse4.mm.bing.net/th?id=OIP.1d6tBbNiJTFQNEK_k0sSjQHaFj&pid=Api" alt="">
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>

        <section class="relative w-full px-2 py-4 text-black bg-opacity-50 h-72" id="contact">
            <div class="flex flex-wrap items-center justify-center h-full max-w-screen-xl mx-auto">
                <h1 class="text-3xl font-bold text-seagreen">Email to know more about us.</h1>
                <p class="text-center">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Illum et minus praesentium veritatis quis dicta sequi, sint quas tempora error nihil saepe eligendi corrupti quasi porro, ad impedit, sunt cumque.</p>
                <form action="">
                    <div class="join">
                        <input class="input input-bordered join-item" placeholder="Email"/>
                        <button class="rounded-r-full btn join-item btn-primary">Subscribe</button>
                    </div>
                </form>
            </div>
        </section>


        {{-- <section class="relative w-full h-screen px-2 py-4 pt-16 text-black">
            <div class="h-full max-w-screen-xl mx-auto space-y-10">
                <div class="flex flex-row w-4/5 my-2">
                    <span class="text-4xl ">&#8212;</span>
                    <h1 class="text-3xl font-bold">Services</h1>
                </div>

                <div class="flex items-center justify-center">
                    <div class="max-w-sm mx-2 border border-gray-200 rounded-lg">
                        <a href="">
                            <img class="rounded-t-lg" src="https://cdn2.wanderlust.co.uk/media/1008/kemeri-bog-from-above.jpg?anchor=center&mode=crop&width=1200&height=0&rnd=132162153630000000" alt="">
                        </a>
                        <div class="p-2">
                            <a href="">
                                <h3 class="mb-2 text-2xl font-medium">Service 1</h3>
                            </a>
                            <p class="h-32 mb-3 overflow-hidden overflow-ellipsis card-p">Lorem ipsum dolor sit amet consectetur, adipisicing elit. Hic debitis recusandae sequi quibusdam reprehenderit voluptatum, aut praesentium corporis ducimus eligendi minima dignissimos magni tempore dolorem, odit consequatur omnis! Corrupti, accusantium!</p>
                            
                            <x-forms.primary-button
                            color="ashgray"
                            name="Read more"
                            type="submit"
                            class="px-0">
                            <i class="ml-2 fa-solid fa-arrow-right"></i>
                            </x-forms.primary-button>
                        </div>
                    </div>

                    <div class="max-w-sm mx-2 border border-gray-200 rounded-lg">
                        <a href="">
                            <img class="rounded-t-lg" src="https://cdn2.wanderlust.co.uk/media/1008/kemeri-bog-from-above.jpg?anchor=center&mode=crop&width=1200&height=0&rnd=132162153630000000" alt="">
                        </a>
                        <div class="p-2">
                            <a href="">
                                <h3 class="mb-2 text-2xl font-medium">Service 1</h3>
                            </a>
                            <p class="h-32 mb-3 overflow-hidden overflow-ellipsis card-p">Lorem ipsum dolor sit amet consectetur, adipisicing elit. Hic debitis recusandae sequi quibusdam reprehenderit voluptatum, aut praesentium corporis ducimus eligendi minima dignissimos magni tempore dolorem, odit consequatur omnis! Corrupti, accusantium!</p>
                            
                            <x-forms.primary-button
                            color="ashgray"
                            name="Read more"
                            type="submit"
                            class="px-0">
                            <i class="ml-2 fa-solid fa-arrow-right"></i>
                            </x-forms.primary-button>
                        </div>
                    </div>

                    <div class="max-w-sm mx-2 border border-gray-200 rounded-lg">
                        <a href="">
                            <img class="rounded-t-lg" src="https://cdn2.wanderlust.co.uk/media/1008/kemeri-bog-from-above.jpg?anchor=center&mode=crop&width=1200&height=0&rnd=132162153630000000" alt="">
                        </a>
                        <div class="p-2">
                            <a href="">
                                <h3 class="mb-2 text-2xl font-medium">Service 1</h3>
                            </a>
                            <p class="h-32 mb-3 overflow-hidden overflow-ellipsis card-p">Lorem ipsum dolor sit amet consectetur, adipisicing elit. Hic debitis recusandae sequi quibusdam reprehenderit voluptatum, aut praesentium corporis ducimus eligendi minima dignissimos magni tempore dolorem, odit consequatur omnis! Corrupti, accusantium!</p>
                            
                            <x-forms.primary-button
                            color="ashgray"
                            name="Read more"
                            type="submit"
                            class="px-0">
                            <i class="ml-2 fa-solid fa-arrow-right"></i>
                            </x-forms.primary-button>
                        </div>
                    </div>                    
                </div>

            </div>
        </section> --}}

        {{-- <footer class="bg-darthmouthgreen">
            <div class="w-full max-w-screen-xl mx-auto">
                <div class="grid grid-cols-2 gap-8 px-4 py-6 lg:py-8 md:grid-cols-4 place-items-center">
                    <div>
                        <h2 class="mb-6 text-sm font-semibold text-gray-900 uppercase dark:text-white">Company</h2>
                        <ul class="font-medium text-gray-500 dark:text-gray-400">
                            <li class="mb-4">
                                <a href="#" class=" hover:underline">About</a>
                            </li>
                            <li class="mb-4">
                                <a href="#" class="hover:underline">Careers</a>
                            </li>
                            <li class="mb-4">
                                <a href="#" class="hover:underline">Brand Center</a>
                            </li>
                            <li class="mb-4">
                                <a href="#" class="hover:underline">Blog</a>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <h2 class="mb-6 text-sm font-semibold text-gray-900 uppercase dark:text-white">Help center</h2>
                        <ul class="font-medium text-gray-500 dark:text-gray-400">
                            <li class="mb-4">
                                <a href="#" class="hover:underline">Discord Server</a>
                            </li>
                            <li class="mb-4">
                                <a href="#" class="hover:underline">Twitter</a>
                            </li>
                            <li class="mb-4">
                                <a href="#" class="hover:underline">Facebook</a>
                            </li>
                            <li class="mb-4">
                                <a href="#" class="hover:underline">Contact Us</a>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <h2 class="mb-6 text-sm font-semibold text-gray-900 uppercase dark:text-white">Legal</h2>
                        <ul class="font-medium text-gray-500 dark:text-gray-400">
                            <li class="mb-4">
                                <a href="#" class="hover:underline">Privacy Policy</a>
                            </li>
                            <li class="mb-4">
                                <a href="#" class="hover:underline">Licensing</a>
                            </li>
                            <li class="mb-4">
                                <a href="#" class="hover:underline">Terms &amp; Conditions</a>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <h2 class="mb-6 text-sm font-semibold text-gray-900 uppercase dark:text-white">Download</h2>
                        <ul class="font-medium text-gray-500 dark:text-gray-400">
                            <li class="mb-4">
                                <a href="#" class="hover:underline">iOS</a>
                            </li>
                            <li class="mb-4">
                                <a href="#" class="hover:underline">Android</a>
                            </li>
                            <li class="mb-4">
                                <a href="#" class="hover:underline">Windows</a>
                            </li>
                            <li class="mb-4">
                                <a href="#" class="hover:underline">MacOS</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="w-full p-4 antialiased shadow bg-darthmouthgreen sm:flex sm:items-center sm:justify-between sm:p-6 xl:p-8">
                    <div class="flex flex-wrap items-center justify-between w-full max-w-screen-xl mx-auto">
                        <p class="mb-4 text-sm text-center sm:mb-0">
                            &copy; 2023 <a href="" class="hover:underline" target="_blank">Eskwela4EveryJuan</a>. All rights reserved.
                        </p>
                        <div class="flex items-center justify-center space-x-1">
                            <a href="#" data-tooltip-target="tooltip-facebook" class="inline-flex justify-center p-2 text-gray-500 rounded-lg cursor-pointer dark:text-gray-400 dark:hover:text-white hover:text-gray-900 hover:bg-gray-100 dark:hover:bg-gray-600">
                                <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 8 19">
                                    <path fill-rule="evenodd" d="M6.135 3H8V0H6.135a4.147 4.147 0 0 0-4.142 4.142V6H0v3h2v9.938h3V9h2.021l.592-3H5V3.591A.6.6 0 0 1 5.592 3h.543Z" clip-rule="evenodd"/>
                                </svg>
                                <span class="sr-only">Facebook</span>
                            </a>
                            <div id="tooltip-facebook" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                Like us on Facebook
                                <div class="tooltip-arrow" data-popper-arrow></div>
                            </div>
                            <a href="#" data-tooltip-target="tooltip-twitter" class="inline-flex justify-center p-2 text-gray-500 rounded-lg cursor-pointer dark:text-gray-400 dark:hover:text-white hover:text-gray-900 hover:bg-gray-100 dark:hover:bg-gray-600">
                                <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                    <path fill="currentColor" d="M12.186 8.672 18.743.947h-2.927l-5.005 5.9-4.44-5.9H0l7.434 9.876-6.986 8.23h2.927l5.434-6.4 4.82 6.4H20L12.186 8.672Zm-2.267 2.671L8.544 9.515 3.2 2.42h2.2l4.312 5.719 1.375 1.828 5.731 7.613h-2.2l-4.699-6.237Z"/>
                                </svg>
                                <span class="sr-only">Twitter</span>
                            </a>
                            <div id="tooltip-twitter" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                Follow us on Twitter
                                <div class="tooltip-arrow" data-popper-arrow></div>
                            </div>
                            <a href="#" data-tooltip-target="tooltip-github" class="inline-flex justify-center p-2 text-gray-500 rounded-lg cursor-pointer dark:text-gray-400 dark:hover:text-white hover:text-gray-900 hover:bg-gray-100 dark:hover:bg-gray-600">
                                <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 .333A9.911 9.911 0 0 0 6.866 19.65c.5.092.678-.215.678-.477 0-.237-.01-1.017-.014-1.845-2.757.6-3.338-1.169-3.338-1.169a2.627 2.627 0 0 0-1.1-1.451c-.9-.615.07-.6.07-.6a2.084 2.084 0 0 1 1.518 1.021 2.11 2.11 0 0 0 2.884.823c.044-.503.268-.973.63-1.325-2.2-.25-4.516-1.1-4.516-4.9A3.832 3.832 0 0 1 4.7 7.068a3.56 3.56 0 0 1 .095-2.623s.832-.266 2.726 1.016a9.409 9.409 0 0 1 4.962 0c1.89-1.282 2.717-1.016 2.717-1.016.366.83.402 1.768.1 2.623a3.827 3.827 0 0 1 1.02 2.659c0 3.807-2.319 4.644-4.525 4.889a2.366 2.366 0 0 1 .673 1.834c0 1.326-.012 2.394-.012 2.72 0 .263.18.572.681.475A9.911 9.911 0 0 0 10 .333Z" clip-rule="evenodd"/>
                                </svg>
                                <span class="sr-only">Github</span>
                            </a>
                            <div id="tooltip-github" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                Star us on GitHub
                                <div class="tooltip-arrow" data-popper-arrow></div>
                            </div>
                            <a href="#" data-tooltip-target="tooltip-dribbble" class="inline-flex justify-center p-2 text-gray-500 rounded-lg cursor-pointer dark:text-gray-400 dark:hover:text-white hover:text-gray-900 hover:bg-gray-100 dark:hover:bg-gray-600">
                                <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 0a10 10 0 1 0 10 10A10.009 10.009 0 0 0 10 0Zm6.613 4.614a8.523 8.523 0 0 1 1.93 5.32 20.094 20.094 0 0 0-5.949-.274c-.059-.149-.122-.292-.184-.441a23.879 23.879 0 0 0-.566-1.239 11.41 11.41 0 0 0 4.769-3.366ZM8 1.707a8.821 8.821 0 0 1 2-.238 8.5 8.5 0 0 1 5.664 2.152 9.608 9.608 0 0 1-4.476 3.087A45.758 45.758 0 0 0 8 1.707ZM1.642 8.262a8.57 8.57 0 0 1 4.73-5.981A53.998 53.998 0 0 1 9.54 7.222a32.078 32.078 0 0 1-7.9 1.04h.002Zm2.01 7.46a8.51 8.51 0 0 1-2.2-5.707v-.262a31.64 31.64 0 0 0 8.777-1.219c.243.477.477.964.692 1.449-.114.032-.227.067-.336.1a13.569 13.569 0 0 0-6.942 5.636l.009.003ZM10 18.556a8.508 8.508 0 0 1-5.243-1.8 11.717 11.717 0 0 1 6.7-5.332.509.509 0 0 1 .055-.02 35.65 35.65 0 0 1 1.819 6.476 8.476 8.476 0 0 1-3.331.676Zm4.772-1.462A37.232 37.232 0 0 0 13.113 11a12.513 12.513 0 0 1 5.321.364 8.56 8.56 0 0 1-3.66 5.73h-.002Z" clip-rule="evenodd"/>
                                </svg>
                                <span class="sr-only">Dribbble</span>
                            </a>
                            <div id="tooltip-dribbble" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                Follow us on Dribbble
                                <div class="tooltip-arrow" data-popper-arrow></div>
                            </div>
                        </div>                
                    </div>
                </div>
            </div>
        </footer> --}}

        <footer class="p-10 footer footer-center bg-primary text-primary-content">
            <aside>
                <svg width="50" height="50" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd" class="inline-block fill-current"><path d="M22.672 15.226l-2.432.811.841 2.515c.33 1.019-.209 2.127-1.23 2.456-1.15.325-2.148-.321-2.463-1.226l-.84-2.518-5.013 1.677.84 2.517c.391 1.203-.434 2.542-1.831 2.542-.88 0-1.601-.564-1.86-1.314l-.842-2.516-2.431.809c-1.135.328-2.145-.317-2.463-1.229-.329-1.018.211-2.127 1.231-2.456l2.432-.809-1.621-4.823-2.432.808c-1.355.384-2.558-.59-2.558-1.839 0-.817.509-1.582 1.327-1.846l2.433-.809-.842-2.515c-.33-1.02.211-2.129 1.232-2.458 1.02-.329 2.13.209 2.461 1.229l.842 2.515 5.011-1.677-.839-2.517c-.403-1.238.484-2.553 1.843-2.553.819 0 1.585.509 1.85 1.326l.841 2.517 2.431-.81c1.02-.33 2.131.211 2.461 1.229.332 1.018-.21 2.126-1.23 2.456l-2.433.809 1.622 4.823 2.433-.809c1.242-.401 2.557.484 2.557 1.838 0 .819-.51 1.583-1.328 1.847m-8.992-6.428l-5.01 1.675 1.619 4.828 5.011-1.674-1.62-4.829z"></path></svg>
                <p class="font-bold">
                Eskwela4EveryJuan <br>Providing reliable tech since 2023
                </p> 
                <p>Copyright © 2024 - All right reserved</p>
            </aside> 
            <nav>
                <div class="grid grid-flow-col gap-4">
                <a><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" class="fill-current"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"></path></svg></a>
                <a><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" class="fill-current"><path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"></path></svg></a>
                <a><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" class="fill-current"><path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"></path></svg></a>
                </div>
            </nav>
        </footer>

    </section>

<script>

    $(document).ready(function() {
        $(window).scroll(function() {
            if ($(this).scrollTop() > 600) { // Change 50 to the desired scroll position
                $('nav').removeClass('bg-opacity-40');
            } else {
                $('nav').addClass('bg-opacity-40');
            }
        });

        $("nav").find("a").click(function(e) {
            e.preventDefault();
            var section = $(this).attr("href");
            $("html, body").animate({
                scrollTop: $(section).offset().top
            });
        });
    });


</script>

@endsection