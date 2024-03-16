@extends('layouts.learner_layout')

@section('content')
<section class="w-full h-auto md:h-screen md:w-3/4 lg:w-10/12">
    <div class="h-full px-2 py-4 pt-24 rounded-lg shadow-lg md:overflow-hidden md:overflow-y-scroll md:pt-0">
        
        <a href="{{ url("/learner/dashboard") }}" class="my-2 bg-gray-300 rounded-full ">
            <svg  xmlns="http://www.w3.org/2000/svg" height="30" viewBox="0 -960 960 960" width="24"><path d="M560-240 320-480l240-240 56 56-184 184 184 184-56 56Z"/></svg>
        </a>
        <h1 class="text-2xl font-semibold md:text-3xl">PERFORMANCE DASHBOARD</h1>
        <hr class="my-6 border-t-2 border-gray-300">

        <div class="flex w-full space-x-2" id="genInfo">
            <div class="relative w-1/2 md:w-3/5 lg:h-[300px] border-2 border-darthmouthgreen flex flex-col justify-between py-2 md:py-4" id="totalCourseArea">
                <div class="flex justify-center text-center item-center">
                    <i class="absolute -translate-y-1/2 md:px-4 md:opacity-100 md:relative fa-solid fa-book-open-reader text-darthmouthgreen fa-10x opacity-20 top-1/2"></i>
                    <p class="text-2xl font-bold"><span class="text-8xl text-darthmouthgreen" id="totalCourseNum">0</span><br>Total Courses Enrolled</p>
                </div>
                <div class="flex flex-col justify-center md:flex-row">
                    <div class="flex items-center">
                        <div class="w-3 h-3 mx-3 rounded-full bg-darthmouthgreen"></div>
                        <p class="font-bold text-md">Approved: <span id="totalApprovedCourse" class="">0</span></p>
                    </div>

                    <div class="flex items-center">
                        <div class="w-3 h-3 mx-3 bg-yellow-400 rounded-full"></div>
                        <p class="font-bold text-md">Pending: <span id="totalPendingCourse" class="">0</span></p>
                    </div>

                    <div class="flex items-center">
                        <div class="w-3 h-3 mx-3 bg-red-700 rounded-full"></div>
                        <p class="font-bold text-md">Rejected: <span id="totalRejectedCourse" class="">0</span></p>
                    </div>
                </div>
                
            </div>
            <div class="relative w-1/2 md:w-3/5 lg:h-[300px] border-2 border-darthmouthgreen flex flex-col justify-between py-2 md:py-4" id="enrolledLearnerSyllabusCompletionCount">
                <div class="flex justify-center text-center item-center">
                    <i class="absolute -translate-y-1/2 md:px-4 md:opacity-100 md:relative fa-solid fa-book-bookmark text-darthmouthgreen fa-10x opacity-20 top-1/2"></i>
                    <p class="text-2xl font-bold"><span class="text-8xl text-darthmouthgreen" id="totalSyllabusCompletedCount">0</span><br>Topics Completed</p>
                </div>

                <div class="flex flex-col justify-center md:flex-row">
                    <div class="">
                        <div class="flex items-center mx-1">
                            <i class="mx-3 text-xl fa-solid fa-file text-darthmouthgreen"></i>
                            <p class="font-bold text-md">Total Lessons: <span id="totalLessonsCount" class="">0</span></p>
                        </div>
    
                        <div class="flex items-center mx-1">
                            <i class="mx-3 text-xl fa-solid fa-clipboard text-darthmouthgreen"></i>
                            <p class="font-bold text-md">Total Activities: <span id="totalActivitiesCount" class="">0</span></p>
                        </div>
    
                        <div class="flex items-center mx-1">
                            <i class="mx-3 text-xl fa-solid fa-pen-to-square text-darthmouthgreen"></i>
                            <p class="font-bold text-md">Total Quizzes: <span id="totalQuizzesCount" class="">0</span></p>
                        </div>
                    </div>
    
                    <div class="">
                        <div class="flex items-center mx-1">
                            <i class="mx-3 text-xl fa-solid fa-file text-darthmouthgreen"></i>
                            <p class="font-bold text-md">Completed: <span id="totalLessonsCompletedCount" class="">0</span></p>
                        </div>
    
                        <div class="flex items-center mx-1">
                            <i class="mx-3 text-xl fa-solid fa-clipboard text-darthmouthgreen"></i>
                            <p class="font-bold text-md">Completed: <span id="totalActivitiesCompletedCount" class="">0</span></p>
                        </div>
    
                        <div class="flex items-center mx-1">
                            <i class="mx-3 text-xl fa-solid fa-pen-to-square text-darthmouthgreen"></i>
                            <p class="font-bold text-md">Completed: <span id="totalQuizzesCompletedCount" class="">0</span></p>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>

        <hr class="my-6 border-t-2 border-gray-300">

        <div class="w-full" id="perCourseArea">
            <select name="" class="w-full py-3 text-lg" id="perCourseSelectArea">
                <option value="ALL" selected>ALL COURSES</option>
                @foreach ($courseData as $course)
                    <option value="{{ $course->course_id }}">{{ $course->course_name }}</option>
                @endforeach
            </select>

            <div class="flex w-full mt-5" id="perCourseInfoArea">
                <div class="w-1/2 lg:h-[350px] border-2 border-darthmouthgreen p-5" id="courseInfo"></div>

                <div class="w-1/2 lg:h-[350px] ml-5 border-2 border-darthmouthgreen" id="courseGraph">
                    <canvas id="courseDataChart"></canvas>
                </div>
            </div>
        </div>

        <hr class="my-6 border-t-2 border-gray-300">

        <div class="w-full overflow-auto" id="courseListArea">
            <h1 class="mb-5 text-2xl font-semibold text-black">List of Enrolled Courses</h1>
            <table class="table table-fixed rounded-xl">
                <thead class="py-3 text-white bg-darthmouthgreen">
                    <th class="w-[150px]">Course Name</th>
                    <th class="w-[150px]">Course Code</th>
                    <th class="w-[150px]">Instructor</th>
                    <th class="w-[150px]">Status</th>
                    <th class="w-[150px]">Date Started</th>
                    <th class="w-[150px]"></th>
                </thead>

                <tbody class="mt-5 rowCourseDataArea">
            
                </tbody>
            </table>
        </div>

        <hr class="my-6 border-t-2 border-gray-300">

        <div class="flex justify-between">
            <h1 class="mx-5 text-2xl font-semibold">Your session data</h1>
        </div>

        <div class="flex justify-center mt-5" id="learnerSessionDataArea">
            <div class="mx-5 w-11/12 h-[350px] border-2 border-darthmouthgreen rounded-xl" id="learnerSessionGraphArea">
                <canvas id="learnerSessionGraph"></canvas>
            </div>
        </div>


    </div>
</section>
@include('partials.chatbot')
@endsection

