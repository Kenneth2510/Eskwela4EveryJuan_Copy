@extends('layouts.learner_layout')

@section('content')
<section class="w-full h-screen md:w-3/4 lg:w-10/12">
    <div class="h-full px-2 py-4 pt-24 overflow-hidden overflow-y-scroll rounded-lg shadow-lg md:pt-6">
        
        <div style="background-color:{{$mainBackgroundCol}};" class="z-50 p-2 text-white fill-white rounded-xl">
            <a href="{{ url("/learner/course/manage/$learnerCourseData->course_id/overview") }}" class="my-2 bg-gray-300 rounded-full ">
                <svg  xmlns="http://www.w3.org/2000/svg" height="30" viewBox="0 -960 960 960" width="24"><path d="M560-240 320-480l240-240 56 56-184 184 184 184-56 56Z"/></svg>
            </a>
            <h1 class="w-1/2 py-4 text-2xl font-bold md:text-3xl lg:text-4xl"><span class="">{{ $learnerCourseData->course_name }}</span></h1>
        {{-- subheaders --}}
            <div class="flex flex-col justify-between fill-mainwhitebg">
                <h1 class="w-1/2 py-4 text-lg font-bold md:text-xl"><span class="">COURSE GRADESHEET</span></h1>
            </div>
        </div> 

        <div class="mx-2">
            <div class="mt-1 text-gray-600">
                <a href="{{ url('/learner/courses') }}" class="">course></a>
                <a href="{{ url("/learner/course/$learnerCourseData->course_id") }}">{{$learnerCourseData->course_name}}></a>
                <a href="{{ url("/learner/course/manage/$learnerCourseData->course_id/overview") }}">content></a>
                <a href="">Pre Assessment</a>
            </div>
            {{-- head --}}
            <div class="flex flex-col justify-between py-4 border-b-2 lg:flex-row">
                <div class="flex flex-row items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32"><path fill="currentColor" d="M12 29a1 1 0 0 1-.92-.62L6.33 17H2v-2h5a1 1 0 0 1 .92.62L12 25.28l8.06-21.63A1 1 0 0 1 21 3a1 1 0 0 1 .93.68L25.72 15H30v2h-5a1 1 0 0 1-.95-.68L21 7l-8.06 21.35A1 1 0 0 1 12 29Z"/></svg>
                    <h1 class="mx-2 text-2xl font-semibold" id="titleArea" data-course-id="{{$learnerCourseData->course_id}}">Pre Assessment</h1>
                </div>
                <h1 class="mx-2 text-xl font-semibold">
                    @if ($preAssessmentData->status === "NOT YET STARTED")
                    <p>STATUS: <span class="text-danger">NOT YET STARTED</span></p>
                    @elseif ($preAssessmentData->status === "COMPLETED")
                    <p>STATUS: <span class="text-primary">COMPLETED</span></p>
                    @else
                    <p>STATUS: <span class="text-warning">IN PROGRESS</span></p>
                    @endif
                </h1>
            </div>

            {{-- <div class="flex flex-row items-center mt-5">
                <h3 class="my-2 text-xl font-medium">Coverage:</h3>
            </div>

            <div id="coverageArea" class="mt-5">
                <table class="w-full">
                    <thead class="h-10 text-2xl text-white bg-green-700 rounded-xl">
                      
                        <th class="w-4/5">Title</th>
                        <th class="w-3/5"></th>
                    </thead>

                    <tbody class="referenceTable">
           
                        @forelse ($quizReferenceData as $reference)
                        <tr class="h-16 py-5 mt-5">
                         
                            <td class="w-4/5">
                            <p class="mx-10 text-lg">{{$reference->topic_title}}</p>
                            </td>
                        
                        </tr>
                        @empty
                        <tr>
                            <td rowspan="3">No Criterias Found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div> --}}

            <div class="mt-16" id="durationArea">
                <h3 class="my-2 text-xl font-medium">Pre Assessment Attempt Duration:</h3>
                <div class="flex flex-col justify-start w-1/2 md:flex-row">
                    <div class="w-1/3">
                        <label class="text-lg" for="hours">Hours:</label>
                        <input disabled class="px-1 text-lg border-2 border-gray-400 rounded-lg duration_input" type="number" id="hours" name="hours" min="0" max="24" placeholder="0" value="0" required>                        
                    </div>
                    <div class="w-1/3">
                        <label class="text-lg" for="minutes">Minutes:</label>
                        <input disabled class="px-1 text-lg border-2 border-gray-400 rounded-lg duration_input" type="number" id="minutes" name="minutes" min="0" max="59" placeholder="0" value="30" required>                        
                    </div>
                    <div class="w-1/3">
                        <label class="text-lg" for="seconds">Seconds:</label>
                        <input disabled class="px-1 text-lg border-2 border-gray-400 rounded-lg duration_input" type="number" id="seconds" name="seconds" min="0" max="59" placeholder="0" value="0" required>                        
                    </div>
                </div>
            </div>

            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    // Get the milliseconds duration from your controller
                    let durationInMilliseconds = {{$preAssessmentData->max_duration}};
            
                    // Calculate hours, minutes, and seconds
                    let hours = Math.floor(durationInMilliseconds / 3600000);
                    let minutes = Math.floor((durationInMilliseconds % 3600000) / 60000);
                    let seconds = Math.floor((durationInMilliseconds % 60000) / 1000);
            
                    // Set the values in the input fields
                    document.getElementById('hours').value = hours;
                    document.getElementById('minutes').value = minutes;
                    document.getElementById('seconds').value = seconds;
                });
            </script>

 
            <div class="px-10 mt-8" id="score_area">
                {{-- <h1 class="mb-2 text-2xl font-semibold">Attempt Number: {{$quizAttemptData->attempt}}</h1> --}}

                @if($preAssessmentData->remarks)
                <h1 class="mb-2 text-2xl font-semibold">Attempt Taken on {{$preAssessmentData->start_period}}</h1>
                @endif
                <div class="p-6 bg-gray-100 shadow-md rounded-xl">
                    <h1 class="mb-4 text-3xl font-bold">Score:</h1>
                    <h1 class="text-4xl font-bold text-green-600">{{$preAssessmentData->score}} <span class="text-2xl font-bold text-black"> / {{ $questionsCount }}</span></h1>
                    
                    <div class="my-5">
                        <h1 class="text-xl font-semibold">Remarks:</h1>
   
                            <span class="mx-2 text-2xl font-semibold {{ in_array($preAssessmentData->remarks, ['Excellent', 'Very Good', 'Good']) ? 'text-dartmouthgreen' : 'text-red-600' }}">
                                {{ $preAssessmentData->remarks }}
                            </span>
                        </h1>
                        
                    </div>

                    <div class="my-3">
                        @if($preAssessmentData->remarks)
                        <a href="{{ url("/learner/course/content/$learnerCourseData->course_id/$learnerCourseData->learner_course_id/pre_assessment/view_output") }}" method="GET" class="px-5 py-3 text-lg text-white bg-darthmouthgreen hover:bg-green-950 rounded-xl">
                            View Output
                        </a> 
                        @endif
                    </div>
                    
                      
                </div>
            </div>


        <div class="mt-[50px] flex justify-between items-center flex-col md:flex-row space-y-2 md:space-y-0 md:space-x-2">
            {{-- @if(count($learnerQuizProgressData) == 1) --}}
                    
                    @if ($preAssessmentData->status == 'COMPLETED')
                        <!-- has attempt 1 only and complete -->
                        {{-- @if ($preAssessmentData->remarks == 'PASS') --}}
                            <!-- has attempt 1 only and pass -->
                        <a href="{{ url("/learner/course/manage/$learnerCourseData->course_id/overview") }}" class="flex justify-center w-1/2 py-5 mx-3 font-semibold text-white bg-darthmouthgreen hover:bg-green-900 rounded-xl">
                            Return    
                        </a>

                        <a href="{{ url("/learner/course/content/$learnerCourseData->course_id/$learnerCourseData->learner_course_id/pre_assessment/view_output") }}" class="flex justify-center w-1/2 py-5 mx-3 font-semibold text-white bg-gray-400 opacity-50 cursor-not-allowed rounded-xl">
                        View Output
                        </a>

                        {{-- @endif --}}

                    @else
                        <!-- has attempt 1 only and not yet started -->
                    <a href="{{ url("/learner/course/manage/$learnerCourseData->course_id/overview") }}" class="flex justify-center w-1/2 py-5 mx-3 font-semibold text-white bg-darthmouthgreen hover:bg-green-900 rounded-xl">
                        Return    
                    </a>

                    <a href="{{ url("/learner/course/content/$learnerCourseData->course_id/$learnerCourseData->learner_course_id/pre_assessment/answer") }}" class="flex justify-center w-1/2 py-5 mx-3 font-semibold text-white bg-darthmouthgreen hover:bg-green-900 rounded-xl">
                        Answer Now
                    </a>  

                    @endif

            {{-- @endif --}}
        </div>
    </div>
</section>
@include('partials.chatbot')

@endsection
