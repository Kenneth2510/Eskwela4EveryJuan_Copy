@include('partials.header')

<section class="flex flex-row w-screen text-sm main-container bg-mainwhitebg md:text-base">
@include('partials.sidebar')




<section class="w-screen px-2 pt-[40px] mx-2 mt-2  overscroll-auto md:overflow-auto">
    <div class="flex justify-between px-10">
        <h1 class="text-6xl font-bold text-darthmouthgreen">Performance Overview</h1>
        <div class="">
            <p class="text-xl font-semibold text-darthmouthgreen">{{$admin->admin_codename}}</p>
        </div>
    </div>

    <div class="mt-10">
        <div class="mb-5">
            <a href="/admin/performance" class="">
                <i class="text-2xl md:text-3xl fa-solid fa-arrow-left" style="color: #000000;"></i>
            </a>
        </div>
        <h1 class="mx-5 text-2xl font-semibold">{{$courseData->course_name}} Activity Overview</h1>
        <hr class="my-6 border-t-2 border-gray-300">    
    </div>

    <div class="flex p-10 mt-5" id="genInfo">
        <div class="w-1/2 mx-3 h-[300px] border-2 border-darthmouthgreen" id="totalLearnerProgressCount">
            <div class="flex justify-center mx-10 mt-10 text-center h-2/3 item-center">
                
                <i class="fa-solid fa-user text-darthmouthgreen text-[175px]"></i>
                <p class="mx-5 mt-3 text-2xl font-bold py-14"><span class="text-darthmouthgreen text-[125px]" id="totalLearnerSyllabusCompleteStatus">0</span><br>Learners Completed</p>
            </div>
            <div class="flex justify-center mt-5">
                <div class="flex items-center mx-1">
                    <div class="w-3 h-3 mx-2 rounded-full bg-darthmouthgreen"></div>
                    <p class="font-bold text-md">TOTAL: <span id="totalLearnersCount" class="">0</span></p>
                </div>

                <div class="flex items-center mx-1">
                    <div class="w-3 h-3 mx-2 bg-yellow-400 rounded-full"></div>
                    <p class="font-bold text-md">IN PROGRESS: <span id="totalLearnerSyllabusInProgressStatus" class="">0</span></p>
                </div>

                <div class="flex items-center mx-1">
                    <div class="w-3 h-3 mx-2 bg-red-700 rounded-full"></div>
                    <p class="font-bold text-md">NOT YET STARTED: <span id="totalLearnerSyllabusNotYetStatus" class="">0</span></p>
                </div>
            </div>
        </div>


        <div class="w-1/2 mx-3 h-[300px] border-2 border-darthmouthgreen" id="averageLearnerProgressTime">
            <div class="flex justify-center mx-10 mt-10 text-center h-2/3 item-center">

                <i class="fa-solid fa-clock text-darthmouthgreen text-[175px]"></i>
                <p class="mx-5 mt-3 text-2xl font-bold py-14"><span class="text-darthmouthgreen text-[50px]" id="averageLearnerProgress">0</span><br>Average Time of Completion</p>
            </div>
        </div>
    </div>

    <hr class="my-6 border-t-2 border-gray-300">

    <h1 class="mx-5 mb-5 text-2xl">Activity Progress</h1>
    <div class="flex justify-between px-7" id="learnerSyllabusChartsArea">

        <div class="w-1/3 h-[350px] ml-5 border-2 border-darthmouthgreen" id="learnerSyllabusStatusChartArea">
            <canvas id="learnerSyllabusStatusChart"></canvas>
        </div>

        <div class="w-1/3 h-[350px] ml-5 border-2 border-darthmouthgreen" id="learnerSyllabusStatusTimeChartArea">
            <canvas id="learnerSyllabusStatusTimeChart"></canvas>
        </div>

        <div class="w-1/3 h-[350px] ml-5 border-2 border-darthmouthgreen" id="learnerSyllabusAttemptNumberChartArea">
            <canvas id="learnerSyllabusAttemptNumberChart"></canvas>
        </div>
    </div>


    <hr class="my-6 border-t-2 border-gray-300">

    <h1 class="mx-5 mb-5 text-2xl">Activity Outputs</h1>
    <div class="flex justify-between px-7" id="learnerSyllabusOutputChartsArea">

        <div class="w-1/2 h-[350px] ml-5 border-2 border-darthmouthgreen" id="learnerSyllabusOverallScoreChartArea">
            <canvas id="learnerSyllabusOverallScoreChart"></canvas>
        </div>

        <div class="w-1/2 h-[350px] ml-5 border-2 border-darthmouthgreen" id="learnerSyllabusCriteriaScoreChartArea">
            <canvas id="learnerSyllabusCriteriaScoreChart"></canvas>
        </div>
    </div>

    <hr class="my-6 border-t-2 border-gray-300">

    <div class="flex flex-col w-full px-10 mt-5" id="learnerSyllabusProgressTableArea">
        <table class="w-full mt-5">
            <thead class="py-3 text-xl text-white bg-darthmouthgreen">
                <th class="w-1/5">Name</th>
                <th class="w-1/5">Status</th>
                <th class="w-1/5">Start Period</th>
                <th class="w-1/5">Finish Period</th>
                <th class="w-1/5"></th>
            </thead>
            <tbody class="text-lg text-center learnerSyllabusProgressRowData">
            
            </tbody>
        </table>
    </div>


    
</section>
</section>

@include('partials.footer')