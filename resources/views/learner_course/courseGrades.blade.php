@extends('layouts.learner_layout')

@section('content')

<section class="w-full h-screen md:w-3/4 lg:w-10/12">
    <div class="h-full px-2 py-4 pt-24 overflow-hidden overflow-y-scroll rounded-lg shadow-lg md:pt-6">
        
        <div style="background-color:{{$mainBackgroundCol}};" class="p-2 text-white fill-white rounded-xl">
            <a href="{{ url("/learner/course/manage/$courseData->course_id/overview") }}" class="my-2 bg-gray-300 rounded-full ">
                <svg  xmlns="http://www.w3.org/2000/svg" height="30" viewBox="0 -960 960 960" width="24"><path d="M560-240 320-480l240-240 56 56-184 184 184 184-56 56Z"/></svg>
            </a>
            <h1 class="w-1/2 py-4 text-2xl font-bold md:text-3xl lg:text-4xl"><span class="">{{ $courseData->course_name }}</span></h1>
        {{-- subheaders --}}
            <div class="flex flex-col justify-between fill-mainwhitebg">
                <h1 class="w-1/2 py-4 text-lg font-bold md:text-xl"><span class="">COURSE GRADESHEET</span></h1>
            </div>
        </div> 


        <div class="mx-2">
            <div class="mt-1 text-gray-600 text-l">
                <a href="{{ url('/learner/courses') }}" class="">course></a>
                <a href="{{ url("/learner/course/$courseData->course_id") }}">{{$courseData->course_name}}></a>
                <a href="{{ url("/learner/course/manage/$courseData->course_id/overview") }}">content></a>
                <a href="">Grades</a>
            </div>
            {{-- head --}}
            <div class="flex flex-col justify-between py-4 border-b-2 lg:flex-row">
                <div class="flex flex-row items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32"><path fill="currentColor" d="M12 29a1 1 0 0 1-.92-.62L6.33 17H2v-2h5a1 1 0 0 1 .92.62L12 25.28l8.06-21.63A1 1 0 0 1 21 3a1 1 0 0 1 .93.68L25.72 15H30v2h-5a1 1 0 0 1-.95-.68L21 7l-8.06 21.35A1 1 0 0 1 12 29Z"/></svg>
                    <h1 class="mx-2 text-2xl font-semibold" id="gradesheetTitle" data-course-id="{{$courseData->course_id}}">Gradesheet</h1>
                </div>
                <h1 class="mx-2 text-xl font-semibold">
                    @if ($courseData->course_progress === "NOT YET STARTED")
                    <p >STATUS: <span class=" text-danger">NOT YET STARTED</span></p>
                    @elseif ($courseData->course_progress === "COMPLETED")
                    <p >STATUS: <span class=" text-primary">COMPLETED</span></p>
                    @else
                    <p >STATUS: <span class=" text-warning">IN PROGRESS</span></p>
                    @endif
                </h1>
            </div>
        </div>

        <div class="mt-10">
            <h1 class="mx-2 text-xl font-semibold md:text-2xl lg:text-3xl">Pre Assessment</h1>
            <h1 class="py-5 mx-16 text-4xl font-bold text-green-600">{{$preAssessmentLearnerSumScore}} <span class="text-2xl font-bold text-black"> / {{$totalScoreCount_pre_assessment}}</span></h1>
        </div>
                    

        <div class="mt-10">
            <h1 class="mx-2 text-xl font-semibold md:text-2xl lg:text-3xl">Lessons</h1>
            <div class="overflow-auto">
                <table class="table w-full py-5 text-center table-fixed">
                    <thead>
                        <th class="w-[150px]">Lesson Title</th>
                        <th class="w-[150px]">Start Date</th>
                        <th class="w-[150px]">Finish Date</th>
                    </thead>
                    <tbody>
                        @foreach ($learnerLessonsData as $lesson)
                            <tr>
                                <td>{{ $lesson->lesson_title }}</td>
                                <td>{{ $lesson->start_period }}</td>
                                <td>{{ $lesson->finish_period }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>                
            </div>

        </div>

        <div class="mt-10">
            <h1 class="mx-2 text-xl font-semibold md:text-2xl lg:text-3xl">Activities</h1>
            <div class="overflow-auto">
                <table class="table w-full py-5 text-center table-fixed">
                    <thead>
                        <th class="w-[150px]">Activity Title</th>
                        <th class="w-[150px]">Score</th>
                    </thead>
                    <tbody>
                        @foreach ($activityScoresData as $activity)
                            <tr>
                                <td>{{ $activity->activity_title }}</td>
                                <td>{{ $activity->average_score }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>                
            </div>

        </div>

        <div class="mt-10">
            <h1 class="mx-2 text-xl font-semibold md:text-2xl lg:text-3xl">Quizzes</h1>
            <div class="overflow-auto">
                <table class="table w-full py-5 text-center table-fixed">
                    <thead>
                        <th class="w-[150px]">Quiz Title</th>
                        <th class="w-[150px]">Score</th>
                    </thead>
                    <tbody>
                        @foreach ($quizScoresData as $quiz)
                            <tr>
                                <td>{{ $quiz->quiz_title }}</td>
                                <td>{{ $quiz->average_score }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>                
            </div>

        </div>

        <div class="mt-10">
            <h1 class="mx-2 text-xl font-semibold md:text-2xl lg:text-3xl">Post Assessment</h1>
            <h1 class="py-5 mx-16 text-4xl font-bold text-green-600">{{$postAssessmentLearnerSumScore}} <span class="text-2xl font-bold text-black"> / {{$totalScoreCount_post_assessment}}</span></h1>
        </div>

        
        @if($courseData->course_progress === 'COMPLETED')
        <hr class="my-6 border-t-2 border-gray-300">
        <h1 class="mx-2 text-xl font-semibold md:text-2xl lg:text-3xl">Computation of Grades</h1>
        <div class="px-10 mt-3">

            <h1 class="text-xl font-bold">Activities</h1>
            <p class="py-5 mx-16 text-xl font-bold">[[ {{ $activityLearnerSumScore }}  / {{ $activityTotalSum }} ] x 100 ] x 35% = {{ $activityGrade }}%</p>

            
            <h1 class="text-xl font-bold">Quizzes</h1>
            <p class="py-5 mx-16 text-xl font-bold">[[ {{ $quizLearnerSumScore }}  / {{ $quizTotalSum }} ] x 100 ] x 35% = {{ $quizGrade }}%</p>


            <h1 class="text-xl font-bold">Post Assessment</h1>
            <p class="py-5 mx-16 text-xl font-bold">[[ {{ $postAssessmentLearnerSumScore }}  / {{ $totalScoreCount_post_assessment }} ] x 100 ] x 30% = {{ $postAssessmentScoreGrade }}%</p>

            <h1 class="text-xl font-bold text-green-600">Overall Grade</h1>
            <p class="py-5 mx-16 text-xl font-bold text-green-600">{{ $activityGrade }} + {{ $quizGrade }} + {{$postAssessmentScoreGrade}} = {{ $totalGrade }}%</p>
            

            <hr class="my-6 border-t-2 border-gray-300">
            <h1 class="text-2xl font-bold">Final Grade: <span class="text-green-600">{{$totalGrade}}%</span></h1>
            <h1 class="text-2xl font-bold">Remarks: <span class="text-green-600">{{$remarks}}</span></h1>
        </div>
        @endif
    </div>
</section>

@include('partials.chatbot')
@endsection

