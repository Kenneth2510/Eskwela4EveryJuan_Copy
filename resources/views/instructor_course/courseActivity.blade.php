@extends('layouts.instructor_layout')

@section('content')
    {{-- MAIN --}}
    <section class="w-full h-screen md:w-3/4 lg:w-10/12">
        <div class="relative w-full h-full px-2 py-4 pt-24 overflow-hidden overflow-y-scroll rounded-lg shadow-lg md:pt-4">
            <div style="background-color:{{$mainBackgroundCol}}" class="p-2 text-white fill-white rounded-xl">
                <a href="{{ url("/instructor/course/content/$course->course_id") }}" class="my-2 bg-gray-300 rounded-full ">
                    <svg  xmlns="http://www.w3.org/2000/svg" height="30" viewBox="0 -960 960 960" width="24"><path d="M560-240 320-480l240-240 56 56-184 184 184 184-56 56Z"/></svg>
                </a>
                <h1 class="w-1/2 py-4 text-2xl font-bold md:text-4xl"><span class="">{{ $course->course_name }}</span></h1>
            {{-- subheaders --}}
                <div class="flex flex-col fill-mainwhitebg">
                    <h1 class="w-1/2 py-4 text-lg"><span class="">{{ $activityInfo->activity_title }}</span></h1>
                </div>
            </div>
            
            <div class="mx-2">
                <div class="mt-1 text-gray-600 text-l">
                    <a href="{{ url('/instructor/courses') }}" class="">course></a>
                    <a href="{{ url("/instructor/course/$course->course_id") }}">{{$course->course_name}}></a>
                    <a href="{{ url("/instructor/course/content/$course->course_id") }}">content></a>
                    <a href="">{{ $activityInfo->activity_title }}</a>
                </div>
                {{-- head --}}
                <div class="flex flex-row items-center py-4 border-b-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32"><path fill="currentColor" d="M12 29a1 1 0 0 1-.92-.62L6.33 17H2v-2h5a1 1 0 0 1 .92.62L12 25.28l8.06-21.63A1 1 0 0 1 21 3a1 1 0 0 1 .93.68L25.72 15H30v2h-5a1 1 0 0 1-.95-.68L21 7l-8.06 21.35A1 1 0 0 1 12 29Z"/></svg>
                    <h1 class="mx-2 text-xl font-semibold">{{$activityInfo->activity_title}}</h1>
                </div>
                
                {{-- body --}}
                <div class="py-4 " id="defaultView">
                    <div class="flex flex-row items-center">
                        <h3 class="my-2 text-xl font-medium md:text-2xl">Instructions:</h3>
                        <button id="editInstructionsBtn" class="hidden">
                            <svg class="mx-2" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M80 0v-160h800V0H80Zm80-240v-150l362-362 150 150-362 362H160Zm80-80h36l284-282-38-38-282 284v36Zm477-326L567-796l72-72q11-12 28-11.5t28 11.5l94 94q11 11 11 27.5T789-718l-72 72ZM240-320Z"/></svg>
                        </button>
                        
                    </div>
                    @forelse($activityContent as $activity)
                    {{-- <p>{{ $activity->activity_instructions }}</p> --}}
                    {{-- <input type="text" name="activity_instructions" class="w-full max-w-full min-w-full activity_instructions" value="{{$activity->activity_instructions}}" disabled> --}}

                    <textarea name="activity_instructions" class="w-full max-w-full min-w-full activity_instructions max-h-[200px]" disabled>{{$activity->activity_instructions}}</textarea>
                    <div class="hidden mt-3 editInstructions_clickedBtn">
                        <button class="px-3 py-3 text-white bg-green-600 saveInstructionsBtn hover:bg-green-900 rounded-xl">Save</button>
                        <button class="px-3 py-3 text-white bg-red-600 cancelInstructionsBtn hover:bg-red-900 rounded-xl">Cancel</button>
                    </div>

                    <div class="flex flex-row items-center mt-5">
                        <h3 class="my-2 text-xl font-medium md:text-2xl">Criteria:</h3>
                        <button id="editCriteriaBtn" class="hidden">
                            <svg class="mx-2" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M80 0v-160h800V0H80Zm80-240v-150l362-362 150 150-362 362H160Zm80-80h36l284-282-38-38-282 284v36Zm477-326L567-796l72-72q11-12 28-11.5t28 11.5l94 94q11 11 11 27.5T789-718l-72 72ZM240-320Z"/></svg>
                        </button>
                        
                    </div>
                    <table class="table w-full table-fixed rounded-xl">
                        <thead class="w-full text-xl text-white bg-green-700 rounded-xl">
                            <th class="w-2/5">Criteria</th>
                            <th class="w-1/5">Score</th>
                            <th class="w-1/5"></th>
                        </thead>
                        <tbody>
                            @forelse ($activityContentCriteria as $criteria)
                            <tr>
                                <td>
                                    <input type="text" class="w-full" value="{{ $criteria->criteria_title }}" disabled>
                                </td>
                                <td class="flex justify-end">
                                    <input type="text" class="flex w-full text-center" value="{{ $criteria->score }}" disabled></td>
                                <td>
                                    <button class="hidden px-3 py-1 mx-2 font-semibold text-white bg-green-600 rounded-xl editCriteriaBtn hover:bg-green-900">Edit</button>
                                    <div class="flex edit_btns">
                                        <button class="hidden px-3 py-1 mx-2 font-semibold text-white bg-green-600 rounded-xl saveCriteriaBtn hover:bg-green-900">Save</button>
                                        <button class="hidden px-3 py-1 mx-2 font-semibold text-white bg-red-600 rounded-xl deleteCriteriaBtn hover:bg-red-900">Delete</button>
                                        <button class="hidden px-3 py-1 mx-2 font-semibold text-white bg-red-600 rounded-xl cancelCriteriaBtn hover:bg-red-900">Cancel</button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td rowspan="3">No Criterias Found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <button id="addNewCriteria" class="hidden px-3 py-1 mx-2 font-semibold text-white bg-darthmouthgreen rounded-xl hover:bg-green-900">Add Criteria</button>
                    <div class="hidden mt-3" id="editCriteria_clickedBtn"> 
                        <button id="saveCriteriaBtn" data-activity-content-id="{{$activity->activity_content_id}}" class="p-3 text-white rounded-xl bg-darthmouthgreen hover:bg-green-900">Save Criteria</button>
                        <button id="cancelCriteriaBtn" class="p-3 text-white bg-red-600 hover:bg-red-900 rounded-xl">Cancel</button>
                    </div>
                    <br>
                    <br>
                    <br>
                    <div class="">
                       
                        <p class="text-2xl font-semibold">Overall Total Score: </p>
                        <input type="number" id="overallScore_input py-5 px-5 border-2 border-green-400" class="w-full text-4xl" value="{{$activity->total_score}}" disabled> 
                        <p class="px-10 text-4xl">/ {{$activity->total_score}}</p>
                        <div class="hidden mt-3" id="editTotalScore_clickedBtn"> 
                            <button id="saveTotalScoreBtn" class="px-5 py-3 text-white rounded-xl bg-darthmouthgreen hover:bg-green-900">Save Score</button>
                            <button id="cancelTotalScoreBtn" class="px-5 py-3 text-white bg-red-600 hover:bg-red-900 rounded-xl">Cancel</button>
                        </div>
                    </div>
                    
                    
                    @empty  
                    <p>No instructions given</p>
                    @endforelse
                    

                </div>
                <div class="my-2" id="studentsList" data-course-id="{{$activityInfo->course_id}}" data-syllabus-id="{{$activityInfo->syllabus_id}}" data-topic-id="{{$activityInfo->topic_id}}">
                </div>
                <div class="my-2" id="studentsStatistics">

                </div>
            </div>

            <div class="flex justify-between">
                <button id="editActivityBtn" class="w-1/2 h-16 px-5 py-3 mx-3 text-white bg-darthmouthgreen hover:bg-green-900 rounded-xl" data-course-id="{{$course->course_id}}" data-syllabus-id="{{$activityInfo->syllabus_id}}" data-topic_id="{{$activityInfo->topic_id}}">Edit</button>
                <div class="hidden w-1/2 h-16 mx-5" id="editActivity_clickedBtns">
                    <button id="saveActivityBtn" class="w-full h-16 px-5 py-3 text-white bg-darthmouthgreen hover:bg-green-900 rounded-xl">Finish Editing</button>
                </div>
                <button id="viewResponseActivity" class="w-1/2 h-16 px-5 py-3 mx-3 text-white bg-darthmouthgreen hover:bg-green-900 rounded-xl" data-course-id="{{$course->course_id}}" data-syllabus-id="{{$activityInfo->syllabus_id}}" data-topic_id="{{$activityInfo->topic_id}}">View Responses</button>

            </div>
            
        </div>
        
</section>

<div id="responsesModal" class="fixed top-0 left-0 z-50 flex items-center justify-center hidden w-full h-full bg-gray-200 bg-opacity-75">
    <div class="w-3/5 max-h-[600px] p-4 bg-white rounded-lg shadow-lg modal-content">

        <div class="flex justify-end w-full">
            <button class="exitResponsesModalBtn">
                <i class="text-xl fa-solid fa-xmark" style="color: #949494;"></i>
            </button>
        </div>

        <h1 class="text-2xl font-bold ">View All Responses</h1>
        
        <div id="responsesContent" class="mt-5 overflow-y-auto ">
            <table class="w-full h-11/12 overflow-y-auto">
                <thead class="text-white bg-darthmouthgreen">
                    <th class="w-1/12">Enrollee ID</th>
                    <th class="w-1/12">Learner ID</th>
                    <th class="w-2/12">Name</th>
                    <th class="w-1/12">Attempt</th>
                    <th class="w-2/12">Attempt Taken</th>
                    <th class="w-1/12">Score</th>
                    <th class="w-2/12">Status</th>
                    <th class="w-1/12">Mark</th>
                    <th class="w-1/12"></th>
                </thead>

                <tbody id="responsesRowDataArea" class="">  
                    {{-- <tr class="text-center">
                        <td class="py-5 my-3">1</td>
                        <td>Kenneth Timblaco</td>
                        <td>1</td>
                        <td>December 10, 2023</td>
                        <td>5/6</td>
                        <td>
                            <button class="px-5 py-3 text-white bg-darthmouthgreen hover:bg-green-950 rounded-xl">View</button>
                        </td>
                    </tr> --}}
                </tbody>
            </table>
        </div>

    </div>

  <div id="loaderModal" class="fixed top-0 left-0 z-50 flex items-center justify-center hidden w-full h-full bg-gray-200 bg-opacity-75 ">
      <div class="flex flex-col items-center justify-center w-full h-screen p-4 bg-white rounded-lg shadow-lg modal-content md:h-1/3 lg:w-1/3">
          <span class="loading loading-spinner text-primary loading-lg"></span> 

          <p class="mt-5 text-xl text-darthmouthgreen">loading</p>  
      </div>
  </div>

</div>
@endsection
