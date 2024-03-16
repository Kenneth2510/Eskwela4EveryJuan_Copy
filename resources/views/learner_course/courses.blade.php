@extends('layouts.learner_layout')

@section('content')
        {{-- MAIN START --}}
    <section class="w-full h-screen md:w-3/4 lg:w-10/12">
        <div class="h-full px-2 py-4 pt-24 overflow-hidden overflow-y-scroll rounded-lg shadow-lg md:pt-0">
             
            {{-- MAIN HEADER --}}
            <div class="flex flex-row items-center justify-between px-4">
                <h1 class="text-xl font-semibold md:text-4xl ">My Courses</h1>
                <form class="relative flex flex-row items-center" action="">
                    <button class="absolute left-0" type="submit">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M784-120 532-372q-30 24-69 38t-83 14q-109 0-184.5-75.5T120-580q0-109 75.5-184.5T380-840q109 0 184.5 75.5T640-580q0 44-14 83t-38 69l252 252-56 56ZM380-400q75 0 127.5-52.5T560-580q0-75-52.5-127.5T380-760q-75 0-127.5 52.5T200-580q0 75 52.5 127.5T380-400Z"/></svg>
                    </button>
                    <input class="w-32 h-10 pl-6 rounded-lg md:w-64 md:h-12" type="search" name="search" id="searchVal" placeholder="search">
                </form>
            </div>
    
            {{-- MAIN CONTENT --}}
            <div class="mt-5">
                <div class="mx-5">
                    <h1 class="text-lg font-semibold md:text-xl">Recents</h1>
                </div>
             
                <div class="relative px-20 overflow-hidden h-80" id="courseCarouselArea">
                    <button id="course_carousel_left_btn" class="absolute left-0 flex items-center justify-center h-full mx-5">
                        <i class="text-2xl fa-solid fa-angle-left"></i>
                    </button>
                    <button id="course_carousel_right_btn" class="absolute right-0 flex items-center justify-center h-full mx-5">
                        <i class="text-2xl fa-solid fa-angle-right"></i>
                    </button>
                    <div class="flex overflow-x-auto h-80 scroll scroll-smooth" id="courseCardContainer">
                        @foreach ($learnerCourse as $course)
        
                        <div style="background-color: #00693e" class="relative px-3 py-2 m-4 rounded-lg shadow-lg h-72 w-52">
                            <div style="background-color: #9DB0A3" class="relative h-32 mx-auto my-4 rounded w-44">
                                <img class="absolute w-16 h-16 bg-yellow-500 rounded-full right-3 -bottom-4" src="{{ asset('storage/' . $course->profile_picture) }}" alt="">
                            </div>
                            
                            <div class="px-4">
                                <h1 class="mb-2 overflow-hidden text-lg font-bold text-white whitespace-no-wrap">{{ $course->course_name }}</h1>
        
                                <div class="text-sm text-gray-100 ">
                                    <p>{{ $course->course_code }}</p>
                                    <h3>{{ $course->instructor_fname }} {{ $course->instructor_lname }}</h3>
                                </div>
                            </div>
                            
                            <a href="{{ url("/learner/course/$course->course_id") }}" style="background-color: #00693e; right:0; bottom: 0;" class="absolute float-right mx-4 mb-3 rounded">
                                <svg class="w-10 h-10" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M504-480 320-664l56-56 240 240-240 240-56-56 184-184Z"/></svg>
                            </a>
                        </div>
        
                        @endforeach
                    </div>
                </div>
            </div>


            <hr class="my-6 border-t-2 border-gray-300">

            <div class="mx-5">
                <h1 class="text-lg font-semibold md:text-xl">All your courses</h1>
            </div>

            <div class="flex flex-row flex-wrap items-center justify-center mx-auto mt-5 border-2 rounded-lg shadow grow lg:justify-start">
                
                <div class="flex flex-row flex-wrap items-center justify-center" id="coursesArea">
                    <div class="flex flex-row flex-wrap items-center justify-center" id="courses">
                        @foreach ($learnerCourse as $course)
        
                        <div style="background-color: #00693e" class="relative px-3 py-2 m-4 rounded-lg shadow-lg h-72 w-52">
                            <div style="background-color: #9DB0A3" class="relative h-32 mx-auto my-4 rounded w-44">
                                <img class="absolute w-16 h-16 bg-yellow-500 rounded-full right-3 -bottom-4" src="{{ asset('storage/' . $course->profile_picture) }}" alt="">
                            </div>
                            
                            <div class="px-4">
                                <h1 class="mb-2 overflow-hidden text-lg font-bold text-white whitespace-no-wrap">{{ $course->course_name }}</h1>
        
                                <div class="text-sm text-gray-100 ">
                                    <p>{{ $course->course_code }}</p>
                                    <h3>{{ $course->instructor_fname }} {{ $course->instructor_lname }}</h3>
                                </div>
                            </div>
                            
                            <a href="{{ url("/learner/course/$course->course_id") }}" style="background-color: #00693e; right:0; bottom: 0;" class="absolute float-right mx-4 mb-3 rounded">
                                <svg class="w-10 h-10" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M504-480 320-664l56-56 240 240-240 240-56-56 184-184Z"/></svg>
                            </a>
                        </div>
                    @endforeach
                    </div>

                </div>

            </div>


            
            <hr class="my-6 mt-5 border-t-2 border-gray-300">
            <div class="mt-5">
                <div class="flex justify-between">
                    <h1 class="mx-5 text-2xl font-semibold">Available Courses</h1>
                </div>
              
        
                <div class="relative px-20 overflow-hidden h-80" id="courseCarouselArea">
                    <button id="course_carousel_left_btn" class="absolute left-0 flex items-center justify-center h-full mx-5">
                        <i class="text-2xl fa-solid fa-angle-left"></i>
                    </button>
                    <button id="course_carousel_right_btn" class="absolute right-0 flex items-center justify-center h-full mx-5">
                        <i class="text-2xl fa-solid fa-angle-right"></i>
                    </button>
                    <div class="flex overflow-x-auto h-80 scroll scroll-smooth" id="courseCardContainer">
                        @foreach ($allCourses as $coursedata)
        
                        <div style="background-color: #00693e" class="relative px-3 py-2 m-4 rounded-lg shadow-lg h-72 w-52">
                            <div style="background-color: #9DB0A3" class="relative h-32 mx-auto my-4 rounded w-44">
                                <img class="absolute w-16 h-16 bg-yellow-500 rounded-full right-3 -bottom-4" src="{{ asset('storage/' . $coursedata->profile_picture) }}" alt="">
                            </div>
                            
                            <div class="px-4">
                                <h1 class="mb-2 overflow-hidden text-lg font-bold text-white whitespace-no-wrap">{{ $coursedata->course_name }}</h1>
        
                                <div class="text-sm text-gray-100 ">
                                    <p>{{ $coursedata->course_code }}</p>
                                    <h3>{{ $coursedata->instructor_fname }} {{ $coursedata->instructor_lname }}</h3>
                                </div>
                            </div>
                            
                            <a href="{{ url("/learner/course/$coursedata->course_id") }}" style="background-color: #00693e; right:0; bottom: 0;" class="absolute float-right mx-4 mb-3 rounded">
                                <svg class="w-10 h-10" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M504-480 320-664l56-56 240 240-240 240-56-56 184-184Z"/></svg>
                            </a>
                        </div>
        
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    @include('partials.chatbot')
        {{-- MAIN END --}}
@endsection
