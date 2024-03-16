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
        <h1 class="mx-5 text-2xl font-semibold">Learner {{$learner->name}}'s Overview</h1>
        <hr class="my-6 border-t-2 border-gray-300">    
    </div>


    <div class="flex p-10 mt-5" id="genInfo">
        <div class="w-3/5 h-[300px] border-2 border-darthmouthgreen" id="totalCourseArea">
            <div class="flex justify-center mx-10 mt-10 text-center h-2/3 item-center">
                <i class="fa-solid fa-book-open-reader text-darthmouthgreen text-[175px]"></i>
                <p class="mx-5 mt-3 text-2xl font-bold py-14"><span class="text-darthmouthgreen text-[125px]" id="totalCourseNum">0</span><br>Total Courses Enrolled</p>
            </div>
            <div class="flex justify-center mt-5">
                <div class="flex items-center mx-5">
                    <div class="w-3 h-3 mx-3 rounded-full bg-darthmouthgreen"></div>
                    <p class="font-bold text-md">Approved: <span id="totalApprovedCourse" class="">0</span></p>
                </div>

                <div class="flex items-center mx-5">
                    <div class="w-3 h-3 mx-3 bg-yellow-400 rounded-full"></div>
                    <p class="font-bold text-md">Pending: <span id="totalPendingCourse" class="">0</span></p>
                </div>

                <div class="flex items-center mx-5">
                    <div class="w-3 h-3 mx-3 bg-red-700 rounded-full"></div>
                    <p class="font-bold text-md">Rejected: <span id="totalRejectedCourse" class="">0</span></p>
                </div>
            </div>
            
        </div>
        <div class="h-[300px] w-2/5 ml-5 border-2 py-5 border-darthmouthgreen flex flex-col justify-between items-center" id="enrolledLearnerSyllabusCompletionCount">
            <div class="flex items-center py-5 mt-10 ml-10">
                <i class="fa-solid fa-book-bookmark text-darthmouthgreen text-[100px]"></i>
                <p class="px-8 pt-5 font-bold text-center text-md"><span class="text-darthmouthgreen text-[75px]" id="totalSyllabusCompletedCount">0</span><br>Topics Completed</p>
            </div>

            <div class="flex justify-between">
                <div class="">
                    <div class="flex items-center mx-1">
                        <i class="mx-3 text-xl fa-solid fa-file text-darthmouthgreen"></i>
                        <p class="font-bold text-md">Total Lessons: <span id="totalLessonsCount" class="">0</span></p>
                    </div>

                    <div class="flex items-center mx-1">
                        <i class="mx-3 text-xl fa-solid fa-clipboard text-darthmouthgreen"></i>
                        <p class="font-bold text-md">Total Activities: <span id="totalActivitiesCount" class="">0</span></p>
                    </div>

                    <div class="flex items-center mx-1">
                        <i class="mx-3 text-xl fa-solid fa-pen-to-square text-darthmouthgreen"></i>
                        <p class="font-bold text-md">Total Quizzes: <span id="totalQuizzesCount" class="">0</span></p>
                    </div>
                </div>

                <div class="">
                    <div class="flex items-center mx-1">
                        <i class="mx-3 text-xl fa-solid fa-file text-darthmouthgreen"></i>
                        <p class="font-bold text-md">Completed: <span id="totalLessonsCompletedCount" class="">0</span></p>
                    </div>

                    <div class="flex items-center mx-1">
                        <i class="mx-3 text-xl fa-solid fa-clipboard text-darthmouthgreen"></i>
                        <p class="font-bold text-md">Completed: <span id="totalActivitiesCompletedCount" class="">0</span></p>
                    </div>

                    <div class="flex items-center mx-1">
                        <i class="mx-3 text-xl fa-solid fa-pen-to-square text-darthmouthgreen"></i>
                        <p class="font-bold text-md">Completed: <span id="totalQuizzesCompletedCount" class="">0</span></p>
                    </div>
                </div>
            </div>
            
        </div>
    </div>

    <hr class="my-6 border-t-2 border-gray-300">

    <div class="w-full p-10" id="perCourseArea">
        <select name="" class="w-full px-5 py-3 text-lg" id="perCourseSelectArea">
            <option value="ALL" selected>ALL COURSES</option>
            @foreach ($courseData as $course)
                <option value="{{ $course->course_id }}">{{ $course->course_name }}</option>
            @endforeach
        </select>

        <div class="flex w-full mt-5" id="perCourseInfoArea">
            <div class="w-1/2 h-[350px] border-2 border-darthmouthgreen p-5" id="courseInfo"></div>

            <div class="w-1/2 h-[350px] ml-5 border-2 border-darthmouthgreen" id="courseGraph">
                <canvas id="courseDataChart"></canvas>
            </div>
        </div>
    </div>

    <hr class="my-6 border-t-2 border-gray-300">

    <div class="w-full p-10" id="courseListArea">
        <h1 class="mb-5 text-2xl font-semibold text-black">List of Enrolled Courses</h1>
        <table class="rounded-xl">
            <thead class="py-3 text-xl text-white bg-darthmouthgreen">
                <th class="w-1/5">Course Name</th>
                <th class="w-1/5">Course Code</th>
                <th class="w-1/4">Instructor</th>
                <th class="w-1/5">Status</th>
                <th class="w-1/5">Date Started</th>
                <th class="w-1/5"></th>
            </thead>

            <tbody class="mt-5 rowCourseDataArea">
        
            </tbody>
        </table>
    </div>


    <hr class="my-6 border-t-2 border-gray-300">

    <div class="flex justify-between">
        <h1 class="mx-5 text-2xl font-semibold">Session data</h1>
    </div>

    <div class="flex justify-center mt-5" id="learnerSessionDataArea">
        <div class="mx-5 w-11/12 h-[350px] border-2 border-darthmouthgreen rounded-xl" id="learnerSessionGraphArea">
            <canvas id="learnerSessionGraph"></canvas>
        </div>
    </div>



</section>
</section>

@include('partials.footer')