@include('partials.header')

<section class="flex flex-row w-screen text-sm main-container bg-mainwhitebg md:text-base">
@include('partials.sidebar')




<section class="w-screen px-2 pt-[40px] mx-2 mt-2  overscroll-auto md:overflow-auto">
    <div class="flex justify-between px-10">
        <h1 class="text-6xl font-bold text-darthmouthgreen">Course Management</h1>
        <div class="">
            <p class="text-xl font-semibold text-darthmouthgreen">{{$admin->admin_codename}}</p>
        </div>
    </div>

    <div class="w-full px-3 pb-4 mt-10 rounded-lg shadow-lg b">
        <div class="mb-5">
            <a href="/admin/courses" class="">
                <i class="text-2xl md:text-3xl fa-solid fa-arrow-left" style="color: #000000;"></i>
            </a>
        </div>
        <div class="flex justify-between">
            <div class="flex flex-col items-center justify-start w-3/12 h-full py-10 mx-5 bg-white rounded-lg shadow-lg" id="upper_left_container">
                <div class="relative flex flex-col items-center justify-start"  style="margin:0 auto; padding: auto;">
                    <img class="z-0 w-40 h-40 rounded-full" src="{{ asset('storage/images/course_img.png')}}" alt="Profile Picture">
                </div>

                <div class="mt-10" id="name_area">
                    <h1 class="text-2xl font-semibold text-center" id="codenameDisp">NAME</h1>
                </div>

                <div class="mt-5 text-center" id="account_status_area">
                    <h1 class="text-xl" id="roleDisp">COURSE</h1>
                </div>

                
                <div class="flex justify-center w-full px-5 mt-5">
                    <button type="button" class="px-5 py-3 mx-2 text-lg text-white bg-darthmouthgreen hover:border-2 hover:bg-white hover:border-darthmouthgreen hover:text-darthmouthgreen rounded-xl" id="submit_new_admin">Save new Course</button>
                </div>

            </div> 
            <div class="w-9/12 h-full" id="upper_right_container">
                <div class="w-full px-5 py-10 bg-white shadow-lg rounded-xl" id="upper_right_1">
                    <h1 class="text-4xl font-semibold text-darthmouthgreen">Course Details</h1>

                    <hr class="my-6 border-t-2 border-gray-300">

                    <div class="w-full mt-5" id="userInfo">

                        <div class="w-full mt-3" id="courseNameArea">
                            <label for="course_name">Course Name</label><br>
                            <input class="w-full h-12 px-5 py-1 border-2 rounded-lg border-darthmouthgreen" type="text" name="course_name" id="course_name" value="">
                            <span id="courseNameError" class="text-red-500"></span>
                        </div>

                        <div class="w-full mt-3" id="courseDifficultyArea">
                            <label for="course_difficulty">Course Difficulty</label><br>
                            <select class="w-full h-12 px-10 py-1 border-2 rounded-lg border-darthmouthgreen" name="course_difficulty" id="course_difficulty">
                                <option value="" selected disabled>-- select an option --</option>
                                <option value="Beginner">Beginner</option>
                                <option value="Intermmediate">Intermmediate</option>
                                <option value="Advanced">Advanced</option>
                            </select>
                            <span id="courseDifficultyError" class="text-red-500"></span>
                        </div>

                        <div class="mt-3" id="course_descriptionArea">
                            <label for="course_description">Course Description</label><br>
                            <textarea name="course_description" class="w-full px-5 py-1 border-2 rounded-lg h-36 border-darthmouthgreen" id="course_description"></textarea>
                            <span id="courseDescriptionError" class="text-red-500"></span>
                        </div>
                    </div>
    
                </div>

                    <div class="w-full px-5 py-10 mt-5 bg-white shadow-lg rounded-xl" id="upper_right_3">
                        <h1 class="text-4xl font-semibold text-darthmouthgreen">Instructor Details</h1>

                        <hr class="my-6 border-t-2 border-gray-300">

                        <div class="w-full mt-3" id="courseInstructorArea">
                            <label for="course_instructor">Course Instructor</label><br>
                            <select class="w-full h-12 px-10 py-1 border-2 rounded-lg border-darthmouthgreen" name="course_instructor" id="course_instructor">
                                <option value="" selected disabled>-- select an option --</option>
                                @foreach ($instructors as $instructor)
                                <option value="{{$instructor->id}}">{{$instructor->name}}</option>
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



<div id="loaderModal" class="fixed top-0 left-0 z-50 flex items-center justify-center hidden w-full h-full bg-gray-200 bg-opacity-75 ">
    <div class="flex flex-col items-center justify-center w-full h-screen p-4 bg-white rounded-lg shadow-lg modal-content md:h-1/3 lg:w-1/3">
        <span class="loading loading-spinner text-primary loading-lg"></span> 
            
        <p class="mt-5 text-xl text-darthmouthgreen">loading</p>  
    </div>
</div>


<script>
    $(document).ready(function() {

        var baseUrl = window.location.href
        var csrfToken = $('meta[name="csrf-token"]').attr('content'); // Get the CSRF token from the meta tag
    



        $('#course_name').on('input', function() {
            var val = $(this).val();
            
            $('#codenameDisp').text(val)
        })

        $('#submit_new_admin').on('click', function() {
            var course_name = $('#course_name').val();
            var course_difficulty = $('#course_difficulty').val();
            var course_description = $('#course_description').val();
            var course_instructor = $('#course_instructor').val();


            var isValid = true;

            if (course_name === '') {
                $('#courseNameError').text('Please enter a course name.');
                isValid = false;
            } else {
                $('#courseNameError').text('');
            }

            if (course_difficulty === '') {
                $('#courseDifficultyError').text('Please choose a difficulty');
                isValid = false;
            } else {
                $('#courseDifficultyError').text('');
            }

            if (course_description === '') {
                $('#courseDescriptionError').text('Please enter your course description.');
                isValid = false;
            } else {
                $('#courseDescriptionError').text('');
            }

            if (course_instructor === '') {
                $('#courseInstructorError').text('Please choose an Instructor');
                isValid = false;
            } else {
                $('#courseInstructorError').text('');
            }


            if(isValid) {
                var adminInfo = {
                    course_name: course_name,
                    course_difficulty: course_difficulty,
                    course_description: course_description,
                    instructor_id: course_instructor,
                }
    
            var url = baseUrl;
    
            $('#loaderModal').removeClass('hidden');
            $.ajax ({
                type: "POST",
                url: url,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                data: adminInfo,
                success: function (response){
                    console.log(response)
                    if (response.redirect_url) {
                        
            $('#loaderModal').addClass('hidden');
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
@include('partials.footer')
