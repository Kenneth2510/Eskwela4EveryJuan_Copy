@extends('layouts.learner_layout')

@section('content')    
    {{-- MAIN --}}
<section class="w-full h-screen md:w-3/4 lg:w-10/12">
    <div class="h-full px-2 py-4 pt-24 overflow-hidden overflow-y-scroll rounded-lg shadow-lg md:pt-6">

        <div class="relative z-0 w-full p-4 text-black border rounded-lg shadow-lg">
            {{-- course name/title --}}
            <a href="{{ url('/learner/courses') }}" class="w-8 h-8 m-2">
                <svg xmlns="http://www.w3.org/2000/svg" height="25" viewBox="0 -960 960 960" width="24"><path d="m313-440 224 224-57 56-320-320 320-320 57 56-224 224h487v80H313Z"/></svg>
            </a>
            <div class="flex justify-between w-full" id="courseInfo">
                <div class="" id="courseInfo_left">
                    <h1 class="text-2xl font-semibold md:text-4xl">{{$course->course_name}}</h1>
                    <h4 class="text-xl">{{$course->course_code}}</h4>
                    <h4 class="mt-10">Course Level: <span class="font-medium text-darhmouthgreen">{{$course->course_difficulty}}</span></h4>
                    <h4 class=""><i class="fa-regular fa-clock text-darthmouthgreen"></i> Est. Time:  {{$totalCourseTime}}</h4>
                    <h4 class="mt-3">Total  Units: {{$totalSyllabusCount}}</h4>
                    <h4>&emsp;<i class="fa-regular fa-file text-darthmouthgreen"></i> Lessons: {{$totalLessonsCount}}</h4>
                    <h4>&emsp;<i class="fa-regular fa-clipboard text-darthmouthgreen"></i> Activities: {{$totalActivitiesCount}}</h4>
                    <h4>&emsp;<i class="fa-regular fa-pen-to-square text-darthmouthgreen"></i> Quizzes:  {{$totalQuizzesCount}}</h4>


                    @if ($isEnrolled)
                    <h4 class="flex items-center mt-10">Enrollment Status: 
                        @if ($isEnrolled->status === 'Approved')
                            <div class="w-5 h-5 mx-2 rounded-full bg-darthmouthgreen"></div>
                        @elseif ($isEnrolled->status ==='Pending')
                            <div class="w-5 h-5 mx-2 bg-yellow-500 rounded-full"></div>
                        @else
                            <div class="w-5 h-5 mx-2 bg-red-500 rounded-full"></div>
                        @endif
                    {{$isEnrolled->status}}</h4>
                    @if ($courseProgress)
                    <h4 class="flex items-center my-10">Your Progress: {{$courseProgress->course_progress}}</h4>
                        @if($courseProgress->course_progress == 'COMPLETED')
                            
                        <a href="{{ url("/learner/course/$course->course_id/$courseProgress->learner_course_id/certificate") }}" target="_blank" class="px-5 py-3 mb-5 text-white rounded-xl bg-darthmouthgreen hover:bg-white hover:border-2 hover:border-darthmouthgreen hover:text-darthmouthgreen">Download Certificate of Completion</a>
                        @endif
                    @endif
                    @endif
                    

            </div>
            <div class="flex flex-col items-center justify-between mr-10" id="courseInfo_right">
                <img class="w-40 h-40 my-4 mb-3 rounded-full lg:w-40 lg:h-40" src="{{ asset('storage/' . $course->profile_picture) }}" alt="Profile Picture">
                <div class="mb-10 text-center">
                    <h1 class="text-xl font-semibold">{{$course->instructor_fname}} {{$course->instructor_lname}}</h1>
                    <p class="text-lg">INSTRUCTOR</p>
                    <a href="{{ url("/learner/profile/instructor/$course->instructor_email") }}" class="px-3 py-1 text-white rounded-xl bg-darthmouthgreen hover:bg-white hover:border-darthmouthgreen hover:border hover:text-darthmouthgreen">View Profile</a>
                </div>
                <div class="flex flex-col">
                    @if($isEnrolled)
                    <a href="{{ url("/learner/course/manage/$course->course_id/overview") }}" id="" class="px-5 py-3 my-1 text-xl text-center text-white rounded-xl bg-darthmouthgreen hover:bg-white hover:text-darthmouthgreen hover:border-2 hover:border-darthmouthgreen">Enter</a>

                        <button id="unenrollBtn" class="px-5 py-3 my-1 text-xl text-white bg-red-600 rounded-xl hover:bg-white hover:text-red-600 hover:border-2 hover:border-red-600">Unenroll</button>
                        @else
                        <button id="enrollBtn" data-course-id="{{$course->course_id}}" class="px-5 py-3 my-1 text-xl text-white rounded-xl bg-darthmouthgreen hover:bg-white hover:text-darthmouthgreen hover:border-2 hover:border-darthmouthgreen">Enroll Now</button>
                        @endif
                        
                        <button id="viewDetailsBtn" class="px-5 py-3 my-1 text-lg text-white rounded-xl bg-darthmouthgreen hover:bg-white hover:text-darthmouthgreen hover:border-2 hover:border-darthmouthgreen">View Details</button>    
                    </div>
                </div>
            </div>
        </div>

        
        <hr class="my-6 border-t-2 border-gray-300">

        <div class="flex flex-col mt-5 lg:flex-row lg:justify-between" id="enrolledData">
            <div class="lg:w-7/12 lg:h-[100px]" id="totalEnrollees">
                <h1 class="mt-10 text-xl text-center">
                    <span class="text-6xl font-semibold text-darthmouthgreen">
                        {{$totalEnrolledCount}}
                    </span><br>
                    Learners Enrolled
                </h1>
            </div>
            <div class="flex flex-col items-center justify-between lg:flex-row lg:w-5/12" id="learnerProgressData">
                <div class="mx-1" id="progressPercent">
                    <h1 class="mt-10 text-xl text-center">
                        <span class="text-6xl font-semibold text-darthmouthgreen">
                            {{$progressPercent}} %
                        </span><br>
                        Progress
                    </h1>
                </div>
                <div class="mx-1" id="totalTimeSpent">
                    <h1 class="mt-10 text-xl text-center">
                        <span class="text-4xl font-semibold text-darthmouthgreen">
                            <i class="fa-regular fa-clock text-darthmouthgreen"></i> {{$totalLearnerTime}}
                        </span><br>
                        Time Spent
                    </h1>
                </div>
            </div>
        </div>

        <hr class="my-6 border-t-2 border-gray-300">

        <div class="relative z-0 flex flex-col p-3 pb-4 mt-10 text-black border rounded-lg shadow-lg lg:flex-row lg:justify-between" id="courseDescAndTopics">
            <div class="lg:w-7/12 overflow-y-auto h-[400px]" id="courseDesc">
                <h1 class="text-2xl font-semibold md:text-3xl lg:text-4xl">Course Description</h1>
                <div class="whitespace-pre-line">
                    {{$course->course_description}}
                </div>
            </div>
            <div class="lg:w-5/12 overflow-y-auto h-[400px]" id="courseTopics">
                <h1 class="text-2xl font-semibold md:text-3xl lg:text-4xl">Course Topics</h1>
                @foreach ($syllabus as $topic)
                    @if ($topic->category === "LESSON")
                        <h4 class="pt-5 text-lg "><i class="text-2xl fa-regular fa-file text-darthmouthgreen "></i> - {{$topic->topic_title}}</h4>
                    @elseif ($topic->category === "ACTIVITY")
                        <h4 class="pt-5 text-lg "><i class="text-2xl fa-regular fa-clipboard text-darthmouthgreen "></i> - {{$topic->topic_title}}</h4>
                    @elseif ($topic->category === "QUIZ")
                        <h4 class="pt-5 text-lg "><i class="text-2xl fa-regular fa-pen-to-square text-darthmouthgreen "></i> - {{$topic->topic_title}}</h4>
                    @endif
                @endforeach
            </div>
        </div>


        @if ($isEnrolled)
        <div class="mt-16" id="learnerProgressArea">
            <div class="">
                <h1 class="text-4xl font-semibold">Your Progress</h1>
                <div class="mt-3 h-7 rounded-xl" style="background: #9DB0A3" id="skill_bar">
                    <div class="relative py-1 text-center text-white h-7 bg-darthmouthgreen rounded-xl" id="skill_per" per="{{$progressPercent}}%" style="max-width: {{$progressPercent}}%">{{$progressPercent}}%</div>
                </div>    
            </div>
            
            <div class="px-5 mx-5">
                <table class="w-full mt-5">
                    <thead class="text-left">
                        <th class="text-lg">Topic</th>
                        <th class="text-lg">Status</th>
                    </thead>
                    <tbody id="enrollePercentArea">
                        @foreach ($syllabusProgress as $topic)
                        <tr>
                            <td class="px-5">{{$topic->topic_title}}</td>
                            <td>{{$topic->status}}</td>
                        </tr>
                        @endforeach
                        
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>
</section>

@include('partials.chatbot')

<div id="enrollCourseModal" class="fixed top-0 left-0 z-50 flex items-center justify-center hidden w-full h-full bg-gray-200 bg-opacity-75">
    <div class="modal-content bg-white p-4 rounded-lg shadow-lg w-[500px]">
        <div class="flex justify-end w-full">
            <button class="cancelEnroll">
                <i class="text-xl fa-solid fa-xmark" style="color: #949494;"></i>
            </button>
        </div>

        <h2 class="mb-2 text-2xl font-semibold">Confirm Enrollment</h2>

        <p class="text-gray-600">Are you sure you want to enroll to the course?</p>

        <div class="flex justify-center w-full mt-5">
            <button id="enrollCourse" data-course-id="{{$course->course_id}}" class="px-5 py-3 mx-2 mt-4 text-white rounded-lg bg-seagreen hover:bg-white hover:text-darthmouthgreen hover:border-2 hover:border-darthmouthgreen">Enroll Now</button>
            <button id="" class="px-5 py-3 mx-2 mt-4 text-white bg-red-500 rounded-lg cancelEnroll hover:bg-white hover:text-red-500 hover:border-2 hover:border-red-500">Cancel</button>
        </div>
    </div>
</div>


<div id="unenrollCourseModal" class="fixed top-0 left-0 z-50 flex items-center justify-center hidden w-full h-full bg-gray-200 bg-opacity-75">
    <div class="modal-content bg-white p-4 rounded-lg shadow-lg w-[500px]">
        <div class="flex justify-end w-full">
            <button class="cancelUnenroll">
                <i class="text-xl fa-solid fa-xmark" style="color: #949494;"></i>
            </button>
        </div>

        <h2 class="mb-2 text-2xl font-semibold">Confirm Unenrollment</h2>

        <p class="text-gray-600">Are you sure you want to enroll to the course?</p>

        <div class="flex justify-center w-full mt-5">
            @if ($isEnrolled)
            <button id="unenrollCourse" data-learner-course-id="{{ $isEnrolled->learner_course_id }}" class="px-5 py-3 mx-2 mt-4 text-white bg-red-500 rounded-lg hover:bg-white hover:text-red-500 hover:border-2 hover:border-red-500">Unenroll Now</button>
            <button id="" class="px-5 py-3 mx-2 mt-4 text-white bg-gray-500 rounded-lg cancelUnenroll hover:bg-white hover:text-gray-500 hover:border-2 hover:border-gray-500">Cancel</button>
            @endif
        </div>
    </div>
</div>



<div id="courseDetailsModal" class="fixed top-0 left-0 z-50 flex items-center justify-center hidden w-full h-screen bg-gray-200 bg-opacity-75">
    <div class="w-full p-4 bg-white rounded-lg shadow-lg modal-content lg:w-3/5 lg:h-3/4">
        <div class="flex justify-end w-full">
            <button class="closeCourseDetailsModal">
                <i class="text-xl fa-solid fa-xmark" style="color: #949494;"></i>
            </button>
        </div>
        <div class="flex flex-col w-full md:flex-row" id="content">
            <div class="h-full ring-gray-300 md:ring-0 ring-2 bg-darthmouthgreen" id="courseDetailsDirectory">
                <ul class="flex divide-x-2 md:flex-col md:divide-x-0">
                    <li class="flex items-center justify-center w-full py-8 font-semibold text-center text-white hover:bg-white hover:text-darthmouthgreen bg-darthmouthgreen" id="courseDetailsBtn">Course Details</li>
                    <li class="flex items-center justify-center w-full py-8 font-semibold text-center text-white hover:bg-white hover:text-darthmouthgreen bg-darthmouthgreen" id="learnersEnrolledBtn">Learners Enrolled</li>
                    <li class="flex items-center justify-center w-full py-8 font-semibold text-center text-white hover:bg-white hover:text-darthmouthgreen bg-darthmouthgreen" id="gradesheetBtn">Gradesheet</li>
                    <li class="flex items-center justify-center w-full py-8 font-semibold text-center text-white hover:bg-white hover:text-darthmouthgreen bg-darthmouthgreen" id="courseFilesBtn">Course Files</li>
                </ul>
            </div>

            <div class="w-full min-h-[500px]" id="courseDetailsContentArea">
                <div class="flex justify-between w-full" id="courseInfoArea">

                    <div class="" id="courseInfo_left">
                        <h1 class="text-2xl font-semibold md:text-4xl">{{$course->course_name}}</h1>
                        <h4 class="text-xl">{{$course->course_code}}</h4>
                        <h4 class="mt-10">Course Level: <span class="font-medium text-darhmouthgreen">{{$course->course_difficulty}}</span></h4>
                        <h4 class=""><i class="fa-regular fa-clock text-darthmouthgreen"></i> Est. Time:  {{$totalCourseTime}}</h4>
                        <h4 class="mt-3">Total  Units: {{$totalSyllabusCount}}</h4>
                        <h4>&emsp;<i class="fa-regular fa-file text-darthmouthgreen"></i> Lessons: {{$totalLessonsCount}}</h4>
                        <h4>&emsp;<i class="fa-regular fa-clipboard text-darthmouthgreen"></i> Activities: {{$totalActivitiesCount}}</h4>
                        <h4>&emsp;<i class="fa-regular fa-pen-to-square text-darthmouthgreen"></i> Quizzes:  {{$totalQuizzesCount}}</h4>
                        <h4 class="mt-10 text-xl">Course Description</h4>
                        <div class="w-full overflow-y-auto whitespace-pre-line">
                            {{$course->course_description}}
                        </div>
                    </div>
                    <div class="flex flex-col items-center justify-center" id="courseInfo_right">
                        <img class="w-40 h-40 my-4 rounded-full lg:w-40 lg:h-40" src="{{ asset('storage/' . $course->profile_picture) }}" alt="Profile Picture">
                        <h4 class="text-xl">{{$course->instructor_fname}} {{$course->instructor_lname}}</h4>
                        <h4 class="text-xl">INSTRUCTOR</h4>
                    </div>
                </div>


                <div class="hidden w-full py-5" id="learnersEnrolledArea">
                    <h1 class="text-2xl font-semibold md:text-4xl">Learners Enrolled</h1>
                    
                    <div class="overflow-auto">
                        <table class="table w-full table-auto">
                            <thead class="text-left">
                                <th class="w-[150px]">Name</th>
                                <th class="w-[150px]">Email</th>
                                <th class="w-[150px]">Enrollment Status</th>
                                <th class="w-[150px]">Date Enrolled</th>
                                <th class="w-[150px]">Course Progress</th>
                                <th class="w-[150px]"></th>
                            </thead>
                            <tbody class="">
                                @foreach ($enrollees as $enrollee)
                                <tr class="border-b-2 border-gray-500">
                                    <td class="py-3">{{ $enrollee->learner_fname }} {{ $enrollee->learner_lname }}</td>
                                    <td>{{ $enrollee->learner_email }}</td>
                                    <td>{{ $enrollee->status }}</td>
                                    <td>{{ $enrollee->created_at }}</td>
                                    <td>{{ $enrollee->course_progress }}</td>
                                    <td>
                                        @if($enrollee->learner_id !== $learner->learner_id)
                                        <a href="{{ url("/learner/profile/learner/$enrollee->learner_email") }}" class="py-1 text-white rounded-xl bg-darthmouthgreen hover:bg-white hover:border-darthmouthgreen hover:border hover:text-darthmouthgreen">View Profile</a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                                @empty($enrollees)
                                <tr>
                                    <td class="py-3">No enrollees enrolled</td>
                                </tr>
                                @endempty

                                
                            </tbody>
                        </table>
                    </div>
                </div>


            <div class="hidden py-5 mx-5" id="gradesheetArea">
                <h1 class="text-4xl font-semibold">Your Grades</h1>
                <div class="m-5 px-5 overflow-auto overflow-x-auto h-[600px]">
                    <table class="table-fixed w-[3000px]">
                        <thead class="text-left">
                            <th class="w-6/12">Name</th>
                            <th class="w-6/12">Status</th>
                            <th class="w-4/12">Date Started</th>
                            <th class="w-4/12">Pre Assessment</th>
                            
                            @foreach ($activitySyllabus as $activity)
                                <th class="w-4/12">{{ $activity->activity_title }}</th>
                            @endforeach
                            
                            @foreach ($quizSyllabus as $quiz)
                                <th class="w-4/12">{{ $quiz->quiz_title }}</th>
                            @endforeach
                    
                            <th class="w-4/12">Post Assessment</th>
                            <th class="w-4/12">Grade</th>
                            <th class="w-4/12">Remarks</th>
                            <th class="w-4/12">Date Finished</th>
                        </thead>
                    
                        <tbody class="text-center">
                            @if($syllabusProgress)
                                @forelse ($gradesheet as $grade)
                                    <tr>
                                        <td class="w-1/2">{{ $grade->learner_fname }} {{ $grade->learner_lname }}</td>
                                        <td>{{ $grade->course_progress }}</td>
                                        <td>{{ $grade->start_period }}</td>
                                        <td>{{ $preAssessmentGrade->score }}</td>
                                        
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
                                        
                                        <td>{{ $postAssessmentGrade }}</td>
                                        <td>{{ $courseProgress->grade }}</td>
                                        <td>{{ $courseProgress->remarks }}</td>
                                        <td>{{ $courseProgress->finish_period }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4">No gradesheet available</td>
                                    </tr>
                                @endforelse
                            @endif
                        </tbody>
                    </table>
                    
                </div>
            </div>


                <div class="hidden w-full py-5" id="filesArea">
                    <h1 class="text-2xl font-semibold md:text-4xl">Your Files</h1>
                    <div class="overflow-auto">
                        <table class="table w-full table-fixed">
                            <thead class="text-left">
                                <th class="w-[150px] text-xl">File</th>
                                <th class="w-[150px]"></th>
                                <th class="w-[150px]"></th>
                            </thead>
                            <tbody>
                                @if($isEnrolled)
                                @foreach($courseFiles as $file)
                                    <tr>
                                        <td class="py-3">{{ basename($file) }}</td>
                                        <td>
                                            <a href="{{ Storage::url("$file") }}" target="_blank" class="px-5 py-3 text-white rounded-xl bg-darthmouthgreen hover:bg-white hover:border-2 hover:border-darthmouthgreen hover:text-darthmouthgreen">View File</a>
                                        </td>  
                                        <td>
                                            <a href="{{ Storage::url($file) }}" class="px-5 py-3 text-white rounded-xl bg-darthmouthgreen hover:bg-white hover:border-2 hover:border-darthmouthgreen hover:text-darthmouthgreen" download>Download</a>
                                        </td>                                  
                                    </tr>
                                @endforeach
                                @else 
                                <tr>
                                    <td class="py-3">No available files</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
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
