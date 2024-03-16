<section class="fixed z-20 w-auto h-auto overflow-hidden text-black md:w-full lg:w-2/12 md:relative" id="sidebar_full">
    <div class="fixed flex flex-col justify-between w-full bg-mainwhitebg md:h-screen md:relative lg:border-r-4 border-darthmouthgreen" id="instructorSidebar">

        <div class="">
            <div class="p-2 md:p-4 md:pb-8">
                <div class="flex items-center justify-center hidden md:justify-start md:px-4 " id="logo_half">
                    <a href="{{ url('/learner/dashboard') }}">
                        <i class="mx-2 text-4xl fa-solid fa-book-bookmark"></i>
                    </a>
                </div>
                <div class="flex items-center justify-between" id="logo_full">
                    <a href="{{ url('/learner/dashboard') }}">
                        <span class="self-center text-lg font-semibold font-semibbold whitespace-nowrap md:text-2xl text-darthmouthgreen">
                            Eskwela4EveryJuan
                        </span>
                    </a>
                    <button class="px-2 md:hidden" id="eBot">
                        <svg class="h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="currentColor" d="M17.5 15.5c0 1.11-.89 2-2 2s-2-.89-2-2s.9-2 2-2s2 .9 2 2m-9-2c-1.1 0-2 .9-2 2s.9 2 2 2s2-.89 2-2s-.89-2-2-2M23 15v3c0 .55-.45 1-1 1h-1v1c0 1.11-.89 2-2 2H5a2 2 0 0 1-2-2v-1H2c-.55 0-1-.45-1-1v-3c0-.55.45-1 1-1h1c0-3.87 3.13-7 7-7h1V5.73c-.6-.34-1-.99-1-1.73c0-1.1.9-2 2-2s2 .9 2 2c0 .74-.4 1.39-1 1.73V7h1c3.87 0 7 3.13 7 7h1c.55 0 1 .45 1 1m-2 1h-2v-2c0-2.76-2.24-5-5-5h-4c-2.76 0-5 2.24-5 5v2H3v1h2v3h14v-3h2z"/></svg>
                    </button>
                </div>
            </div>
     
    
    
            <ul class="flex flex-row justify-between md:flex-col md:text-base">
                <li id="instructor_dashboard" class="w-full py-3 hover:bg-darthmouthgreen group md:py-4">
                    <a class="flex items-center justify-center md:justify-start md:px-4 " href="{{ url('/learner/dashboard')}}">
                        <svg class="mx-3 duration-500 stroke-black group-hover:stroke-white group-hover:animate-bounce" width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g opacity="0.7">
                        <path d="M21.875 19.7917V12.7781C21.875 12.213 21.76 11.6537 21.5371 11.1344C21.3141 10.6151 20.9879 10.1465 20.5781 9.75729L13.9354 3.44792C13.5482 3.08009 13.0346 2.875 12.5005 2.875C11.9665 2.875 11.4528 3.08009 11.0656 3.44792L4.42187 9.75729C4.01214 10.1465 3.68587 10.6151 3.46292 11.1344C3.23997 11.6537 3.125 12.213 3.125 12.7781V19.7917C3.125 20.3442 3.34449 20.8741 3.73519 21.2648C4.12589 21.6555 4.6558 21.875 5.20833 21.875H19.7917C20.3442 21.875 20.8741 21.6555 21.2648 21.2648C21.6555 20.8741 21.875 20.3442 21.875 19.7917Z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </g>
                        </svg>
                        <h1 class="hidden md:block group-hover:text-white">Dashboard</h1>
                    </a>
                </li>
                
                <li id="instructor_discussions" class="w-full py-3 hover:bg-darthmouthgreen group md:py-4">
                    <a class="flex items-center justify-center md:justify-start md:px-4" href="/learner/discussions">
                        <svg class="mx-3 duration-500 fill-black group-hover:fill-white group-hover:animate-bounce" width="23" height="23" viewBox="0 0 23 23" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M3.84962 17.0976C3.99883 17.2474 4.11317 17.4282 4.18448 17.6273C4.25579 17.8263 4.28232 18.0386 4.26219 18.2491C4.16116 19.2231 3.96962 20.1856 3.69006 21.1241C5.69537 20.6597 6.92013 20.1221 7.47644 19.8404C7.79197 19.6806 8.15535 19.6427 8.49706 19.734C9.47662 19.9951 10.4862 20.1266 11.5 20.125C17.2442 20.125 21.5625 16.0899 21.5625 11.5C21.5625 6.9115 17.2442 2.875 11.5 2.875C5.75575 2.875 1.4375 6.9115 1.4375 11.5C1.4375 13.6102 2.32444 15.5681 3.84962 17.0976ZM3.14094 22.7111C2.80035 22.7786 2.45865 22.8404 2.116 22.8965C1.8285 22.9425 1.61 22.6435 1.72356 22.3761C1.8512 22.0751 1.96819 21.7697 2.07431 21.4604L2.07862 21.4461C2.43512 20.4111 2.7255 19.2208 2.83188 18.1125C1.06806 16.3444 0 14.03 0 11.5C0 5.94262 5.14912 1.4375 11.5 1.4375C17.8509 1.4375 23 5.94262 23 11.5C23 17.0574 17.8509 21.5625 11.5 21.5625C10.361 21.564 9.22672 21.4161 8.12619 21.1226C7.37869 21.5007 5.77012 22.1892 3.14094 22.7111Z" fill-opacity="0.75"/>
                        </svg>
                        <h1 class="hidden md:block group-hover:text-white">Discussions</h1>
                    </a>
                </li>
                
                <li id="instructor_courses" class="w-full py-3 hover:bg-darthmouthgreen group md:py-4">
                    <a class="flex items-center justify-center md:justify-start md:px-4" href="{{ url('/learner/courses') }}">
                        
                        <svg class="mx-3 duration-500 fill-black group-hover:fill-white group-hover:animate-bounce" width="27" height="27" viewBox="0 0 27 27" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M21.375 6.75H18V5.625C18 5.02826 17.7629 4.45597 17.341 4.03401C16.919 3.61205 16.3467 3.375 15.75 3.375H11.25C10.6533 3.375 10.081 3.61205 9.65901 4.03401C9.23705 4.45597 9 5.02826 9 5.625V6.75H5.625C4.72989 6.75 3.87145 7.10558 3.23851 7.73851C2.60558 8.37145 2.25 9.22989 2.25 10.125V20.25C2.25 21.1451 2.60558 22.0036 3.23851 22.6365C3.87145 23.2694 4.72989 23.625 5.625 23.625H21.375C22.2701 23.625 23.1285 23.2694 23.7615 22.6365C24.3944 22.0036 24.75 21.1451 24.75 20.25V10.125C24.75 9.22989 24.3944 8.37145 23.7615 7.73851C23.1285 7.10558 22.2701 6.75 21.375 6.75ZM11.25 5.625H15.75V6.75H11.25V5.625ZM22.5 20.25C22.5 20.5484 22.3815 20.8345 22.1705 21.0455C21.9595 21.2565 21.6734 21.375 21.375 21.375H5.625C5.32663 21.375 5.04048 21.2565 4.82951 21.0455C4.61853 20.8345 4.5 20.5484 4.5 20.25V13.9388L9.765 15.75C9.88445 15.7662 10.0055 15.7662 10.125 15.75H16.875C16.997 15.7477 17.1181 15.7288 17.235 15.6937L22.5 13.9388V20.25ZM22.5 11.565L16.695 13.5H10.305L4.5 11.565V10.125C4.5 9.82663 4.61853 9.54048 4.82951 9.32951C5.04048 9.11853 5.32663 9 5.625 9H21.375C21.6734 9 21.9595 9.11853 22.1705 9.32951C22.3815 9.54048 22.5 9.82663 22.5 10.125V11.565Z" fill-opacity="0.7"/>
                        </svg>
    
                        <h1 class="hidden md:block group-hover:text-white">Courses</h1>
                    </a>
                </li>

                <li id="instructor_message" class="w-full py-3 rounded-lg hover:text-white hover:bg-darthmouthgreen group md:py-4">
                    <a class="flex items-center justify-center md:justify-start md:px-4" href="{{ url('/learner/message') }}">

                        <i class="mx-3 text-xl duration-500 fa-regular fa-message fill-black group-hover:fill-white group-hover:animate-bounce"></i>
    
                        <h1 class="hidden md:block group-hover:text-white">Message</h1>
                    </a>
                </li>
    
                <li id="instructor_performances" class="w-full py-3 hover:bg-darthmouthgreen group md:py-4">
                    <a class="flex items-center justify-center md:justify-start md:px-4" href="{{ url("/learner/performances") }}">
                        
                        <svg class="mx-3 duration-500 fill-black group-hover:fill-white group-hover:animate-bounce" width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12.5002 6.24998C12.2239 6.24998 11.9589 6.35973 11.7636 6.55508C11.5682 6.75043 11.4585 7.01538 11.4585 7.29165V17.7083C11.4585 17.9846 11.5682 18.2495 11.7636 18.4449C11.9589 18.6402 12.2239 18.75 12.5002 18.75C12.7764 18.75 13.0414 18.6402 13.2367 18.4449C13.4321 18.2495 13.5418 17.9846 13.5418 17.7083V7.29165C13.5418 7.01538 13.4321 6.75043 13.2367 6.55508C13.0414 6.35973 12.7764 6.24998 12.5002 6.24998ZM7.29183 12.5C7.01556 12.5 6.75061 12.6097 6.55526 12.8051C6.35991 13.0004 6.25016 13.2654 6.25016 13.5416V17.7083C6.25016 17.9846 6.35991 18.2495 6.55526 18.4449C6.75061 18.6402 7.01556 18.75 7.29183 18.75C7.5681 18.75 7.83305 18.6402 8.0284 18.4449C8.22375 18.2495 8.3335 17.9846 8.3335 17.7083V13.5416C8.3335 13.2654 8.22375 13.0004 8.0284 12.8051C7.83305 12.6097 7.5681 12.5 7.29183 12.5ZM17.7085 10.4166C17.4322 10.4166 17.1673 10.5264 16.9719 10.7217C16.7766 10.9171 16.6668 11.182 16.6668 11.4583V17.7083C16.6668 17.9846 16.7766 18.2495 16.9719 18.4449C17.1673 18.6402 17.4322 18.75 17.7085 18.75C17.9848 18.75 18.2497 18.6402 18.4451 18.4449C18.6404 18.2495 18.7502 17.9846 18.7502 17.7083V11.4583C18.7502 11.182 18.6404 10.9171 18.4451 10.7217C18.2497 10.5264 17.9848 10.4166 17.7085 10.4166ZM19.7918 2.08331H5.2085C4.37969 2.08331 3.58484 2.41255 2.99879 2.9986C2.41274 3.58466 2.0835 4.37951 2.0835 5.20831V19.7916C2.0835 20.6204 2.41274 21.4153 2.99879 22.0014C3.58484 22.5874 4.37969 22.9166 5.2085 22.9166H19.7918C20.6206 22.9166 21.4155 22.5874 22.0015 22.0014C22.5876 21.4153 22.9168 20.6204 22.9168 19.7916V5.20831C22.9168 4.37951 22.5876 3.58466 22.0015 2.9986C21.4155 2.41255 20.6206 2.08331 19.7918 2.08331ZM20.8335 19.7916C20.8335 20.0679 20.7238 20.3329 20.5284 20.5282C20.3331 20.7236 20.0681 20.8333 19.7918 20.8333H5.2085C4.93223 20.8333 4.66728 20.7236 4.47193 20.5282C4.27658 20.3329 4.16683 20.0679 4.16683 19.7916V5.20831C4.16683 4.93205 4.27658 4.66709 4.47193 4.47174C4.66728 4.27639 4.93223 4.16665 5.2085 4.16665H19.7918C20.0681 4.16665 20.3331 4.27639 20.5284 4.47174C20.7238 4.66709 20.8335 4.93205 20.8335 5.20831V19.7916Z" fill-opacity="0.75"/>
                        </svg>
    
                        <h1 class="hidden md:block group-hover:text-white">Performance</h1>
                    </a>
                </li>

                <li id="instructor_settings" class="w-full py-3 hover:bg-darthmouthgreen group md:py-4">
                    <a class="flex items-center justify-center md:justify-start md:px-4" href="{{ url('/learner/profile') }}">
                        
                        <svg class="mx-3 duration-500 fill-black group-hover:fill-white group-hover:animate-bounce" width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M11.6656 2.69475C11.9209 2.55301 12.208 2.47864 12.5 2.47864C12.792 2.47864 13.0791 2.55301 13.3344 2.69475L20.8344 6.86142C21.3802 7.16454 21.7188 7.73954 21.7188 8.36454V16.6354C21.7188 17.2604 21.3802 17.8354 20.8344 18.1385L13.3344 22.3052C13.0791 22.4469 12.792 22.5213 12.5 22.5213C12.208 22.5213 11.9209 22.4469 11.6656 22.3052L4.16562 18.1385C3.89752 17.9896 3.67414 17.7717 3.51863 17.5074C3.36312 17.2431 3.28116 16.942 3.28125 16.6354V8.36454C3.28125 7.73954 3.61979 7.16454 4.16562 6.86142L11.6656 2.69475ZM12.576 4.06142C12.5528 4.04846 12.5266 4.04167 12.5 4.04167C12.4734 4.04167 12.4472 4.04846 12.424 4.06142L4.92396 8.22808C4.8998 8.24154 4.87965 8.26117 4.86556 8.28496C4.85147 8.30875 4.84394 8.33585 4.84375 8.3635V16.6354C4.84375 16.6927 4.875 16.7448 4.92396 16.7729L12.424 20.9395C12.4472 20.9525 12.4734 20.9593 12.5 20.9593C12.5266 20.9593 12.5528 20.9525 12.576 20.9395L20.076 16.7729C20.1005 16.7592 20.1209 16.7393 20.135 16.7151C20.1491 16.6909 20.1564 16.6634 20.1562 16.6354V8.36454C20.1562 8.33672 20.1488 8.30939 20.1347 8.28541C20.1206 8.26142 20.1004 8.24163 20.076 8.22808L12.576 4.06142Z" fill-opacity="0.75"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M7.55225 12.4999C7.55225 11.1876 8.07354 9.92913 9.00146 9.00121C9.92937 8.0733 11.1879 7.552 12.5002 7.552C13.8124 7.552 15.071 8.0733 15.9989 9.00121C16.9268 9.92913 17.4481 11.1876 17.4481 12.4999C17.4481 13.8122 16.9268 15.0707 15.9989 15.9986C15.071 16.9265 13.8124 17.4478 12.5002 17.4478C11.1879 17.4478 9.92937 16.9265 9.00146 15.9986C8.07354 15.0707 7.55225 13.8122 7.55225 12.4999ZM12.5002 9.1145C11.6023 9.1145 10.7412 9.47118 10.1063 10.1061C9.47142 10.741 9.11475 11.6021 9.11475 12.4999C9.11475 13.3978 9.47142 14.2589 10.1063 14.8938C10.7412 15.5287 11.6023 15.8853 12.5002 15.8853C13.398 15.8853 14.2591 15.5287 14.894 14.8938C15.5289 14.2589 15.8856 13.3978 15.8856 12.4999C15.8856 11.6021 15.5289 10.741 14.894 10.1061C14.2591 9.47118 13.398 9.1145 12.5002 9.1145Z" fill-opacity="0.75"/>
                        </svg>
    
                        <h1 class="hidden md:block group-hover:text-white">Profile</h1>
                    </a>
                </li>
                
                <li id="eBot-md" class="hidden w-full py-3 hover:bg-darthmouthgreen md:block group md:py-4 lg:hidden">
                    <button class="flex items-center justify-center md:justify-start md:px-4">
                        
                        <svg class="mx-3 duration-500 fill-black group-hover:fill-white group-hover:animate-bounce" width="25" height="25" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M17.5 15.5c0 1.11-.89 2-2 2s-2-.89-2-2s.9-2 2-2s2 .9 2 2m-9-2c-1.1 0-2 .9-2 2s.9 2 2 2s2-.89 2-2s-.89-2-2-2M23 15v3c0 .55-.45 1-1 1h-1v1c0 1.11-.89 2-2 2H5a2 2 0 0 1-2-2v-1H2c-.55 0-1-.45-1-1v-3c0-.55.45-1 1-1h1c0-3.87 3.13-7 7-7h1V5.73c-.6-.34-1-.99-1-1.73c0-1.1.9-2 2-2s2 .9 2 2c0 .74-.4 1.39-1 1.73V7h1c3.87 0 7 3.13 7 7h1c.55 0 1 .45 1 1m-2 1h-2v-2c0-2.76-2.24-5-5-5h-4c-2.76 0-5 2.24-5 5v2H3v1h2v3h14v-3h2z"/></svg>
    
                        <h1 class=" group-hover:text-white">Eskwela Bot</h1>
                    </button>
                </li>
                
                <form class="hidden mx-4 mt-10 rounded-lg bg-darthmouthgreen md:block group hover:bg-white hover:border-2 hover:border-darthmouthgreen" action="{{ url('/learner/logout') }}" method="POST"> 
                    @csrf
                    <button type="submit" class="flex flex-row items-center justify-center w-full h-12 group-hover:cursor-pointer" >
                        <svg class="fill-white group-hover:fill-black" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h280v80H200v560h280v80H200Zm440-160-55-58 102-102H360v-80h327L585-622l55-58 200 200-200 200Z"/></svg>
                        <h1 class="px-5 text-white group-hover:text-black">Logout</h1>
                    </button>
                </form>
                
            </ul>
        </div>
        

        <div class="flex-row items-center justify-center hidden w-full h-12 md:flex hover:cursor-pointer" id="sidebar_half_btn">
            <i class="mb-5 text-3xl fa-regular fa-square-caret-left"></i>
        </div>
    </div>
    
</section>



<section class="fixed z-20 hidden h-auto overflow-hidden text-black md:w-1/4 lg:w-1/5 md:relative" id="sidebar_half">

    
    <div class="fixed flex flex-col justify-between px-2 border-r-4 bg-mainwhitebg md:h-screen md:pt-16 md:relative border-darthmouthgreen" id="instructorSidebar">

        <div class="">
            <div class="">
                <div class="flex items-center justify-center md:justify-start md:px-4 " id="logo_half">
                    <a href="{{ url('/learner/dashboard') }}">
                        <i class="mx-2 text-4xl fa-solid fa-book-bookmark"></i>
                    </a>
                </div>
                <div class="hidden" id="logo_full">
                    <a href="{{ url('/learner/dashboard') }}">
                        <span class="self-center text-lg font-semibold text-black font-semibbold whitespace-nowrap md:text-2xl">
                            Eskwela4EveryJuan
                        </span>
                    </a>
                </div>
            </div>
     
    
    
            <ul class="flex flex-row justify-between mt-10 md:flex-col md:text-base">
                <li id="" class="w-full py-3 rounded-lg instructor_dashboard hover:bg-darthmouthgreen group md:py-4">
                    <a class="flex items-center justify-center md:justify-start md:px-4 " href="{{ url('/learner/dashboard')}}">
                        <svg class="flex-shrink-0 mx-2 duration-500 stroke-black group-hover:stroke-white group-hover:animate-bounce" width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g opacity="0.7">
                                <path d="M21.875 19.7917V12.7781C21.875 12.213 21.76 11.6537 21.5371 11.1344C21.3141 10.6151 20.9879 10.1465 20.5781 9.75729L13.9354 3.44792C13.5482 3.08009 13.0346 2.875 12.5005 2.875C11.9665 2.875 11.4528 3.08009 11.0656 3.44792L4.42187 9.75729C4.01214 10.1465 3.68587 10.6151 3.46292 11.1344C3.23997 11.6537 3.125 12.213 3.125 12.7781V19.7917C3.125 20.3442 3.34449 20.8741 3.73519 21.2648C4.12589 21.6555 4.6558 21.875 5.20833 21.875H19.7917C20.3442 21.875 20.8741 21.6555 21.2648 21.2648C21.6555 20.8741 21.875 20.3442 21.875 19.7917Z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </g>
                        </svg>
                        {{-- <h1 class="hidden md:block group-hover:text-white">Dashboard</h1> --}}
                    </a>
                </li>
                
                <li id="" class="w-full py-3 rounded-lg instructor_discussions hover:bg-darthmouthgreen group md:py-4">
                    <a class="flex items-center justify-center md:justify-start md:px-4" href="/learner/discussions">
                        <svg class="flex-shrink-0 mx-2 duration-500 fill-black group-hover:fill-white group-hover:animate-bounce" width="23" height="23" viewBox="0 0 23 23" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M3.84962 17.0976C3.99883 17.2474 4.11317 17.4282 4.18448 17.6273C4.25579 17.8263 4.28232 18.0386 4.26219 18.2491C4.16116 19.2231 3.96962 20.1856 3.69006 21.1241C5.69537 20.6597 6.92013 20.1221 7.47644 19.8404C7.79197 19.6806 8.15535 19.6427 8.49706 19.734C9.47662 19.9951 10.4862 20.1266 11.5 20.125C17.2442 20.125 21.5625 16.0899 21.5625 11.5C21.5625 6.9115 17.2442 2.875 11.5 2.875C5.75575 2.875 1.4375 6.9115 1.4375 11.5C1.4375 13.6102 2.32444 15.5681 3.84962 17.0976ZM3.14094 22.7111C2.80035 22.7786 2.45865 22.8404 2.116 22.8965C1.8285 22.9425 1.61 22.6435 1.72356 22.3761C1.8512 22.0751 1.96819 21.7697 2.07431 21.4604L2.07862 21.4461C2.43512 20.4111 2.7255 19.2208 2.83188 18.1125C1.06806 16.3444 0 14.03 0 11.5C0 5.94262 5.14912 1.4375 11.5 1.4375C17.8509 1.4375 23 5.94262 23 11.5C23 17.0574 17.8509 21.5625 11.5 21.5625C10.361 21.564 9.22672 21.4161 8.12619 21.1226C7.37869 21.5007 5.77012 22.1892 3.14094 22.7111Z" fill-opacity="0.75"/>
                        </svg>
                        {{-- <h1 class="hidden md:block group-hover:text-white">Discussions</h1> --}}
                    </a>
                </li>
                
                <li id="" class="w-full py-3 rounded-lg instructor_courses hover:bg-darthmouthgreen group md:py-4">
                    <a class="flex items-center justify-center md:justify-start md:px-4" href="{{ url('/learner/courses') }}">
                        
                        <svg class="flex-shrink-0 mx-2 duration-500 fill-black group-hover:fill-white group-hover:animate-bounce" width="27" height="27" viewBox="0 0 27 27" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M21.375 6.75H18V5.625C18 5.02826 17.7629 4.45597 17.341 4.03401C16.919 3.61205 16.3467 3.375 15.75 3.375H11.25C10.6533 3.375 10.081 3.61205 9.65901 4.03401C9.23705 4.45597 9 5.02826 9 5.625V6.75H5.625C4.72989 6.75 3.87145 7.10558 3.23851 7.73851C2.60558 8.37145 2.25 9.22989 2.25 10.125V20.25C2.25 21.1451 2.60558 22.0036 3.23851 22.6365C3.87145 23.2694 4.72989 23.625 5.625 23.625H21.375C22.2701 23.625 23.1285 23.2694 23.7615 22.6365C24.3944 22.0036 24.75 21.1451 24.75 20.25V10.125C24.75 9.22989 24.3944 8.37145 23.7615 7.73851C23.1285 7.10558 22.2701 6.75 21.375 6.75ZM11.25 5.625H15.75V6.75H11.25V5.625ZM22.5 20.25C22.5 20.5484 22.3815 20.8345 22.1705 21.0455C21.9595 21.2565 21.6734 21.375 21.375 21.375H5.625C5.32663 21.375 5.04048 21.2565 4.82951 21.0455C4.61853 20.8345 4.5 20.5484 4.5 20.25V13.9388L9.765 15.75C9.88445 15.7662 10.0055 15.7662 10.125 15.75H16.875C16.997 15.7477 17.1181 15.7288 17.235 15.6937L22.5 13.9388V20.25ZM22.5 11.565L16.695 13.5H10.305L4.5 11.565V10.125C4.5 9.82663 4.61853 9.54048 4.82951 9.32951C5.04048 9.11853 5.32663 9 5.625 9H21.375C21.6734 9 21.9595 9.11853 22.1705 9.32951C22.3815 9.54048 22.5 9.82663 22.5 10.125V11.565Z" fill-opacity="0.7"/>
                        </svg>
    
                        {{-- <h1 class="hidden md:block group-hover:text-white">Courses</h1> --}}
                    </a>
                </li>

                <li id="" class="w-full py-3 rounded-lg instructor_courses hover:text-white hover:bg-darthmouthgreen group md:py-4">
                    <a class="flex items-center justify-center md:justify-start md:px-4" href="{{ url('/learner/message') }}">

                        <i class="mx-3 text-xl duration-500 fa-regular fa-message fill-black group-hover:fill-white group-hover:animate-bounce"></i>
    
                        {{-- <h1 class="hidden md:block group-hover:text-white">Message</h1> --}}
                    </a>
                </li>
    
                <li id="" class="w-full py-3 rounded-lg instructor_performances hover:bg-darthmouthgreen group md:py-4">
                    <a class="flex items-center justify-center md:justify-start md:px-4" href="{{ url("/learner/performances") }}">
                        
                        <svg class="flex-shrink-0 mx-3 duration-500 fill-black group-hover:fill-white group-hover:animate-bounce" width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12.5002 6.24998C12.2239 6.24998 11.9589 6.35973 11.7636 6.55508C11.5682 6.75043 11.4585 7.01538 11.4585 7.29165V17.7083C11.4585 17.9846 11.5682 18.2495 11.7636 18.4449C11.9589 18.6402 12.2239 18.75 12.5002 18.75C12.7764 18.75 13.0414 18.6402 13.2367 18.4449C13.4321 18.2495 13.5418 17.9846 13.5418 17.7083V7.29165C13.5418 7.01538 13.4321 6.75043 13.2367 6.55508C13.0414 6.35973 12.7764 6.24998 12.5002 6.24998ZM7.29183 12.5C7.01556 12.5 6.75061 12.6097 6.55526 12.8051C6.35991 13.0004 6.25016 13.2654 6.25016 13.5416V17.7083C6.25016 17.9846 6.35991 18.2495 6.55526 18.4449C6.75061 18.6402 7.01556 18.75 7.29183 18.75C7.5681 18.75 7.83305 18.6402 8.0284 18.4449C8.22375 18.2495 8.3335 17.9846 8.3335 17.7083V13.5416C8.3335 13.2654 8.22375 13.0004 8.0284 12.8051C7.83305 12.6097 7.5681 12.5 7.29183 12.5ZM17.7085 10.4166C17.4322 10.4166 17.1673 10.5264 16.9719 10.7217C16.7766 10.9171 16.6668 11.182 16.6668 11.4583V17.7083C16.6668 17.9846 16.7766 18.2495 16.9719 18.4449C17.1673 18.6402 17.4322 18.75 17.7085 18.75C17.9848 18.75 18.2497 18.6402 18.4451 18.4449C18.6404 18.2495 18.7502 17.9846 18.7502 17.7083V11.4583C18.7502 11.182 18.6404 10.9171 18.4451 10.7217C18.2497 10.5264 17.9848 10.4166 17.7085 10.4166ZM19.7918 2.08331H5.2085C4.37969 2.08331 3.58484 2.41255 2.99879 2.9986C2.41274 3.58466 2.0835 4.37951 2.0835 5.20831V19.7916C2.0835 20.6204 2.41274 21.4153 2.99879 22.0014C3.58484 22.5874 4.37969 22.9166 5.2085 22.9166H19.7918C20.6206 22.9166 21.4155 22.5874 22.0015 22.0014C22.5876 21.4153 22.9168 20.6204 22.9168 19.7916V5.20831C22.9168 4.37951 22.5876 3.58466 22.0015 2.9986C21.4155 2.41255 20.6206 2.08331 19.7918 2.08331ZM20.8335 19.7916C20.8335 20.0679 20.7238 20.3329 20.5284 20.5282C20.3331 20.7236 20.0681 20.8333 19.7918 20.8333H5.2085C4.93223 20.8333 4.66728 20.7236 4.47193 20.5282C4.27658 20.3329 4.16683 20.0679 4.16683 19.7916V5.20831C4.16683 4.93205 4.27658 4.66709 4.47193 4.47174C4.66728 4.27639 4.93223 4.16665 5.2085 4.16665H19.7918C20.0681 4.16665 20.3331 4.27639 20.5284 4.47174C20.7238 4.66709 20.8335 4.93205 20.8335 5.20831V19.7916Z" fill-opacity="0.75"/>
                        </svg>
    
                        {{-- <h1 class="hidden md:block group-hover:text-white">Performance</h1> --}}
                    </a>
                </li>
     
                {{-- <li id="instructor_calendar" class="w-full py-3 rounded-lg hover:bg-darthmouthgreen group md:py-4">
                    <a class="flex items-center justify-center md:justify-start md:px-4" href="">
                        
                        <svg class="mx-2 duration-500 stroke-white group-hover:stroke-black group-hover:animate-bounce" width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M2.0835 12.5C2.0835 8.5719 2.0835 6.60731 3.30433 5.38752C4.52412 4.16669 6.4887 4.16669 10.4168 4.16669H14.5835C18.5116 4.16669 20.4762 4.16669 21.696 5.38752C22.9168 6.60731 22.9168 8.5719 22.9168 12.5V14.5834C22.9168 18.5115 22.9168 20.4761 21.696 21.6959C20.4762 22.9167 18.5116 22.9167 14.5835 22.9167H10.4168C6.4887 22.9167 4.52412 22.9167 3.30433 21.6959C2.0835 20.4761 2.0835 18.5115 2.0835 14.5834V12.5Z"  stroke-opacity="0.75" stroke-width="2"/>
                        <path d="M7.29199 4.16669V2.60419M17.7087 4.16669V2.60419M2.60449 9.37502H22.3962"  stroke-opacity="0.75" stroke-width="2" stroke-linecap="round"/>
                        <path class="duration-500 fill-white stroke-white group-hover:fill-black group-hover:stroke-black" d="M18.75 17.7083C18.75 17.9846 18.6403 18.2496 18.4449 18.4449C18.2496 18.6403 17.9846 18.75 17.7083 18.75C17.4321 18.75 17.1671 18.6403 16.9718 18.4449C16.7764 18.2496 16.6667 17.9846 16.6667 17.7083C16.6667 17.4321 16.7764 17.1671 16.9718 16.9718C17.1671 16.7764 17.4321 16.6667 17.7083 16.6667C17.9846 16.6667 18.2496 16.7764 18.4449 16.9718C18.6403 17.1671 18.75 17.4321 18.75 17.7083ZM18.75 13.5417C18.75 13.8179 18.6403 14.0829 18.4449 14.2782C18.2496 14.4736 17.9846 14.5833 17.7083 14.5833C17.4321 14.5833 17.1671 14.4736 16.9718 14.2782C16.7764 14.0829 16.6667 13.8179 16.6667 13.5417C16.6667 13.2654 16.7764 13.0004 16.9718 12.8051C17.1671 12.6097 17.4321 12.5 17.7083 12.5C17.9846 12.5 18.2496 12.6097 18.4449 12.8051C18.6403 13.0004 18.75 13.2654 18.75 13.5417ZM13.5417 17.7083C13.5417 17.9846 13.4319 18.2496 13.2366 18.4449C13.0412 18.6403 12.7763 18.75 12.5 18.75C12.2237 18.75 11.9588 18.6403 11.7634 18.4449C11.5681 18.2496 11.4583 17.9846 11.4583 17.7083C11.4583 17.4321 11.5681 17.1671 11.7634 16.9718C11.9588 16.7764 12.2237 16.6667 12.5 16.6667C12.7763 16.6667 13.0412 16.7764 13.2366 16.9718C13.4319 17.1671 13.5417 17.4321 13.5417 17.7083ZM13.5417 13.5417C13.5417 13.8179 13.4319 14.0829 13.2366 14.2782C13.0412 14.4736 12.7763 14.5833 12.5 14.5833C12.2237 14.5833 11.9588 14.4736 11.7634 14.2782C11.5681 14.0829 11.4583 13.8179 11.4583 13.5417C11.4583 13.2654 11.5681 13.0004 11.7634 12.8051C11.9588 12.6097 12.2237 12.5 12.5 12.5C12.7763 12.5 13.0412 12.6097 13.2366 12.8051C13.4319 13.0004 13.5417 13.2654 13.5417 13.5417ZM8.33333 17.7083C8.33333 17.9846 8.22359 18.2496 8.02824 18.4449C7.83289 18.6403 7.56793 18.75 7.29167 18.75C7.0154 18.75 6.75045 18.6403 6.5551 18.4449C6.35975 18.2496 6.25 17.9846 6.25 17.7083C6.25 17.4321 6.35975 17.1671 6.5551 16.9718C6.75045 16.7764 7.0154 16.6667 7.29167 16.6667C7.56793 16.6667 7.83289 16.7764 8.02824 16.9718C8.22359 17.1671 8.33333 17.4321 8.33333 17.7083ZM8.33333 13.5417C8.33333 13.8179 8.22359 14.0829 8.02824 14.2782C7.83289 14.4736 7.56793 14.5833 7.29167 14.5833C7.0154 14.5833 6.75045 14.4736 6.5551 14.2782C6.35975 14.0829 6.25 13.8179 6.25 13.5417C6.25 13.2654 6.35975 13.0004 6.5551 12.8051C6.75045 12.6097 7.0154 12.5 7.29167 12.5C7.56793 12.5 7.83289 12.6097 8.02824 12.8051C8.22359 13.0004 8.33333 13.2654 8.33333 13.5417Z"  fill-opacity="0.75"/>
                        </svg>
    
                        <h1 class="hidden md:block group-hover:text-black">Calendar</h1>
                    </a>
                </li> --}}
    
                <li id="" class="w-full py-3 rounded-lg instructor_settings hover:bg-darthmouthgreen group md:py-4">
                    <a class="flex items-center justify-center md:justify-start md:px-4" href="{{ url('/learner/profile') }}">
                        
                        <svg class="flex-shrink-0 mx-3 duration-500 fill-black group-hover:fill-white group-hover:animate-bounce" width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M11.6656 2.69475C11.9209 2.55301 12.208 2.47864 12.5 2.47864C12.792 2.47864 13.0791 2.55301 13.3344 2.69475L20.8344 6.86142C21.3802 7.16454 21.7188 7.73954 21.7188 8.36454V16.6354C21.7188 17.2604 21.3802 17.8354 20.8344 18.1385L13.3344 22.3052C13.0791 22.4469 12.792 22.5213 12.5 22.5213C12.208 22.5213 11.9209 22.4469 11.6656 22.3052L4.16562 18.1385C3.89752 17.9896 3.67414 17.7717 3.51863 17.5074C3.36312 17.2431 3.28116 16.942 3.28125 16.6354V8.36454C3.28125 7.73954 3.61979 7.16454 4.16562 6.86142L11.6656 2.69475ZM12.576 4.06142C12.5528 4.04846 12.5266 4.04167 12.5 4.04167C12.4734 4.04167 12.4472 4.04846 12.424 4.06142L4.92396 8.22808C4.8998 8.24154 4.87965 8.26117 4.86556 8.28496C4.85147 8.30875 4.84394 8.33585 4.84375 8.3635V16.6354C4.84375 16.6927 4.875 16.7448 4.92396 16.7729L12.424 20.9395C12.4472 20.9525 12.4734 20.9593 12.5 20.9593C12.5266 20.9593 12.5528 20.9525 12.576 20.9395L20.076 16.7729C20.1005 16.7592 20.1209 16.7393 20.135 16.7151C20.1491 16.6909 20.1564 16.6634 20.1562 16.6354V8.36454C20.1562 8.33672 20.1488 8.30939 20.1347 8.28541C20.1206 8.26142 20.1004 8.24163 20.076 8.22808L12.576 4.06142Z" fill-opacity="0.75"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M7.55225 12.4999C7.55225 11.1876 8.07354 9.92913 9.00146 9.00121C9.92937 8.0733 11.1879 7.552 12.5002 7.552C13.8124 7.552 15.071 8.0733 15.9989 9.00121C16.9268 9.92913 17.4481 11.1876 17.4481 12.4999C17.4481 13.8122 16.9268 15.0707 15.9989 15.9986C15.071 16.9265 13.8124 17.4478 12.5002 17.4478C11.1879 17.4478 9.92937 16.9265 9.00146 15.9986C8.07354 15.0707 7.55225 13.8122 7.55225 12.4999ZM12.5002 9.1145C11.6023 9.1145 10.7412 9.47118 10.1063 10.1061C9.47142 10.741 9.11475 11.6021 9.11475 12.4999C9.11475 13.3978 9.47142 14.2589 10.1063 14.8938C10.7412 15.5287 11.6023 15.8853 12.5002 15.8853C13.398 15.8853 14.2591 15.5287 14.894 14.8938C15.5289 14.2589 15.8856 13.3978 15.8856 12.4999C15.8856 11.6021 15.5289 10.741 14.894 10.1061C14.2591 9.47118 13.398 9.1145 12.5002 9.1145Z" fill-opacity="0.75"/>
                        </svg>
    
                        {{-- <h1 class="hidden md:block group-hover:text-white">Settings</h1> --}}
                    </a>
                </li>
                

                
                <form class="hidden mt-10 rounded-lg bg-darthmouthgreen md:block group hover:bg-white hover:border-2 hover:border-darthmouthgreen" action="{{ url('/learner/logout') }}" method="POST"> 
                    @csrf
                    <button type="submit" class="flex flex-row items-center justify-center w-full h-12 group-hover:cursor-pointer" >
                        <svg class="flex-shrink-0 fill-white group-hover:fill-black" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h280v80H200v560h280v80H200Zm440-160-55-58 102-102H360v-80h327L585-622l55-58 200 200-200 200Z"/></svg>
                        {{-- <h1 class="px-5 text-white group-hover:text-black">Logout</h1> --}}
                    </button>
                </form>
                
            </ul>
        </div>

        <div class="flex flex-row items-center justify-center w-full h-12 hover:cursor-pointer" id="sidebar_full_btn">
            <i class="mb-5 text-3xl fa-regular fa-square-caret-right"></i>
        </div>
    </div>
    
</section>

<div class="fixed z-50 hidden w-full h-screen bg-white bg-opacity-60" id="bot-for-sm">
    <div id="bot-container" class="fixed bottom-0 w-full p-3 ease-in-out bg-white fade-in h-3/4 md:w-3/4 md:h-full md:right-0">

        <div class="flex flex-col justify-between hidden h-full p-3 overflow-hidden rounded-lg shadow-lg mainchatbotarea">
            {{-- head --}}
            <div>
                <div class="relative py-3 text-center border-b-2 border-gray-300">
                    <i class="absolute top-0 right-0 px-3 cursor-pointer fa-solid fa-xmark" id="sm-AIClose"></i>
                    <h1 class="text-2xl font-bold">Eskwela Bot</h1>
                </div>        
            </div>
        
            {{-- body --}}
            <div class="h-full overflow-auto">
                <div class="flex flex-col chatContainer">
                    
                    {{-- chat area --}}
        
                    
                </div>      
            </div>
        
            {{-- foot --}}
            <div class="py-3 border-t-2 border-gray-300">
                
                <p class="bottom-0 hidden text-lg text-gray-700 botloader">the bot is typing...</p>
                <div class="flex items-center justify-between">
                    <textarea type="text" placeholder="Type here" class="w-full lg:w-4/5 question input input-bordered input-primary"></textarea>
                    <button class="w-1/5 mx-1 submitQuestion btn btn-primary"><i class="rotate-90 fa-solid fa-arrow-turn-down"></i></button>
                </div>  
            </div>
        
        </div>

        <div style="height: 80%;" class="absolute inset-0 flex items-center justify-center w-full z-100 loaderArea">
            <div class="chatbotloader"></div><br>
            <p class="mt-3 text-darthmouthgreen">preparing your bot</p>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        var currentUrl = window.location.href;

        if (currentUrl.includes('/learner/dashboard')) {
            $('#instructor_dashboard').addClass('bg-green-100');
        } else if (currentUrl.includes('/learner/discussions')) {
            $('#instructor_discussions').addClass('bg-green-100');
        } else if (currentUrl.includes('/learner/courses')) {
            $('#instructor_courses').addClass('bg-green-100');
        } else if (currentUrl.includes('/learner/performances')) {
            $('#instructor_performances').addClass('bg-green-100');
        } else if (currentUrl.includes('/learner/settings')) {
            $('#instructor_settings').addClass('bg-green-100');
        }

        // hides the chatbot when learner is answering
        if (currentUrl.includes('/answer')) {
            $('#eBot').addClass('hidden')
            $('#eBot-md').removeClass('md:block')
        }

        $('#sidebar_half_btn').on('click', function() {
            $('#sidebar_full').addClass('hidden');
            $('#sidebar_half').removeClass('hidden');
            $('#sidebar_full, #sidebar_half').css('width', '5%');
        });

        $('#sidebar_full_btn').on('click', function() {
            $('#sidebar_half').addClass('hidden');
            $('#sidebar_full').removeClass('hidden');
            $('#sidebar_full, #sidebar_half').css('width', '23%');
        });
        $('#eBot').on('click', (e)=> {
            e.preventDefault();
            $('#bot-for-sm').toggleClass('hidden')
        //   $('#bot-for-sm').toggleClass('fade-in')
            
        })

        $('#sm-AIClose').on('click', ()=> {
            $('#bot-for-sm').toggleClass('hidden')
        //   $('#bot-for-sm').toggleClass('fade-out')
        })

        $('#eBot-md').on('click', ()=> {
            $('#bot-for-sm').toggleClass('hidden')
        })
    });
</script>
