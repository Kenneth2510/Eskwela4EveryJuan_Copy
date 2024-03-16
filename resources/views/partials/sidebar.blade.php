<section class="fixed z-20 w-auto h-auto overflow-hidden text-black md:w-full lg:w-2/12 md:relative" id="sidebar_full">
    <div class="fixed flex flex-col justify-between w-full overflow-x-hidden overflow-y-auto bg-mainwhitebg md:h-screen md:relative lg:border-r-4 border-darthmouthgreen" id="instructorSidebar">

        <div class="">
            <div class="flex justify-between p-3">
                <div class="flex items-center justify-center hidden md:justify-start md:px-4 " id="logo_half">
                    <a href="{{ url('/admin/dashboard') }}">
                        <i class="mx-2 text-4xl fa-solid fa-book-bookmark"></i>
                    </a>
                </div>
                <div class="" id="logo_full">
                    <a href="{{ url('/admin/dashboard') }}">
                        <span class="self-center text-lg font-semibold text-darthmouthgreen font-semibbold whitespace-nowrap md:text-2xl">
                            Eskwela4EveryJuan
                        </span>
                    </a>
                </div>
                
                <button class="md:hidden" id="nav-btn">
                    <i class="fa-solid fa-bars fa-xl"></i>
                </button>
            </div>
    
            <ul class="flex-row justify-between hidden mt-10 md:flex md:flex-col md:text-base">
                <li id="" class="w-full py-3 rounded-lg dashboardSideBtn instructor_dashboard hover:bg-darthmouthgreen group md:py-4">
                    <a class="flex items-center justify-center md:justify-start md:px-4 " href="{{ url('/admin/dashboard')}}">
                        <svg class="mx-3 duration-500 stroke-black group-hover:stroke-white group-hover:animate-bounce" width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g opacity="0.7">
                        <path d="M21.875 19.7917V12.7781C21.875 12.213 21.76 11.6537 21.5371 11.1344C21.3141 10.6151 20.9879 10.1465 20.5781 9.75729L13.9354 3.44792C13.5482 3.08009 13.0346 2.875 12.5005 2.875C11.9665 2.875 11.4528 3.08009 11.0656 3.44792L4.42187 9.75729C4.01214 10.1465 3.68587 10.6151 3.46292 11.1344C3.23997 11.6537 3.125 12.213 3.125 12.7781V19.7917C3.125 20.3442 3.34449 20.8741 3.73519 21.2648C4.12589 21.6555 4.6558 21.875 5.20833 21.875H19.7917C20.3442 21.875 20.8741 21.6555 21.2648 21.2648C21.6555 20.8741 21.875 20.3442 21.875 19.7917Z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </g>
                        </svg>
                        <h1 class="hidden md:block group-hover:text-white">Dashboard</h1>
                    </a>
                </li>
                
                <li id="" class="w-full py-3 rounded-lg instructor_courses hover:text-white hover:bg-darthmouthgreen group md:py-4">
                    <a class="flex items-center justify-center md:justify-start md:px-4" href="{{ url('/admin/learners') }}">

                        <i class="mx-3 text-xl text-gray-700 duration-500 fa-regular fa-user group-hover:fill-white group-hover:animate-bounce"></i>
    
                        <h1 class="hidden md:block group-hover:text-white">Learners</h1>
                    </a>
                </li>

                <li id="" class="w-full py-3 rounded-lg instructor_courses hover:text-white hover:bg-darthmouthgreen group md:py-4">
                    <a class="flex items-center justify-center md:justify-start md:px-4" href="{{ url('/admin/instructors') }}">
                        
                        <svg class="mx-3 duration-500 group-hover:stroke-white group-hover:animate-bounce" width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M21.2372 5.28849L12.2372 2.28849C12.0832 2.23717 11.9168 2.23717 11.7628 2.28849L2.76281 5.28849C2.61347 5.33828 2.48358 5.43379 2.39155 5.5615C2.29951 5.68921 2.24999 5.84264 2.25 6.00006V13.5001C2.25 13.699 2.32902 13.8897 2.46967 14.0304C2.61032 14.171 2.80109 14.2501 3 14.2501C3.19891 14.2501 3.38968 14.171 3.53033 14.0304C3.67098 13.8897 3.75 13.699 3.75 13.5001V7.04068L6.89906 8.08974C6.0624 9.44143 5.79634 11.0699 6.15931 12.6176C6.52229 14.1653 7.48462 15.5057 8.835 16.3444C7.1475 17.0063 5.68875 18.2035 4.62187 19.8404C4.56639 19.9228 4.52785 20.0155 4.50849 20.113C4.48914 20.2105 4.48936 20.3109 4.50913 20.4083C4.52891 20.5057 4.56785 20.5982 4.62369 20.6804C4.67953 20.7626 4.75116 20.8329 4.83441 20.8872C4.91766 20.9415 5.01087 20.9787 5.10863 20.9967C5.20639 21.0147 5.30674 21.013 5.40386 20.9918C5.50097 20.9707 5.59291 20.9304 5.67433 20.8734C5.75575 20.8164 5.82502 20.7438 5.87813 20.6597C7.29094 18.4922 9.52219 17.2501 12 17.2501C14.4778 17.2501 16.7091 18.4922 18.1219 20.6597C18.2319 20.8232 18.4018 20.9369 18.5949 20.9761C18.788 21.0153 18.9888 20.9769 19.1539 20.8693C19.3189 20.7616 19.435 20.5933 19.4769 20.4008C19.5189 20.2083 19.4834 20.0069 19.3781 19.8404C18.3112 18.2035 16.8469 17.0063 15.165 16.3444C16.5141 15.5057 17.4755 14.1662 17.8384 12.6196C18.2013 11.0731 17.9361 9.44573 17.1009 8.09443L21.2372 6.71631C21.3866 6.66655 21.5165 6.57105 21.6086 6.44334C21.7006 6.31563 21.7502 6.16218 21.7502 6.00474C21.7502 5.8473 21.7006 5.69386 21.6086 5.56615C21.5165 5.43843 21.3866 5.34294 21.2372 5.29318V5.28849ZM16.5 11.2501C16.5002 11.9615 16.3317 12.6628 16.0084 13.2965C15.6851 13.9302 15.2161 14.4782 14.6399 14.8956C14.0638 15.313 13.3969 15.5878 12.694 15.6975C11.9911 15.8072 11.2722 15.7487 10.5962 15.5268C9.92031 15.3049 9.30663 14.9258 8.80555 14.4208C8.30448 13.9158 7.93028 13.2991 7.71367 12.6215C7.49705 11.9438 7.44419 11.2245 7.55942 10.5225C7.67465 9.82043 7.95469 9.15572 8.37656 8.58287L11.7628 9.70787C11.9168 9.75919 12.0832 9.75919 12.2372 9.70787L15.6234 8.58287C16.1932 9.35537 16.5005 10.2901 16.5 11.2501ZM12 8.20974L5.37187 6.00006L12 3.79037L18.6281 6.00006L12 8.20974Z" fill="black"/>
                            </svg>
                            
                        <h1 class="hidden md:block group-hover:text-white">Instructors</h1>
                    </a>
                </li>
                
                <li id="" class="w-full py-3 rounded-lg instructor_courses hover:bg-darthmouthgreen group md:py-4">
                    <a class="flex items-center justify-center md:justify-start md:px-4" href="{{ url('/admin/courses') }}">
                        
                        <svg class="mx-3 duration-500 fill-black group-hover:fill-white group-hover:animate-bounce" width="27" height="27" viewBox="0 0 27 27" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M21.375 6.75H18V5.625C18 5.02826 17.7629 4.45597 17.341 4.03401C16.919 3.61205 16.3467 3.375 15.75 3.375H11.25C10.6533 3.375 10.081 3.61205 9.65901 4.03401C9.23705 4.45597 9 5.02826 9 5.625V6.75H5.625C4.72989 6.75 3.87145 7.10558 3.23851 7.73851C2.60558 8.37145 2.25 9.22989 2.25 10.125V20.25C2.25 21.1451 2.60558 22.0036 3.23851 22.6365C3.87145 23.2694 4.72989 23.625 5.625 23.625H21.375C22.2701 23.625 23.1285 23.2694 23.7615 22.6365C24.3944 22.0036 24.75 21.1451 24.75 20.25V10.125C24.75 9.22989 24.3944 8.37145 23.7615 7.73851C23.1285 7.10558 22.2701 6.75 21.375 6.75ZM11.25 5.625H15.75V6.75H11.25V5.625ZM22.5 20.25C22.5 20.5484 22.3815 20.8345 22.1705 21.0455C21.9595 21.2565 21.6734 21.375 21.375 21.375H5.625C5.32663 21.375 5.04048 21.2565 4.82951 21.0455C4.61853 20.8345 4.5 20.5484 4.5 20.25V13.9388L9.765 15.75C9.88445 15.7662 10.0055 15.7662 10.125 15.75H16.875C16.997 15.7477 17.1181 15.7288 17.235 15.6937L22.5 13.9388V20.25ZM22.5 11.565L16.695 13.5H10.305L4.5 11.565V10.125C4.5 9.82663 4.61853 9.54048 4.82951 9.32951C5.04048 9.11853 5.32663 9 5.625 9H21.375C21.6734 9 21.9595 9.11853 22.1705 9.32951C22.3815 9.54048 22.5 9.82663 22.5 10.125V11.565Z" fill-opacity="0.7"/>
                        </svg>
    
                        <h1 class="hidden md:block group-hover:text-white">Courses</h1>
                    </a>
                </li>

                <li id="" class="w-full py-3 rounded-lg instructor_courses hover:bg-darthmouthgreen group md:py-4">
                    <a class="flex items-center justify-center md:justify-start md:px-4" href="{{ url('/admin/courseManage') }}">
                        
                        <svg 
                        xmlns="http://www.w3.org/2000/svg"
                        class="mx-3 duration-500 group-hover:fill-white group-hover:animate-bounce" width="27" height="27" viewBox="0 0 27 27" fill="none"
                        stroke="#000000"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                      >
                        <path d="M22 10v6M2 10l10-5 10 5-10 5z" />
                        <path d="M6 12v5c3 3 9 3 12 0v-5" />
                      </svg>
    
                        <h1 class="hidden md:block group-hover:text-white">Course Management</h1>
                    </a>
                </li>


                <li id="" class="w-full py-3 rounded-lg instructor_courses hover:text-white hover:bg-darthmouthgreen group md:py-4">
                    <a class="flex items-center justify-center md:justify-start md:px-4" href="{{ url('/admin/course/enrollment') }}">

                        <svg class="mx-3 duration-500 group-hover:fill-white group-hover:animate-bounce" width="27" height="27" viewBox="0 0 27 27" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M14 22V18C14 17.4696 13.7893 16.9609 13.4142 16.5858C13.0391 16.2107 12.5304 16 12 16C11.4696 16 10.9609 16.2107 10.5858 16.5858C10.2107 16.9609 10 17.4696 10 18V22" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M18 10L22 12V20C22 20.5304 21.7893 21.0391 21.4142 21.4142C21.0391 21.7893 20.5304 22 20 22H4C3.46957 22 2.96086 21.7893 2.58579 21.4142C2.21071 21.0391 2 20.5304 2 20V12L6 10M18 5V22M4 6L12 2L20 6M6 5V22" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M12 11C13.1046 11 14 10.1046 14 9C14 7.89543 13.1046 7 12 7C10.8954 7 10 7.89543 10 9C10 10.1046 10.8954 11 12 11Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            

                        <h1 class="hidden md:block group-hover:text-white">Course Enrollment</h1>
                    </a>
                </li>


                <li id="" class="w-full py-3 rounded-lg instructor_courses hover:text-white hover:bg-darthmouthgreen group md:py-4">
                    <a class="flex items-center justify-center md:justify-start md:px-4" href="{{ url('/admin/message') }}">

                        <i class="mx-3 text-xl text-gray-700 duration-500 fa-regular fa-message group-hover:fill-white group-hover:animate-bounce"></i>
    
                        <h1 class="hidden md:block group-hover:text-white">Message</h1>
                    </a>
                </li>
    
                <li id="" class="w-full py-3 rounded-lg instructor_performances hover:bg-darthmouthgreen group md:py-4">
                    <a class="flex items-center justify-center md:justify-start md:px-4" href="{{ url("/admin/performance") }}">
                        
                        <svg class="mx-3 duration-500 fill-black group-hover:fill-white group-hover:animate-bounce" width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12.5002 6.24998C12.2239 6.24998 11.9589 6.35973 11.7636 6.55508C11.5682 6.75043 11.4585 7.01538 11.4585 7.29165V17.7083C11.4585 17.9846 11.5682 18.2495 11.7636 18.4449C11.9589 18.6402 12.2239 18.75 12.5002 18.75C12.7764 18.75 13.0414 18.6402 13.2367 18.4449C13.4321 18.2495 13.5418 17.9846 13.5418 17.7083V7.29165C13.5418 7.01538 13.4321 6.75043 13.2367 6.55508C13.0414 6.35973 12.7764 6.24998 12.5002 6.24998ZM7.29183 12.5C7.01556 12.5 6.75061 12.6097 6.55526 12.8051C6.35991 13.0004 6.25016 13.2654 6.25016 13.5416V17.7083C6.25016 17.9846 6.35991 18.2495 6.55526 18.4449C6.75061 18.6402 7.01556 18.75 7.29183 18.75C7.5681 18.75 7.83305 18.6402 8.0284 18.4449C8.22375 18.2495 8.3335 17.9846 8.3335 17.7083V13.5416C8.3335 13.2654 8.22375 13.0004 8.0284 12.8051C7.83305 12.6097 7.5681 12.5 7.29183 12.5ZM17.7085 10.4166C17.4322 10.4166 17.1673 10.5264 16.9719 10.7217C16.7766 10.9171 16.6668 11.182 16.6668 11.4583V17.7083C16.6668 17.9846 16.7766 18.2495 16.9719 18.4449C17.1673 18.6402 17.4322 18.75 17.7085 18.75C17.9848 18.75 18.2497 18.6402 18.4451 18.4449C18.6404 18.2495 18.7502 17.9846 18.7502 17.7083V11.4583C18.7502 11.182 18.6404 10.9171 18.4451 10.7217C18.2497 10.5264 17.9848 10.4166 17.7085 10.4166ZM19.7918 2.08331H5.2085C4.37969 2.08331 3.58484 2.41255 2.99879 2.9986C2.41274 3.58466 2.0835 4.37951 2.0835 5.20831V19.7916C2.0835 20.6204 2.41274 21.4153 2.99879 22.0014C3.58484 22.5874 4.37969 22.9166 5.2085 22.9166H19.7918C20.6206 22.9166 21.4155 22.5874 22.0015 22.0014C22.5876 21.4153 22.9168 20.6204 22.9168 19.7916V5.20831C22.9168 4.37951 22.5876 3.58466 22.0015 2.9986C21.4155 2.41255 20.6206 2.08331 19.7918 2.08331ZM20.8335 19.7916C20.8335 20.0679 20.7238 20.3329 20.5284 20.5282C20.3331 20.7236 20.0681 20.8333 19.7918 20.8333H5.2085C4.93223 20.8333 4.66728 20.7236 4.47193 20.5282C4.27658 20.3329 4.16683 20.0679 4.16683 19.7916V5.20831C4.16683 4.93205 4.27658 4.66709 4.47193 4.47174C4.66728 4.27639 4.93223 4.16665 5.2085 4.16665H19.7918C20.0681 4.16665 20.3331 4.27639 20.5284 4.47174C20.7238 4.66709 20.8335 4.93205 20.8335 5.20831V19.7916Z" fill-opacity="0.75"/>
                        </svg>
    
                        <h1 class="hidden md:block group-hover:text-white">Performance</h1>
                    </a>
                </li>


                <li id="" class="w-full py-3 rounded-lg instructor_performances hover:bg-darthmouthgreen group md:py-4">
                    <a class="flex items-center justify-center md:justify-start md:px-4" href="{{ url("/admin/report") }}">
                        
                        <svg class="mx-3 duration-500 fill-black group-hover:fill-white group-hover:animate-bounce" width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M17 6.5L10.625 0.5H2.125C1.56141 0.5 1.02091 0.710714 0.622398 1.08579C0.223883 1.46086 0 1.96957 0 2.5V18.5C0 19.0304 0.223883 19.5391 0.622398 19.9142C1.02091 20.2893 1.56141 20.5 2.125 20.5H14.875C15.4386 20.5 15.9791 20.2893 16.3776 19.9142C16.7761 19.5391 17 19.0304 17 18.5V6.5ZM5.3125 17.5H3.1875V8.5H5.3125V17.5ZM9.5625 17.5H7.4375V11.5H9.5625V17.5ZM13.8125 17.5H11.6875V14.5H13.8125V17.5ZM10.625 7.5H9.5625V2.5L14.875 7.5H10.625Z" fill="black"/>
                            </svg>

    
                        <h1 class="hidden md:block group-hover:text-white">Reports</h1>
                    </a>
                </li>


                    
                @if ($admin->role === 'SUPER_ADMIN' || $admin->role === 'IT_DEPT')
                <li id="" class="w-full py-3 rounded-lg instructor_courses hover:text-white hover:bg-darthmouthgreen group md:py-4">
                    <a class="flex items-center justify-center md:justify-start md:px-4" href="{{ url('/admin/admins') }}">

                        <svg class="mx-3 duration-500 group-hover:fill-white group-hover:stroke-white group-hover:animate-bounce" width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 14V16C10.4087 16 8.88258 16.6321 7.75736 17.7574C6.63214 18.8826 6 20.4087 6 22H4C4 19.8783 4.84285 17.8434 6.34315 16.3431C7.84344 14.8429 9.87827 14 12 14ZM12 13C8.685 13 6 10.315 6 7C6 3.685 8.685 1 12 1C15.315 1 18 3.685 18 7C18 10.315 15.315 13 12 13ZM12 11C14.21 11 16 9.21 16 7C16 4.79 14.21 3 12 3C9.79 3 8 4.79 8 7C8 9.21 9.79 11 12 11ZM21 17H22V22H14V17H15V16C15 15.2044 15.3161 14.4413 15.8787 13.8787C16.4413 13.3161 17.2044 13 18 13C18.7956 13 19.5587 13.3161 20.1213 13.8787C20.6839 14.4413 21 15.2044 21 16V17ZM19 17V16C19 15.7348 18.8946 15.4804 18.7071 15.2929C18.5196 15.1054 18.2652 15 18 15C17.7348 15 17.4804 15.1054 17.2929 15.2929C17.1054 15.4804 17 15.7348 17 16V17H19Z" fill="black"/>
                            </svg>
                            
                        <h1 class="hidden md:block group-hover:text-white">Admin Management</h1>
                    </a>
                </li>
                @endif


                <li id="" class="w-full py-3 rounded-lg instructor_settings hover:bg-darthmouthgreen group md:py-4">
                    <a class="flex items-center justify-center md:justify-start md:px-4" href="{{ url('/admin/profile') }}">
                        
                        <svg class="mx-3 duration-500 fill-black group-hover:fill-white group-hover:animate-bounce" width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M11.6656 2.69475C11.9209 2.55301 12.208 2.47864 12.5 2.47864C12.792 2.47864 13.0791 2.55301 13.3344 2.69475L20.8344 6.86142C21.3802 7.16454 21.7188 7.73954 21.7188 8.36454V16.6354C21.7188 17.2604 21.3802 17.8354 20.8344 18.1385L13.3344 22.3052C13.0791 22.4469 12.792 22.5213 12.5 22.5213C12.208 22.5213 11.9209 22.4469 11.6656 22.3052L4.16562 18.1385C3.89752 17.9896 3.67414 17.7717 3.51863 17.5074C3.36312 17.2431 3.28116 16.942 3.28125 16.6354V8.36454C3.28125 7.73954 3.61979 7.16454 4.16562 6.86142L11.6656 2.69475ZM12.576 4.06142C12.5528 4.04846 12.5266 4.04167 12.5 4.04167C12.4734 4.04167 12.4472 4.04846 12.424 4.06142L4.92396 8.22808C4.8998 8.24154 4.87965 8.26117 4.86556 8.28496C4.85147 8.30875 4.84394 8.33585 4.84375 8.3635V16.6354C4.84375 16.6927 4.875 16.7448 4.92396 16.7729L12.424 20.9395C12.4472 20.9525 12.4734 20.9593 12.5 20.9593C12.5266 20.9593 12.5528 20.9525 12.576 20.9395L20.076 16.7729C20.1005 16.7592 20.1209 16.7393 20.135 16.7151C20.1491 16.6909 20.1564 16.6634 20.1562 16.6354V8.36454C20.1562 8.33672 20.1488 8.30939 20.1347 8.28541C20.1206 8.26142 20.1004 8.24163 20.076 8.22808L12.576 4.06142Z" fill-opacity="0.75"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M7.55225 12.4999C7.55225 11.1876 8.07354 9.92913 9.00146 9.00121C9.92937 8.0733 11.1879 7.552 12.5002 7.552C13.8124 7.552 15.071 8.0733 15.9989 9.00121C16.9268 9.92913 17.4481 11.1876 17.4481 12.4999C17.4481 13.8122 16.9268 15.0707 15.9989 15.9986C15.071 16.9265 13.8124 17.4478 12.5002 17.4478C11.1879 17.4478 9.92937 16.9265 9.00146 15.9986C8.07354 15.0707 7.55225 13.8122 7.55225 12.4999ZM12.5002 9.1145C11.6023 9.1145 10.7412 9.47118 10.1063 10.1061C9.47142 10.741 9.11475 11.6021 9.11475 12.4999C9.11475 13.3978 9.47142 14.2589 10.1063 14.8938C10.7412 15.5287 11.6023 15.8853 12.5002 15.8853C13.398 15.8853 14.2591 15.5287 14.894 14.8938C15.5289 14.2589 15.8856 13.3978 15.8856 12.4999C15.8856 11.6021 15.5289 10.741 14.894 10.1061C14.2591 9.47118 13.398 9.1145 12.5002 9.1145Z" fill-opacity="0.75"/>
                        </svg>
    
                        <h1 class="hidden md:block group-hover:text-white">Settings</h1>
                    </a>
                </li>
                
                
                <form class="hidden mx-4 mt-10 rounded-lg bg-darthmouthgreen md:block group hover:bg-white hover:border-2 hover:border-darthmouthgreen" action="{{ url('/admin/logout') }}" method="POST"> 
                    @csrf
                    <button type="submit" class="flex flex-row items-center justify-center w-full h-12 group-hover:cursor-pointer" >
                        <svg class="fill-white group-hover:fill-black" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h280v80H200v560h280v80H200Zm440-160-55-58 102-102H360v-80h327L585-622l55-58 200 200-200 200Z"/></svg>
                        <h1 class="px-5 text-white group-hover:text-black">Logout</h1>
                    </button>
                </form>
                
            </ul>
        </div>
        

        <div class="flex-row items-center justify-center hidden w-full md:flex hover:cursor-pointer" id="sidebar_half_btn">
            <i class="text-3xl fa-regular fa-square-caret-left"></i>
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
                <li id="" class="w-full py-3 rounded-lg dashboardSideBtn instructor_dashboard hover:bg-darthmouthgreen group md:py-4">
                    <a class="flex items-center justify-center md:justify-start md:px-4 " href="{{ url('/admin/dashboard')}}">
                        <svg class="mx-3 duration-500 stroke-black group-hover:stroke-white group-hover:animate-bounce" width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g opacity="0.7">
                        <path d="M21.875 19.7917V12.7781C21.875 12.213 21.76 11.6537 21.5371 11.1344C21.3141 10.6151 20.9879 10.1465 20.5781 9.75729L13.9354 3.44792C13.5482 3.08009 13.0346 2.875 12.5005 2.875C11.9665 2.875 11.4528 3.08009 11.0656 3.44792L4.42187 9.75729C4.01214 10.1465 3.68587 10.6151 3.46292 11.1344C3.23997 11.6537 3.125 12.213 3.125 12.7781V19.7917C3.125 20.3442 3.34449 20.8741 3.73519 21.2648C4.12589 21.6555 4.6558 21.875 5.20833 21.875H19.7917C20.3442 21.875 20.8741 21.6555 21.2648 21.2648C21.6555 20.8741 21.875 20.3442 21.875 19.7917Z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </g>
                        </svg>
                        {{-- <h1 class="hidden md:block group-hover:text-white">Dashboard</h1> --}}
                    </a>
                </li>
                
                <li id="" class="w-full py-3 rounded-lg instructor_courses hover:text-white hover:bg-darthmouthgreen group md:py-4">
                    <a class="flex items-center justify-center md:justify-start md:px-4" href="{{ url('/admin/learners') }}">

                        <i class="mx-3 text-xl text-gray-700 duration-500 fa-regular fa-user group-hover:fill-white group-hover:animate-bounce"></i>
    
                        {{-- <h1 class="hidden md:block group-hover:text-white">Learners</h1> --}}
                    </a>
                </li>

                <li id="" class="w-full py-3 rounded-lg instructor_courses hover:text-white hover:bg-darthmouthgreen group md:py-4">
                    <a class="flex items-center justify-center md:justify-start md:px-4" href="{{ url('/admin/instructors') }}">
                        
                        <svg class="mx-3 duration-500 group-hover:stroke-white group-hover:animate-bounce" width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M21.2372 5.28849L12.2372 2.28849C12.0832 2.23717 11.9168 2.23717 11.7628 2.28849L2.76281 5.28849C2.61347 5.33828 2.48358 5.43379 2.39155 5.5615C2.29951 5.68921 2.24999 5.84264 2.25 6.00006V13.5001C2.25 13.699 2.32902 13.8897 2.46967 14.0304C2.61032 14.171 2.80109 14.2501 3 14.2501C3.19891 14.2501 3.38968 14.171 3.53033 14.0304C3.67098 13.8897 3.75 13.699 3.75 13.5001V7.04068L6.89906 8.08974C6.0624 9.44143 5.79634 11.0699 6.15931 12.6176C6.52229 14.1653 7.48462 15.5057 8.835 16.3444C7.1475 17.0063 5.68875 18.2035 4.62187 19.8404C4.56639 19.9228 4.52785 20.0155 4.50849 20.113C4.48914 20.2105 4.48936 20.3109 4.50913 20.4083C4.52891 20.5057 4.56785 20.5982 4.62369 20.6804C4.67953 20.7626 4.75116 20.8329 4.83441 20.8872C4.91766 20.9415 5.01087 20.9787 5.10863 20.9967C5.20639 21.0147 5.30674 21.013 5.40386 20.9918C5.50097 20.9707 5.59291 20.9304 5.67433 20.8734C5.75575 20.8164 5.82502 20.7438 5.87813 20.6597C7.29094 18.4922 9.52219 17.2501 12 17.2501C14.4778 17.2501 16.7091 18.4922 18.1219 20.6597C18.2319 20.8232 18.4018 20.9369 18.5949 20.9761C18.788 21.0153 18.9888 20.9769 19.1539 20.8693C19.3189 20.7616 19.435 20.5933 19.4769 20.4008C19.5189 20.2083 19.4834 20.0069 19.3781 19.8404C18.3112 18.2035 16.8469 17.0063 15.165 16.3444C16.5141 15.5057 17.4755 14.1662 17.8384 12.6196C18.2013 11.0731 17.9361 9.44573 17.1009 8.09443L21.2372 6.71631C21.3866 6.66655 21.5165 6.57105 21.6086 6.44334C21.7006 6.31563 21.7502 6.16218 21.7502 6.00474C21.7502 5.8473 21.7006 5.69386 21.6086 5.56615C21.5165 5.43843 21.3866 5.34294 21.2372 5.29318V5.28849ZM16.5 11.2501C16.5002 11.9615 16.3317 12.6628 16.0084 13.2965C15.6851 13.9302 15.2161 14.4782 14.6399 14.8956C14.0638 15.313 13.3969 15.5878 12.694 15.6975C11.9911 15.8072 11.2722 15.7487 10.5962 15.5268C9.92031 15.3049 9.30663 14.9258 8.80555 14.4208C8.30448 13.9158 7.93028 13.2991 7.71367 12.6215C7.49705 11.9438 7.44419 11.2245 7.55942 10.5225C7.67465 9.82043 7.95469 9.15572 8.37656 8.58287L11.7628 9.70787C11.9168 9.75919 12.0832 9.75919 12.2372 9.70787L15.6234 8.58287C16.1932 9.35537 16.5005 10.2901 16.5 11.2501ZM12 8.20974L5.37187 6.00006L12 3.79037L18.6281 6.00006L12 8.20974Z" fill="black"/>
                            </svg>
                            
                        {{-- <h1 class="hidden md:block group-hover:text-white">Instructors</h1> --}}
                    </a>
                </li>
                
                <li id="" class="w-full py-3 rounded-lg instructor_courses hover:bg-darthmouthgreen group md:py-4">
                    <a class="flex items-center justify-center md:justify-start md:px-4" href="{{ url('/admin/courses') }}">
                        
                        <svg class="mx-3 duration-500 fill-black group-hover:fill-white group-hover:animate-bounce" width="27" height="27" viewBox="0 0 27 27" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M21.375 6.75H18V5.625C18 5.02826 17.7629 4.45597 17.341 4.03401C16.919 3.61205 16.3467 3.375 15.75 3.375H11.25C10.6533 3.375 10.081 3.61205 9.65901 4.03401C9.23705 4.45597 9 5.02826 9 5.625V6.75H5.625C4.72989 6.75 3.87145 7.10558 3.23851 7.73851C2.60558 8.37145 2.25 9.22989 2.25 10.125V20.25C2.25 21.1451 2.60558 22.0036 3.23851 22.6365C3.87145 23.2694 4.72989 23.625 5.625 23.625H21.375C22.2701 23.625 23.1285 23.2694 23.7615 22.6365C24.3944 22.0036 24.75 21.1451 24.75 20.25V10.125C24.75 9.22989 24.3944 8.37145 23.7615 7.73851C23.1285 7.10558 22.2701 6.75 21.375 6.75ZM11.25 5.625H15.75V6.75H11.25V5.625ZM22.5 20.25C22.5 20.5484 22.3815 20.8345 22.1705 21.0455C21.9595 21.2565 21.6734 21.375 21.375 21.375H5.625C5.32663 21.375 5.04048 21.2565 4.82951 21.0455C4.61853 20.8345 4.5 20.5484 4.5 20.25V13.9388L9.765 15.75C9.88445 15.7662 10.0055 15.7662 10.125 15.75H16.875C16.997 15.7477 17.1181 15.7288 17.235 15.6937L22.5 13.9388V20.25ZM22.5 11.565L16.695 13.5H10.305L4.5 11.565V10.125C4.5 9.82663 4.61853 9.54048 4.82951 9.32951C5.04048 9.11853 5.32663 9 5.625 9H21.375C21.6734 9 21.9595 9.11853 22.1705 9.32951C22.3815 9.54048 22.5 9.82663 22.5 10.125V11.565Z" fill-opacity="0.7"/>
                        </svg>
    
                        {{-- <h1 class="hidden md:block group-hover:text-white">Course Management</h1> --}}
                    </a>
                </li>

                <li id="" class="w-full py-3 rounded-lg instructor_courses hover:bg-darthmouthgreen group md:py-4">
                    <a class="flex items-center justify-center md:justify-start md:px-4" href="{{ url('/admin/courseManage') }}">
                        
                        <svg 
                        xmlns="http://www.w3.org/2000/svg"
                        class="mx-3 duration-500 group-hover:fill-white group-hover:animate-bounce" width="27" height="27" viewBox="0 0 27 27" fill="none"
                        stroke="#000000"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                      >
                        <path d="M22 10v6M2 10l10-5 10 5-10 5z" />
                        <path d="M6 12v5c3 3 9 3 12 0v-5" />
                      </svg>
    
                        {{-- <h1 class="hidden md:block group-hover:text-white">Course Management</h1> --}}
                    </a>
                </li>


                <li id="" class="w-full py-3 rounded-lg instructor_courses hover:text-white hover:bg-darthmouthgreen group md:py-4">
                    <a class="flex items-center justify-center md:justify-start md:px-4" href="{{ url('/admin/course/enrollment') }}">

                        <svg class="mx-3 duration-500 group-hover:fill-white group-hover:animate-bounce" width="27" height="27" viewBox="0 0 27 27" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M14 22V18C14 17.4696 13.7893 16.9609 13.4142 16.5858C13.0391 16.2107 12.5304 16 12 16C11.4696 16 10.9609 16.2107 10.5858 16.5858C10.2107 16.9609 10 17.4696 10 18V22" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M18 10L22 12V20C22 20.5304 21.7893 21.0391 21.4142 21.4142C21.0391 21.7893 20.5304 22 20 22H4C3.46957 22 2.96086 21.7893 2.58579 21.4142C2.21071 21.0391 2 20.5304 2 20V12L6 10M18 5V22M4 6L12 2L20 6M6 5V22" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M12 11C13.1046 11 14 10.1046 14 9C14 7.89543 13.1046 7 12 7C10.8954 7 10 7.89543 10 9C10 10.1046 10.8954 11 12 11Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            

                        {{-- <h1 class="hidden md:block group-hover:text-white">Course Enrollment</h1> --}}
                    </a>
                </li>


                <li id="" class="w-full py-3 rounded-lg instructor_courses hover:text-white hover:bg-darthmouthgreen group md:py-4">
                    <a class="flex items-center justify-center md:justify-start md:px-4" href="{{ url('/admin/message') }}">

                        <i class="mx-3 text-xl text-gray-700 duration-500 fa-regular fa-message group-hover:fill-white group-hover:animate-bounce"></i>
    
                        {{-- <h1 class="hidden md:block group-hover:text-white">Message</h1> --}}
                    </a>
                </li>
    
                <li id="" class="w-full py-3 rounded-lg instructor_performances hover:bg-darthmouthgreen group md:py-4">
                    <a class="flex items-center justify-center md:justify-start md:px-4" href="{{ url("/admin/performance") }}">
                        
                        <svg class="mx-3 duration-500 fill-black group-hover:fill-white group-hover:animate-bounce" width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12.5002 6.24998C12.2239 6.24998 11.9589 6.35973 11.7636 6.55508C11.5682 6.75043 11.4585 7.01538 11.4585 7.29165V17.7083C11.4585 17.9846 11.5682 18.2495 11.7636 18.4449C11.9589 18.6402 12.2239 18.75 12.5002 18.75C12.7764 18.75 13.0414 18.6402 13.2367 18.4449C13.4321 18.2495 13.5418 17.9846 13.5418 17.7083V7.29165C13.5418 7.01538 13.4321 6.75043 13.2367 6.55508C13.0414 6.35973 12.7764 6.24998 12.5002 6.24998ZM7.29183 12.5C7.01556 12.5 6.75061 12.6097 6.55526 12.8051C6.35991 13.0004 6.25016 13.2654 6.25016 13.5416V17.7083C6.25016 17.9846 6.35991 18.2495 6.55526 18.4449C6.75061 18.6402 7.01556 18.75 7.29183 18.75C7.5681 18.75 7.83305 18.6402 8.0284 18.4449C8.22375 18.2495 8.3335 17.9846 8.3335 17.7083V13.5416C8.3335 13.2654 8.22375 13.0004 8.0284 12.8051C7.83305 12.6097 7.5681 12.5 7.29183 12.5ZM17.7085 10.4166C17.4322 10.4166 17.1673 10.5264 16.9719 10.7217C16.7766 10.9171 16.6668 11.182 16.6668 11.4583V17.7083C16.6668 17.9846 16.7766 18.2495 16.9719 18.4449C17.1673 18.6402 17.4322 18.75 17.7085 18.75C17.9848 18.75 18.2497 18.6402 18.4451 18.4449C18.6404 18.2495 18.7502 17.9846 18.7502 17.7083V11.4583C18.7502 11.182 18.6404 10.9171 18.4451 10.7217C18.2497 10.5264 17.9848 10.4166 17.7085 10.4166ZM19.7918 2.08331H5.2085C4.37969 2.08331 3.58484 2.41255 2.99879 2.9986C2.41274 3.58466 2.0835 4.37951 2.0835 5.20831V19.7916C2.0835 20.6204 2.41274 21.4153 2.99879 22.0014C3.58484 22.5874 4.37969 22.9166 5.2085 22.9166H19.7918C20.6206 22.9166 21.4155 22.5874 22.0015 22.0014C22.5876 21.4153 22.9168 20.6204 22.9168 19.7916V5.20831C22.9168 4.37951 22.5876 3.58466 22.0015 2.9986C21.4155 2.41255 20.6206 2.08331 19.7918 2.08331ZM20.8335 19.7916C20.8335 20.0679 20.7238 20.3329 20.5284 20.5282C20.3331 20.7236 20.0681 20.8333 19.7918 20.8333H5.2085C4.93223 20.8333 4.66728 20.7236 4.47193 20.5282C4.27658 20.3329 4.16683 20.0679 4.16683 19.7916V5.20831C4.16683 4.93205 4.27658 4.66709 4.47193 4.47174C4.66728 4.27639 4.93223 4.16665 5.2085 4.16665H19.7918C20.0681 4.16665 20.3331 4.27639 20.5284 4.47174C20.7238 4.66709 20.8335 4.93205 20.8335 5.20831V19.7916Z" fill-opacity="0.75"/>
                        </svg>
    
                        {{-- <h1 class="hidden md:block group-hover:text-white">Performance</h1> --}}
                    </a>
                </li>

                <li id="" class="w-full py-3 rounded-lg instructor_performances hover:bg-darthmouthgreen group md:py-4">
                    <a class="flex items-center justify-center md:justify-start md:px-4" href="{{ url("/admin/report") }}">
                        
                        <svg class="mx-3 duration-500 fill-black group-hover:fill-white group-hover:animate-bounce" width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M17 6.5L10.625 0.5H2.125C1.56141 0.5 1.02091 0.710714 0.622398 1.08579C0.223883 1.46086 0 1.96957 0 2.5V18.5C0 19.0304 0.223883 19.5391 0.622398 19.9142C1.02091 20.2893 1.56141 20.5 2.125 20.5H14.875C15.4386 20.5 15.9791 20.2893 16.3776 19.9142C16.7761 19.5391 17 19.0304 17 18.5V6.5ZM5.3125 17.5H3.1875V8.5H5.3125V17.5ZM9.5625 17.5H7.4375V11.5H9.5625V17.5ZM13.8125 17.5H11.6875V14.5H13.8125V17.5ZM10.625 7.5H9.5625V2.5L14.875 7.5H10.625Z" fill="black"/>
                            </svg>

    
                        {{-- <h1 class="hidden md:block group-hover:text-white">Reports</h1> --}}
                    </a>
                </li>


                @if ($admin->role === 'SUPER_ADMIN' || $admin->role === 'IT_DEPT')
                <li id="" class="w-full py-3 rounded-lg instructor_courses hover:text-white hover:bg-darthmouthgreen group md:py-4">
                    <a class="flex items-center justify-center md:justify-start md:px-4" href="{{ url('/admin/admins') }}">

                        <svg class="mx-3 duration-500 group-hover:fill-white group-hover:stroke-white group-hover:animate-bounce" width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 14V16C10.4087 16 8.88258 16.6321 7.75736 17.7574C6.63214 18.8826 6 20.4087 6 22H4C4 19.8783 4.84285 17.8434 6.34315 16.3431C7.84344 14.8429 9.87827 14 12 14ZM12 13C8.685 13 6 10.315 6 7C6 3.685 8.685 1 12 1C15.315 1 18 3.685 18 7C18 10.315 15.315 13 12 13ZM12 11C14.21 11 16 9.21 16 7C16 4.79 14.21 3 12 3C9.79 3 8 4.79 8 7C8 9.21 9.79 11 12 11ZM21 17H22V22H14V17H15V16C15 15.2044 15.3161 14.4413 15.8787 13.8787C16.4413 13.3161 17.2044 13 18 13C18.7956 13 19.5587 13.3161 20.1213 13.8787C20.6839 14.4413 21 15.2044 21 16V17ZM19 17V16C19 15.7348 18.8946 15.4804 18.7071 15.2929C18.5196 15.1054 18.2652 15 18 15C17.7348 15 17.4804 15.1054 17.2929 15.2929C17.1054 15.4804 17 15.7348 17 16V17H19Z" fill="black"/>
                            </svg>
                            
                        {{-- <h1 class="hidden md:block group-hover:text-white">Admin Management</h1> --}}
                    </a>
                </li>
                @endif
                <li id="" class="w-full py-3 rounded-lg instructor_settings hover:bg-darthmouthgreen group md:py-4">
                    <a class="flex items-center justify-center md:justify-start md:px-4" href="{{ url('/admin/profile') }}">
                        
                        <svg class="mx-3 duration-500 fill-black group-hover:fill-white group-hover:animate-bounce" width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M11.6656 2.69475C11.9209 2.55301 12.208 2.47864 12.5 2.47864C12.792 2.47864 13.0791 2.55301 13.3344 2.69475L20.8344 6.86142C21.3802 7.16454 21.7188 7.73954 21.7188 8.36454V16.6354C21.7188 17.2604 21.3802 17.8354 20.8344 18.1385L13.3344 22.3052C13.0791 22.4469 12.792 22.5213 12.5 22.5213C12.208 22.5213 11.9209 22.4469 11.6656 22.3052L4.16562 18.1385C3.89752 17.9896 3.67414 17.7717 3.51863 17.5074C3.36312 17.2431 3.28116 16.942 3.28125 16.6354V8.36454C3.28125 7.73954 3.61979 7.16454 4.16562 6.86142L11.6656 2.69475ZM12.576 4.06142C12.5528 4.04846 12.5266 4.04167 12.5 4.04167C12.4734 4.04167 12.4472 4.04846 12.424 4.06142L4.92396 8.22808C4.8998 8.24154 4.87965 8.26117 4.86556 8.28496C4.85147 8.30875 4.84394 8.33585 4.84375 8.3635V16.6354C4.84375 16.6927 4.875 16.7448 4.92396 16.7729L12.424 20.9395C12.4472 20.9525 12.4734 20.9593 12.5 20.9593C12.5266 20.9593 12.5528 20.9525 12.576 20.9395L20.076 16.7729C20.1005 16.7592 20.1209 16.7393 20.135 16.7151C20.1491 16.6909 20.1564 16.6634 20.1562 16.6354V8.36454C20.1562 8.33672 20.1488 8.30939 20.1347 8.28541C20.1206 8.26142 20.1004 8.24163 20.076 8.22808L12.576 4.06142Z" fill-opacity="0.75"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M7.55225 12.4999C7.55225 11.1876 8.07354 9.92913 9.00146 9.00121C9.92937 8.0733 11.1879 7.552 12.5002 7.552C13.8124 7.552 15.071 8.0733 15.9989 9.00121C16.9268 9.92913 17.4481 11.1876 17.4481 12.4999C17.4481 13.8122 16.9268 15.0707 15.9989 15.9986C15.071 16.9265 13.8124 17.4478 12.5002 17.4478C11.1879 17.4478 9.92937 16.9265 9.00146 15.9986C8.07354 15.0707 7.55225 13.8122 7.55225 12.4999ZM12.5002 9.1145C11.6023 9.1145 10.7412 9.47118 10.1063 10.1061C9.47142 10.741 9.11475 11.6021 9.11475 12.4999C9.11475 13.3978 9.47142 14.2589 10.1063 14.8938C10.7412 15.5287 11.6023 15.8853 12.5002 15.8853C13.398 15.8853 14.2591 15.5287 14.894 14.8938C15.5289 14.2589 15.8856 13.3978 15.8856 12.4999C15.8856 11.6021 15.5289 10.741 14.894 10.1061C14.2591 9.47118 13.398 9.1145 12.5002 9.1145Z" fill-opacity="0.75"/>
                        </svg>
    
                        {{-- <h1 class="hidden md:block group-hover:text-white">Settings</h1> --}}
                    </a>
                </li>
                
                
                <form class="hidden mx-4 mt-10 rounded-lg bg-darthmouthgreen md:block group hover:bg-white hover:border-2 hover:border-darthmouthgreen" action="{{ url('/admin/logout') }}" method="POST"> 
                    @csrf
                    <button type="submit" class="flex flex-row items-center justify-center w-full h-12 group-hover:cursor-pointer" >
                        <svg class="fill-white group-hover:fill-black" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h280v80H200v560h280v80H200Zm440-160-55-58 102-102H360v-80h327L585-622l55-58 200 200-200 200Z"/></svg>
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

<section class="fixed z-50 hidden w-full h-screen text-black bg-white bg-opacity-30" id="admin-sm-sidebar">
    <div class="float-right w-3/4 h-screen py-4 bg-white">
        <div class="relative p-3 text-center">
            <button class="absolute top-0 right-0 px-3" id="close-admin-sidebar">
                <i class="fa-solid fa-xmark"></i>
            </button>
            <h1 class="text-xl font-bold">Navigation</h1>
        </div>

        {{-- contents --}}
        <div class="w-full py-4">
            <ul class="flex flex-col justify-center divide-y-2">
                <li class="p-3 hover:bg-darthmouthgreen hover:bg-opacity-75 hover:text-white">
                    <a href="{{ url('/admin/dashboard')}}" class="flex items-center">
                        <svg class="mx-3 duration-500 stroke-black group-hover:stroke-white group-hover:animate-bounce" width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g opacity="0.7">
                        <path d="M21.875 19.7917V12.7781C21.875 12.213 21.76 11.6537 21.5371 11.1344C21.3141 10.6151 20.9879 10.1465 20.5781 9.75729L13.9354 3.44792C13.5482 3.08009 13.0346 2.875 12.5005 2.875C11.9665 2.875 11.4528 3.08009 11.0656 3.44792L4.42187 9.75729C4.01214 10.1465 3.68587 10.6151 3.46292 11.1344C3.23997 11.6537 3.125 12.213 3.125 12.7781V19.7917C3.125 20.3442 3.34449 20.8741 3.73519 21.2648C4.12589 21.6555 4.6558 21.875 5.20833 21.875H19.7917C20.3442 21.875 20.8741 21.6555 21.2648 21.2648C21.6555 20.8741 21.875 20.3442 21.875 19.7917Z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </g>
                        </svg>
                        <h1>Dashboard</h1>
                    </a>
                </li>
                <li class="p-3 hover:bg-darthmouthgreen hover:bg-opacity-75 hover:text-white">
                    <a href="{{ url('/admin/learners')}}" class="flex items-center">
                        <i class="mx-3 text-xl text-gray-700 duration-500 fa-regular fa-user group-hover:fill-white group-hover:animate-bounce"></i>
                        <h1>Learner</h1>
                    </a>
                </li>
                <li class="p-3 hover:bg-darthmouthgreen hover:bg-opacity-75 hover:text-white">
                    <a href="{{ url('/admin/instructors')}}" class="flex items-center">
                        <svg class="mx-3 duration-500 group-hover:stroke-white group-hover:animate-bounce" width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M21.2372 5.28849L12.2372 2.28849C12.0832 2.23717 11.9168 2.23717 11.7628 2.28849L2.76281 5.28849C2.61347 5.33828 2.48358 5.43379 2.39155 5.5615C2.29951 5.68921 2.24999 5.84264 2.25 6.00006V13.5001C2.25 13.699 2.32902 13.8897 2.46967 14.0304C2.61032 14.171 2.80109 14.2501 3 14.2501C3.19891 14.2501 3.38968 14.171 3.53033 14.0304C3.67098 13.8897 3.75 13.699 3.75 13.5001V7.04068L6.89906 8.08974C6.0624 9.44143 5.79634 11.0699 6.15931 12.6176C6.52229 14.1653 7.48462 15.5057 8.835 16.3444C7.1475 17.0063 5.68875 18.2035 4.62187 19.8404C4.56639 19.9228 4.52785 20.0155 4.50849 20.113C4.48914 20.2105 4.48936 20.3109 4.50913 20.4083C4.52891 20.5057 4.56785 20.5982 4.62369 20.6804C4.67953 20.7626 4.75116 20.8329 4.83441 20.8872C4.91766 20.9415 5.01087 20.9787 5.10863 20.9967C5.20639 21.0147 5.30674 21.013 5.40386 20.9918C5.50097 20.9707 5.59291 20.9304 5.67433 20.8734C5.75575 20.8164 5.82502 20.7438 5.87813 20.6597C7.29094 18.4922 9.52219 17.2501 12 17.2501C14.4778 17.2501 16.7091 18.4922 18.1219 20.6597C18.2319 20.8232 18.4018 20.9369 18.5949 20.9761C18.788 21.0153 18.9888 20.9769 19.1539 20.8693C19.3189 20.7616 19.435 20.5933 19.4769 20.4008C19.5189 20.2083 19.4834 20.0069 19.3781 19.8404C18.3112 18.2035 16.8469 17.0063 15.165 16.3444C16.5141 15.5057 17.4755 14.1662 17.8384 12.6196C18.2013 11.0731 17.9361 9.44573 17.1009 8.09443L21.2372 6.71631C21.3866 6.66655 21.5165 6.57105 21.6086 6.44334C21.7006 6.31563 21.7502 6.16218 21.7502 6.00474C21.7502 5.8473 21.7006 5.69386 21.6086 5.56615C21.5165 5.43843 21.3866 5.34294 21.2372 5.29318V5.28849ZM16.5 11.2501C16.5002 11.9615 16.3317 12.6628 16.0084 13.2965C15.6851 13.9302 15.2161 14.4782 14.6399 14.8956C14.0638 15.313 13.3969 15.5878 12.694 15.6975C11.9911 15.8072 11.2722 15.7487 10.5962 15.5268C9.92031 15.3049 9.30663 14.9258 8.80555 14.4208C8.30448 13.9158 7.93028 13.2991 7.71367 12.6215C7.49705 11.9438 7.44419 11.2245 7.55942 10.5225C7.67465 9.82043 7.95469 9.15572 8.37656 8.58287L11.7628 9.70787C11.9168 9.75919 12.0832 9.75919 12.2372 9.70787L15.6234 8.58287C16.1932 9.35537 16.5005 10.2901 16.5 11.2501ZM12 8.20974L5.37187 6.00006L12 3.79037L18.6281 6.00006L12 8.20974Z" fill="black"/>
                        </svg>
                        <h1>Instructors</h1>
                    </a>
                </li>
                <li class="p-3 hover:bg-darthmouthgreen hover:bg-opacity-75 hover:text-white">
                    <a href="{{ url('/admin/courses')}}" class="flex items-center">
                        <svg class="mx-3 duration-500 fill-black group-hover:fill-white group-hover:animate-bounce" width="27" height="27" viewBox="0 0 27 27" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M21.375 6.75H18V5.625C18 5.02826 17.7629 4.45597 17.341 4.03401C16.919 3.61205 16.3467 3.375 15.75 3.375H11.25C10.6533 3.375 10.081 3.61205 9.65901 4.03401C9.23705 4.45597 9 5.02826 9 5.625V6.75H5.625C4.72989 6.75 3.87145 7.10558 3.23851 7.73851C2.60558 8.37145 2.25 9.22989 2.25 10.125V20.25C2.25 21.1451 2.60558 22.0036 3.23851 22.6365C3.87145 23.2694 4.72989 23.625 5.625 23.625H21.375C22.2701 23.625 23.1285 23.2694 23.7615 22.6365C24.3944 22.0036 24.75 21.1451 24.75 20.25V10.125C24.75 9.22989 24.3944 8.37145 23.7615 7.73851C23.1285 7.10558 22.2701 6.75 21.375 6.75ZM11.25 5.625H15.75V6.75H11.25V5.625ZM22.5 20.25C22.5 20.5484 22.3815 20.8345 22.1705 21.0455C21.9595 21.2565 21.6734 21.375 21.375 21.375H5.625C5.32663 21.375 5.04048 21.2565 4.82951 21.0455C4.61853 20.8345 4.5 20.5484 4.5 20.25V13.9388L9.765 15.75C9.88445 15.7662 10.0055 15.7662 10.125 15.75H16.875C16.997 15.7477 17.1181 15.7288 17.235 15.6937L22.5 13.9388V20.25ZM22.5 11.565L16.695 13.5H10.305L4.5 11.565V10.125C4.5 9.82663 4.61853 9.54048 4.82951 9.32951C5.04048 9.11853 5.32663 9 5.625 9H21.375C21.6734 9 21.9595 9.11853 22.1705 9.32951C22.3815 9.54048 22.5 9.82663 22.5 10.125V11.565Z" fill-opacity="0.7"/>
                        </svg>
                        <h1>Courses</h1>
                    </a>
                </li>
                <li class="p-3 hover:bg-darthmouthgreen hover:bg-opacity-75 hover:text-white">
                    <a href="{{ url('/admin/courseManage') }}" class="flex items-center">
                        <svg 
                        xmlns="http://www.w3.org/2000/svg"
                        class="mx-3 duration-500 group-hover:fill-white group-hover:animate-bounce" width="27" height="27" viewBox="0 0 27 27" fill="none"
                        stroke="#000000"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                      >
                        <path d="M22 10v6M2 10l10-5 10 5-10 5z" />
                        <path d="M6 12v5c3 3 9 3 12 0v-5" />
                      </svg>
                        <h1>Course Management</h1>
                    </a>
                </li>
                <li class="p-3 hover:bg-darthmouthgreen hover:bg-opacity-75 hover:text-white">
                    <a href="{{ url('/admin/course/enrollment') }}" class="flex items-center">
                        <svg class="mx-3 duration-500 group-hover:fill-white group-hover:animate-bounce" width="27" height="27" viewBox="0 0 27 27" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M14 22V18C14 17.4696 13.7893 16.9609 13.4142 16.5858C13.0391 16.2107 12.5304 16 12 16C11.4696 16 10.9609 16.2107 10.5858 16.5858C10.2107 16.9609 10 17.4696 10 18V22" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M18 10L22 12V20C22 20.5304 21.7893 21.0391 21.4142 21.4142C21.0391 21.7893 20.5304 22 20 22H4C3.46957 22 2.96086 21.7893 2.58579 21.4142C2.21071 21.0391 2 20.5304 2 20V12L6 10M18 5V22M4 6L12 2L20 6M6 5V22" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M12 11C13.1046 11 14 10.1046 14 9C14 7.89543 13.1046 7 12 7C10.8954 7 10 7.89543 10 9C10 10.1046 10.8954 11 12 11Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <h1>Course Enrollment</h1>
                    </a>
                </li>
                <li class="p-3 hover:bg-darthmouthgreen hover:bg-opacity-75 hover:text-white">
                    <a href="{{ url('/admin/message') }}" class="flex items-center">
                        <i class="mx-3 text-xl text-gray-700 duration-500 fa-regular fa-message group-hover:fill-white group-hover:animate-bounce"></i>
                        <h1>Messages</h1>
                    </a>
                </li>
                <li class="p-3 hover:bg-darthmouthgreen hover:bg-opacity-75 hover:text-white">
                    <a href="{{ url("/admin/performance") }}" class="flex items-center">
                        <svg class="mx-3 duration-500 fill-black group-hover:fill-white group-hover:animate-bounce" width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12.5002 6.24998C12.2239 6.24998 11.9589 6.35973 11.7636 6.55508C11.5682 6.75043 11.4585 7.01538 11.4585 7.29165V17.7083C11.4585 17.9846 11.5682 18.2495 11.7636 18.4449C11.9589 18.6402 12.2239 18.75 12.5002 18.75C12.7764 18.75 13.0414 18.6402 13.2367 18.4449C13.4321 18.2495 13.5418 17.9846 13.5418 17.7083V7.29165C13.5418 7.01538 13.4321 6.75043 13.2367 6.55508C13.0414 6.35973 12.7764 6.24998 12.5002 6.24998ZM7.29183 12.5C7.01556 12.5 6.75061 12.6097 6.55526 12.8051C6.35991 13.0004 6.25016 13.2654 6.25016 13.5416V17.7083C6.25016 17.9846 6.35991 18.2495 6.55526 18.4449C6.75061 18.6402 7.01556 18.75 7.29183 18.75C7.5681 18.75 7.83305 18.6402 8.0284 18.4449C8.22375 18.2495 8.3335 17.9846 8.3335 17.7083V13.5416C8.3335 13.2654 8.22375 13.0004 8.0284 12.8051C7.83305 12.6097 7.5681 12.5 7.29183 12.5ZM17.7085 10.4166C17.4322 10.4166 17.1673 10.5264 16.9719 10.7217C16.7766 10.9171 16.6668 11.182 16.6668 11.4583V17.7083C16.6668 17.9846 16.7766 18.2495 16.9719 18.4449C17.1673 18.6402 17.4322 18.75 17.7085 18.75C17.9848 18.75 18.2497 18.6402 18.4451 18.4449C18.6404 18.2495 18.7502 17.9846 18.7502 17.7083V11.4583C18.7502 11.182 18.6404 10.9171 18.4451 10.7217C18.2497 10.5264 17.9848 10.4166 17.7085 10.4166ZM19.7918 2.08331H5.2085C4.37969 2.08331 3.58484 2.41255 2.99879 2.9986C2.41274 3.58466 2.0835 4.37951 2.0835 5.20831V19.7916C2.0835 20.6204 2.41274 21.4153 2.99879 22.0014C3.58484 22.5874 4.37969 22.9166 5.2085 22.9166H19.7918C20.6206 22.9166 21.4155 22.5874 22.0015 22.0014C22.5876 21.4153 22.9168 20.6204 22.9168 19.7916V5.20831C22.9168 4.37951 22.5876 3.58466 22.0015 2.9986C21.4155 2.41255 20.6206 2.08331 19.7918 2.08331ZM20.8335 19.7916C20.8335 20.0679 20.7238 20.3329 20.5284 20.5282C20.3331 20.7236 20.0681 20.8333 19.7918 20.8333H5.2085C4.93223 20.8333 4.66728 20.7236 4.47193 20.5282C4.27658 20.3329 4.16683 20.0679 4.16683 19.7916V5.20831C4.16683 4.93205 4.27658 4.66709 4.47193 4.47174C4.66728 4.27639 4.93223 4.16665 5.2085 4.16665H19.7918C20.0681 4.16665 20.3331 4.27639 20.5284 4.47174C20.7238 4.66709 20.8335 4.93205 20.8335 5.20831V19.7916Z" fill-opacity="0.75"/>
                        </svg>
                        <h1>Performance</h1>
                    </a>
                </li>
                @if ($admin->role === 'SUPER_ADMIN' || $admin->role === 'IT_DEPT')
                    <li class="p-3 hover:bg-darthmouthgreen hover:bg-opacity-75 hover:text-white">
                        <a href="{{ url('/admin/admins') }}" class="flex items-center">
                            <svg class="mx-3 duration-500 group-hover:fill-white group-hover:stroke-white group-hover:animate-bounce" width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 14V16C10.4087 16 8.88258 16.6321 7.75736 17.7574C6.63214 18.8826 6 20.4087 6 22H4C4 19.8783 4.84285 17.8434 6.34315 16.3431C7.84344 14.8429 9.87827 14 12 14ZM12 13C8.685 13 6 10.315 6 7C6 3.685 8.685 1 12 1C15.315 1 18 3.685 18 7C18 10.315 15.315 13 12 13ZM12 11C14.21 11 16 9.21 16 7C16 4.79 14.21 3 12 3C9.79 3 8 4.79 8 7C8 9.21 9.79 11 12 11ZM21 17H22V22H14V17H15V16C15 15.2044 15.3161 14.4413 15.8787 13.8787C16.4413 13.3161 17.2044 13 18 13C18.7956 13 19.5587 13.3161 20.1213 13.8787C20.6839 14.4413 21 15.2044 21 16V17ZM19 17V16C19 15.7348 18.8946 15.4804 18.7071 15.2929C18.5196 15.1054 18.2652 15 18 15C17.7348 15 17.4804 15.1054 17.2929 15.2929C17.1054 15.4804 17 15.7348 17 16V17H19Z" fill="black"/>
                            </svg>
                            <h1>Admin Management</h1>
                        </a>
                    </li>
                @endif
                <li class="p-3 hover:bg-darthmouthgreen hover:bg-opacity-75 hover:text-white">
                    <a href="{{ url('/admin/profile') }}" class="flex items-center">
                        <svg class="mx-3 duration-500 fill-black group-hover:fill-white group-hover:animate-bounce" width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M11.6656 2.69475C11.9209 2.55301 12.208 2.47864 12.5 2.47864C12.792 2.47864 13.0791 2.55301 13.3344 2.69475L20.8344 6.86142C21.3802 7.16454 21.7188 7.73954 21.7188 8.36454V16.6354C21.7188 17.2604 21.3802 17.8354 20.8344 18.1385L13.3344 22.3052C13.0791 22.4469 12.792 22.5213 12.5 22.5213C12.208 22.5213 11.9209 22.4469 11.6656 22.3052L4.16562 18.1385C3.89752 17.9896 3.67414 17.7717 3.51863 17.5074C3.36312 17.2431 3.28116 16.942 3.28125 16.6354V8.36454C3.28125 7.73954 3.61979 7.16454 4.16562 6.86142L11.6656 2.69475ZM12.576 4.06142C12.5528 4.04846 12.5266 4.04167 12.5 4.04167C12.4734 4.04167 12.4472 4.04846 12.424 4.06142L4.92396 8.22808C4.8998 8.24154 4.87965 8.26117 4.86556 8.28496C4.85147 8.30875 4.84394 8.33585 4.84375 8.3635V16.6354C4.84375 16.6927 4.875 16.7448 4.92396 16.7729L12.424 20.9395C12.4472 20.9525 12.4734 20.9593 12.5 20.9593C12.5266 20.9593 12.5528 20.9525 12.576 20.9395L20.076 16.7729C20.1005 16.7592 20.1209 16.7393 20.135 16.7151C20.1491 16.6909 20.1564 16.6634 20.1562 16.6354V8.36454C20.1562 8.33672 20.1488 8.30939 20.1347 8.28541C20.1206 8.26142 20.1004 8.24163 20.076 8.22808L12.576 4.06142Z" fill-opacity="0.75"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M7.55225 12.4999C7.55225 11.1876 8.07354 9.92913 9.00146 9.00121C9.92937 8.0733 11.1879 7.552 12.5002 7.552C13.8124 7.552 15.071 8.0733 15.9989 9.00121C16.9268 9.92913 17.4481 11.1876 17.4481 12.4999C17.4481 13.8122 16.9268 15.0707 15.9989 15.9986C15.071 16.9265 13.8124 17.4478 12.5002 17.4478C11.1879 17.4478 9.92937 16.9265 9.00146 15.9986C8.07354 15.0707 7.55225 13.8122 7.55225 12.4999ZM12.5002 9.1145C11.6023 9.1145 10.7412 9.47118 10.1063 10.1061C9.47142 10.741 9.11475 11.6021 9.11475 12.4999C9.11475 13.3978 9.47142 14.2589 10.1063 14.8938C10.7412 15.5287 11.6023 15.8853 12.5002 15.8853C13.398 15.8853 14.2591 15.5287 14.894 14.8938C15.5289 14.2589 15.8856 13.3978 15.8856 12.4999C15.8856 11.6021 15.5289 10.741 14.894 10.1061C14.2591 9.47118 13.398 9.1145 12.5002 9.1145Z" fill-opacity="0.75"/>
                        </svg>
                        <h1>Settings</h1>
                    </a>
                </li>
            </ul>

            <form class="mx-4 mt-10 rounded-lg bg-darthmouthgreen md:block group hover:bg-white hover:border-2 hover:border-darthmouthgreen" action="{{ url('/admin/logout') }}" method="POST"> 
                @csrf
                <button type="submit" class="flex flex-row items-center justify-center w-full h-12 group-hover:cursor-pointer" >
                    <svg class="fill-white group-hover:fill-black" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h280v80H200v560h280v80H200Zm440-160-55-58 102-102H360v-80h327L585-622l55-58 200 200-200 200Z"/></svg>
                    <h1 class="px-5 text-white group-hover:text-black">Logout</h1>
                </button>
            </form>
        </div>
    </div>
</section>

<script>
    $(document).ready(function() {
        var currentUrl = window.location.href;

        if (currentUrl.includes('/admin/dashboard')) {
            $('.dashboardSideBtn').addClass('bg-darthmouthgreen');
        } else if (currentUrl.includes('/learner/discussions')) {
            $('#instructor_discussions').addClass('bg-green-100');
        } else if (currentUrl.includes('/learner/courses')) {
            $('#instructor_courses').addClass('bg-green-100');
        } else if (currentUrl.includes('/learner/performances')) {
            $('#instructor_performances').addClass('bg-green-100');
        } else if (currentUrl.includes('/learner/settings')) {
            $('#instructor_settings').addClass('bg-green-100');
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

        $('#close-admin-sidebar').on('click', ()=> {
            $('#admin-sm-sidebar').toggleClass('hidden')
        })
        $('#nav-btn').on('click', ()=> {
            $('#admin-sm-sidebar').toggleClass('hidden')
        })
    });
</script>
