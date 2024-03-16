@extends('layouts.instructor_layout')

@section('content')
    {{-- MAIN --}}
    <section class="w-full h-auto md:h-screen md:w-3/4 lg:w-10/12">
        <div class="h-full px-2 py-4 pt-24 rounded-lg shadow-lg md:overflow-hidden md:overflow-y-scroll md:pt-0">

            <div class="py-4">
                <h1 class="text-2xl font-semibold md:text-3xl">PERFORMANCE DASHBOARD</h1>
            </div>
            
            <hr class="my-6 border-t-2 border-gray-300">
            
            <div class="flex w-full space-x-2" id="genInfo">
                <div class="relative w-1/2 md:w-3/5 h-[300px] border-2 border-darthmouthgreen flex flex-col justify-between py-2 md:py-4" id="totalCourseArea">
                    <div class="flex justify-center text-center item-center">
                        <i class="absolute -translate-y-1/2 md:px-4 md:opacity-100 md:relative fa-solid fa-book-open-reader text-darthmouthgreen fa-10x opacity-20 top-1/2"></i>
                        <p class="text-2xl font-bold"><span class="text-8xl text-darthmouthgreen" id="totalCourseNum">0</span><br>Total Courses</p>
                    </div>
                    <div class="flex flex-col justify-center md:flex-row">
                        <div class="flex items-center">
                            <div class="w-3 h-3 mx-3 rounded-full bg-darthmouthgreen"></div>
                            <p class="font-bold text-md">Approved: <span id="totalApprovedCourse" class="">0</span></p>
                        </div>

                        <div class="flex items-center">
                            <div class="w-3 h-3 mx-3 bg-yellow-400 rounded-full"></div>
                            <p class="font-bold text-md">Pending: <span id="totalPendingCourse" class="">0</span></p>
                        </div>

                        <div class="flex items-center">
                            <div class="w-3 h-3 mx-3 bg-red-700 rounded-full"></div>
                            <p class="font-bold text-md">Rejected: <span id="totalRejectedCourse" class="">0</span></p>
                        </div>
                    </div>
                    
                </div>
                <div class="w-1/2 h-[300px] md:w-2/5 flex flex-col justify-between space-y-2" id="totalCourseSubInfoArea">
                    <div class="relative flex flex-col items-center border-2 md:flex-row h-1/2 border-darthmouthgreen md:justify-center" id="enrolledLearnersArea">
                        <div class="flex items-center">
                            <i class="absolute -translate-x-1/2 -translate-y-1/2 lg:px-4 lg:opacity-100 lg:relative fa-solid fa-user text-darthmouthgreen fa-5x top-1/2 left-1/2 opacity-20 lg:left-auto lg:-translate-x-0 lg:-translate-y-0"></i>
                            <p class="font-bold text-center text-md"><span class="text-6xl text-darthmouthgreen" id="totalLearnersCount">0</span><br>Learners</p>
                        </div>
                        <div class="text-xs lg:text-base">
                            <div class="flex items-center">
                                <div class="w-3 h-3 mx-3 rounded-full bg-darthmouthgreen"></div>
                                <p class="font-bold">Approved: <span id="totalApprovedLearnersCount" class="">0</span></p>
                            </div>
    
                            <div class="flex items-center">
                                <div class="w-3 h-3 mx-3 bg-yellow-400 rounded-full"></div>
                                <p class="font-bold">Pending: <span id="totalPendingLearnersCount" class="">0</span></p>
                            </div>
    
                            <div class="flex items-center">
                                <div class="w-3 h-3 mx-3 bg-red-700 rounded-full"></div>
                                <p class="font-bold">Rejected: <span id="totalRejectedLearnersCount" class="">0</span></p>
                            </div>
                        </div>
                    </div>

                    <div class="relative flex flex-col items-center border-2 md:flex-row h-1/2 border-darthmouthgreen md:justify-center" id="syllabusContentsArea">
                        <div class="flex items-center ">
                            <i class="absolute -translate-x-1/2 -translate-y-1/2 lg:px-4 lg:left-auto lg:opacity-100 lg:-translate-x-0 lg:-translate-y-0 lg:relative left-1/2 fa-solid fa-book-bookmark text-darthmouthgreen fa-5x top-1/2 opacity-20"></i>
                            <p class="font-bold text-center text-md"><span class="text-6xl text-darthmouthgreen" id="totalSyllabusCount">0</span><br>Topics</p>
                        </div>
                        <div class="text-xs lg:text-base">
                            <div class="flex items-center">
                                <i class="mx-3 text-xl fa-solid fa-file text-darthmouthgreen"></i>
                                <p class="font-bold ">Lessons: <span id="totalLessonsCount" class="">0</span></p>
                            </div>
    
                            <div class="flex items-center">
                                <i class="mx-3 text-xl fa-solid fa-clipboard text-darthmouthgreen"></i>
                                <p class="font-bold ">Activities: <span id="totalActivitiesCount" class="">0</span></p>
                            </div>
    
                            <div class="flex items-center">
                                <i class="mx-3 text-xl fa-solid fa-pen-to-square text-darthmouthgreen"></i>
                                <p class="font-bold ">Quizzes: <span id="totalQuizzesCount" class="">0</span></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="my-6 border-t-2 border-gray-300">

            <div class="w-full" id="perCourseArea">
                <select name="" class="w-full px-5 py-3 text-lg" id="perCourseSelectArea">
                    <option value="ALL" selected>ALL COURSES</option>
                    @foreach ($courses as $course)
                        <option value="{{ $course->course_id }}">{{ $course->course_name }}</option>
                    @endforeach
                </select>

                <div class="flex flex-col w-full space-y-2 md:flex-row md:space-x-2" id="perCourseInfoArea">
                    <div class="w-full md:w-1/2 h-[350px] border-2 border-darthmouthgreen" id="courseInfo"></div>

                    <div class="w-full md:w-1/2 h-[350px] border-2 border-darthmouthgreen" id="courseGraph">
                        <canvas id="courseDataChart"></canvas>
                    </div>
                </div>
            </div>

            <hr class="my-6 border-t-2 border-gray-300">

            <div class="w-full overflow-auto overflow-x-auto" id="courseListArea">
                <h1 class="text-2xl font-semibold text-black ">List of Courses</h1>
                <table class="table w-full table-fixed rounded-xl">
                    <thead class="py-3 text-white bg-darthmouthgreen">
                        <th class="w-[130px]">Course Name</th>
                        <th class="w-[130px]">Course Code</th>
                        <th class="w-[130px]">Number of Active Enrolles</th>
                        <th class="w-[130px]">Date Created</th>
                        <th class="w-[130px]">Status</th>
                        <th class="w-[130px]"></th>
                    </thead>

                    <tbody class="mt-5 rowCourseDataArea">
                        {{-- <tr class="my-5 text-center rowCourseData">
                            <td class="py-5 mt-5">Sample 1</td>
                            <td>jrkh90</td>
                            <td>10/13</td>
                            <td>10/25/2024</td>
                            <td>Approved</td>
                            <td>
                                <a href="" method="GET" class="px-5 py-3 text-white bg-darthmouthgreen hover:bg-green-950 rounded-xl">View</a>
                            </td>
                        </tr> --}}
                    </tbody>
                </table>
            </div>


            <hr class="my-6 border-t-2 border-gray-300">

            <div class="flex justify-between">
                <h1 class="text-2xl font-semibold ">Your session data</h1>
            </div>

            <div class="flex justify-center py-4" id="instructorSessionDataArea">
                    <div class=" w-11/12 h-[350px] border-2 border-darthmouthgreen rounded-xl" id="instructorSessionGraphArea">
                        <canvas id="instructorSessionGraph"></canvas>
                    </div>
            </div>
        </div>
    </section>
 
@endsection

