@extends('layouts.learner_layout')

@section('content')
<section class="w-full h-screen md:w-3/4 lg:w-10/12">
    <div class="h-full px-2 py-4 pt-24 overflow-auto rounded-lg shadow-lg md:pt-6">
        
        <div style="background-color:{{$mainBackgroundCol}};" class="z-50 p-2 text-white fill-white rounded-xl">
            <a href="{{ url("/learner/course/manage/$learnerCourseData->course_id/overview") }}" class="my-2 bg-gray-300 rounded-full ">
                <svg  xmlns="http://www.w3.org/2000/svg" height="30" viewBox="0 -960 960 960" width="24"><path d="M560-240 320-480l240-240 56 56-184 184 184 184-56 56Z"/></svg>
            </a>
            <h1 class="w-1/2 py-4 text-2xl font-bold md:text-3xl lg:text-4xl"><span class="">{{ $learnerCourseData->course_name }}</span></h1>
        {{-- subheaders --}}
            <div class="flex flex-col justify-between fill-mainwhitebg">
                <h1 class="w-1/2 py-4 text-lg font-bold md:text-xl"><span class="">COURSE PRE ASSESSMENT</span></h1>
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
                    <h1 class="mx-2 text-2xl font-semibold">Pre Assessment</h1>
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


            {{-- main content --}}
            <div class="flex mt-5">
                <div class="w-2/6 border-r-2 border-green-200 px-auto" id="quiz_info_area">
                    <div class="grid grid-cols-5 gap-2 px-3 py-5 mx-5 mt-5 border-2 border-gray-200" id="isAnsweredMeter">
                        {{-- <div class="flex items-center justify-center question_isAnswered w-[35px] h-[45px] hover:cursor-pointer border border-darthmouthgreen transition-all duration-300">1</div>
                        <div class="flex items-center justify-center question_isAnswered w-[35px] h-[45px] hover:cursor-pointer border border-darthmouthgreen transition-all duration-300">2</div>
                        <div class="flex items-center justify-center question_isAnswered w-[35px] h-[45px] hover:cursor-pointer border border-darthmouthgreen transition-all duration-300">3</div>
                        <div class="flex items-center justify-center question_isAnswered w-[35px] h-[45px] hover:cursor-pointer border border-darthmouthgreen transition-all duration-300">4</div>
                        <div class="flex items-center justify-center question_isAnswered w-[35px] h-[45px] hover:cursor-pointer border border-darthmouthgreen transition-all duration-300">5</div> --}}
                    </div>

                    <div class="mt-5" id="timerArea">
                        <h1 class="">Time remaining: </h1>
                    </div>
                    
                </div>
                <div id="quiz_content_area" class="w-full overflow-y-auto px-auto">
                    <div id="questionContainer" class="w-4/5 p-5 mx-auto my-5 rounded-lg">
                        
                        {{-- <div class="px-3 py-5 my-5 border-2 rounded-lg questionData border-darthmouthgreen">
                            <div class="questionContent">
                                <h6 class="text-right opacity-40">Question 1</h6>
                                <p class="p-2 text-xl font-normal font-semibold">question 1</p>
                            </div>
                            <div class="mt-2 text-lg questionChoices">
                                <input type="radio" name="1" class="w-5 h-5 mx-3 questionChoice">Option 1<br>
                                <input type="radio" name="1" class="w-5 h-5 mx-3 questionChoice">Option 1<br>
                                <input type="radio" name="1" class="w-5 h-5 mx-3 questionChoice">Option 1<br>
                            </div>
                        </div> --}}


                        {{--<div class="px-3 py-5 my-5 border-2 rounded-lg questionData border-darthmouthgreen">
                            <div class="questionContent">
                                <h6 class="text-right opacity-40">Question 3</h6>
                                <p class="p-2 text-xl font-normal font-semibold">question 3</p>
                            </div>
                            <div class="mt-2 text-lg questionChoices">
                                <textarea type="text" class="w-full p-3 text-lg border-2 border-gray-200 identificationAns " placeholder=""></textarea>
                            </div>
                        </div> --}}

                    </div>
                    <div id="pagination" class="mx-10 mt-4 mb-8">
                        <button id="prevPage" class="px-4 py-2 text-white bg-gray-600 rounded-lg hover:bg-white hover:text-gray-600 hover:border hover:border-gray-600 ">Previous</button>
                        <span id="currentPage" class="mx-4 text-lg font-semibold">Page 1</span>
                        <button id="nextPage" class="px-4 py-2 text-white bg-gray-600 rounded-lg hover:bg-white hover:text-gray-600 hover:border hover:border-gray-600 ">Next</button>
                    </div>
                    
                
                </div>

            </div>

            <div class="w-full text-center" id="quizSubmitBtn">
                <button class="btn btn-primary">Submit Quiz</button>
            </div>

        </div>

    </div>
</section>

<div id="confirmSubmitQuizModal" class="fixed top-0 left-0 z-50 flex items-center justify-center hidden w-full h-full bg-gray-200 bg-opacity-75 ">
    <div class="modal-content bg-white p-4 rounded-lg shadow-lg w-[500px]">
        <div class="flex justify-end w-full">
            <button class="cancelConfirmSubmitQuiz">
                <i class="text-xl fa-solid fa-xmark" style="color: #949494;"></i>
            </button>
        </div>

        <h2 class="mb-2 text-xl font-semibold">Are you sure you want to submit your assessment?</h2>

        <p class="text-gray-600">Once you submit, you won't be able to make any changes. Make sure you have answered all the questions.</p>

        <div class="flex justify-center w-full mt-5">
            <button id="confirmSubmitQuizBtn" class="px-4 py-2 mx-2 mt-4 text-white rounded bg-seagreen hover:bg-darkenedColor">Submit Quiz</button>
            <button id="" class="px-4 py-2 mx-2 mt-4 text-white bg-red-500 rounded cancelConfirmSubmitQuiz">Cancel</button>
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
