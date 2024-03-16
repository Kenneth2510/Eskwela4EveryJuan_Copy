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
        <h1 class="mx-5 text-2xl font-semibold">Learner {{$courseData->learner_fname}} {{$courseData->learner_lname}}'s Overview</h1>
        <hr class="my-6 border-t-2 border-gray-300">    
    </div>

    <div class="flex justify-between py-4 mt-10 border-b-2">
        <div class="flex flex-row items-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32"><path fill="currentColor" d="M12 29a1 1 0 0 1-.92-.62L6.33 17H2v-2h5a1 1 0 0 1 .92.62L12 25.28l8.06-21.63A1 1 0 0 1 21 3a1 1 0 0 1 .93.68L25.72 15H30v2h-5a1 1 0 0 1-.95-.68L21 7l-8.06 21.35A1 1 0 0 1 12 29Z"/></svg>
            <h1 class="mx-2 text-2xl font-semibold">Post Assessment</h1>
        </div>
        <h1 class="mx-2 text-2xl font-semibold">
            @if ($postAssessmentData->status === "NOT YET STARTED")
            <span class="">STATUS: NOT YET STARTED</span>
            @elseif ($postAssessmentData->status === "COMPLETED")
            <span class="">STATUS: COMPLETED</span>
            @else
            <span class="">STATUS: IN PROGRESS</span>
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

            {{-- <div class="mt-5">
                <h1 class="">Time remaining: </h1>
            </div> --}}
            
        </div>

        <div id="quiz_content_area" class="w-full overflow-y-auto px-auto">
            <div id="questionContainer" class="w-4/5 p-5 mx-auto my-5 rounded-lg">
              
            </div>
            <div id="pagination" class="mx-10 mt-4 mb-8">
                <button id="prevPage" class="px-4 py-2 text-gray-600 bg-gray-200 rounded-lg">Previous</button>
                <span id="currentPage" class="mx-4 text-lg font-semibold">Page 1</span>
                <button id="nextPage" class="px-4 py-2 text-gray-600 bg-gray-200 rounded-lg">Next</button>
            </div>
            
        
        </div>

    </div>
    
</section>
</section>

@include('partials.footer')