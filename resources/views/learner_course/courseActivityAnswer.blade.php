@extends('layouts.learner_layout')

@section('content')
<section class="w-full h-screen md:w-3/4 lg:w-10/12">
    <div class="h-full px-2 py-4 pt-24 overflow-hidden overflow-y-scroll rounded-lg shadow-lg md:pt-6">
        
        <div style="background-color:{{$mainBackgroundCol}};" class="z-50 p-2 text-white fill-white rounded-xl">
            <a href="{{ url("/learner/course/manage/$syllabus->course_id/overview") }}" class="my-2 bg-gray-300 rounded-full ">
                <svg  xmlns="http://www.w3.org/2000/svg" height="30" viewBox="0 -960 960 960" width="24"><path d="M560-240 320-480l240-240 56 56-184 184 184 184-56 56Z"/></svg>
            </a>
            <h1 class="w-1/2 py-4 text-2xl font-bold md:text-3xl lg:text-4xl"><span class="">{{ $syllabus->course_name }}</span></h1>
        {{-- subheaders --}}
            <div class="flex flex-col fill-mainwhitebg">
                <h1 class="w-1/2 py-4 text-lg font-bold md:text-xl"><span class="">{{ $syllabus->activity_title }}</span></h1>
            </div>
        </div>   
        
        <div class="mx-2">
            <div class="mt-1 text-gray-600 text-l">
                <a href="{{ url('/learner/courses') }}" class="">course></a>
                <a href="{{ url("/learner/course/$syllabus->course_id") }}">{{$syllabus->course_name}}></a>
                <a href="{{ url("/learner/course/manage/$syllabus->course_id/overview") }}">content></a>
                <a href="">{{ $syllabus->activity_title }}</a>
            </div>
            {{-- head --}}
            <div class="flex flex-col justify-between py-4 border-b-2 lg:flex-row">
                <div class="flex flex-row items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32"><path fill="currentColor" d="M12 29a1 1 0 0 1-.92-.62L6.33 17H2v-2h5a1 1 0 0 1 .92.62L12 25.28l8.06-21.63A1 1 0 0 1 21 3a1 1 0 0 1 .93.68L25.72 15H30v2h-5a1 1 0 0 1-.95-.68L21 7l-8.06 21.35A1 1 0 0 1 12 29Z"/></svg>
                    <h1 class="mx-2 text-2xl font-semibold">{{$syllabus->activity_title}}</h1>
                </div>
                <div class="text-right">
                    <p class="text-2xl font-semibold">Your Score: </p>
                    @if ($activityOutput)
                        <p class="px-4 text-4xl">{{$activityOutput->total_score ?? 'N/A'}} / {{$activity->total_score ?? 'N/A'}}</p>
                    @else
                        <p class="px-4 text-4xl">0</p>
                    @endif
                </div>
            </div>
            
            {{-- body --}}
            <div class="py-4 " id="defaultView">
                <div class="flex flex-row items-center">
                    <h3 class="my-2 text-xl font-medium">Instructions:</h3>
                </div>

                <p style="white-space: pre-wrap">{{ $activity->activity_instructions }}</p>
     
                {{-- <textarea name="activity_instructions" class="w-full max-w-full min-w-full activity_instructions h-[200px]" disabled>{{$activity->activity_instructions}}</textarea> --}}

                <div class="flex flex-row items-center mt-5">
                    <h3 class="my-2 text-xl font-medium">Criteria:</h3>
                </div>
                <div class="overflow-auto">
                    <table class="table w-full table-fixed rounded-xl">
                        <thead class="text-xl text-white bg-green-700 rounded-xl">
                            <th class="w-[150[px]]">Criteria</th>
                            <th class="w-[150[px]]">Score</th>
                            <th class="w-[150[px]]">Your Score</th>
                        </thead>
                        <tbody>
                            @forelse ($activityCriteria as $index => $criteria)
                                <tr>
                                    <td>
                                        <input type="text" class="w-10/12" value="{{ $criteria->criteria_title }}" disabled>
                                    </td>
                                    <td class="flex justify-end">
                                        <input type="text" class="flex text-center" value="{{ $criteria->score }}" disabled>
                                    </td>
                                    
                                    <!-- Assuming $activityScore is an array and its index corresponds to $criteria -->
                                    @if (isset($activityScore[$index]))
                                        <td>
                                            <input type="text" class="flex text-center" value="{{ $activityScore[$index]->score }}" disabled>
                                        </td>
                                    @else
                                        <td></td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td rowspan="3">No Criterias Found</td>
                                </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>

              
                <br>
                <br>
                <br>
                
            </div>
          
        </div>
        <!--scorearea-->
        <div class="w-full px-5">
            <h3 class="w-full my-2 text-2xl font-semibold border-b-4 border-green-900">Your Answer:</h3>
            
            @if ($activityOutput && $activityOutput->answer)
                <textarea name="" style="white-space: pre-wrap" class="mt-5 px-5 py-5 h-[300px] w-full rounded-xl border-2 border-black" readonly>{{$activityOutput->answer}}</textarea>
            @else
                <textarea id="activity_answer" name="activity_answer" style="white-space: pre-wrap" class="mt-5 px-5 py-5 h-[300px] w-full rounded-xl border-2 border-black"></textarea>
            @endif
        </div>
    
        <div class="px-5 my-10">
            <h3 class="my-2 text-2xl font-medium">Instructor's Remarks:</h3>
        @if ($activityOutput)
        <p style="white-space: pre-wrap" class="px-5">{{ $activityOutput->remarks }}</p>
        @else
        @endif
        </div>
        
        <div class="px-10 mt-[50px] flex justify-between">
            <a href="{{ url("/learner/course/content/$activity->course_id/$activity->learner_course_id/activity/$activity->syllabus_id") }}" class="flex justify-center w-1/2 py-5 mx-3 text-xl font-semibold text-white bg-darthmouthgreen hover:bg-green-900 rounded-xl">
                Return    
            </a>
        
            @if ($activityOutput && $activityOutput->answer)
                <!-- Disable the submit button if answer already exists -->
                <button class="flex justify-center w-1/2 py-5 mx-3 text-xl font-semibold text-white bg-gray-400 cursor-not-allowed rounded-xl" disabled>
                    Already Submitted
                </button>
            @else
                <!-- Enable the submit button if there is no answer -->
                <button id="submitButton" class="flex justify-center w-1/2 py-5 mx-3 text-xl font-semibold text-white bg-darthmouthgreen hover:bg-green-900 rounded-xl">
                    Submit
                </button>
            @endif
        </div>
    </div>
</section>

<div id="confirmationModal" class="fixed z-[99] top-0 left-0 flex items-center justify-center hidden w-screen h-screen bg-black bg-opacity-50">
    <div class="p-5 text-center bg-white rounded-lg">
        <p class="mb-4 text-xl font-semibold">Are you sure you want to submit?</p>
        <div class="flex justify-end">
            <button id="cancelButton" class="px-4 py-2 mr-2 bg-gray-300 rounded-md">Cancel</button>
            <button id="confirmButton" 
            data-learner-course-id="{{$activity->learner_course_id}}"
            data-course-id="{{$activity->course_id}}"
            data-syllabus-id="{{$activity->syllabus_id}}"
            data-activity-id="{{$activity->activity_id}}"
            data-activity-content-id="{{$activity->activity_content_id}}"
            data-attempt="{{$activityOutput->attempt}}"
            class="px-4 py-2 text-white rounded-md bg-darthmouthgreen">Submit</button>
        </div>
    </div>
</div>

<div id="loaderModal" class="fixed top-0 left-0 z-50 flex items-center justify-center hidden w-full h-full bg-gray-200 bg-opacity-75 ">
    <div class="flex flex-col items-center justify-center w-full h-screen p-4 bg-white rounded-lg shadow-lg modal-content md:h-1/3 lg:w-1/3">
        <span class="loading loading-spinner text-primary loading-lg"></span> 
            
        <p class="mt-5 text-xl text-darthmouthgreen">loading</p>  
    </div>
</div>
@endsection
