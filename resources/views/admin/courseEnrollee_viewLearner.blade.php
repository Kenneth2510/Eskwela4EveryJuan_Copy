@include('partials.header')

<section class="flex flex-row w-screen text-sm main-container bg-mainwhitebg md:text-base">
@include('partials.sidebar')




<section class="w-screen px-2 pt-[40px] mx-2 mt-2  overscroll-auto md:overflow-auto">
    <div class="flex justify-between px-10">
        <h1 class="text-6xl font-bold text-darthmouthgreen">Course Enrollment</h1>
        <div class="">
            <p class="text-xl font-semibold text-darthmouthgreen">{{$admin->admin_codename}}</p>
        </div>
    </div>

    <div class="w-full px-3 pb-4 mt-10 rounded-lg shadow-lg b">
        <div class="mb-5">
            <a href="/admin/course/enrollment" class="">
                <i class="text-2xl md:text-3xl fa-solid fa-arrow-left" style="color: #000000;"></i>
            </a>
        </div>


        <div class="flex justify-between">
            <div class="flex flex-col items-center justify-start w-5/12 h-full py-10 mx-5 bg-white rounded-lg shadow-lg" id="upper_left_container">
                <div class="relative flex flex-col items-center justify-start" id="learner_profile_photo"  style="margin:0 auto; padding: auto;">
                    <img class="z-0 w-40 h-40 rounded-full" src="{{ asset('storage/images/default_profile.png')}}" alt="Profile Picture">
                </div>

                <div class="mt-10" id="name_area">
                    <h1 class="text-2xl font-semibold text-center" id="nameDisp">{{$learnerCourse->learner_fname}} {{$learnerCourse->learner_lname}}</h1>
                    
                </div>

                <div class="mt-5 text-center" id="courseNameDispArea">
                    <h1 class="text-xl" id="courseNameDisp">{{$learnerCourse->course_name}}</h1>
                </div>

                <div class="flex justify-center my-2" id="learnerAreaID">
                    <select class="w-full px-5 py-3 mx-2 text-sm border border-darthmouthgreen rounded-xl" name="learnerID" id="learnerID" disabled>
                        <option value="" selected disabled>choose a learner</option>
                        @foreach ($learners as $learner)
                        <option value="{{$learner->learner_id}}" {{$learnerCourse->learner_id == $learner->learner_id ? 'selected' : ''}}>{{$learner->name}}</option>
                        @endforeach
                    </select>
                    <span id="learnerIDError" class="text-red-500"></span>
                </div>

                <div class="flex justify-center my-2" id="courseAreaID">
                    <select class="w-full px-5 py-3 mx-2 text-sm border border-darthmouthgreen rounded-xl" name="courseID" id="courseID" disabled>
                        <option value="" selected disabled>choose a course</option>
                        @foreach ($courses as $course)
                        <option value="{{$course->course_id}}" {{$learnerCourse->course_id == $course->course_id ? 'selected' : ''}}>{{$course->course_name}}</option>
                        @endforeach
                    </select>
                    <span id="courseIDError" class="text-red-500"></span>
                </div>

                <div class="mt-5 text-center" id="account_status_area">
                    <h1 class="text-xl" id="statusDisp">Status</h1>
                    
                    @if(in_array($admin->role, ['IT_DEPT', 'SUPER_ADMIN', 'COURSE_SUPERVISOR', 'COURSE_ASST_SUPERVISOR']))
                    @if ($learnerCourse->status == 'Approved')
                        <div id="status" class="px-5 py-3 my-1 text-lg text-white bg-darthmouthgreen rounded-xl">Approved</div>
                        <div id="button" class="flex flex-col hidden mx-4">
                            <form action="/admin/manage_course/enrollee/pending/{{$learnerCourse->learner_course_id}}" method="POST">
                                @method('put')
                                @csrf
                                <button class="px-5 py-3 mx-2 my-1 text-lg text-white bg-yellow-500 hover:border-2 hover:bg-white hover:border-yellow-500 hover:text-yellow-500 rounded-xl">change to pending</button>
                            </form>
                            <form action="/admin/manage_course/enrollee/reject/{{$learnerCourse->learner_course_id}}" method="POST">
                                @method('PUT')
                                @csrf
                                <button class="px-5 py-3 mx-2 my-1 text-lg text-white bg-red-600 hover:border-2 hover:bg-white hover:border-red-600 hover:text-red-600 rounded-xl">reject now</button>
                            </form>
                        </div> 
                    @elseif ($learnerCourse->status == 'Rejected')
                        <div id="status" class="px-5 py-3 my-1 text-lg text-white bg-red-500 rounded-xl">Rejected</div>
                        <div id="button" class="flex flex-col hidden mx-4">
                            <form action="/admin/manage_course/enrollee/pending/{{$learnerCourse->learner_course_id}}" method="POST">
                                @method('put')
                                @csrf
                                <button class="px-5 py-3 mx-2 my-1 text-lg text-white bg-yellow-500 hover:border-2 hover:bg-white hover:border-yellow-500 hover:text-yellow-500 rounded-xl">change to pending</button>
                            </form>
                            <form action="/admin/manage_course/enrollee/approve/{{$learnerCourse->learner_course_id}}" method="POST">
                                @method('put')
                                @csrf
                                <button type="submit" class="px-5 py-3 my-1 text-lg text-white bg-darthmouthgreen hover:border-2 hover:bg-white hover:border-darthmouthgreen hover:text-darthmouthgreen rounded-xl">approve now</button>
                            </form>
                        </div> 
                    @else 
                        <div id="status" class="px-5 py-3 my-1 text-lg text-white bg-yellow-500 rounded-xl">pending</div>
                        <div id="button" class="flex flex-col hidden mx-4">
                            <form action="/admin/manage_course/enrollee/approve/{{$learnerCourse->learner_course_id}}" method="POST">
                                @method('put')
                                @csrf
                                <button type="submit" class="px-5 py-3 my-1 text-lg text-white bg-darthmouthgreen hover:border-2 hover:bg-white hover:border-darthmouthgreen hover:text-darthmouthgreen rounded-xl">approve now</button>
                            </form>
                            
                            <form action="/admin/manage_course/enrollee/reject/{{$learnerCourse->learner_course_id}}" method="POST">
                                @method('PUT')
                                @csrf
                                <button class="px-5 py-3 mx-2 my-1 text-lg text-white bg-red-600 hover:border-2 hover:bg-white hover:border-red-600 hover:text-red-600 rounded-xl">reject</button>
                            </form>
                            
                        </div> 
                    @endif
                    @endif
                </div>
                
                <div class="flex justify-center w-full px-5 mt-5">
                    @if(in_array($admin->role, ['IT_DEPT', 'SUPER_ADMIN', 'COURSE_SUPERVISOR', 'COURSE_ASST_SUPERVISOR']))
                    <button type="button" class="px-5 py-3 mx-2 text-lg text-white bg-darthmouthgreen hover:border-2 hover:bg-white hover:border-darthmouthgreen hover:text-darthmouthgreen rounded-xl" id="changeStatusBtn">Update Enrollment Status</button>
                    <button type="button" class="hidden px-5 py-3 mx-2 text-lg text-white bg-darthmouthgreen hover:border-2 hover:bg-white hover:border-darthmouthgreen hover:text-darthmouthgreen rounded-xl" id="cancelChangeStatusBtn">Cancel</button>
                    @endif
                </div>
            </div> 



            <div class="w-9/12 h-full mx-2" id="upper_right_container">
                <div class="w-full px-5 py-10 bg-white shadow-lg rounded-xl" id="upper_right_1">
                    <h1 class="text-4xl font-semibold text-darthmouthgreen">User Details</h1>

                    <hr class="my-6 border-t-2 border-gray-300">

                    <div class="flex w-full mt-5" id="userInfo">
                        <div class="w-1/2 mx-2" id="userInfo_left">
                            <div class="mt-3" id="firstNameArea">
                                <label for="learner_fname">First Name</label><br>
                                <input disabled class="w-full h-12 px-5 py-1 border-2 rounded-lg border-darthmouthgreen" type="text" name="learner_fname" id="learner_fname" value="{{$learnerCourse->learner_fname}}">
                                <span id="firstNameError" class="text-red-500"></span>
                            </div>
                            <div class="mt-3" id="bdayArea">
                                <label for="learner_bday ">Birthday</label><br>
                                <input disabled class="w-full h-12 px-5 py-1 border-2 rounded-lg border-darthmouthgreen" type="date" name="learner_bday" id="learner_bday" value="{{$learnerCourse->learner_bday}}">
                                <span id="bdayError" class="text-red-500"></span>
                            </div>
                            <div class="mt-3" id="contactArea">
                                <label for="learner_contactno">Contact Number</label><br>
                                <input disabled class="w-full h-12 px-5 py-1 border-2 rounded-lg border-darthmouthgreen" type="text" maxlength="11" name="learner_contactno" id="learner_contactno" value="{{$learnerCourse->learner_contactno}}" placeholder="09">
                                <span id="contactError" class="text-red-500"></span>
                            </div>
                        </div>
                        <div class="w-1/2 mx-2" id="userInfo_right">
                            <div class="mt-3" id="lastNameArea">
                                <label for="learner_lname">Last Name</label><br>
                                <input disabled class="w-full h-12 px-5 py-1 border-2 rounded-lg border-darthmouthgreen" type="text" name="learner_lname" id="learner_lname" value="{{$learnerCourse->learner_lname}}">
                                <span id="lastNameError" class="text-red-500"></span>
                            </div>
                            <div class="mt-3" id="genderArea">
                                <label for="learner_gender">Gender</label><br>
                                <select disabled class="w-full h-12 px-5 py-1 border-2 rounded-lg border-darthmouthgreen" name="learner_gender" id="learner_gender" >
                                    <option value="" {{$learnerCourse->learner_gender = "" ? 'selected' : ''}}>-- select disabled an option --</option>
                                    <option value="Male" {{$learnerCourse->learner_gender = "Male" ? 'selected' : ''}}>Male</option>
                                    <option value="Female" {{$learnerCourse->learner_gender = "Female" ? 'selected' : ''}}>Female</option>
                                    <option value="Others" {{$learnerCourse->learner_gender = "Others" ? 'selected' : ''}}>Preferred not to say</option>
                                </select>
                                <span id="genderError" class="text-red-500"></span>
                            </div>
                            <div class="mt-3" id="emailArea">
                                <label for="learner_email">Email Address</label><br>
                                <input disabled class="w-full h-12 px-5 py-1 border-2 rounded-lg border-darthmouthgreen" type="email" name="learner_email" id="learner_email" value="{{$learnerCourse->learner_email}}">
                                <span id="emailError" class="text-red-500"></span>
                            </div>
                        </div>
                    </div>
    
                </div>

                <div class="w-full px-5 py-10 mt-5 bg-white shadow-lg rounded-xl" id="upper_right_2">
                    <h1 class="text-4xl font-semibold text-darthmouthgreen">Business Details</h1>

                    <hr class="my-6 border-t-2 border-gray-300">

                    <div class="mt-3" id="businessNameArea">
                        <label for="business_name">Business Name</label><br>
                        <input disabled class="w-full h-12 px-5 py-1 border-2 rounded-lg border-darthmouthgreen" type="text" name="business_name" id="business_name" value="{{$learnerCourse->business_name}}">
                        <span id="businessNameError" class="text-red-500"></span>
                    </div>

                    <div class="mt-3" id="businessAddressArea">
                        <label for="business_address">Business Address</label><br>
                        <input disabled class="w-full h-12 px-5 py-1 border-2 rounded-lg border-darthmouthgreen" type="text" name="business_address" id="business_address" value="{{$learnerCourse->business_address}}">
                        <span id="businessAddressError" class="text-red-500"></span>
                    </div>

                    <div class="mt-3" id="businessOwnerArea">
                        <label for="business_owner_name">Business Owner Name</label><br>
                        <input disabled class="w-full h-12 px-5 py-1 border-2 rounded-lg border-darthmouthgreen" type="text" name="business_owner_name" id="business_owner_name" value="{{$learnerCourse->business_owner_name}}">
                        <span id="businessOwnerNameError" class="text-red-500"></span>
                    </div>

                    <div class="mt-3" id="bplo_account_numberArea">
                        <label for="bplo_account_number">BPLO Account Number</label><br>
                        <input disabled class="w-full h-12 px-5 py-1 border-2 rounded-lg border-darthmouthgreen" maxlength="13" type="text" name="bplo_account_number" id="bplo_account_number" value="{{$learnerCourse->bplo_account_number}}">
                        <span id="bploError" class="text-red-500"></span>
                    </div>

                    <div class="flex justify-between w-full">
                                                
                        <div class="w-full mt-3 mr-2" id="business_categoryArea">
                            <label for="business_category">Business Category</label><br>
                            <select disabled class="w-full h-12 px-5 py-1 border-2 rounded-lg border-darthmouthgreen" name="business_category" id="business_category">
                                <option value=""{{$learnerCourse->business_category == '' ? 'selected' : ''}} disabled>-- select an option --</option>
                                <option value="Micro" {{$learnerCourse->business_category == 'Micro' ? 'selected' : ''}}>Micro</option>
                                <option value="Small" {{$learnerCourse->business_category == 'Small' ? 'selected' : ''}}>Small</option>
                                <option value="Medium" {{$learnerCourse->business_category == 'Medium' ? 'selected' : ''}}>Medium</option>
                            </select>
                            <span id="businessCategoryError" class="text-red-500"></span>
                        </div>

                        <div class="w-full mt-3 ml-2" id="business_classificationArea">
                            <label for="business_classification">Business Classification</label><br>
                            <select disabled class="w-full h-12 px-5 py-1 border-2 rounded-lg border-darthmouthgreen" name="business_classification" id="business_classification">
                                <option value="" {{$learnerCourse->business_classification == '' ? 'selected' : ''}} disabled>-- select an option --</option>
                                <option value="Retail" {{$learnerCourse->business_classification == 'Retail' ? 'selected' : ''}}>Retail</option>
                                <option value="Wholesale" {{$learnerCourse->business_classification == 'Wholesale' ? 'selected' : ''}}>Wholesale</option>
                                <option value="Financial Services" {{$learnerCourse->business_classification == 'Financial Services' ? 'selected' : ''}}>Financial Services</option>
                                <option value="Real Estate" {{$learnerCourse->business_classification == 'Real Estate' ? 'selected' : ''}}>Real Estate</option>
                                <option value="Transportation and Logistics" {{$learnerCourse->business_classification == 'Transportation and Logistics' ? 'selected' : ''}}>Transportation and Logistics</option>
                                <option value="Technology" {{$learnerCourse->business_classification == 'Technology' ? 'selected' : ''}}>Technology</option>
                                <option value="Healthcare" {{$learnerCourse->business_classification == 'Healthcare' ? 'selected' : ''}}>Healthcare</option>
                                <option value="Education and Training" {{$learnerCourse->business_classification == 'Education and Training' ? 'selected' : ''}}>Education and Training</option>
                                <option value="Entertainment and Media" {{$learnerCourse->business_classification == 'Entertainment and Media' ? 'selected' : ''}}>Entertainment and Media</option>
                                <option value="Hospitality and Tourism" {{$learnerCourse->business_classification == 'Hospitality and Tourism' ? 'selected' : ''}}>Hospitality and Tourism</option>

                            </select>
                            <span id="businessClassificationError" class="text-red-500"></span>
                        </div>
                    </div>

                    <div class="mt-3" id="business_descriptionArea">
                        <label for="business_description">Business Description</label><br>
                        <textarea name="business_description" disabled class="w-full px-5 py-1 border-2 rounded-lg h-36 border-darthmouthgreen" id="business_description" disabled>{{$learnerCourse->business_description}}</textarea>
                        <span id="businessDescriptionError" class="text-red-500"></span>
                    </div>

                </div>


            </div>


            <div class="w-9/12 h-full mx-2" id="upper_right_container">
                <div class="w-full px-5 py-10 bg-white shadow-lg rounded-xl" id="upper_right_1">
                    <h1 class="text-4xl font-semibold text-darthmouthgreen">Course Details</h1>

                    <hr class="my-6 border-t-2 border-gray-300">

                    <div class="w-full mt-5" id="userInfo">

                        <div class="w-full mt-3" id="courseNameArea">
                            <label for="course_name">Course Name</label><br>
                            <input disabled class="w-full h-12 px-5 py-1 border-2 rounded-lg border-darthmouthgreen" type="text" name="course_name" id="course_name" value="{{$learnerCourse->course_name}}">
                            <span id="courseNameError" class="text-red-500"></span>
                        </div>

                        <div class="w-full mt-3" id="courseDifficultyArea">
                            <label for="course_difficulty">Course Difficulty</label><br>
                            <select disabled class="w-full h-12 px-10 py-1 border-2 rounded-lg border-darthmouthgreen" name="course_difficulty" id="course_difficulty">
                                <option value="" {{$learnerCourse->course_difficulty == '' ? 'selected' : ''}} disabled>-- select disabled an option --</option>
                                <option value="Beginner" {{$learnerCourse->course_difficulty == 'Beginner' ? 'selected' : ''}}>Beginner</option>
                                <option value="Intermmediate" {{$learnerCourse->course_difficulty == 'Intermmediate' ? 'selected' : ''}}>Intermmediate</option>
                                <option value="Advanced" {{$learnerCourse->course_difficulty == 'Advanced' ? 'selected' : ''}}>Advanced</option>
                            </select>
                            <span id="courseDifficultyError" class="text-red-500"></span>
                        </div>

                        <div class="mt-3" id="course_descriptionArea">
                            <label for="course_description">Course Description</label><br>
                            <textarea disabled name="course_description" class="w-full px-5 py-1 border-2 rounded-lg h-36 border-darthmouthgreen" id="course_description">{{$learnerCourse->course_description}}</textarea>
                            <span id="courseDescriptionError" class="text-red-500"></span>
                        </div>
                    </div>
    
                </div>

                    <div class="w-full px-5 py-10 mt-5 bg-white shadow-lg rounded-xl" id="upper_right_3">
                        <h1 class="text-4xl font-semibold text-darthmouthgreen">Instructor Details</h1>

                        <hr class="my-6 border-t-2 border-gray-300">

                        <div class="w-full mt-3" id="courseInstructorArea">
                            <label for="course_instructor">Course Instructor</label><br>
                            <select disabled class="w-full h-12 px-10 py-1 border-2 rounded-lg border-darthmouthgreen" name="course_instructor" id="course_instructor">
                                <option value="" selected  disabled>-- select an option --</option>
                                @foreach ($instructors as $instructor)
                                <option value="{{$instructor->id}}" {{$learnerCourse->instructor_id == $instructor->id ? 'selected' : ''}}>{{$instructor->name}}</option>
                                @endforeach
                            </select>
                            <span id="courseInstructorError" class="text-red-500"></span>
                        </div>
            
                    </div>
            </div>
            
        </div>


    </div>
</section>
</section>

@include('partials.footer')

<script>
$(document).ready(function() {

    var baseUrl = window.location.href
    var csrfToken = $('meta[name="csrf-token"]').attr('content'); // Get the CSRF token from the meta tag
    
    $('#changeStatusBtn').on('click', function(e) {
        e.preventDefault()

        $('#button').removeClass('hidden')
        $('#cancelChangeStatusBtn').removeClass('hidden')
        $('#changeStatusBtn').addClass('hidden')
    })

    $('#cancelChangeStatusBtn').on('click', function(e) {
        e.preventDefault()

        $('#button').addClass('hidden')
        $('#cancelChangeStatusBtn').addClass('hidden')
        $('#changeStatusBtn').removeClass('hidden')
    })

})

</script>