@include('partials.header')

<section class="flex flex-row w-full h-screen text-sm main-container bg-mainwhitebg md:text-base">

    @include('partials.instructorNav')
    @include('partials.instructorSidebar')

        
    {{-- MAIN --}}
    <section class="w-full px-2 pt-[70px] mx-2 mt-2 md:w-3/4 lg:w-9/12  overscroll-auto md:overflow-auto">
        <div class="px-3 pb-4 rounded-lg shadow-lg b">
            <a href="{{ back()->getTargetUrl() }}" class="w-8 h-8 m-2">
                <svg xmlns="http://www.w3.org/2000/svg" height="25" viewBox="0 -960 960 960" width="24"><path d="m313-440 224 224-57 56-320-320 320-320 57 56-224 224h487v80H313Z"/></svg>
            </a>
            <div class="flex" id="upper_container">

                <div class="flex flex-col items-center justify-start w-3/12 h-full py-10 mx-5 bg-white rounded-lg shadow-lg" id="upper_left_container">
                    <div class="relative flex flex-col items-center justify-start"  style="margin:0 auto; padding: auto;">
                        <img class="z-0 w-40 h-40 rounded-full" src="{{ asset('storage/' . $instructorData->profile_picture) }}" alt="Profile Picture">
                   </div>

                    <div class="mt-10" id="name_area">
                        <h1 class="text-2xl font-semibold text-center">{{$instructorData->instructor_fname}} {{$instructorData->instructor_lname}}</h1>
                    </div>

                    <div class="mt-5 text-center" id="account_status_area">
                        <h1 class="text-xl">instructor</h1>
                        {{-- <h1 class="text-xl">ID: 1</h1> --}}

                        @if ($instructorData->status == 'Approved')
                        <div class="px-5 py-2 text-white bg-darthmouthgreen rounded-xl">Approved</div>
                        @elseif ($instructorData->status == 'Pending')
                        <div class="px-5 py-2 text-white bg-yellow-600 rounded-xl">Pending</div>
                        @else
                        <div class="px-5 py-2 text-white bg-red-500 rounded-xl">Rejected</div>
                        @endif
                    </div>

                    <div class="mt-10 text-center" id="email_area">
                        <h1 class="text-xl">Email</h1>
                        <h2 class="mb-5 text-md">{{$instructorData->instructor_email}}</h2>

                        <a href="{{ url('/instructor/message') }}?email={{ $learner->learner_email }}&type=Instructor" class="px-5 py-3 mt-10 text-lg text-white bg-darthmouthgreen hover:border-2 hover:bg-white hover:border-darthmouthgreen hover:text-darthmouthgreen rounded-xl">Send Message</a>
                    </div>
                </div> 

                
                <div class="w-9/12 h-full" id="upper_right_container">
                    <div class="w-full px-5 py-10 bg-white shadow-lg rounded-xl" id="upper_right_1">
                        <h1 class="text-4xl font-semibold text-darthmouthgreen">User Details</h1>

                        <hr class="my-6 border-t-2 border-gray-300">

                        <div class="flex w-full mt-5" id="userInfo">
                            
                            <div class="w-1/2 mx-2" id="userInfo_left">
                                <div class="mt-3" id="firstNameArea">
                                    <label for="instructor_fname">First Name</label><br>
                                    <input class="w-full h-12 px-5 py-1 border-2 rounded-lg border-darthmouthgreen" type="text" name="instructor_fname" id="instructor_fname" value="{{$instructorData->instructor_fname}}" disabled>
                                    <span id="firstNameError" class="text-red-500"></span>
                                </div>
                                <div class="mt-3" id="bdayArea">
                                    <label for="instructor_bday ">Birthday</label><br>
                                    <input class="w-full h-12 px-5 py-1 border-2 rounded-lg border-darthmouthgreen" type="date" name="instructor_bday" id="instructor_bday" value="{{$instructorData->instructor_bday}}" disabled>
                                    <span id="bdayError" class="text-red-500"></span>
                                </div>
                                <div class="mt-3" id="contactArea">
                                    <label for="instructor_contactno">Contact Number</label><br>
                                    <input class="w-full h-12 px-5 py-1 border-2 rounded-lg border-darthmouthgreen" type="text" maxlength="11" name="instructor_contactno" id="instructor_contactno" value="{{$instructorData->instructor_contactno}}" disabled>
                                </div>
                            </div>
                            <div class="w-1/2 mx-2" id="userInfo_right">
                                <div class="mt-3" id="lastNameArea">
                                    <label for="instructor_lname">Last Name</label><br>
                                    <input class="w-full h-12 px-5 py-1 border-2 rounded-lg border-darthmouthgreen" type="text" name="instructor_lname" id="instructor_lname" value="{{$instructorData->instructor_lname}}" disabled>
                                    <span id="lastNameError" class="text-red-500"></span>
                                </div>
                                <div class="mt-3" id="genderArea">
                                    <label for="instructor_gender">Gender</label><br>
                                    <select class="w-full h-12 px-5 py-1 border-2 rounded-lg border-darthmouthgreen" name="instructor_gender" id="instructor_gender" disabled>
                                        <option value="" {{$instructorData->instructor_gender == "" ? 'selected' : ''}}>-- select an option --</option>
                                        <option value="Male" {{$instructorData->instructor_gender == "Male" ? 'selected' : ''}}>Male</option>
                                        <option value="Female" {{$instructorData->instructor_gender == "Female" ? 'selected' : ''}}>Female</option>
                                        <option value="Others" {{$instructorData->instructor_gender == "Others" ? 'selected' : ''}}>Preferred not to say</option>
                                    </select>
                                    <span id="genderError" class="text-red-500"></span>
                                </div>
                                <div class="mt-3" id="emailArea">
                                    <label for="instructor_email">Email Address</label><br>
                                    <input class="w-full h-12 px-5 py-1 border-2 rounded-lg border-darthmouthgreen" type="email" name="instructor_email" id="instructor_email" value="{{$instructorData->instructor_email}}" disabled>
                                </div>
                            </div>
                        </div>
         
                    </div>



                                                
                        <div class="w-full px-5 py-10 mt-5 bg-white shadow-lg rounded-xl" id="courseProgress">
                            <h1 class="text-4xl font-semibold text-darthmouthgreen">Courses Managed</h1>

                            <hr class="my-6 border-t-2 border-gray-300">

                            <table class="w-full">
                                <thead>
                                    <th>Course Name</th>
                                    <th>Learners Enrolled</th>
                                </thead>
                                <tbody>
                                    @foreach ($courses as $course)
                                    <tr>
                                        <td class="py-5">{{$course->course_name}}</td>
                                        <td>{{$course->learner_count}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
            
        </div>
    </section>


    @include('partials.instructorProfile')
        
    </section>




@include('partials.footer')