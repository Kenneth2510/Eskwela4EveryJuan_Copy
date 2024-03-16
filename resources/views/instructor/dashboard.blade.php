@extends('layouts.instructor_layout')

@section('content')
    {{-- MAIN START --}}
    <section class="w-full h-screen md:w-3/4 lg:w-10/12">
        <div class="h-full px-2 py-4 pt-24 rounded-lg shadow-lg md:overflow-hidden md:overflow-y-scroll md:pt-0">

            <div class="py-4" id="welcome">
                <h1 class="text-2xl font-semibold md:text-3xl">Welcome back, <span class="text-darthmouthgreen">{{$instructor->instructor_fname}}</span>!</h1>
            </div>

            <div class="py-4 border-t-2 border-gray-300">
                <h1 class="py-2 text-2xl font-semibold">Overview</h1>

                <div class="flex items-center justify-between text-sm md:text-base text-darthmouthgreen" id="overview_area">
                    <div class="flex flex-col items-center justify-between w-1/3 h-24 mx-2 text-center border-2 md:py-4 lg:py-8 md:h-36 lg:h-56 border-darthmouthgreen rounded-2xl" id="totalActiveCoursesArea">
                        <h1 class="text-lg font-semibold md:text-4xl lg:text-7xl" id="totalCoursesText">#</h1>
                        <p class="font-medium text-black">Active Courses Managed</p>
                    </div>
                    <div class="flex flex-col items-center justify-between w-1/3 h-24 mx-2 text-center border-2 md:py-4 lg:py-8 md:h-36 lg:h-56 border-darthmouthgreen rounded-2xl" id="totalLearnersArea">
                        <h1 class="text-lg font-semibold md:text-4xl lg:text-7xl" id="totalLearnersCountText">#</h1>
                        <p class="font-medium text-black">Learners Enrolled</p>
                    </div>
                    <div class="flex flex-col items-center justify-between w-1/3 h-24 mx-2 text-center border-2 md:py-4 lg:py-8 md:h-36 lg:h-56 border-darthmouthgreen rounded-2xl" id="totalTopicsArea">
                        <h1 class="text-lg font-semibold md:text-4xl lg:text-7xl" id="totalSyllabusCountText">#</h1>
                        <p class="font-medium text-black">Topics Created</p>
                    </div>
                </div>                    
            </div>

            <div class="py-4 border-t-2 border-gray-300">
                <div class="flex justify-between">
                    <h1 class="text-2xl font-semibold">Manage your courses</h1>
                    <a href="{{ url('/instructor/courses') }}" class="">view all</a>
                </div>
            

                <div class="relative px-20 overflow-hidden" id="courseCarouselArea">
                    <button id="course_carousel_left_btn" class="absolute left-0 flex items-center justify-center h-full mx-5">
                        <i class="text-2xl fa-solid fa-angle-left"></i>
                    </button>
                    <button id="course_carousel_right_btn" class="absolute right-0 flex items-center justify-center h-full mx-5">
                        <i class="text-2xl fa-solid fa-angle-right"></i>
                    </button>
                    <div class="flex overflow-x-auto scroll scroll-smooth" id="courseCardContainer">
                        @foreach ($courses as $course)

                        <div style="background-color: #00693e" class="relative px-3 py-2 m-4 rounded-lg shadow-lg h-72 w-52">
                            <div style="background-color: #9DB0A3" class="relative h-32 mx-auto rounded w-44">
                                <img class="absolute w-16 h-16 bg-yellow-500 rounded-full right-3 -bottom-4" src="{{ asset('storage/' . $instructor->profile_picture) }}" alt="">
                            </div>
                            
                            <div class="px-4">
                                <h1 class="mb-2 overflow-hidden text-lg font-bold text-white whitespace-no-wrap">{{ $course->course_name }}</h1>
        
                                <div class="text-sm text-gray-100 ">
                                    <p>{{ $course->course_code }}</p>
                                    <h3>{{ $course->instructor_fname }} {{ $course->instructor_lname }}</h3>
                                </div>
                            </div>
                            
                            <a href="{{ url("/instructor/course/$course->course_id") }}" style="background-color: #00693e; right:0; bottom: 0;" class="absolute float-right mx-4 mb-3 rounded">
                                <svg class="w-10 h-10" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M504-480 320-664l56-56 240 240-240 240-56-56 184-184Z"/></svg>
                            </a>
                        </div>

                        @endforeach
                    </div>
                </div>                    
            </div>

            <div class="py-4 border-t-2 border-gray-300">
                <div class="flex justify-between">
                    <h1 class="mx-5 text-2xl font-semibold">Enrolled Learners</h1>
                    <a href="{{ url('/instructor/courses') }}" class="">view all</a>
                </div>

                <div class="w-11/12 mx-5" id="enrolledLearnersArea">
                    <table class="w-full mt-5">
                        <thead class="text-left">
                            <th class="text-lg">Course Name</th>
                            <th class="text-lg">Number of Enrollees</th>
                        </thead>
                        <tbody id="enrollePercentArea">
                            {{-- <tr>
                                <td>Course 1</td>
                                <td>
                                    <div class="h-7 rounded-xl" style="background: #9DB0A3" id="skill_bar">
                                        <div class="relative py-1 text-center text-white h-7 bg-darthmouthgreen rounded-xl" id="skill_per" per="70%" style="max-width: 70%">70%</div>
                                    </div>
                                </td>
                            </tr> --}}
                        </tbody>
                    </table>
                </div>                
            </div>
        </div>
    </section>
    {{-- MAIN END --}}     
@endsection

