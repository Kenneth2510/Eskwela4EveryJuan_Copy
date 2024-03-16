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
        <h1 class="mx-5 text-2xl font-semibold">Learner {{$learner->learner_fname}} {{$learner->learner_lname}}'s Overview</h1>
        <hr class="my-6 border-t-2 border-gray-300">    
    </div>



    <div class="flex p-10 mt-5" id="genInfo">
        <div class="w-1/2 mx-3 h-[300px] border-2 border-darthmouthgreen" id="totalLearnerPerformancePercent">
            <div class="flex justify-center mx-10 mt-10 text-center h-2/3 item-center">
                
                <i class="fa-solid fa-user text-darthmouthgreen text-[175px]"></i>
                <p class="mx-5 mt-3 text-2xl font-bold py-14"><span class="text-darthmouthgreen text-[125px]" id="learnerPerformancePercent">0</span><span class="text-darthmouthgreen text-[125px]">%</span><br>Syllabus Topics Completed</p>
            </div>
            <div class="flex justify-center mt-5">
                <div class="flex items-center mx-1">
                    <div class="w-3 h-3 mx-2 rounded-full bg-darthmouthgreen"></div>
                    <p class="font-bold text-md">COMPLETED: <span id="totalCompletedSyllabus" class="">0</span></p>
                </div>

                <div class="flex items-center mx-1">
                    <div class="w-3 h-3 mx-2 bg-yellow-400 rounded-full"></div>
                    <p class="font-bold text-md">IN PROGRESS: <span id="totalInProgressSyllabus" class="">0</span></p>
                </div>

                <div class="flex items-center mx-1">
                    <div class="w-3 h-3 mx-2 bg-red-700 rounded-full"></div>
                    <p class="font-bold text-md">NOT YET STARTED: <span id="totalLockedSyllabus" class="">0</span></p>
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
    @if($learnerCourseData->course_progress === 'COMPLETED')
    <hr class="my-6 border-t-2 border-gray-300">

    <h1 class="mx-5 mb-5 text-2xl">Course Grade</h1>
    <div class="flex p-10 mt-5" id="gradeSheetArea">
        <div class="overflow-y-auto w-2/5 mx-3 text-center h-[300px] border-2 border-darthmouthgreen" id="overallGrade">
            
            <h1 class="mx-5 mt-5 mb-5 text-4xl">Course Grade</h1>
            <p class="mx-5 mt-3 text-2xl font-bold py-14"><span class="text-darthmouthgreen text-[125px]" id="learnerPerformancePercent">{{$learnerCourseData->grade}}</span><span class="text-darthmouthgreen text-[125px]"></span><br>{{$learnerCourseData->remarks}}</p>
            <div class=""> <div class="mt-10">
                <h1 class="mx-2 text-2xl font-semibold">Pre Assessment</h1>
                <h1 class="py-5 mx-16 text-4xl font-bold text-green-600">{{$gradeComputation['preAssessmentLearnerSumScore']}} <span class="text-2xl font-bold text-black"> / {{$gradeComputation['totalScoreCount_pre_assessment']}}</span></h1>
            </div>
                        
    
            <div class="mt-10">
                <h1 class="mx-2 text-2xl font-semibold">Lessons</h1>
                <table class="text-center py-5 mx-16 w-[700px]">
                    <thead>
                        <th>Lesson Title</th>
                        <th>Start Date</th>
                        <th>Finish Date</th>
                    </thead>
                    <tbody>
                        @foreach ($gradeComputation['learnerLessonsData'] as $lesson)
                            <tr>
                                <td>{{ $lesson->lesson_title }}</td>
                                <td>{{ $lesson->start_period }}</td>
                                <td>{{ $lesson->finish_period }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
    
            <div class="mt-10">
                <h1 class="mx-2 text-2xl font-semibold">Activities</h1>
                <table class="text-center py-5 mx-16 w-[700px]">
                    <thead>
                        <th>Activity Title</th>
                        <th>Score</th>
                    </thead>
                    <tbody>
                        @foreach ($gradeComputation['activityScoresData'] as $activity)
                            <tr>
                                <td>{{ $activity->activity_title }}</td>
                                <td>{{ $activity->average_score }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
    
            <div class="mt-10">
                <h1 class="mx-2 text-2xl font-semibold">Quizzes</h1>
                <table class="text-center py-5 mx-16 w-[700px]">
                    <thead>
                        <th>Quiz Title</th>
                        <th>Score</th>
                    </thead>
                    <tbody>
                        @foreach ($gradeComputation['quizScoresData'] as $quiz)
                            <tr>
                                <td>{{ $quiz->quiz_title }}</td>
                                <td>{{ $quiz->average_score }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
    
            <div class="mt-10">
                <h1 class="mx-2 text-2xl font-semibold">Post Assessment</h1>
                <h1 class="py-5 mx-16 text-4xl font-bold text-green-600">{{$gradeComputation['postAssessmentLearnerSumScore']}} <span class="text-2xl font-bold text-black"> / {{$gradeComputation['totalScoreCount_post_assessment']}}</span></h1>
            </div>
    
            
            <hr class="my-6 border-t-2 border-gray-300">
            <h1 class="mx-2 text-2xl font-semibold">Computation of Grades</h1>
            <div class="px-10 mt-3">
    
                <h1 class="text-xl font-bold">Activities</h1>
                <p class="py-5 mx-16 text-xl font-bold">[[ {{ $gradeComputation['activityLearnerSumScore'] }}  / {{ $gradeComputation['activityTotalSum'] }} ] x 100 ] x 35% = {{ $gradeComputation['activityGrade'] }}%</p>
    
                
                <h1 class="text-xl font-bold">Quizzes</h1>
                <p class="py-5 mx-16 text-xl font-bold">[[ {{ $gradeComputation['quizLearnerSumScore'] }}  / {{ $gradeComputation['quizTotalSum'] }} ] x 100 ] x 35% = {{ $gradeComputation['quizGrade'] }}%</p>
    
    
                <h1 class="text-xl font-bold">Post Assessment</h1>
                <p class="py-5 mx-16 text-xl font-bold">[[ {{ $gradeComputation['postAssessmentLearnerSumScore'] }}  / {{ $gradeComputation['totalScoreCount_post_assessment'] }} ] x 100 ] x 30% = {{ $gradeComputation['postAssessmentScoreGrade'] }}%</p>
    
                <h1 class="text-xl font-bold text-green-600">Overall Grade</h1>
                <p class="py-5 mx-16 text-xl font-bold text-green-600">{{ $gradeComputation['activityGrade'] }} + {{ $gradeComputation['quizGrade'] }} + {{$gradeComputation['postAssessmentScoreGrade']}} = {{ $gradeComputation['totalGrade'] }}%</p>
                
    
                <hr class="my-6 border-t-2 border-gray-300">
                <h1 class="text-2xl font-bold">Final Grade: <span class="text-green-600">{{$gradeComputation['totalGrade']}}%</span></h1>
                <h1 class="text-2xl font-bold">Remarks: <span class="text-green-600">{{$gradeComputation['remarks']}}</span></h1>
            </div></div>
        </div>
        <div class="w-3/5 mx-3 h-[300px] border-2 border-darthmouthgreen" id="gradesheet">
            <h1 class="text-4xl font-semibold">Enrollee Gradesheet</h1>
            <div class="m-5 px-5 overflow-auto overflow-x-auto h-[200px]">
                <table id="gradesheet" class="table-fixed w-[3000px]">
                    <thead class="px-3 text-center text-white bg-darthmouthgreen">
                        <th class="w-4/12 pl-5">Name</th>
                        <th class="w-4/12">Status</th>
                        <th class="w-4/12">Date Started</th>
                        <th class="w-4/12">Pre Assessment ({{$gradeComputation['totalScoreCount_pre_assessment']}})</th>
                        
                        @foreach ($activitySyllabus as $activity)
                            <th class="w-4/12">{{ $activity->activity_title }} ({{$activity->total_score}})</th>
                        @endforeach
                        
                        @foreach ($quizSyllabus as $quiz)
                            <th class="w-4/12">{{ $quiz->quiz_title }} ({{$quiz->total_score}})</th>
                        @endforeach
                
                        <th class="w-4/12">Post Assessment ({{$gradeComputation['totalScoreCount_post_assessment']}})</th>
                        <th class="w-4/12">Grade</th>
                        <th class="w-4/12">Remarks</th>
                        <th class="w-4/12">Date Finished</th>
                    </thead>
                
                    <tbody class="text-center">
                        @forelse ($gradesheet as $grade)
                            <tr>
                                <td class="py-3 pl-5">{{ $grade->learner_fname }} {{ $grade->learner_lname }}</td>
                                <td>{{ $grade->course_progress }}</td>
                                <td>{{ $grade->start_period }}</td>
                                <td>{{$learnerPreAssessmentGrade->score}}</td>
                                
                                
                                {{-- Display activity scores --}}
                                @foreach ($activitySyllabus as $activity)
                                    @php
                                        $activityScore = $grade->activities->firstWhere('activity_id', $activity->activity_id);
                                    @endphp
                                    <td>{{ $activityScore ? $activityScore->average_score : '#' }}</td>
                                @endforeach
                                
                                {{-- Display quiz scores --}}
                                @foreach ($quizSyllabus as $quiz)
                                    @php
                                        $quizScore = $grade->quizzes->firstWhere('quiz_id', $quiz->quiz_id);
                                    @endphp
                                    <td>{{ $quizScore ? $quizScore->average_score : '#' }}</td>
                                @endforeach
                                
                                <td>{{$learnerPostAssessmentGrade->average_score}}</td>
                                <td>{{$grade->grade}}</td>
                                <td>{{$grade->remarks}}</td>
                                <td>{{ $grade->finish_period }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">No gradesheet available</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                
            </div>
        </div>
    </div>
    @endif

    @if($learnerPreAssessmentData)
    <hr class="my-6 border-t-2 border-gray-300">

    <h1 class="mx-5 mb-5 text-2xl">Pre Assessment Overview</h1>
    <div class="flex p-10 mt-5" id="preAssessmentData">
        <div class="w-full h-[150px] ml-5 border-2 overflow-y-auto border-darthmouthgreen" id="preAssessmentDataTableArea">
            <table>
                <thead class="py-3 text-lg text-white bg-darthmouthgreen">
                    <th class="w-2/12">Date Taken</th>
                    <th class="w-1/12">Status</th>
                    <th class="w-1/12">Score</th>
                    <th class="w-1/12">Remarks</th>
                    <th class="w-2/12">Finish Period</th>
                    <th class="w-2/12"></th>
                </thead>
                <tbody class="text-lg text-center">
                    <tr>
                        <td>{{$learnerPreAssessmentData->start_period}}</td>
                        <td>{{$learnerPreAssessmentData->status}}</td>
                        <td>{{$learnerPreAssessmentData->score}} / ({{$gradeComputation['totalScoreCount_pre_assessment']}})</td>
                        <td>{{$learnerPreAssessmentData->remarks}}</td>
                        <td>{{$learnerPreAssessmentData->finish_period}}</td>
                        <td>
                            <a href="{{  url("/admin/performance/learners/view/$learnerCourseData->course_id/$learnerCourseData->learner_course_id/pre_assessment/view_output")}}" class="px-5 py-3 text-white bg-darthmouthgreen rounded-xl hover:bg-white hover:text-darthmouthgreen hover:border hover:border-darthmouthgreen">view</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    @endif
    @if($learnerCourseData->course_progress === 'COMPLETED')
    <hr class="my-6 border-t-2 border-gray-300">

    <h1 class="mx-5 mb-5 text-2xl">Post Assessment Overview</h1>
    <div class="flex p-10 mt-5" id="postAssessmentData">
        <div class="w-full h-[150px] ml-5 border-2 overflow-y-auto border-darthmouthgreen" id="postAssessmentDataTableArea">
            <table>
                <thead class="py-3 text-lg text-white bg-darthmouthgreen">
                    <th class="w-2/12">Date Taken</th>
                    <th class="w-1/12">Status</th>
                    <th class="w-1/12">Score</th>
                    <th class="w-1/12">Attempts</th>
                    <th class="w-1/12">Remarks</th>
                    <th class="w-2/12">Finish Period</th>
                    <th class="w-2/12"></th>
                </thead>
                <tbody class="text-lg text-center">
                    @forelse ($learnerPostAssessmentData as $postAssessment)
                    <tr>
                        <td class="py-1">{{$postAssessment->start_period}}</td>
                        <td>{{$postAssessment->status}}</td>
                        <td>{{$postAssessment->score}} / ({{$gradeComputation['totalScoreCount_post_assessment']}})</td>
                        <td>{{$postAssessment->attempt}}</td>
                        <td>{{$postAssessment->remarks}}</td>
                        <td>{{$postAssessment->finish_period}}</td>
                        <td>
                            <a href="{{  url("/admin/performance/learners/view/$learnerCourseData->course_id/$learnerCourseData->learner_course_id/post_assessment/view_output/$postAssessment->attempt")}}" class="px-5 py-3 text-white bg-darthmouthgreen rounded-xl hover:bg-white hover:text-darthmouthgreen hover:border hover:border-darthmouthgreen">view</a>
                        </td>
                    </tr>
                    @empty
                        <tr>
                            <td>no data available</td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>
    </div>
    @endif
    <hr class="my-6 border-t-2 border-gray-300">

    <div class="flex p-10 mt-5 w-11/12 mx-auto h-[300px] border-2 border-darthmouthgreen  overflow-y-scroll" id="learnerSyllabusProgressTable">
        <table class="w-full table-fixed">
            <thead >
                <th>Topic ID</th>
                <th>Topic Title</th>
                <th>Category</th>
                <th>Status</th>
            </thead>
            <tbody style="max-height: 300px;">
                @foreach ($learnerSyllabusData as $course)
                <tr class="text-center">
                    <td>{{ $course->topic_id }}</td>
                    <td>{{ $course->topic_title }}</td>
                    <td>{{ $course->category }}</td>
                    <td>{{ $course->status }}</td>
                </tr>
                @endforeach
                
            </tbody>
        </table>
    </div>

    <hr class="my-6 border-t-2 border-gray-300">

    <h1 class="mx-5 mb-5 text-2xl">Lesson Progress</h1>
    <div class=" px-7" id="learnerLessonProgressChartContainer">
        <div class="flex justify-between w-full">
            <div class="w-1/2 h-[350px] ml-5 border-2 border-darthmouthgreen" id="learnerLessonProgressChartArea">
                <canvas id="learnerLessonProgressChart"></canvas>
            </div>

            <div class="ml-5 w-1/2 h-[350px] flex flex-col justify-between" id="learnerLessonProgressChartTable">
                
                <div class="border-2 h-[230px] border-darthmouthgreen overflow-y-scroll" style="max-height: 230px;">
                    <table class="w-full table-fixed">
                        <thead class="text-white text-md bg-darthmouthgreen">
                            <th class="w-1/5">Lesson Title</th>
                            <th class="w-1/5">Status</th>
                            <th class="w-1/5">Start Period</th>
                            <th class="w-1/5">Finish Period</th>
                        </thead>
                        <tbody class="overflow-y-auto text-sm text-center learnerLessonProgressRowData" style="max-height: 220px;">
                            <!-- Your table rows go here -->
                        </tbody>
                    </table>
                </div>
                
                <div class="flex items-center px-10 py-3 mt-3 border-2 border-darthmouthgreen">
                    <i class="text-4xl fa-solid fa-clock text-darthmouthgreen"></i>
                    <p class="flex items-center mx-5 mt-3 text-xl font-bold"><span class="text-darthmouthgreen text-[50px] mr-5" id="averageLearnerLessonProgress">0</span>Average Time of Completion</p>
                </div>
            </div>
            
        </div>
        
        <div class="w-full mt-5 h-[350px] ml-5 border-2 border-darthmouthgreen" id="learnerLessonProgressLineChartArea">
            <canvas id="learnerLessonProgressLineChart"></canvas>
        </div>
    </div>

    <hr class="my-6 border-t-2 border-gray-300">

    <h1 class="mx-5 mb-5 text-2xl">Activity Progress</h1>
    <div class=" px-7" id="learnerActivityProgressChartContainer">
        <div class="flex justify-between">
            <div class="w-1/2 h-[350px] ml-5 border-2 border-darthmouthgreen" id="learnerActivityProgressChartArea">
                <canvas id="learnerActivityProgressChart"></canvas>
            </div>

            <div class="w-1/2 h-[350px] ml-5 border-2 border-darthmouthgreen overflow-y-scroll" id="learnerActivityProgressChartTable">
                <table class="table-fixed">
                    <thead class="py-3 text-white text-md bg-darthmouthgreen">
                        <th class="w-1/5">Activity Title</th>
                        <th class="w-1/5">Status</th>
                        <th class="w-1/5">Start Period</th>
                        <th class="w-1/5">Finish Period</th>
                        <th class="w-1/5"></th>
                    </thead>
                    <tbody class="text-sm text-center learnerActivityProgressRowData" style="max-height: 350px;">
                
                    </tbody>
                </table>
            </div>
        </div>
        

        <div class="flex justify-between">
            <div class="w-full mt-5 h-[350px] ml-5 border-2 border-darthmouthgreen" id="learnerActivityProgressLineChartArea">
                <canvas id="learnerActivityProgressLineChart"></canvas>
            </div>
            <div class="w-1/3 mt-5 h-[350px] ml-5 border-2 border-darthmouthgreen"  id="learnerActivityProgressRemarksChartArea">
                <canvas id="learnerActivityProgressRemarksChart"></canvas>
            </div>
        </div>
       
    </div>


    <hr class="my-6 border-t-2 border-gray-300">

    <h1 class="mx-5 mb-5 text-2xl">Quiz Progress</h1>
    <div class=" px-7" id="learnerQuizProgressChartContainer">
        <div class="flex justify-between">
            <div class="w-1/3 h-[350px] ml-5 border-2 border-darthmouthgreen" id="learnerQuizProgressChartArea">
                <canvas id="learnerQuizProgressChart"></canvas>
            </div>

            <div class="w-2/3 h-[350px] ml-5 border-2 overflow-y-auto border-darthmouthgreen" id="learnerQuizProgressChartTable">
                <table>
                    <thead class="py-3 text-white text-md bg-darthmouthgreen">
                        <th class="w-2/12">Quiz Title</th>
                        <th class="w-1/12">Status</th>
                        <th class="w-1/12">Attempt</th>
                        <th class="w-1/12">Score</th>
                        <th class="w-1/12">Remarks</th>
                        <th class="w-2/12">Start Period</th>
                        <th class="w-2/12">Finish Period</th>
                        <th class="w-2/12"></th>
                    </thead>
                    <tbody class="text-sm text-center learnerQuizProgressRowData">
    
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="flex justify-between">
            <div class="w-2/3 mt-5 h-[350px] ml-5 border-2 border-darthmouthgreen" id="learnerQuizProgressLineChartArea">
                <canvas id="learnerQuizProgressLineChart"></canvas>
            </div>
            <div class="w-1/3 mt-5 h-[350px] ml-5 border-2 border-darthmouthgreen"  id="learnerQuizProgressRemarksChartArea">
                <canvas id="learnerQuizProgressRemarksChart"></canvas>
            </div>
        </div>
        

</div>

</section>
</section>

@include('partials.footer')