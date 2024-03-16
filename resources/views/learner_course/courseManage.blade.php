@include('partials.header')
<section class="flex flex-row w-full h-screen text-sm bg-mainwhitebg md:text-base lg:h-screen">


@include('partials.learnerSidebar')

{{-- MAIN --}}
<section class="w-full pt-[125px] mx-4  overscroll-auto md:overflow-auto">
    {{-- course name/title --}}
    <a href="{{ url('/learner/dashboard') }}" class="w-8 h-8 m-2">
        <svg xmlns="http://www.w3.org/2000/svg" height="25" viewBox="0 -960 960 960" width="24"><path d="m313-440 224 224-57 56-320-320 320-320 57 56-224 224h487v80H313Z"/></svg>
    </a>
    <div class="relative z-0 pb-4 bg-black border border-gray-400 rounded-lg shadow-lg text-mainwhitebg">
        <img class="absolute top-0 left-0 object-cover w-full h-full pointer-events-none -z-10 opacity-30" src="{{asset('images/marketing-img.png')}}" alt="computer with microphone">
        <div class="z-50 p-2">
            <h1 class="w-1/2 py-4 text-lg font-semibold">{{$course->course_name}}</h1>
            {{-- subheaders --}}
            <div class="flex flex-col fill-mainwhitebg">
                <div class="flex flex-row my-2">
                    <svg class="mr-2 " xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M240-80q-33 0-56.5-23.5T160-160v-640q0-33 23.5-56.5T240-880h480q33 0 56.5 23.5T800-800v640q0 33-23.5 56.5T720-80H240Zm0-80h480v-640h-80v280l-100-60-100 60v-280H240v640Zm0 0v-640 640Zm200-360 100-60 100 60-100-60-100 60Z"/></svg>
                    <p>{{$course->course_code}}</p>
                </div>
                <div class="flex flex-row my-2">
                    <svg class="mr-2" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M480-120 200-272v-240L40-600l440-240 440 240v320h-80v-276l-80 44v240L480-120Zm0-332 274-148-274-148-274 148 274 148Zm0 241 200-108v-151L480-360 280-470v151l200 108Zm0-241Zm0 90Zm0 0Z"/></svg>
                    <p>{{$course->course_difficulty}}</p>
                </div>
                <div class="flex flex-row my-2">
                    <svg class="mr-2" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M300-80q-58 0-99-41t-41-99v-520q0-58 41-99t99-41h500v600q-25 0-42.5 17.5T740-220q0 25 17.5 42.5T800-160v80H300Zm-60-267q14-7 29-10t31-3h20v-440h-20q-25 0-42.5 17.5T240-740v393Zm160-13h320v-440H400v440Zm-160 13v-453 453Zm60 187h373q-6-14-9.5-28.5T660-220q0-16 3-31t10-29H300q-26 0-43 17.5T240-220q0 26 17 43t43 17Z"/></svg>
                    <p>10 Lessons</p>
                </div>
                <div class="flex flex-row my-2">
                    @if ($isEnrolled !== null)
                    <p>Enroll Status: {{ $isEnrolled->status }}</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="flex items-center w-full">
            @if ($isEnrolled !== null)
                <a href="" id="startBtn" class="w-32 h-10 px-6 py-2 m-2 rounded-full bg-seagreen hover:bg-green-800">
                    Start Now
                </a>
                <x-forms.primary-button
                color="red"
                name="Unenroll" 
                type="button"
                class="bg-red-600 hover:bg-red-700"
                id="unenrollBtn"/>
                {{-- <button type="button" id="unenrollBtn" class="w-32 h-10 m-2 bg-red-600 rounded-full hover:bg-red-800">
                    Unenroll
                </button> --}}
            @else
                <x-forms.primary-button
                color="seagreen"
                name="Enroll Now"
                type="button"
                class="hover:bg-green-800"
                id="enrollBtn"/>
                {{-- <button type="button" id="enrollBtn" class="w-32 h-10 m-2 rounded-full bg-seagreen hover:bg-green-800">
                    <h1>Enroll Now</h1>
                </button> --}}
            @endif
        </div>
        
        
        
        
    </div>
    @if($isEnrolled !== null)

    <div id="unenrollCourseModal" class="fixed top-0 left-0 z-30 flex items-center justify-center hidden w-screen h-screen bg-black bg-opacity-50">
        <form id="unenrollCourse" data-learner_course-id="{{ $isEnrolled->learner_course_id }}">
            @csrf
            <div class="p-5 text-center bg-white rounded-lg">
                <p>Are you sure you want to unenroll the course?</p>
                <x-forms.primary-button
                color="red"
                name="Unenroll"
                class="bg-red-600 hover:bg-red-700"
                id="confirmUnenroll"/>
                <x-forms.primary-button
                color="gray"
                name="Cancel" 
                type="button"
                id="cancelUnenroll"/>
                {{-- <button type="submit" id="confirmUnenroll" class="px-4 py-2 m-2 text-white bg-red-600 rounded-md">
                    Unenroll
                </button>
                <button type="button" id="cancelUnenroll" class="px-4 py-2 m-2 text-gray-700 bg-gray-400 rounded-md">
                    Cancel
                </button> --}}
            </div>
        </form>
    </div>

    @else 
    <div id="enrollCourseModal" class="fixed top-0 left-0 z-30 flex items-center justify-center hidden w-screen h-screen bg-black bg-opacity-50"> 
        <form id="enrollCourse" action="" data-course-id="{{$course->course_id}}">
            @csrf
            <div class="p-5 text-center bg-white rounded-lg">
                <p>Are you sure you want to Enroll course?</p>
                <x-forms.primary-button
                color="green"
                name="Confirm" 
                class="bg-green-600 hover:bg-green-700"
                id="confirmEnroll"/>
                <x-forms.primary-button
                color="gray"
                name="Cancel" 
                type="button"
                id="cancelEnroll"/>
                {{-- <button type="submit" id="confirmEnroll" class="px-4 py-2 m-2 text-white bg-green-600 rounded-md">Confirm</button>
                <button id="cancelEnroll" class="px-4 py-2 m-2 text-gray-700 bg-gray-400 rounded-md">Cancel</button> --}}
            </div>
        </form>
    </div>
    @endif


    {{-- course management --}}
    <div class="relative w-full mt-5">
        {{-- course left --}}
        <div class="flex justify-between text-mainwhitebg fill-mainwhitebg">
            <a class="relative w-1/2 h-16 p-2 mr-2 text-center rounded-lg bg-darthmouthgreen">
                <h1>Manage Course</h1>
                <svg class="absolute bottom-0 right-0 hidden mx-2 " xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M647-440H160v-80h487L423-744l57-56 320 320-320 320-57-56 224-224Z"/></svg>
            </a>
            <button class="relative w-1/2 h-16 p-2 ml-2 text-center rounded-lg bg-seagreen">
                <h1>View Progress</h1>
                <svg class="absolute bottom-0 right-0 hidden mx-2" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M647-440H160v-80h487L423-744l57-56 320 320-320 320-57-56 224-224Z"/></svg>
            </button>
        </div>

        <div class="flex mt-5 rounded-lg">
            <div id="side_items" class="w-1/6 h-full bg-green-700 rounded-lg h-">
                <ul class="px-5 py-5 text-xl font-medium text-white">
                    <li id="display_info_btn" class="w-full px-2 py-5 mt-2 rounded-xl hover:bg-green-900">
                            <i class="pr-2 text-3xl fa-solid fa-book-open"></i>
                            Course Info
                    </li>
                    <li id="enrolled_learners_btn" class="w-full px-2 py-5 mt-2 rounded-xl hover:bg-green-900">
                            <i class="pr-2 text-3xl fa-solid fa-users"></i>
                            Enrolled Learners
                    </li>
                    <li id="enrollment_summary_btn" class="w-full px-2 py-5 mt-2 rounded-xl hover:bg-green-900">
                            <i class="pr-2 text-3xl fa-solid fa-book"></i>
                            Enrollment Summary
                    </li>
                    <li class="w-full px-2 py-3 mt-2 rounded-xl">
                      
                    </li>
                    <li class="w-full px-2 py-3 mt-2 rounded-xl">
                      
                    </li>
                </ul>
            </div>
            
            <div id="content_area" class="w-5/6 m-5 overflow-y-auto ">
                <div id="learner_course_info" class="">
                    <h1 class="text-2xl font-semibold border-b-2 border-black">Course Information</h1>

                    <form id="updateCourse" name="updateCourse" data-course-id="{{ $course->course_id }}">
                        @csrf
                        <div id="info" class="mt-5 overflow-y-auto">
                            <div class="flex">
                                <div class="w-2/5">
                                    <div class="flex my-2 justify-normal">
                                        <label for="" class="w-2/6 text-lg">Course ID:</label>
                                        <input type="text" value="{{ $course->course_id }}" class="w-4/6 text-lg" disabled>
                                    </div>
                                    <div class="flex my-2 justify-normal ">
                                        <label for="course_name" class="w-2/6 text-lg">Course Name:</label>
                                        <input type="text" id="course_name" name="course_name" value="{{ $course->course_name }}" class="w-4/6 text-lg" disabled>
                                    </div>
                                    <div class="flex my-2 justify-normal ">
                                        <label for="" class="w-2/6 text-lg">Course Code:</label>
                                        <input type="text" value="{{ $course->course_code }}" class="w-4/6 text-lg" disabled>
                                    </div>
                                </div>
                                
                                <div class="w-2/5 mx-5">
                                    <div class="flex my-1 justify-normal ">
                                        <h1 class="w-2/6 text-lg">Course Status:</h1>
                                        @if ($course->course_status == 'Approved')
                                        <p class="px-5 py-2 bg-green-600 rounded-full">Approved</p>
                                        @elseif ($course->course_status == 'Pending')
                                        <p class="px-5 py-2 bg-yellow-400 rounded-full">Pending</p>
                                        @else
                                        <p class="px-5 py-2 bg-red-600 rounded-full">Rejected</p>
                                        @endif
                                        
                                    </div>
                                    <div class="flex py-1 my-1 justify-normal">
                                        <label for="" class="w-2/5 text-lg">Course Difficulty:</label>
                                        <select name="course_difficulty" id="course_difficulty" class="w-2/5" disabled>
                                            <option value="" {{ $course->course_difficulty == '' ? 'selected' : '' }}>--select an option--</option>
                                            <option value="Beginner" {{ $course->course_difficulty == 'Beginner' ? 'selected': '' }}>Beginner</option>
                                            <option value="Intermediate" {{ $course->course_difficulty == 'Intermediate' ? 'selected': '' }}>Intermediate</option>
                                            <option value="Advanced" {{ $course->course_difficulty == 'Advanced' ? 'selected': '' }}>Advanced</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="mt-5">
                                    <h1>Created {{ $course->created_at }} by {{ $course->instructor_fname }} {{ $course->instructor_lname }}</h1>
                                    <h1>Last Modified {{ $course->updated_at }}</h1>
                                </div>
                            </div>

                            <div class="mt-1">
                                <h1>Course Description</h1>
                                {{-- <p class="h-24 overflow-y-auto ">{{ $course->course_description }}</p> --}}
                                <textarea name="course_description" id="course_description" class="w-full h-24 max-w-full max-h-24" disabled>{{ $course->course_description }}</textarea>
                            </div>

                        </div>
                    </form>
                    
                </div>


                <div id="learner_enrolled_learners" class="hidden">
                    <h1 class="text-2xl font-semibold border-b-2 border-black">Enrolled Learner</h1>

                    <form id="enrolleeForm" data-course-id="{{$course->course_id}}"  action="/learner/course/manage/{{$course->course_id}}" method="GET">
                        <div class="flex items-center">
                            <div class="flex items-center mx-10">
                                <div class="mx-2">
                                    <label for="filterDate" class="">Filter by Date</label><br>
                                    <input type="date" name="filterDate" class="w-40 px-2 py-2 text-base border-2 border-black rounded-xl" value="{{ request('filterDate') }}">
                                </div>
                                <div class="mx-2">
                                    <label for="filterStatus" class="">Filter by Status</label><br>
                                    <select name="filterStatus" id="filterStatus" class="w-32 px-2 py-2 text-base border-2 border-black rounded-xl">
                                        <option value="" {{ request('filterDate') == '' ? 'selected': ''}}>Select Status</option>
                                        <option value="Pending" {{ request('filterStatus') == 'Pending' ? 'selected': ''}}>Pending</option>
                                        <option value="Approved" {{ request('filterStatus') == 'Approved' ? 'selected': ''}}>Approved</option>
                                        <option value="Rejected" {{ request('filterStatus') == 'Rejected' ? 'selected': ''}}>Rejected</option>
                                    </select>
                                </div>
                                <button class="h-12 px-5 py-1 mx-3 text-lg font-medium bg-green-600 rounded-xl hover:bg-green-900 hover:text-white" type="submit">Filter</button>
                            </div>
                            <div class="">
                                <select name="searchBy" id="" class="w-40 px-2 py-2 text-lg border-2 border-black rounded-xl">
                                    <option value="" {{request('searchBy') == '' ? 'selected' : ''}}class="">Search By</option>
                                    <option value="learner_course_id" {{request('searchBy') == 'learner_course_id' ? 'selected' : ''}}>Enrollee ID</option>
                                    <option value="learner_id" {{request('searchBy') == 'learner_id' ? 'selected' : ''}}>Learner ID</option>
                                    <option value="name" {{request('searchBy') == 'name' ? 'selected' : ''}}>Name</option>
                                    <option value="learner_email" {{request('searchBy') == 'learner_email' ? 'selected' : ''}}>Email</option>
                                    <option value="learner_contactno" {{request('searchBy') == 'learner_contactno' ? 'selected' : ''}}>Contact No.</option>
                                    {{-- <option value="created_at">Date Registered</option> --}}
                                    {{-- <option value="status">Status</option> --}}
                                </select>
                                <input type="text" name="searchVal" class="px-2 py-2 ml-3 text-lg border-2 border-black w-80 rounded-xl" value="{{ request('searchVal') }}" placeholder="Type to search">
                                <button class="px-3 py-2 mx-3 text-lg font-medium bg-green-600 rounded-xl hover:bg-green-900 hover:text-white" type="submit">Search</button>        
                            </div>
                        </div>
                    </form>

                    <div id="learner_table" class="mt-5">
                        <table>
                            <thead class="text-left">
                                <th class="w-1/5">Enrollee ID</th>
                                <th class="w-1/5">Learner ID</th>
                                <th class="w-1/5">Enrollee Info</th>
                                <th class="w-1/5">Date</th>
                                <th class="w-1/5">Status</th>
                                <th class="w-1/5"></th>
                            </thead>
                            <tbody>
                                @forelse ($enrollees as $enrollee)
                                <tr>
                                    <td>{{$enrollee->learner_course_id}}</td>
                                    <td>{{$enrollee->learner_id}}</td>
                                    <td>
                                        <h1>{{$enrollee->learner_fname}} {{$enrollee->learner_lname}} </h1>
                                        <p>{{$enrollee->learner_email}}</p>
                                    </td>
                                    <td>{{$enrollee->created_at}}</td>
                                    <td>{{$enrollee->status}}</td>
                                    <td>
                                        {{-- <button class="px-5 py-2 bg-green-500 rounded-2xl hover:bg-green-700">
                                            view
                                        </button> --}}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td class="py-1 text-lg font-normal" colspan="7">No enrollees found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div id="enrollment_summary" class="hidden overflow-y-auto">
                    <h1 class="text-2xl font-semibold border-b-2 border-black">Course Summary</h1>

                    <div class="flex mt-3 justify-normal">
                        <div class="w-2/5">
                            <h1>Course Name: {{ $course->course_name }}</h1>
                            <h1>Course ID: {{ $course->course_id }}</h1>
                            <h1>Course Code: {{ $course->course_code }}</h1>
                        </div>
                        <div class="w-2/5">
                            <h1>Instructor: {{ $course->instructor_fname }} {{ $course->instructor_lname }}</h1>
                            <h1>Course Difficulty: {{ $course->course_difficulty }}</h1>
                            <div class="flex">
                                <h1>Course Status: </h1>
                                @if ($course->course_status == 'Approved')
                               <p class="px-5 py-2 bg-green-600 rounded-full">Approved</p>
                               @elseif ($course->course_status == 'Pending')
                               <p class="px-5 py-2 bg-yellow-400 rounded-full">Pending</p>
                               @else
                               <p class="px-5 py-2 bg-red-600 rounded-full">Rejected</p>
                               @endif
                            </div>
                            
                        </div>
                        <div class="w-2/5">
                            <h1>Enrollee ID: {{ $isEnrolled->learner_course_id }}</h1>
                            <h1>Enrolled on: {{ $isEnrolled->created_at }}</h1>
                            <h1>Course Created at: {{ $course->created_at }}</h1>
                            <h1>Course Updated at: {{ $course->updated_at }}</h1>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <h1>Course Description</h1>
                        <p class="h-24 overflow-y-auto ">{{ $course->course_description }}</p>
                    </div>

                    {{-- <div class="flex justify-end">
                        <button type="button" id="unenrollBtn2" class="w-32 h-10 m-2 bg-red-600 rounded-full hover:bg-red-800">
                            Unenroll
                        </button>
                    </div>

                    
                    <div id="unenrollCourseModal2" class="fixed top-0 left-0 z-30 flex items-center justify-center hidden w-screen h-screen bg-black bg-opacity-50">
                        <form id="unenrollCourse2" data-learner_course-id="{{ $isEnrolled->learner_course_id }}">
                            @csrf
                            <div class="p-5 text-center bg-white rounded-lg">
                                <p>Are you sure you want to unenroll the course?</p>
                                <button type="submit" id="confirmUnenroll2" class="px-4 py-2 m-2 text-white bg-red-600 rounded-md">
                                    Unenroll
                                </button>
                                <button type="button" id="cancelUnenroll2" class="px-4 py-2 m-2 text-gray-700 bg-gray-400 rounded-md">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div> --}}

                    {{-- <div class="flex justify-end">
                        <button id="showDeleteModal" class="px-5 py-5 text-xl bg-red-600 rounded-xl hover:bg-red-700">Delete Course</button>
                    </div>
                    
                    <div id="deleteCourseModal" class="fixed top-0 left-0 flex items-center justify-center hidden w-screen h-screen bg-black bg-opacity-50">
                        <form id="deleteCourse" action="" data-course-id="{{ $course->course_id }}">
                            @csrf
                            <div class="p-5 text-center bg-white rounded-lg">
                                <p>Are you sure you want to delete this course?</p>
                                <button type="submit" id="confirmDelete" class="px-4 py-2 m-2 text-white bg-red-600 rounded-md">Confirm</button>
                                <button id="cancelDelete" class="px-4 py-2 m-2 text-gray-700 bg-gray-400 rounded-md">Cancel</button>
                            </div>
                        </form>
                        
                    </div> --}}
                    
                </div>

            </div>
        </div>


    </div>
</section>
</section>
@include('partials.footer')