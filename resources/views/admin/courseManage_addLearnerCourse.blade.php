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
                    <h1 class="text-2xl font-semibold text-center" id="nameDisp">NAME</h1>
                    
                </div>

                <div class="mt-5 text-center" id="courseNameDispArea">
                    <h1 class="text-xl" id="courseNameDisp">COURSE</h1>
                </div>

                <div class="flex justify-center my-2" id="learnerAreaID">
                    <select class="w-full px-5 py-3 mx-2 text-sm border border-darthmouthgreen rounded-xl" name="learnerID" id="learnerID">
                        <option value="" selected disabled>choose a learner</option>
                        @foreach ($learners as $learner)
                        <option value="{{$learner->learner_id}}">{{$learner->name}}</option>
                        @endforeach
                    </select>
                    <span id="learnerIDError" class="text-red-500"></span>
                </div>

                <div class="flex justify-center my-2" id="courseAreaID">
                    <select class="w-full px-5 py-3 mx-2 text-sm border border-darthmouthgreen rounded-xl" name="courseID" id="courseID">
                        <option value="" selected disabled>choose a course</option>
                        @foreach ($courses as $course)
                        <option value="{{$course->course_id}}">{{$course->course_name}}</option>
                        @endforeach
                    </select>
                    <span id="courseIDError" class="text-red-500"></span>
                </div>

                
                <div class="flex justify-center w-full px-5 mt-5">
                    @if(in_array($admin->role, ['IT_DEPT', 'SUPER_ADMIN', 'COURSE_SUPERVISOR', 'COURSE_ASST_SUPERVISOR']))
                    <button type="button" class="px-5 py-3 mx-2 text-lg text-white bg-darthmouthgreen hover:border-2 hover:bg-white hover:border-darthmouthgreen hover:text-darthmouthgreen rounded-xl" id="enrollNewBtn">Enroll new Learner</button>
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
                                <input disabled class="w-full h-12 px-5 py-1 border-2 rounded-lg border-darthmouthgreen" type="text" name="learner_fname" id="learner_fname" value="">
                                <span id="firstNameError" class="text-red-500"></span>
                            </div>
                            <div class="mt-3" id="bdayArea">
                                <label for="learner_bday ">Birthday</label><br>
                                <input disabled class="w-full h-12 px-5 py-1 border-2 rounded-lg border-darthmouthgreen" type="date" name="learner_bday" id="learner_bday" value="">
                                <span id="bdayError" class="text-red-500"></span>
                            </div>
                            <div class="mt-3" id="contactArea">
                                <label for="learner_contactno">Contact Number</label><br>
                                <input disabled class="w-full h-12 px-5 py-1 border-2 rounded-lg border-darthmouthgreen" type="text" maxlength="11" name="learner_contactno" id="learner_contactno" value="" placeholder="09">
                                <span id="contactError" class="text-red-500"></span>
                            </div>
                        </div>
                        <div class="w-1/2 mx-2" id="userInfo_right">
                            <div class="mt-3" id="lastNameArea">
                                <label for="learner_lname">Last Name</label><br>
                                <input disabled class="w-full h-12 px-5 py-1 border-2 rounded-lg border-darthmouthgreen" type="text" name="learner_lname" id="learner_lname" value="">
                                <span id="lastNameError" class="text-red-500"></span>
                            </div>
                            <div class="mt-3" id="genderArea">
                                <label for="learner_gender">Gender</label><br>
                                <select disabled class="w-full h-12 px-5 py-1 border-2 rounded-lg border-darthmouthgreen" name="learner_gender" id="learner_gender" >
                                    <option value="">-- select disabled an option --</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Others">Preferred not to say</option>
                                </select>
                                <span id="genderError" class="text-red-500"></span>
                            </div>
                            <div class="mt-3" id="emailArea">
                                <label for="learner_email">Email Address</label><br>
                                <input disabled class="w-full h-12 px-5 py-1 border-2 rounded-lg border-darthmouthgreen" type="email" name="learner_email" id="learner_email" value="">
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
                        <input disabled class="w-full h-12 px-5 py-1 border-2 rounded-lg border-darthmouthgreen" type="text" name="business_name" id="business_name" value="">
                        <span id="businessNameError" class="text-red-500"></span>
                    </div>

                    <div class="mt-3" id="businessAddressArea">
                        <label for="business_address">Business Address</label><br>
                        <input disabled class="w-full h-12 px-5 py-1 border-2 rounded-lg border-darthmouthgreen" type="text" name="business_address" id="business_address" value="">
                        <span id="businessAddressError" class="text-red-500"></span>
                    </div>

                    <div class="mt-3" id="businessOwnerArea">
                        <label for="business_owner_name">Business Owner Name</label><br>
                        <input disabled class="w-full h-12 px-5 py-1 border-2 rounded-lg border-darthmouthgreen" type="text" name="business_owner_name" id="business_owner_name" value="">
                        <span id="businessOwnerNameError" class="text-red-500"></span>
                    </div>

                    <div class="mt-3" id="bplo_account_numberArea">
                        <label for="bplo_account_number">BPLO Account Number</label><br>
                        <input disabled class="w-full h-12 px-5 py-1 border-2 rounded-lg border-darthmouthgreen" maxlength="13" type="text" name="bplo_account_number" id="bplo_account_number" value="">
                        <span id="bploError" class="text-red-500"></span>
                    </div>

                    <div class="flex justify-between w-full">
                                                
                        <div class="w-full mt-3 mr-2" id="business_categoryArea">
                            <label for="business_category">Business Category</label><br>
                            <select disabled class="w-full h-12 px-5 py-1 border-2 rounded-lg border-darthmouthgreen" name="business_category" id="business_category">
                                <option value="" disabled>-- select an option --</option>
                                <option value="Micro">Micro</option>
                                <option value="Small">Small</option>
                                <option value="Medium">Medium</option>
                            </select>
                            <span id="businessCategoryError" class="text-red-500"></span>
                        </div>

                        <div class="w-full mt-3 ml-2" id="business_classificationArea">
                            <label for="business_classification">Business Classification</label><br>
                            <select disabled class="w-full h-12 px-5 py-1 border-2 rounded-lg border-darthmouthgreen" name="business_classification" id="business_classification">
                                <option value=""disabled>-- select an option --</option>
                                <option value="Retail">Retail</option>
                                <option value="Wholesale">Wholesale</option>
                                <option value="Financial Services">Financial Services</option>
                                <option value="Real Estate">Real Estate</option>
                                <option value="Transportation and Logistics">Transportation and Logistics</option>
                                <option value="Technology">Technology</option>
                                <option value="Healthcare">Healthcare</option>
                                <option value="Education and Training">Education and Training</option>
                                <option value="Entertainment and Media">Entertainment and Media</option>
                                <option value="Hospitality and Tourism">Hospitality and Tourism</option>

                            </select>
                            <span id="businessClassificationError" class="text-red-500"></span>
                        </div>
                    </div>

                    <div class="mt-3" id="business_descriptionArea">
                        <label for="business_description">Business Description</label><br>
                        <textarea name="business_description" disabled class="w-full px-5 py-1 border-2 rounded-lg h-36 border-darthmouthgreen" id="business_description"></textarea>
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
                            <input disabled class="w-full h-12 px-5 py-1 border-2 rounded-lg border-darthmouthgreen" type="text" name="course_name" id="course_name" value="">
                            <span id="courseNameError" class="text-red-500"></span>
                        </div>

                        <div class="w-full mt-3" id="courseDifficultyArea">
                            <label for="course_difficulty">Course Difficulty</label><br>
                            <select disabled class="w-full h-12 px-10 py-1 border-2 rounded-lg border-darthmouthgreen" name="course_difficulty" id="course_difficulty">
                                <option value="" selected disabled>-- select disabled an option --</option>
                                <option value="Beginner">Beginner</option>
                                <option value="Intermmediate">Intermmediate</option>
                                <option value="Advanced">Advanced</option>
                            </select>
                            <span id="courseDifficultyError" class="text-red-500"></span>
                        </div>

                        <div class="mt-3" id="course_descriptionArea">
                            <label for="course_description">Course Description</label><br>
                            <textarea disabled name="course_description" class="w-full px-5 py-1 border-2 rounded-lg h-36 border-darthmouthgreen" id="course_description"></textarea>
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
                                {{-- @foreach ($instructors as $instructor)
                                <option value="{{$instructor->id}}">{{$instructor->name}}</option>
                                @endforeach --}}
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
    


    $('#learnerID, #courseID').on('change', function() {
        var learner_id = $('#learnerID').val()
        var course_id = $('#courseID').val()

        var url = baseUrl + "/getData"

        $.ajax({
            type: "GET",
            url: url,
            data: {
                learner_id: learner_id,
                course_id: course_id,
            },
            success: function (response) {
                console.log(response);
                var learner = response['learner']
                var course = response['course']


                displayLearner(learner)
                displayCourse(course)
            },
            error: function(error) {
                console.log(error);
            }
        });
    })


    function displayLearner(learner) {

            const learner_id = learner['learner_id'];
            const learner_fname = learner['learner_fname'];
            const learner_lname = learner['learner_lname'];
            const learner_bday = learner['learner_bday'];
            const learner_gender = learner['learner_gender'];
            const learner_contactno = learner['learner_contactno'];
            const learner_email = learner['learner_email'];
            const business_name = learner['business_name'];
            const business_address = learner['business_address'];
            const business_owner_name = learner['business_owner_name'];
            const bplo_account_number = learner['bplo_account_number'];
            const business_category = learner['business_category'];
            const business_classification = learner['business_classification'];
            const business_description = learner['business_description'];

            $('#nameDisp').text(`${learner_fname} ${learner_lname}`)

            $('#learner_fname').val(learner_fname)
            $('#learner_lname').val(learner_lname)
            $('#learner_bday').val(learner_bday)
            $('#learner_gender').val(learner_gender)
            $('#learner_contactno').val(learner_contactno)
            $('#learner_email').val(learner_email)
            $('#business_name').val(business_name)
            $('#business_address').val(business_address)
            $('#business_owner_name').val(business_owner_name)
            $('#bplo_account_number').val(bplo_account_number)
            $('#business_category').val(business_category)
            $('#business_classification').val(business_classification)
            $('#business_description').text(business_description)
    }


    function displayCourse(course) {
         
            const course_id = course['course_id'];
            const course_name = course['course_name'];
            const course_difficulty = course['course_difficulty'];
            const course_description = course['course_description'];
            const instructor_name = course['instructor_name'];
  
            $('#courseNameDisp').text(course_name)
            $('#course_name').val(course_name)
            $('#course_difficulty').val(course_difficulty)
            $('#course_description').text(course_description)
            $('#course_instructor').val(instructor_name)

    }


    $('#enrollNewBtn').on('click', function() {
        var learner_id = $('#learnerID').val()
        var course_id = $('#courseID').val()

        var isValid = true;

        if (learner_id === '') {
            $('#learnerIDError').text('Please choose a learner.');
            isValid = false;
        } else {
            $('#learnerIDError').text('');
        }

        if (course_id === '') {
            $('#courseIDError').text('Please choose a course');
            isValid = false;
        } else {
            $('#courseIDError').text('');
        }


        if(isValid) {
                var learnerCourseInfo = {
                    learner_id: learner_id,
                    course_id: course_id,
                }
    
            var url = baseUrl + '/enrollNew';
    
            $.ajax ({
                type: "POST",
                url: url,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                data: learnerCourseInfo,
                success: function (response){
                    console.log(response)
                    if (response.redirect_url) {
                    window.location.href = response.redirect_url;
        }
                    // window.location.reload();
                },
                error: function(error) {
                    console.log(error);
                }
            })
            }
    })

})

</script>