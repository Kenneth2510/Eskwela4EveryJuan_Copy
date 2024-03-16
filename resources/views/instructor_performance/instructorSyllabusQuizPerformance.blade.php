@extends('layouts.instructor_layout')

@section('content')
    {{-- MAIN --}}
    <section class="w-full h-auto md:h-screen md:w-3/4 lg:w-10/12">
        <div class="h-full px-2 py-4 pt-24 rounded-lg shadow-lg md:overflow-hidden md:overflow-y-scroll md:pt-0">
            <a href="{{ url("/instructor/performances/course/$courseData->course_id") }}" class="my-2 bg-gray-300 rounded-full ">
                <svg  xmlns="http://www.w3.org/2000/svg" height="30" viewBox="0 -960 960 960" width="24"><path d="M560-240 320-480l240-240 56 56-184 184 184 184-56 56Z"/></svg>
            </a>
            <h1 class="text-xl font-normal md:text-3xl">
                <span class="text-4xl font-bold text-darthmouthgreen">{{ $courseData->course_name }}</span><br>
                <span class="mt-3 text-2xl font-semibold text-darthmouthgreen">{{ $syllabusData->topic_title }}</span>
                <hr class="mt-6 border-t-2 border-gray-300">
                <br>
                PERFORMANCE DASHBOARD</h1>

            <div class="flex w-full space-x-2" id="genInfo">
                <div class="relative w-1/2 md:w-3/5 h-[300px] border-2 border-darthmouthgreen flex flex-col justify-between py-2 md:py-4" id="totalLearnerProgressCount">
                    <div class="flex justify-center text-center item-center">
                        
                        <i class="absolute -translate-x-1/2 -translate-y-1/2 lg:px-4 lg:opacity-100 lg:relative fa-10x top-1/2 left-1/2 opacity-20 lg:top-auto lg:left-auto lg:-translate-x-0 lg:-translate-y-0 fa-solid fa-user text-darthmouthgreen"></i>
                        <p class="text-2xl font-bold"><span class="text-darthmouthgreen text-8xl" id="totalLearnerSyllabusCompleteStatus">0</span><br>Learners Completed</p>
                    </div>
                    <div class="flex flex-col justify-center text-xs md:flex-row">
                        <div class="flex items-center">
                            <div class="w-3 h-3 mx-2 rounded-full bg-darthmouthgreen"></div>
                            <p class="font-bold ">TOTAL: <span id="totalLearnersCount" class="">0</span></p>
                        </div>

                        <div class="flex items-center">
                            <div class="w-3 h-3 mx-2 bg-yellow-400 rounded-full"></div>
                            <p class="font-bold ">IN PROGRESS: <span id="totalLearnerSyllabusInProgressStatus" class="">0</span></p>
                        </div>

                        <div class="flex items-center">
                            <div class="w-3 h-3 mx-2 bg-red-700 rounded-full"></div>
                            <p class="font-bold ">NOT YET STARTED: <span id="totalLearnerSyllabusNotYetStatus" class="">0</span></p>
                        </div>
                    </div>
                </div>


                <div class="relative w-1/2 md:w-3/5 h-[300px] border-2 border-darthmouthgreen flex flex-col justify-between py-2 md:py-4 items-center" id="averageLearnerProgressTime">
                    <div class="flex items-center justify-center h-full text-center">
        
                        <i class="absolute -translate-x-1/2 -translate-y-1/2 lg:px-4 lg:opacity-100 lg:relative fa-10x top-1/2 left-1/2 opacity-20 lg:top-auto lg:left-auto lg:-translate-x-0 lg:-translate-y-0 fa-solid fa-clock text-darthmouthgreen "></i>
                        <p class="text-2xl font-bold"><span class="text-darthmouthgreen" id="averageLearnerProgress">0</span><br>Average Time of Completion</p>
                    </div>
                </div>
            </div>

            <hr class="my-6 border-t-2 border-gray-300">

            <h1 class="mb-5 text-2xl ">Quiz Progress</h1>
            <div class="flex flex-col justify-between space-y-2 md:flex-row md:space-x-2 md:space-y-0" id="learnerSyllabusChartsArea">

                <div class="md:w-1/3 h-[350px] ml-5 border-2 border-darthmouthgreen" id="learnerSyllabusStatusChartArea">
                    <canvas id="learnerSyllabusStatusChart"></canvas>
                </div>

                <div class="md:w-1/3 h-[350px] ml-5 border-2 border-darthmouthgreen" id="learnerSyllabusStatusTimeChartArea">
                    <canvas id="learnerSyllabusStatusTimeChart"></canvas>
                </div>

                <div class="md:w-1/3 h-[350px] ml-5 border-2 border-darthmouthgreen" id="learnerSyllabusAttemptNumberChartArea">
                    <canvas id="learnerSyllabusAttemptNumberChart"></canvas>
                </div>
            </div>

            <hr class="my-6 border-t-2 border-gray-300">

            <h1 class="mb-5 text-2xl ">Quiz Outputs</h1>
            <div class="flex flex-col justify-between space-y-2 md:flex-row md:space-x-2 md:space-y-0" id="learnerSyllabusOutputChartsArea">

                <div class="md:w-2/3 h-[350px] ml-5 border-2 border-darthmouthgreen" id="learnerSyllabusOverallScoreChartArea">
                    <canvas id="learnerSyllabusOverallScoreChart"></canvas>
                </div>

                <div class="md:w-1/3 h-[350px] ml-5 border-2 border-darthmouthgreen" id="learnerSyllabusRemarksChartArea">
                    <canvas id="learnerSyllabusRemarksChart"></canvas>
                </div>
            </div>


            <hr class="my-6 border-t-2 border-gray-300">

            <div class="flex flex-col w-full mt-5 overflow-auto" id="learnerSyllabusProgressTableArea" style="height: 500px;">
                <table class="w-full mt-5 table-fixed">
                    <thead class="text-xl text-white bg-darthmouthgreen">
                        <th class="py-2 w-[130px]">Name</th>
                        <th class="py-2 w-[130px]">Status</th>
                        <th class="py-2 w-[130px]">Attempt</th>
                        <th class="py-2 w-[130px]">Score</th>
                        <th class="py-2 w-[130px]">Remarks</th>
                        <th class="py-2 w-[130px]">Start Period</th>
                        <th class="py-2 w-[130px]">Finish Period</th>
                        <th class="py-2 w-[130px]"></th>
                    </thead>
                    <tbody class="text-lg text-center learnerSyllabusProgressRowData">
                        
                    </tbody>
                </table>
            </div>


            <hr class="my-6 border-t-2 border-gray-300">

            <h1 class="mb-5 text-2xl ">Quiz Content</h1>
            <div class="justify-between " id="learnerSyllabusQuizOutputArea">

                {{-- <div class="flex">
                    <div class="w-2/3 h-[350px] ml-5 border-2 border-darthmouthgreen learnerSyllabusQuizContentOutputArea" id="">
                        <canvas id="learnerSyllabusQuizContentOutputArea"></canvas>
                    </div>
                </div> --}}

                
                
            </div>

        </div>
    </section>
@endsection

