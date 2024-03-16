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
        <h1 class="mx-5 text-2xl font-semibold">Learner Overview</h1>
        <hr class="my-6 border-t-2 border-gray-300">    
    </div>

    <div class="w-full mt-10" id="learnerOverviewArea">
        <div class="flex mt-5" id="learnerOverviewArea_upper">
            <div class="w-1/4 flex flex-col justify-between mx-3 border border-darthmouthgreen rounded-xl h-[325px]" id="totalLearnerNumberArea">
                <div class="text-center pt-28">
                    <h1 class="text-[125px] font-semibold text-darthmouthgreen" id="totalLearners">0</h1>
                    <p class="mt-10 text-xl font-semibold">total learners</p>
                </div>
                <div class="px-5 my-5 text-right">
                    <a href="{{ url("/admin/performance/learners") }}" class="text-right underline text-darthmouthgreen text-md">view list of learners</a>
                </div>
                
            </div>
            <div class="w-3/4 p-3 mx-3 border border-darthmouthgreen rounded-xl h-[325px]" id="dateRegisteredDataArea">
                <h1 class="mx-5 text-xl font-semibold">Registered Learners</h1>
                <select name="dateRegisteredDataFilter" class="w-full " id="dateRegisteredDataFilter">
                    <option value="daily">daily</option>
                    <option value="weekly">weekly</option>
                    <option value="monthly">monthly</option>
                </select>
                <div class="w-full h-full p-5 pb-10 " id="dateRegisteredData_dayArea">
                    <canvas class="" id="dateRegisteredData_day"></canvas>
                </div>
                <div class="hidden w-full h-full p-5 pb-10" id="dateRegisteredData_weekArea">
                    <canvas class="" id="dateRegisteredData_week"></canvas>
                </div>
                <div class="hidden w-full h-full p-5 pb-10" id="dateRegisteredData_monthArea">
                    <canvas class="" id="dateRegisteredData_month"></canvas>
                </div>
            </div>
        </div>
        <div class="flex w-full mt-5" id="learnerOverviewArea_lower">
            <div class="w-3/5 mx-3 p-3 border border-darthmouthgreen rounded-xl h-[325px]" id="AvgSessionDataArea">
                <h1 class="mx-5 text-xl font-semibold">Learners Session Data</h1>
                <select name="AvgSessionDataFilter" class="w-full " id="AvgSessionDataFilter">
                    {{-- <option value="hourly">hourly</option> --}}
                    <option value="daily">daily</option>
                    <option value="weekly">weekly</option>
                    <option value="monthly">monthly</option>
                </select>

                {{-- <div class="w-full h-full p-5 pb-10" id="AvgSessionData_hourArea">
                    <canvas id="AvgSessionData_hour"></canvas>
                </div> --}}
                <div class="w-full h-full p-5 pb-10" id="AvgSessionData_dayArea">
                    <canvas id="AvgSessionData_day"></canvas>
                </div>
                <div class="hidden w-full h-full p-5 pb-10" id="AvgSessionData_weekArea">
                    <canvas id="AvgSessionData_week"></canvas>
                </div>
                <div class="hidden w-full h-full p-5 pb-10" id="AvgSessionData_monthArea">
                    <canvas id="AvgSessionData_month"></canvas>
                </div>
            </div>
            <div class="w-2/5 mx-3 border p-5 border-darthmouthgreen rounded-xl h-[325px]" id="totalLearnerStatusArea">
                <h1 class="mx-5 text-xl font-semibold">Learners Status</h1>
                <canvas class="p-5" id="totalLearnerStatus"></canvas>
            </div>
        </div>
    </div>


    <div class="mt-10">
        <h1 class="mx-5 text-2xl font-semibold">Instructor Overview</h1>
        <hr class="my-6 border-t-2 border-gray-300">    
    </div>

    <div class="w-full mt-10" id="instructorOverviewArea">
        <div class="flex mt-5" id="instructorOverviewArea_upper">
            <div class="w-1/4 flex flex-col justify-between mx-3 border border-darthmouthgreen rounded-xl h-[325px]" id="totalInstructorNumberArea">
                <div class="pt-24 text-center">
                    <h1 class="text-[125px] font-semibold text-darthmouthgreen" id="totalInstructors">0</h1>
                    <p class="mt-10 text-xl font-semibold">total instructors</p>
                </div>
                <div class="px-5 my-5 text-right">
                    <a href="{{ url("/admin/performance/instructors") }}" class="text-right underline text-darthmouthgreen text-md">view list of instructors</a>
                </div>
                
            </div>
            <div class="w-3/4 p-3 mx-3 border border-darthmouthgreen rounded-xl h-[325px]" id="i_dateRegisteredDataArea">
                <h1 class="mx-5 text-xl font-semibold">Registered Instructors</h1>
                <select name="i_dateRegisteredDataFilter" class="w-full " id="i_dateRegisteredDataFilter">
                    <option value="daily">daily</option>
                    <option value="weekly">weekly</option>
                    <option value="monthly">monthly</option>
                </select>
                <div class="w-full h-full p-5 pb-10 " id="i_dateRegisteredData_dayArea">
                    <canvas class="" id="i_dateRegisteredData_day"></canvas>
                </div>
                <div class="hidden w-full h-full p-5 pb-10" id="i_dateRegisteredData_weekArea">
                    <canvas class="" id="i_dateRegisteredData_week"></canvas>
                </div>
                <div class="hidden w-full h-full p-5 pb-10" id="i_dateRegisteredData_monthArea">
                    <canvas class="" id="i_dateRegisteredData_month"></canvas>
                </div>
            </div>
        </div>
        <div class="flex w-full mt-5" id="instructorOverviewArea_lower">
            <div class="w-3/5 mx-3 p-3 border border-darthmouthgreen rounded-xl h-[325px]" id="i_AvgSessionDataArea">
                <h1 class="mx-5 text-xl font-semibold">Learners Session Data</h1>
                <select name="i_AvgSessionDataFilter" class="w-full " id="i_AvgSessionDataFilter">
                    {{-- <option value="hourly">hourly</option> --}}
                    <option value="daily">daily</option>
                    <option value="weekly">weekly</option>
                    <option value="monthly">monthly</option>
                </select>

                {{-- <div class="w-full h-full p-5 pb-10" id="AvgSessionData_hourArea">
                    <canvas id="AvgSessionData_hour"></canvas>
                </div> --}}
                <div class="w-full h-full p-5 pb-10" id="i_AvgSessionData_dayArea">
                    <canvas id="i_AvgSessionData_day"></canvas>
                </div>
                <div class="hidden w-full h-full p-5 pb-10" id="i_AvgSessionData_weekArea">
                    <canvas id="i_AvgSessionData_week"></canvas>
                </div>
                <div class="hidden w-full h-full p-5 pb-10" id="i_AvgSessionData_monthArea">
                    <canvas id="i_AvgSessionData_month"></canvas>
                </div>
            </div>
            <div class="w-2/5 mx-3 border p-5 border-darthmouthgreen rounded-xl h-[325px]" id="totalInstructorStatusArea">
                <h1 class="mx-5 text-xl font-semibold">Instructors Status</h1>
                <canvas class="p-5" id="totalInstructorStatus"></canvas>
            </div>
        </div>
    </div>


    <div class="mt-10">
        <h1 class="mx-5 text-2xl font-semibold">Course Overview</h1>
        <hr class="my-6 border-t-2 border-gray-300">    
    </div>

    <div class="w-full mt-10" id="courseOverviewArea">
        <div class="flex mt-5" id="courseOverviewArea_upper">
            <div class="w-1/4 flex flex-col justify-between mx-3 border border-darthmouthgreen rounded-xl h-[325px]" id="totalCourseNumberArea">
                <div class="text-center pt-28">
                    <h1 class="text-[125px] font-semibold text-darthmouthgreen" id="totalCourse">0</h1>
                    <p class="mt-10 text-xl font-semibold">total courses</p>
                </div>
                <div class="px-5 my-5 text-right">
                    <a href="{{ url("/admin/performance/courses") }}" class="text-right underline text-darthmouthgreen text-md">view list of courses</a>
                </div>
                
            </div>
            <div class="w-3/4 p-3 mx-3 border border-darthmouthgreen rounded-xl h-[325px]" id="c_dateRegisteredDataArea">
                <h1 class="mx-5 text-xl font-semibold">Registered Courses</h1>
                <select name="c_dateRegisteredDataFilter" class="w-full " id="c_dateRegisteredDataFilter">
                    <option value="daily">daily</option>
                    <option value="weekly">weekly</option>
                    <option value="monthly">monthly</option>
                </select>
                <div class="w-full h-full p-5 pb-10 " id="c_dateRegisteredData_dayArea">
                    <canvas class="" id="c_dateRegisteredData_day"></canvas>
                </div>
                <div class="hidden w-full h-full p-5 pb-10" id="c_dateRegisteredData_weekArea">
                    <canvas class="" id="c_dateRegisteredData_week"></canvas>
                </div>
                <div class="hidden w-full h-full p-5 pb-10" id="c_dateRegisteredData_monthArea">
                    <canvas class="" id="c_dateRegisteredData_month"></canvas>
                </div>
            </div>
        </div>
        <div class="flex w-full mt-5" id="courseOverviewArea_lower">
            <div class="w-3/5 mx-3 p-3 border border-darthmouthgreen rounded-xl h-[325px]" id="enrolleeNumbersArea">
                <h1 class="mx-5 text-xl font-semibold">Number of Enrollees</h1>

                <canvas class="p-5" id="enrolleeNumbers"></canvas>
            </div>
            <div class="w-2/5 mx-3 border p-5 border-darthmouthgreen rounded-xl h-[325px]" id="totalCourseStatusArea">
                <h1 class="mx-5 text-xl font-semibold">Courses Status</h1>
                <canvas class="p-5" id="totalCourseStatus"></canvas>
            </div>
        </div>
    </div>

</section>
</section>

@include('partials.footer')