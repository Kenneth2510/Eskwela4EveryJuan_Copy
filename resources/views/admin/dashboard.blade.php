@extends('layouts.admin_layout')

@section('content')
<section class="w-full h-auto md:h-screen lg:w-10/12">
    <div class="h-full px-2 py-4 pt-12 rounded-lg shadow-lg md:overflow-auto md:pt-0">
        <div class="flex items-center justify-between p-3 border-b-2 border-gray-300 md:py-8">
            <h1 class="text-2xl font-bold text-darthmouthgreen md:text-3xl lg:text-4xl">Overview</h1>
            <div class="">
                <p class="font-semibold text-darthmouthgreen md:text-lg">{{$admin->admin_codename}}</p>
            </div>
        </div>

        <div class="w-full p-3 rounded-lg shadow-lg">

            <div class="flex justify-between w-full space-x-2 text-center" id="countDataMainArea">
                <div class="flex flex-col items-center justify-center relative w-1/3 py-3 border-2 shadow-lg border-darthmouthgreen rounded-xl md:h-[150px] lg:h-[200px]" id="totalLearnerCountArea">
                    <i class="absolute translate-x-1/2 -translate-y-1/2 top-1/2 right-1/2 fa-regular fa-user text-darthmouthgreen fa-5x opacity-30 lg:opacity-100 lg:-translate-y-0 lg:-translate-x-0 lg:top-auto lg:right-2"></i>
                    <p class="text-6xl font-bold text-darthmouthgreen">{{$totalLearner}}</p>
                    <h1 class="w-3/4 mx-auto font-semibold text-darthmouthgreen">Total Learners</h1>
                </div>
                <div class="flex flex-col items-center justify-center relative w-1/3 py-3 border-2 shadow-lg border-darthmouthgreen rounded-xl md:h-[150px] lg:h-[200px]" id="totalInstructorCountArea">
                    <svg class="absolute translate-x-1/2 -translate-y-1/2 top-1/2 right-1/2 fill-darthmouthgreen opacity-30 lg:opacity-100 lg:-translate-y-0 lg:-translate-x-0 lg:top-auto lg:right-2" xmlns="http://www.w3.org/2000/svg" width="5em" height="5em" viewBox="0 0 256 256"><path  d="m226.53 56.41l-96-32a8 8 0 0 0-5.06 0l-96 32A8 8 0 0 0 24 64v80a8 8 0 0 0 16 0V75.1l33.59 11.19a64 64 0 0 0 20.65 88.05c-18 7.06-33.56 19.83-44.94 37.29a8 8 0 1 0 13.4 8.74C77.77 197.25 101.57 184 128 184s50.23 13.25 65.3 36.37a8 8 0 0 0 13.4-8.74c-11.38-17.46-27-30.23-44.94-37.29a64 64 0 0 0 20.65-88l44.12-14.7a8 8 0 0 0 0-15.18ZM176 120a48 48 0 1 1-86.65-28.45l36.12 12a8 8 0 0 0 5.06 0l36.12-12A47.89 47.89 0 0 1 176 120"/></svg>
                    <p class="text-6xl font-bold text-darthmouthgreen">{{$totalInstructor}}</p>
                    <h1 class="w-3/4 mx-auto font-semibold text-darthmouthgreen">Total Instructors</h1>
                </div>
                <div class="flex flex-col items-center justify-center relative w-1/3 py-3 border-2 shadow-lg border-darthmouthgreen rounded-xl md:h-[150px] lg:h-[200px]" id="totalCourseCountArea">
                        <svg class="absolute translate-x-1/2 -translate-y-1/2 top-1/2 right-1/2 fill-darthmouthgreen opacity-30 lg:opacity-100 lg:-translate-y-0 lg:-translate-x-0 lg:top-auto lg:right-2" width="5em" height="5em" viewBox="0 0 27 27" xmlns="http://www.w3.org/2000/svg">
                        <path d="M21.375 6.75H18V5.625C18 5.02826 17.7629 4.45597 17.341 4.03401C16.919 3.61205 16.3467 3.375 15.75 3.375H11.25C10.6533 3.375 10.081 3.61205 9.65901 4.03401C9.23705 4.45597 9 5.02826 9 5.625V6.75H5.625C4.72989 6.75 3.87145 7.10558 3.23851 7.73851C2.60558 8.37145 2.25 9.22989 2.25 10.125V20.25C2.25 21.1451 2.60558 22.0036 3.23851 22.6365C3.87145 23.2694 4.72989 23.625 5.625 23.625H21.375C22.2701 23.625 23.1285 23.2694 23.7615 22.6365C24.3944 22.0036 24.75 21.1451 24.75 20.25V10.125C24.75 9.22989 24.3944 8.37145 23.7615 7.73851C23.1285 7.10558 22.2701 6.75 21.375 6.75ZM11.25 5.625H15.75V6.75H11.25V5.625ZM22.5 20.25C22.5 20.5484 22.3815 20.8345 22.1705 21.0455C21.9595 21.2565 21.6734 21.375 21.375 21.375H5.625C5.32663 21.375 5.04048 21.2565 4.82951 21.0455C4.61853 20.8345 4.5 20.5484 4.5 20.25V13.9388L9.765 15.75C9.88445 15.7662 10.0055 15.7662 10.125 15.75H16.875C16.997 15.7477 17.1181 15.7288 17.235 15.6937L22.5 13.9388V20.25ZM22.5 11.565L16.695 13.5H10.305L4.5 11.565V10.125C4.5 9.82663 4.61853 9.54048 4.82951 9.32951C5.04048 9.11853 5.32663 9 5.625 9H21.375C21.6734 9 21.9595 9.11853 22.1705 9.32951C22.3815 9.54048 22.5 9.82663 22.5 10.125V11.565Z"/>
                        </svg>
                    <p class="text-6xl font-bold text-darthmouthgreen">{{$totalCourse}}</p>
                    <h1 class="w-3/4 mx-auto font-semibold text-darthmouthgreen">Total Courses</h1>
                </div>
            </div>

            
            <hr class="my-6 border-t-2 border-gray-300">

            <div class="flex flex-col w-full space-y-8" id="mainChartDataArea">
                <div class="flex flex-col space-y-2" id="mainChartDataLeft">
                    <div class="flex flex-col space-y-2 md:flex-row md:space-y-0 md:space-x-2">
                        <div class="w-full p-3 flex flex-col justify-between rounded-xl border-2 h-[250px] border-darthmouthgreen" id="learnerChartArea">
                            <p class="text-xl font-semibold text-darthmouthgreen">Learner Data</p>
                            <canvas id="learnerData"></canvas>
                        </div>
                        <div class="w-full p-3 flex flex-col justify-between rounded-xl border-2 h-[250px] border-darthmouthgreen" id="instructorChartArea">
                            <p class="text-xl font-semibold text-darthmouthgreen">Instructor Data</p>
                            <canvas id="instructorData"></canvas>
                        </div>
                    </div>
                    <div class="flex flex-col space-y-2 md:flex-row md:space-y-0 md:space-x-2">
                        <div class="w-full p-3 flex flex-col justify-between rounded-xl border-2 h-[250px] border-darthmouthgreen" id="courseChartArea">
                            <p class="text-xl font-semibold text-darthmouthgreen">Course Data</p>
                            <canvas id="courseData"></canvas>
                        </div>
                        <div class="w-full p-3 flex flex-col justify-between rounded-xl border-2 h-[250px] border-darthmouthgreen overflow-visible" id="adminRolesChartArea">
                            <p class="text-xl font-semibold text-darthmouthgreen">Admin Data</p>
                            <canvas id="adminData" class="overflow-x-auto"></canvas>
                        </div>
                    </div>
                </div>
                
                <div class="space-y-2" id="mainChartDataRight">
                    <h1 class="text-2xl font-semibold text-darthmouthgreen">Learner Course Progress</h1>
                    <div class="space-y-2 ">
                        <select name="selectedCourse" id="selectedCourse" class="w-full px-3 py-3 border-2 text-md rounded-xl border-darthmouthgreen">
                            @foreach ($courses as $course)
                                <option value="{{$course->course_id}}">{{$course->course_name}}</option>
                            @endforeach
                        </select>
                        <div class="w-full h-full border-2 rounded-xl border-darthmouthgreen" id="courseProgressChartArea">
                            <canvas id="courseProgressChart" class="overflow-x-auto"></canvas>
                        </div>                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>    
@endsection

