@extends('layouts.instructor_layout')

@section('content')
    {{-- MAIN --}}
    <section class="w-full h-auto md:h-screen md:w-3/4 lg:w-10/12">
        <div class="h-full px-2 py-4 pt-24 rounded-lg shadow-lg md:overflow-hidden md:overflow-y-scroll md:pt-0">
            <a href="{{ url("/instructor/performances") }}" class="my-2 bg-gray-300 rounded-full ">
                <svg  xmlns="http://www.w3.org/2000/svg" height="30" viewBox="0 -960 960 960" width="24"><path d="M560-240 320-480l240-240 56 56-184 184 184 184-56 56Z"/></svg>
            </a>
            <h1 class="text-xl font-normal md:text-3xl">
                <span class="text-4xl font-bold text-darthmouthgreen">{{ $course->course_name }}</span>
                <hr class="mt-6 border-t-2 border-gray-300">
                <br>
                PERFORMANCE DASHBOARD</h1>

            <div class="flex md:space-x-2 " id="genInfo">
                <div class="relative w-1/2 md:w-3/5 h-[300px] border-2 border-darthmouthgreen flex flex-col justify-between py-2 md:py-4" id="totalLearnersArea">
                    <div class="flex justify-center text-center item-center">
                        <i class="absolute -translate-x-1/2 -translate-y-1/2 lg:px-4 lg:opacity-100 lg:relative fa-10x top-1/2 left-1/2 opacity-20 lg:left-auto lg:-translate-x-0 lg:top-auto lg:-translate-y-0 fa-solid fa-user text-darthmouthgreen"></i>
                        <p class="text-2xl font-bold "><span class="text-8xl text-darthmouthgreen" id="totalLearnerCourseCount">0</span><br>Total Learner</p>
                    </div>
                    <div class="flex flex-col justify-center lg:flex-row">
                        <div class="flex items-center">
                            <div class="w-3 h-3 mx-3 rounded-full bg-darthmouthgreen"></div>
                            <p class="font-bold md:text-md">Approved: <span id="totalApprovedLearnerCourseCount" class="">0</span></p>
                        </div>

                        <div class="flex items-center">
                            <div class="w-3 h-3 mx-3 bg-yellow-400 rounded-full"></div>
                            <p class="font-bold md:text-md">Pending: <span id="totalPendingLearnerCourseCount" class="">0</span></p>
                        </div>

                        <div class="flex items-center">
                            <div class="w-3 h-3 mx-3 bg-red-700 rounded-full"></div>
                            <p class="font-bold md:text-md">Rejected: <span id="totalRejectedLearnerCourseCount" class="">0</span></p>
                        </div>
                    </div>
                </div>
                <div class="relative w-1/2 md:w-3/5 h-[300px] border-2 border-darthmouthgreen flex flex-col justify-between py-2 md:py-4" id="totalLearnersArea">
                    <div class="flex justify-center text-center item-center">
                        
                        <i class="absolute -translate-x-1/2 -translate-y-1/2 fa-solid fa-book-bookmark text-darthmouthgreen lg:px-4 lg:opacity-100 lg:relative fa-10x top-1/2 left-1/2 lg:top-auto opacity-20 lg:left-auto lg:-translate-x-0 lg:-translate-y-0"></i>
                        <p class="text-2xl font-bold"><span class="text-darthmouthgreen text-8xl" id="totalSyllabusCount">0</span><br>Total Topics</p>
                    </div>
                    <div class="flex flex-col justify-center lg:flex-row">
                        <div class="flex items-center">
                            <i class="mx-3 text-2xl fa-solid fa-file text-darthmouthgreen"></i>
                            <p class="font-bold text-md">Lessons: <span id="totalLessonsCount" class="">0</span></p>
                        </div>

                        <div class="flex items-center">
                            <i class="mx-3 text-2xl fa-solid fa-clipboard text-darthmouthgreen"></i>
                            <p class="font-bold text-md">Activities: <span id="totalActivitiesCount" class="">0</span></p>
                        </div>

                        <div class="flex items-center">
                            <i class="mx-3 text-2xl fa-solid fa-pen-to-square text-darthmouthgreen"></i>
                            <p class="font-bold text-md">Quizzes: <span id="totalQuizzesCount" class="">0</span></p>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="my-6 border-t-2 border-gray-300 ">

            <h1 class="m-3 text-xl md:text-3xl">Course Progress</h1>
            <div class="flex flex-col justify-between space-y-4 md:flex-row md:space-x-2 md:space-y-0" id="learnerCourseProgressArea">
                <div class="md:w-3/5 h-[350px] border-2 border-darthmouthgreen" id="learnerCourseProgressChart">
                    <canvas id="learnerCourseDataChart"></canvas>
                </div>

                <div class="md:w-2/5 h-[350px] border-2 border-darthmouthgreen overflow-auto" id="learnerCourseListArea">
                    <table id="learnerCourseTable" class="table-fixed ">
                        <thead class="text-white bg-darthmouthgreen">
                            <th class="w-[130px]">Name</th>
                            <th class="w-[130px]">Date Enrolled</th>
                            <th class="w-[130px]">Progress</th>
                            <th class="w-[130px]"></th>
                        </thead>
                        <tbody class="learnerCourseRowData" style="max-height: 300px;">
                          
                        </tbody>
                    </table>
                </div>
                
                
            </div>

            <hr class="my-6 border-t-2 border-gray-300">

            <div class="flex flex-col items-center justify-center w-full mt-5 space-y-4" id="topicDetailsArea">
                <div class="w-full" id="selectTopicArea">
                    <select name="" class="w-full px-5 py-3 text-lg border-2 border-darthmouthgreen" id="selectTopic">
                        <option value="" disabled selected>Choose Topic</option>
                        @foreach ($syllabus as $topic)
                            <option value="{{ $topic->syllabus_id }}">{{ $topic->topic_title }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="w-full h-[400px] border-2 border-darthmouthgreen" id="learnerCourseTopicProgressChart">
                    <canvas id="learnerTopicDataChart"></canvas>
                </div>

                <div class="flex flex-col w-full" id="learnerCourseTopicProgressTable">
                    <a href="" method="GET" class="text-lg text-right underline text-darthmouthgreen hover:text-green-950">view more details</a>
                    <table id="learnerSyllabusTable" class="w-full mt-5 table-fixed">
                        <thead class="text-white bg-darthmouthgreen">
                            <th class="py-2 w-[130px]">Name</th>
                            <th class="py-2 w-[130px]">Date Enrolled</th>
                            <th class="py-2 w-[130px]">Progress</th>
                            <th class="py-2 w-[130px]">Start Date</th>
                            <th class="py-2 w-[130px]">Finish Date</th>
                            <th class="py-2 w-[130px]"></th>
                        </thead>
                        <tbody class="learnerSyllabusRowData">
                       
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section> 
@endsection

