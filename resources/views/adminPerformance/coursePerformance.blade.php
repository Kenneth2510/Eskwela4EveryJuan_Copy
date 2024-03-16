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
        <h1 class="mx-5 text-2xl font-semibold">{{$course->course_name}}'s Overview</h1>
        <hr class="my-6 border-t-2 border-gray-300">    
    </div>


    <div class="flex p-10 mt-5" id="genInfo">
        <div class="w-1/2 mx-3 h-[300px] border-2 border-darthmouthgreen" id="totalLearnersArea">
            <div class="flex justify-center mx-10 mt-10 text-center  h-2/3 item-center">
                
                <i class="fa-solid fa-user text-darthmouthgreen text-[175px]"></i>
                <p class="mx-5 mt-3 text-2xl font-bold py-14"><span class="text-darthmouthgreen text-[125px]" id="totalLearnerCourseCount">0</span><br>Total Learner</p>
            </div>
            <div class="flex justify-center mt-5">
                <div class="flex items-center mx-3">
                    <div class="w-3 h-3 mx-3 rounded-full bg-darthmouthgreen"></div>
                    <p class="font-bold text-md">Approved: <span id="totalApprovedLearnerCourseCount" class="">0</span></p>
                </div>

                <div class="flex items-center mx-3">
                    <div class="w-3 h-3 mx-3 bg-yellow-400 rounded-full"></div>
                    <p class="font-bold text-md">Pending: <span id="totalPendingLearnerCourseCount" class="">0</span></p>
                </div>

                <div class="flex items-center mx-3">
                    <div class="w-3 h-3 mx-3 bg-red-700 rounded-full"></div>
                    <p class="font-bold text-md">Rejected: <span id="totalRejectedLearnerCourseCount" class="">0</span></p>
                </div>
            </div>
        </div>
        <div class="w-1/2 mx-3 h-[300px] border-2 border-darthmouthgreen" id="totalLearnersArea">
            <div class="flex justify-center mx-10 mt-10 text-center  h-2/3 item-center">
                
                <i class="fa-solid fa-book-bookmark text-darthmouthgreen text-[175px]"></i>
                <p class="mx-5 mt-3 text-2xl font-bold py-14"><span class="text-darthmouthgreen text-[125px]" id="totalSyllabusCount">0</span><br>Total Topics</p>
            </div>
            <div class="flex justify-center mt-5">
                <div class="flex items-center mx-3">
                    <i class="mx-3 text-2xl fa-solid fa-file text-darthmouthgreen"></i>
                    <p class="font-bold text-md">Lessons: <span id="totalLessonsCount" class="">0</span></p>
                </div>

                <div class="flex items-center mx-3">
                    <i class="mx-3 text-2xl fa-solid fa-clipboard text-darthmouthgreen"></i>
                    <p class="font-bold text-md">Activities: <span id="totalActivitiesCount" class="">0</span></p>
                </div>

                <div class="flex items-center mx-3">
                    <i class="mx-3 text-2xl fa-solid fa-pen-to-square text-darthmouthgreen"></i>
                    <p class="font-bold text-md">Quizzes: <span id="totalQuizzesCount" class="">0</span></p>
                </div>
            </div>
        </div>
    </div>

    <hr class="my-6 border-t-2 border-gray-300">

    <h1 class="mx-5 mb-5 text-2xl">Course Progress</h1>
    <div class="flex justify-between" id="learnerCourseProgressArea">
        <div class="w-3/5 h-[350px] ml-5 border-2 border-darthmouthgreen" id="learnerCourseProgressChart">
            <canvas id="learnerCourseDataChart"></canvas>
        </div>

        <div class="w-2/5 h-[350px] ml-5 border-2 border-darthmouthgreen overflow-y-scroll" id="learnerCourseListArea">
            <table id="learnerCourseTable" class="w-full table-fixed">
                <thead class="text-white bg-darthmouthgreen">
                    <th class="w-1/5">Name</th>
                    <th class="w-1/5">Date Enrolled</th>
                    <th class="w-1/5">Progress</th>
                    <th class="w-1/5"></th>
                </thead>
                <tbody class="learnerCourseRowData" style="max-height: 300px;">
                  
                </tbody>
            </table>
        </div>
        
        
    </div>

    <hr class="my-6 border-t-2 border-gray-300">

    <h1 class="mx-5 mb-5 text-2xl h-">Grades Overview</h1>
    <div class="w-full mx-3 h-[400px] overflow-y-auto border-2 border-darthmouthgreen" id="gradesheet">
        <h1 class="text-4xl font-semibold">Enrollee Gradesheet</h1>
        <div class="m-5 px-5 overflow-auto overflow-x-auto h-[350px]">
            <table id="gradesheet" class="table-fixed w-[3000px]">
                <thead class="px-3 text-center text-white bg-darthmouthgreen">
                    <th class="w-4/12 pl-5">Name</th>
                    <th class="w-4/12">Status</th>
                    <th class="w-4/12">Date Started</th>
                    <th class="w-4/12">Pre Assessment</th>
                    
                    @foreach ($activitySyllabus as $activity)
                        <th class="w-4/12">{{ $activity->activity_title }} /({{$activity->total_score}})</th>
                    @endforeach
                    
                    @foreach ($quizSyllabus as $quiz)
                        <th class="w-4/12">{{ $quiz->quiz_title }} /({{$quiz->total_score}})</th>
                    @endforeach
            
                    <th class="w-4/12">Post Assessment</th>
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
                            <td>{{$grade->pre_assessment->score}}</td>
                            
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
                            
                            <td>{{$grade->post_assessment->average_score}}</td>
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

    @if($learnerPreAssessmentData)
    <hr class="my-6 border-t-2 border-gray-300">

    <h1 class="mx-5 mb-5 text-2xl">Pre Assessment Overview</h1>
    <div class="flex p-10 mt-5" id="preAssessmentData">
        <div class="w-full h-[250px] ml-5 border-2 overflow-y-auto border-darthmouthgreen" id="preAssessmentDataTableArea">
            <table>
                <thead class="py-3 text-lg text-white bg-darthmouthgreen">
                    <th class="w-2/12">Name</th>
                    <th class="w-2/12">Date Taken</th>
                    <th class="w-1/12">Status</th>
                    <th class="w-1/12">Score</th>
                    <th class="w-1/12">Remarks</th>
                    <th class="w-2/12">Finish Period</th>
                    <th class="w-2/12"></th>
                </thead>
                <tbody class="text-lg text-center">
                    @forelse ($learnerPreAssessmentData as $preAssessmentData)
                    <tr>
                        <td>{{$preAssessmentData->learner_fname}} {{$preAssessmentData->learner_lname}}</td>
                        <td>{{$preAssessmentData->start_period}}</td>
                        <td>{{$preAssessmentData->status}}</td>
                        <td>{{$preAssessmentData->score}}</td>
                        <td>{{$preAssessmentData->remarks}}</td>
                        <td>{{$preAssessmentData->finish_period}}</td>
                        <td>
                            <a href="{{  url("/admin/performance/learners/view/$preAssessmentData->course_id/$preAssessmentData->learner_course_id/pre_assessment/view_output")}}" class="px-5 py-3 text-white bg-darthmouthgreen rounded-xl hover:bg-white hover:text-darthmouthgreen hover:border hover:border-darthmouthgreen">view</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td>No Data available</td>
                    </tr>
                    @endforelse

                </tbody>
            </table>
        </div>
    </div>
    @endif

    @if($learnerPostAssessmentData)
    <hr class="my-6 border-t-2 border-gray-300">

    <h1 class="mx-5 mb-5 text-2xl">Post Assessment Overview</h1>
    <div class="flex p-10 mt-5" id="postAssessmentData">
        <div class="w-full h-[250px] ml-5 border-2 overflow-y-auto border-darthmouthgreen" id="postAssessmentDataTableArea">
            <table>
                <thead class="py-3 text-lg text-white bg-darthmouthgreen">
                    
                    <th class="w-2/12">Name</th>
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
                        <td>{{$postAssessment->learner_fname}} {{$postAssessment->learner_lname}}</td>
                        <td class="py-1">{{$postAssessment->start_period}}</td>
                        <td>{{$postAssessment->status}}</td>
                        <td>{{$postAssessment->score}}</td>
                        <td>{{$postAssessment->attempt}}</td>
                        <td>{{$postAssessment->remarks}}</td>
                        <td>{{$postAssessment->finish_period}}</td>
                        <td>
                            <a href="{{  url("/admin/performance/learners/view/$postAssessment->course_id/$postAssessment->learner_course_id/post_assessment/view_output/$postAssessment->attempt")}}" class="px-5 py-3 text-white bg-darthmouthgreen rounded-xl hover:bg-white hover:text-darthmouthgreen hover:border hover:border-darthmouthgreen">view</a>
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
    <h1 class="mx-5 mb-5 text-2xl">Syllabus Overview</h1>

    <div class="flex flex-col items-center justify-center w-full mt-5" id="topicDetailsArea">
        <div class="w-full" id="selectTopicArea">
            <select name="" class="w-full px-5 py-3 text-lg border-2 border-darthmouthgreen" id="selectTopic">
                <option value="" disabled selected>Choose Topic</option>
                @foreach ($syllabus as $topic)
                    <option value="{{ $topic->syllabus_id }}">{{ $topic->topic_title }}</option>
                @endforeach
            </select>
        </div>

        <div class="w-full mx-10 mt-5 h-[400px] border-2 border-darthmouthgreen" id="learnerCourseTopicProgressChart">
            <canvas id="learnerTopicDataChart"></canvas>
        </div>

        <div class="flex flex-col w-full px-10 mt-5" id="learnerCourseTopicProgressTable">
            <a href="" method="GET" class="text-xl text-right underline text-darthmouthgreen hover:text-green-950">view more details</a>
            <table id="learnerSyllabusTable" class="w-full mt-5">
                <thead class="text-white bg-darthmouthgreen">
                    <th class="w-1/5">Name</th>
                    <th class="w-1/5">Date Enrolled</th>
                    <th class="w-1/5">Progress</th>
                    <th class="w-1/5">Start Date</th>
                    <th class="w-1/5">Finish Date</th>
                    <th class="w-1/5"></th>
                </thead>
                <tbody class="learnerSyllabusRowData">
               
                </tbody>
            </table>
        </div>
    </div>

    
</section>
</section>

@include('partials.footer')