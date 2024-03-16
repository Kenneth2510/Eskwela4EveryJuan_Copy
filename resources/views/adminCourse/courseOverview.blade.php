@include('partials.header')

<section class="flex flex-row w-screen text-sm main-container bg-mainwhitebg md:text-base">
@include('partials.sidebar')




<section class="w-screen px-2 pt-[40px] mx-2 mt-2  overscroll-auto md:overflow-auto">
    <div class="flex justify-between px-10">
        <h1 class="text-6xl font-bold text-darthmouthgreen">Course Syllabus Management</h1>
        <div class="">
            <p class="text-xl font-semibold text-darthmouthgreen">{{$admin->admin_codename}}</p>
        </div>
    </div>
    <div class="mb-5">
        <a href="/admin/courseManage" class="">
            <i class="text-2xl md:text-3xl fa-solid fa-arrow-left" style="color: #000000;"></i>
        </a>
    </div>
        
        <div class="relative z-0 pb-4 text-black border rounded-lg shadow-lg">
            <div class="flex justify-between px-5 mx-3" id="courseInfo">
                <div class="" id="courseInfo_left">
                    <h1 class="text-6xl font-semibold">{{$course->course_name}}</h1>
                    <h4 class="text-4xl">{{$course->course_code}}</h4>
                    <h4 class="mt-10 text-xl">Course Level: {{$course->course_difficulty}}</h4>
                    <h4 class="text-xl"><i class="fa-regular fa-clock text-darthmouthgreen"></i> Est. Time:  {{$totalCourseTime}}</h4>
                    <h4 class="mt-3 text-xl">Total  Units: {{$totalSyllabusCount}}</h4>
                    <h4 class="pl-5 text-xl"><i class="fa-regular fa-file text-darthmouthgreen"></i> Lessons: {{$totalLessonsCount}}</h4>
                    <h4 class="pl-5 text-xl"><i class="fa-regular fa-clipboard text-darthmouthgreen"></i> Activities: {{$totalActivitiesCount}}</h4>
                    <h4 class="pl-5 text-xl"><i class="fa-regular fa-pen-to-square text-darthmouthgreen"></i> Quizzes:  {{$totalQuizzesCount}}</h4>
                
                
                    <h4 class="flex items-center mt-10 text-xl">
                        Approval Status: 
                        @if ($course->course_status === 'Approved')
                        <div class="w-5 h-5 mx-2 rounded-full bg-darthmouthgreen"></div>
                    @elseif ($course->course_status ==='Pending')
                        <div class="w-5 h-5 mx-2 bg-yellow-500 rounded-full"></div>
                    @else
                        <div class="w-5 h-5 mx-2 bg-red-500 rounded-full"></div>
                    @endif
                    
                    {{$course->course_status}}
                    </h4>
                </div>
                <div class="flex flex-col items-center justify-between mr-10" id="courseInfo_right">
                    <img class="w-40 h-40 my-4 mb-10 rounded-full lg:w-40 lg:h-40" src="{{ asset('storage/' . $course->profile_picture) }}" alt="Profile Picture">
                    <div class="flex flex-col">
                        <a href="{{ url("/admin/courseManage/content/$course->course_id") }}" id="" class="px-5 py-3 my-1 text-xl text-center text-white rounded-xl bg-darthmouthgreen hover:bg-white hover:text-darthmouthgreen hover:border-2 hover:border-darthmouthgreen">Enter</a>
                        <button id="viewDetailsBtn"  class="px-5 py-3 my-1 text-lg text-white rounded-xl bg-darthmouthgreen hover:bg-white hover:text-darthmouthgreen hover:border-2 hover:border-darthmouthgreen">View Details</button>    
                    </div>
                </div>
            </div>
        </div>


        <div class="relative z-0 flex justify-between px-5 pb-4 mt-10 text-black border rounded-lg shadow-lg" id="courseDescAndTopics">
            <div class="w-7/12 overflow-y-auto h-[400px]" id="courseDesc">
                <h1 class="text-4xl font-semibold">Course Description</h1>
                <div class="whitespace-pre-line">
                    {{$course->course_description}}
                </div>
            </div>
            <div class="w-5/12 ml-5 overflow-y-auto h-[400px]" id="courseTopics">
                <h1 class="text-4xl font-semibold">Course Topics</h1>
                @foreach ($syllabus as $topic)
                    @if ($topic->category === "LESSON")
                        <h4 class="px-5 pt-5 text-lg"><i class="text-2xl fa-regular fa-file text-darthmouthgreen "></i> - {{$topic->topic_title}}</h4>
                    @elseif ($topic->category === "ACTIVITY")
                        <h4 class="px-5 pt-5 text-lg"><i class="text-2xl fa-regular fa-clipboard text-darthmouthgreen "></i> - {{$topic->topic_title}}</h4>
                    @elseif ($topic->category === "QUIZ")
                        <h4 class="px-5 pt-5 text-lg"><i class="text-2xl fa-regular fa-pen-to-square text-darthmouthgreen "></i> - {{$topic->topic_title}}</h4>
                    @endif
                @endforeach
            </div>
        </div>


        <div class="mt-5 h-[250px] flex justify-between" id="enrolledData">
            <div class="w-5/12" id="totalEnrollees">
                <h1 class="mt-10 text-2xl text-center">
                    <span class="text-6xl font-semibold text-darthmouthgreen">
                        {{$totalEnrolledCount}}
                    </span><br>
                    Learners Enrolled
                </h1>
            </div>
            <div class="flex items-center justify-between w-7/12" id="learnerProgressData">
                <canvas id="learnerProgressChart"></canvas>
            </div>
        </div>


        <div class="mx-5 mt-16" id="learnerProgressArea">
            <div class="">
                <h1 class="text-4xl font-semibold">Enrolled Learners</h1>
             
            </div>
            
            <div class="px-5 mx-5">
                <table class="w-full mt-5">
                    <thead class="text-left">
                        <th class="text-lg">Name</th>
                        <th class="text-lg">Email</th>
                        <th class="text-lg">Date Enrolled</th>
                        <th class="text-lg">Status</th>
                        <th class="text-lg"></th>
                    </thead>
                    <tbody id="enrollePercentArea">
                        @foreach ($courseEnrollees as $enrollee)
                            <tr>
                                <td class="py-5">{{$enrollee->learner_fname}} {{$enrollee->learner_lname}}</td>
                                <td>{{$enrollee->learner_email}}</td>
                                <td>{{$enrollee->start_period}}</td>
                                <td>{{$enrollee->course_progress}}</td>
                                <td>
                                    <a class="px-5 py-3 text-white bg-darthmouthgreen rounded-xl hover:bg-white hover:text-darthmouthgreen hover:border-2 hover:border-darthmouthgreen" href="{{ url("instructor/viewProfile/$enrollee->learner_id") }}">
                                        view profile
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    
        

</section>

    
</section>



<div id="courseDetailsModal" class="fixed top-0 left-0 flex items-center justify-center hidden w-full h-full ml-10 bg-gray-200 bg-opacity-75">
    <div class="p-4 bg-white rounded-lg shadow-lg modal-content">
        <div class="flex justify-end w-full">
            <button class="closeCourseDetailsModal">
                <i class="text-xl fa-solid fa-xmark" style="color: #949494;"></i>
            </button>
        </div>
        <div class="flex" id="content"> <div class="py-10 w-[200px] h-[700px] bg-darthmouthgreen" id="courseDetailsDirectory">
            <ul>
                <li class="flex items-center justify-center w-full h-24 px-5 py-3 text-xl font-semibold text-center text-white hover:bg-white hover:text-darthmouthgreen bg-darthmouthgreen" id="courseDetailsBtn">Course Details</li>
                <li class="flex items-center justify-center w-full h-24 px-5 py-3 text-xl font-semibold text-center text-white hover:bg-white hover:text-darthmouthgreen bg-darthmouthgreen" id="learnersEnrolledBtn">Learners Enrolled</li>
                <li class="flex items-center justify-center w-full h-24 px-5 py-3 text-xl font-semibold text-center text-white hover:bg-white hover:text-darthmouthgreen bg-darthmouthgreen" id="gradesheetBtn">Gradesheet</li>
                <li class="flex items-center justify-center w-full h-24 px-5 py-3 text-xl font-semibold text-center text-white hover:bg-white hover:text-darthmouthgreen bg-darthmouthgreen" id="courseFilesBtn">Course Files</li>
                <li class="flex items-center justify-center w-full h-24 px-5 py-3 text-xl font-semibold text-center text-white hover:bg-white hover:text-darthmouthgreen bg-darthmouthgreen" id="courseGradingBtn">Grading System</li>
            </ul>
        </div>

        <div class="w-[1000px]" id="courseDetailsContentArea">


            <div class="flex justify-between h-[700px]" id="courseInfoArea">

                <div class="w-4/5 py-5 mx-10" id="courseInfo_left">
                    <h1 class="text-6xl font-semibold" id="courseName">{{$course->course_name}}</h1>
                    <h4 class="text-4xl">{{$course->course_code}}</h4>
                    <h4 class="mt-10 text-xl">Course Level: {{$course->course_difficulty}}</h4>
                    <h4 class="text-xl"><i class="fa-regular fa-clock text-darthmouthgreen"></i> Est. Time:  {{$totalCourseTime}}</h4>
                    <h4 class="mt-3 text-xl">Total  Units: {{$totalSyllabusCount}}</h4>
                    <h4 class="pl-5 text-xl"><i class="fa-regular fa-file text-darthmouthgreen"></i> Lessons: {{$totalLessonsCount}}</h4>
                    <h4 class="pl-5 text-xl"><i class="fa-regular fa-clipboard text-darthmouthgreen"></i> Activities: {{$totalActivitiesCount}}</h4>
                    <h4 class="pl-5 text-xl"><i class="fa-regular fa-pen-to-square text-darthmouthgreen"></i> Quizzes:  {{$totalQuizzesCount}}</h4>
                    <h4 class="mt-10 text-xl">Course Description</h4>
                    <div class="whitespace-pre-line w-full overflow-y-auto h-[180px]" id="courseDescription">
                        {{$course->course_description}}
                    </div>
                    <div class="">
                        <button id="deleteCourseBtn" data-course-id="{{ $course->course_id }}" class="px-5 py-3 text-white bg-red-600 hover:bg-white hover:text-red-600 hover:border-2 hover:border-red-600 rounded-xl">Delete Course</button>
                    </div>
                
                </div>
                <div class="flex flex-col items-center justify-center w-1/5" id="courseInfo_right">
                    <img class="w-40 h-40 my-4 mb-10 rounded-full lg:w-40 lg:h-40" src="{{ asset('storage/' . $course->profile_picture) }}" alt="Profile Picture">
                    <h4 class="text-xl">{{$course->instructor_fname}} {{$course->instructor_lname}}</h4>
                    <h4 class="text-xl">INSTRUCTOR</h4>
                    <button id="courseEditBtn" class="px-5 py-3 text-white bg-darthmouthgreen hover:bg-white hover:text-darthmouthgreen hover:border-2 hover:border-darthmouthgreen rounded-xl">Edit</button>
                </div>
            </div>


            <div class="hidden py-5 mx-5" id="learnersEnrolledArea">
                <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>

                <!-- start-generate-pdf -->
                <div class="" id="generatedPdfArea">
                    <h1 id="courseNamePdf" class="text-4xl font-semibold">{{ $course->course_name }}</h1>
                    <h1 class="text-2xl font-semibold">Learners Enrolled</h1>
                    
                    <div class="m-5 mt-5 px-5 overflow-auto h-[600px]">
                        <table class="">
                            <thead class="px-3 text-left text-white bg-darthmouthgreen">
                                <th class="w-3/12 pl-5">Name</th>
                                <th class="w-2/12">Email</th>
                                <th class="w-1/12">Enrollment Status</th>
                                <th class="w-2/12">Date Enrolled</th>
                                <th class="w-1/12">Course Progress</th>
                                <th class="w-2/12"></th>
                            </thead>
                            <tbody class="">
                                @forelse ($courseEnrollees as $enrollee)
                                <tr class="border-b-2 border-gray-500">
                                    <td class="py-3 pl-5">{{ $enrollee->learner_fname }} {{ $enrollee->learner_lname }}</td>
                                    <td>{{ $enrollee->learner_email }}</td>
                                    <td>{{ $enrollee->status }}</td>
                                    <td>{{ $enrollee->created_at }}</td>
                                    <td>{{ $enrollee->course_progress }}</td>
                                    <td>
                                        <a href="{{ url("/instructor/profile/learner/$enrollee->learner_email") }}" class="px-3 py-1 text-white rounded-xl bg-darthmouthgreen hover:bg-white hover:border-darthmouthgreen hover:border hover:text-darthmouthgreen">View Profile</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="py-3">No enrollees enrolled</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

<!-- end-generate-pdf -->
                <button id="generateEnrolledLearnersBtn" class="px-5 py-3 text-white bg-darthmouthgreen rounded-xl hover:bg-white hover:text-darthmouthgreen hover:border-2 hover:border-darthmouthgreen">Download PDF</button>
            </div>


            <div class="hidden py-5 mx-5" id="gradesheetArea">
                <div class="" id="exportExcelGrades">
                    <h1 id="courseNamePdf" class="text-4xl font-semibold">{{ $course->course_name }}</h1>
                    <h1 class="text-4xl font-semibold">Enrollee Gradesheet</h1>
                    <div class="m-5 px-5 overflow-auto overflow-x-auto h-[600px]">
                        <table id="gradesheet" class="table-fixed w-[3000px]">
                            <thead class="px-3 text-center text-white bg-darthmouthgreen">
                                <th class="w-4/12 pl-5">Name</th>
                                <th class="w-4/12">Status</th>
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
                                @forelse ($gradesheet as $grade)
                                    <tr>
                                        <td class="py-3 pl-5">{{ $grade->learner_fname }} {{ $grade->learner_lname }}</td>
                                        <td>{{ $grade->course_progress }}</td>
                                        <td>{{ $grade->start_period }}</td>
                                        <td>{{$grade->pre_assessment->score}}</td>
                                        
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
                                        
                                        <td>{{$grade->post_assessment->average_score}}</td>
                                        <td>{{$grade->grade}}</td>
                                        <td>{{$grade->remarks}}</td>
                                        <td>{{ $grade->finish_period }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4">No gradesheet available</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        
                    </div>
                </div>
                <button id="generateGradesheetBtn" class="px-5 py-3 text-white bg-darthmouthgreen rounded-xl hover:bg-white hover:text-darthmouthgreen hover:border-2 hover:border-darthmouthgreen">Export Excel File</button>
                <button id="generateGradesheetPDFBtn" class="px-5 py-3 text-white bg-darthmouthgreen rounded-xl hover:bg-white hover:text-darthmouthgreen hover:border-2 hover:border-darthmouthgreen">Generate PDF</button>
            </div>


            <div class="hidden py-5 mx-5" id="filesArea">
                <h1 class="text-4xl font-semibold">Your Files</h1>
                <div class="m-5 px-5 overflow-auto overflow-x-auto h-[600px]">
                    <table class="table-fixed">
                        <thead class="w-full text-left">
                            <th class="w-1/2 text-xl">File</th>
                            <th class="w-1/3"></th>
                            <th class="w-1/3"></th>
                            <th class="w-1/3"></th>
                        </thead>
                        <tbody>
            
                            @foreach($courseFiles as $file)
                                <tr>
                                    <td class="py-3">{{ basename($file) }}</td>
                                    <td>
                                        <a href="{{ Storage::url("$file") }}" target="_blank" class="px-5 py-3 text-white rounded-xl bg-darthmouthgreen hover:bg-white hover:border-2 hover:border-darthmouthgreen hover:text-darthmouthgreen">View File</a>
                                    </td>  
                                    <td>
                                        <a href="{{ Storage::url($file) }}" class="px-5 py-3 text-white rounded-xl bg-darthmouthgreen hover:bg-white hover:border-2 hover:border-darthmouthgreen hover:text-darthmouthgreen" download>Download</a>
                                    </td>     
                                    <td>
                                        <a href="{{ url("/admin/courseManage/$course->course_id/delete_file/" . basename($file)) }}" class="px-5 py-3 text-white bg-red-500 rounded-xl hover:bg-white hover:border-2 hover:border-red-500 hover:text-red-500" onclick="return confirm('Are you sure you want to delete this file?')">Delete</a>
                                    </td>
                                                                 
                                </tr>
                            @endforeach
                     
                        </tbody>
                    </table>
                    <button id="addNewFileBtn" class="px-5 py-3 text-white bg-darthmouthgreen rounded-xl hover:bg-white hover:text-darthmouthgreen hover:border-2 hover:border-darthmouthgreen">Add New File</button>
                </div>
            </div>

            <div class="hidden py-5 mx-5" id="courseGradesArea">
                <h1 class="text-4xl font-semibold">Grading System</h1>
                <div class="m-5 px-5 overflow-auto overflow-x-auto h-[600px]">

                    <div class="mt-3" id="activityPercentArea">
                        <label for="activity_percent" class="text-xl text-darthmouthgreen">Activities Grade</label><br>
                        <input class="w-full h-12 px-5 py-1 border-2 rounded-lg border-darthmouthgreen" type="text" name="activity_percent" id="activity_percent" value="{{$gradingSystem->activity_percent}}" disabled>
                        <span id="activityPercentError" class="text-red-500"></span>
                    </div>

                    <div class="mt-3" id="quizPercentArea">
                        <label for="quiz_percent" class="text-xl text-darthmouthgreen">Quizzes Grade</label><br>
                        <input class="w-full h-12 px-5 py-1 border-2 rounded-lg border-darthmouthgreen" type="text" name="quiz_percent" id="quiz_percent" value="{{$gradingSystem->quiz_percent}}" disabled>
                        <span id="quizPercentError" class="text-red-500"></span>
                    </div>
                
                    <div class="mt-3" id="preAssessmentPercentArea">
                        <label for="pre_assessment_percent" class="text-xl text-darthmouthgreen">Pre Assessment Grade</label><br>
                        <input class="w-full h-12 px-5 py-1 border-2 rounded-lg border-darthmouthgreen" type="text" name="pre_assessment_percent" id="pre_assessment_percent" value="{{$gradingSystem->pre_assessment_percent}}" disabled>
                        <span id="preAssessmentPercentError" class="text-red-500"></span>
                    </div>

                    <div class="mt-3" id="postAssessmentPercentArea">
                        <label for="post_assessment_percent" class="text-xl text-darthmouthgreen">Post Assessment Grade</label><br>
                        <input class="w-full h-12 px-5 py-1 border-2 rounded-lg border-darthmouthgreen" type="text" name="post_assessment_percent" id="post_assessment_percent" value="{{$gradingSystem->post_assessment_percent}}" disabled>
                        <span id="postAssessmentPercentError" class="text-red-500"></span>
                    </div>

                    <h1 id="totalPercent"></h1>
                    <span id="totalPercentError" class="text-red-500"></span>

                    <div class="flex mt-3" id="">
                        <button class="px-5 py-3 text-xl text-white bg-darthmouthgreen rounded-xl hover:bg-white hover:text-darthmouthgreen hover:border hover:border-darthmouthgreen" id="editCourseGradesBtn">Edit</button>
                        <button class="hidden px-5 py-3 mx-1 text-xl text-white bg-darthmouthgreen rounded-xl hover:bg-white hover:text-darthmouthgreen hover:border hover:border-darthmouthgreen" id="saveCourseGradesBtn">Save</button>
                        <button class="hidden px-5 py-3 mx-1 text-xl text-white bg-darthmouthgreen rounded-xl hover:bg-white hover:text-darthmouthgreen hover:border hover:border-darthmouthgreen" id="cancelCourseGradesBtn">Cancel</button>
                    </div>
                </div>
            </div>


        </div>
    </div>
       
    </div>
</div>


<div id="courseDetailsEditModal" class="fixed top-0 left-0 flex items-center justify-center hidden w-full h-full ml-10 bg-gray-200 bg-opacity-75">
    <div class="modal-content bg-white p-4 rounded-lg shadow-lg w-[500px]">
        <div class="flex justify-end w-full">
            <button class="cancelEdit">
                <i class="text-xl fa-solid fa-xmark" style="color: #949494;"></i>
            </button>
        </div>

        <h2 class="mb-2 text-2xl font-semibold">Edit Course Details</h2>

        <label for="courseEditName">Course Name</label><br>
        <input id="courseEditName" type="text" class="w-full h-16 px-3 py-3 text-lg text-black border-2 border-gray-500 rounded-lg" placeholder="your course name" value="{{ $course->course_name }}">
        <br><br>
        <label for="courseDescription" class="">Course Description</label><br>
        <textarea id="courseEditDescription" class="w-full h-40 px-3 py-3 text-sm text-black border-2 border-gray-500 rounded-lg" placeholder="Your course description">{{ $course->course_description }}</textarea>

        <div class="flex justify-center w-full mt-5">
            <button id="saveCourseEditDetailsBtn" data-course-id="{{$course->course_id}}" class="px-5 py-3 mx-2 mt-4 text-white rounded-lg bg-seagreen hover:bg-white hover:text-darthmouthgreen hover:border-2 hover:border-darthmouthgreen">Apply Changes</button>
            <button id="" class="px-5 py-3 mx-2 mt-4 text-white bg-red-500 rounded-lg cancelEdit hover:bg-white hover:text-red-500 hover:border-2 hover:border-red-500">Cancel</button>
        </div>
    </div>
</div>


<div id="addNewFileModal" class="fixed top-0 left-0 flex items-center justify-center hidden w-full h-full ml-10 bg-gray-200 bg-opacity-75">
    <div class="modal-content bg-white p-4 rounded-lg shadow-lg w-[500px]">
        <div class="flex justify-end w-full">
            <button class="cancelAddNewFile">
                <i class="text-xl fa-solid fa-xmark" style="color: #949494;"></i>
            </button>
        </div>
        
        <form id="uploadFileForm" action="{{ url("/admin/courseManage/$course->course_id/add_file") }}" method="POST" enctype="multipart/form-data">
            
            @csrf
            <div class="flex flex-col items-center w-full mt-5">
                <label for="file" class="mb-2 text-lg font-semibold">Choose File:</label>
                <input type="file" name="file" id="file" class="w-full p-2 border border-gray-300 rounded-md">
            </div>

            <div class="flex justify-center w-full mt-5">
                <button type="submit" class="px-5 py-3 mx-2 mt-4 text-white rounded-lg bg-seagreen hover:bg-white hover:text-darthmouthgreen hover:border-2 hover:border-darthmouthgreen">Apply File</button>
                <button type="button" class="px-5 py-3 mx-2 mt-4 text-white bg-red-500 rounded-lg cancelAddNewFile hover:bg-white hover:text-red-500 hover:border-2 hover:border-red-500">Cancel</button>
            </div>
        </form>
    </div>
</div>

<div id="deleteCourseModal" class="fixed top-0 left-0 flex items-center justify-center hidden w-full h-full ml-10 bg-gray-200 bg-opacity-75">
    <div class="modal-content bg-white p-4 rounded-lg shadow-lg w-[500px]">
        <div class="flex justify-end w-full">
            <button class="cancelDelete">
                <i class="text-xl fa-solid fa-xmark" style="color: #949494;"></i>
            </button>
        </div>
        
        <div class="text-center">
            <p class="mb-4 text-xl font-semibold">Are you sure you want to delete this course?</p>
            <p class="text-gray-600">This action cannot be undone.</p>
        </div>
        
        <div class="flex justify-center w-full mt-5">
            <button type="button" data-course-id="{{ $course->course_id }}" id="confirmDeleteCourseBtn" class="px-5 py-3 mx-2 mt-4 text-white rounded-lg bg-seagreen hover:bg-white hover:text-darthmouthgreen hover:border-2 hover:border-darthmouthgreen">Delete Course</button>
            <button type="button" class="px-5 py-3 mx-2 mt-4 text-white bg-red-500 rounded-lg cancelDelete hover:bg-white hover:text-red-500 hover:border-2 hover:border-red-500">Cancel</button>
        </div>
      
    </div>
</div>

@include('partials.footer')
