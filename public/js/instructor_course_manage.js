$(document).ready(function() {
    // course overview

    $('#showCourseManageModal').on('click', function(e) {
        e.preventDefault();
        $('#courseManageModal').removeClass('hidden');
    
        var courseID = $(this).data("course-id");
    
        $.ajax({
            type: 'GET',
            url: '/instructor/course/manage/' + courseID,
            dataType: 'json',
            contentType: 'application/json', // Set content type to JSON
            processData: false,
            success: function(data) {
                displayCourseAndEnrolleeData(data);
            },
            error: function(error) {
                console.log(error);
            }
        });
    });
    

    function displayCourseAndEnrolleeData(data) {
        var enrollees = data['enrollees'];
        var isEmptyEnrollees = $.isEmptyObject(enrollees);
        // console.log(isEmptyEnrollees);
        // console.log(enrollees);
        var courseDataDisp = ``;
        var enrolleesTableDisp = ``;
        var courseSummaryDisp = ``;

        var course_id = data['course']['course_id'];
        var course_name = data['course']['course_name'];
        var course_code = data['course']['course_code'];
        var course_description = data['course']['course_description'];
        var course_difficulty = data['course']['course_difficulty'];
        var course_status = data['course']['course_status'];
        var created_at = data['course']['created_at'];
        var instructor_fname = data['course']['instructor_fname'];
        var instructor_lname = data['course']['instructor_lname'];
        var updated_at = data['course']['updated_at'];


        if(isEmptyEnrollees) {
            enrolleesTableDisp += `<tr><td colspan="4">No Enrolled Students</td></tr>`;
        } else {
            for(var i = 0; i < enrollees.length; i++) {
                // console.log(enrollees[i]);
                var learner_course_id = enrollees[i]['learner_course_id'];
                var learner_fname = enrollees[i]['learner_fname'];
                var learner_lname = enrollees[i]['learner_lname'];
                var learner_id = enrollees[i]['learner_id'];
                var learner_email = enrollees[i]['learner_email'];
                var status = enrollees[i]['status'];
                var created_at = enrollees[i]['created_at'];
                
                enrolleesTableDisp += `
    
                    <tr>
                        <td>`+ learner_course_id +`</td>
                        <td>`+ learner_id +`</td>
                        <td>
                            <h1>`+ learner_fname +` `+ learner_lname +` </h1>
                            <p>`+ learner_email +`</p>
                        </td>
                        <td>`+ created_at +`</td>
                        <td>`+ status +`</td>
                        <td>
              
                        </td>
                    </tr>
                `
            }
        }

        $('#enrollees_tableDisp').empty();
        $('#enrollees_tableDisp').append(enrolleesTableDisp);

        

        courseDataDisp += `
        <h1 class="text-2xl font-semibold border-black border-b-2">Course Information</h1>
        <form id="updateCourse" name="updateCourse" data-course-id="`+ course_id +`">
                            <div id="info" class="mt-5 overflow-y-auto">
                                <div class="flex">
                                    <div class="w-2/5">
                                        <div class="flex justify-normal my-2">
                                            <label for="" class="text-lg w-2/6">Course ID:</label>
                                            <input type="text" value="`+ course_id +`" class="text-lg w-4/6" disabled>
                                        </div>
                                        <div class="flex justify-normal my-2 ">
                                            <label for="course_name" class="text-lg w-2/6">Course Name:</label>
                                            <input type="text" id="course_name" name="course_name" value="`+ course_name +`" class="text-lg  w-4/6" disabled>
                                        </div>
                                        <div class="flex justify-normal my-2 ">
                                            <label for="" class="text-lg w-2/6">Course Code:</label>
                                            <input type="text" value="`+ course_code +`" class="text-lg  w-4/6" disabled>
                                        </div>
                                    </div>
                                    
                                    <div class="w-2/5 mx-5">
                                        <div id="course_status"  class="flex justify-normal my-1 ">
                                            <h1 class="text-lg w-2/6">Course Status:</h1>
                                       
                                            
                                        </div>
                                        <div class="flex justify-normal my-1 py-1">
                                            <label for="" class="text-lg w-2/5">Course Difficulty:</label>
                                            <select name="course_difficulty" id="course_difficulty" class="w-2/5" disabled>
                                                <option value="">--select an option--</option>
                                                <option value="Beginner">Beginner</option>
                                                <option value="Intermediate">Intermediate</option>
                                                <option value="Advanced">Advanced</option>
                                            </select>
                                        </div>
                                    </div>
    
                                    <div class="mt-5">
                                        <h1>Created `+ created_at +` by `+ instructor_fname +` `+ instructor_lname +`</h1>
                                        <h1>Last Modified `+ updated_at + `</h1>
                                    </div>
                                </div>
    
                                <div class="mt-1">
                                    <h1>Course Description</h1>
                            
                                    <textarea name="course_description" id="course_description" class="max-h-24 h-24 max-w-full w-full" disabled>`+ course_description +`</textarea>
                                </div>
                                
                               
    
                                <div class="flex justify-end mr-16">
                                    <button type="button" id="editCourse" class="w-44 py-5 rounded-2xl text-lg font-medium bg-green-600 text-white hover:bg-green-800 focus">
                                        Edit Course Info
                                    </button>
    
                                    <button type="button" id="cancelEditCourse" class="hidden w-44 py-5 rounded-2xl text-lg font-medium bg-red-600 text-white hover:bg-red-800 focus">
                                        Cancel
                                    </button>
    
                                    <button type="button" id="saveEditCourse" class="hidden w-44 py-5 rounded-2xl text-lg font-medium bg-green-600 text-white hover:bg-green-800 focus">
                                        Save Changes
                                    </button>
                                </div>

                                <div id="updateCourseModal" class="hidden fixed top-0 left-0 w-screen h-screen flex justify-center items-center bg-black bg-opacity-50">
                                 
                                        <div class="bg-white p-5 rounded-lg text-center">
                                            <p>Are you sure you want to edit this course?</p>
                                            <button type="submit" id="confirmUpdate" class="px-4 py-2 bg-green-600 text-white rounded-md m-2">Confirm</button>
                                            <button type="button" id="cancelUpdate" class="px-4 py-2 bg-gray-400 text-gray-700 rounded-md m-2">Cancel</button>
                                        </div>
                                </div>
                                
                            </div>
                        </form>
        `;

        
        //display course info
        $('#course_info').empty();
        $('#course_info').append(courseDataDisp);

        courseSummaryDisp += `
        <h1 class="text-2xl font-semibold border-black border-b-2">Course Summary</h1>
        <div class="flex justify-normal mt-3">
            <div class="w-2/5">
                <h1>Course Name: `+ course_name +`</h1>
                <h1>Course ID: `+ course_id +`</h1>
                <h1>Course Code: `+ course_code +`</h1>
            </div>
            <div class="w-2/5">
                <h1>Instructor: `+ instructor_fname +` `+ instructor_lname +`</h1>
                <h1>Course Difficulty: `+ course_difficulty +`</h1>
                <div id="course_status2" class="flex">
                    <h1>Course Status: </h1>
                </div>
            </div>
            <div class="w-2/5">
                <h1>Created at: `+ created_at +`</h1>
                <h1>Updated at: `+ updated_at +`</h1>
            </div>
        </div>
                            
        <div class="mt-3">
            <h1>Course Description</h1>
            <p class=" h-24 overflow-y-auto">`+ course_description +`</p>
        </div>

        <div class="justify-end flex">
            <button type="button" id="showDeleteModal" class="px-5 py-5 text-xl rounded-xl bg-red-600 hover:bg-red-700">Delete Course</button>
        </div>
                        
        <div id="deleteCourseModal" class="hidden fixed top-0 left-0 w-screen h-screen flex justify-center items-center bg-black bg-opacity-50">
            <form id="deleteCourse" action="GET">
                @csrf
                <div class="bg-white p-5 rounded-lg text-center">
                    <p>Are you sure you want to delete this course?</p>
                    <button type="submit" id="confirmDelete" class="px-4 py-2 bg-red-600 text-white rounded-md m-2">Confirm</button>
                    <button type="button" id="cancelDelete" class="px-4 py-2 bg-gray-400 text-gray-700 rounded-md m-2">Cancel</button>
                </div>
            </form>
            
        </div>
        `;
        
        $('#course_summary').empty();
        $('#course_summary').prepend(courseSummaryDisp);
        // check status and display
        var statusDisp = ``;
        if(course_status == 'Approved') {
            statusDisp += `
            <p class="px-5 rounded-full bg-green-600">Approved</p>
            `;
        } else if(course_status == 'Pending') {
            statusDisp += `
            <p class="px-5 rounded-full bg-yellow-400">Pending</p>
            `;
        } else {
            statusDisp += `
            <p class="px-5 rounded-full bg-red-600">Rejected</p>
            `
        }

        $('#course_status').append(statusDisp);
        $('#course_status2').append(statusDisp);

        // check difficulty and display
        if(course_difficulty == 'Beginner') {
            $('#course_difficulty').val('Beginner');
        } else if(course_difficulty == 'Intermediate') {
            $('#course_difficulty').val('Intermediate');
         
        } else if(course_difficulty == 'Advanced') {
            $('#course_difficulty').val('Advanced');

        } else {
            $('#course_difficulty').val('');

        }

        // interactive buttons in course info
        $('#edit_info_btn').on('click', function() {
            $('#course_info').removeClass('hidden');
            $('#enrolled_learners').addClass('hidden');
            $('#course_summary').addClass('hidden');
        });
        $('#enrolled_learners_btn').on('click', function() {
            $('#course_info').addClass('hidden');
            $('#enrolled_learners').removeClass('hidden');
            $('#course_summary').addClass('hidden');
        });
        $('#course_summary_btn').on('click', function() {
            $('#course_info').addClass('hidden');
            $('#enrolled_learners').addClass('hidden');
            $('#course_summary').removeClass('hidden');
        })
    
        $('#editCourse').on('click', function(e) {
            e.preventDefault();
    
            $('#course_name').prop('disabled', false);
            $('#course_name').focus();
            $('#course_description').prop('disabled', false);
            $('#course_difficulty').prop('disabled', false);
    
            $('#cancelEditCourse').removeClass('hidden');
            $('#saveEditCourse').removeClass('hidden');
            $('#editCourse').addClass('hidden');
        });
    
        $('#cancelEditCourse').on('click', function(e) {
            e.preventDefault();
    
            $('#course_name').prop('disabled', true);
            $('#course_description').prop('disabled', true);
            $('#course_difficulty').prop('disabled', true);
    
            $('#cancelEditCourse').addClass('hidden');
            $('#saveEditCourse').addClass('hidden');
            $('#editCourse').removeClass('hidden');
        })
    
        $('#saveEditCourse').on('click', function(e) {
            e.preventDefault();
            $('#updateCourseModal').removeClass('hidden');
        });
    
        $('#cancelUpdate').on('click', function(e) {
            e.preventDefault();
    
            $('#updateCourseModal').addClass('hidden');
        });
    
        $('#updateCourse').submit(function(e) {
    
            e.preventDefault();
            const courseName = $('#course_name').val();
            const courseDescription = $('#course_description').val();
            const courseDifficulty = $('#course_difficulty').val();
    
            if(courseName === '' ||
                courseDescription === '' ||
                courseDifficulty === '') {
                    alert("Please fill all fields");
    
                    if(courseName === '') {
                        var errorMsg = `
                        <span class="text-red-600">*Please enter a Course Name*</span>
                        `;
    
                        $('#course_name').before(errorMsg);
                    }
                    if (courseDescription === '') {
                        var errorMsg = `
                        <span class="text-red-600">*Please enter a Course Description*</span>
                        `;
    
                        $('#course_description').before(errorMsg);
                    }
                    if (courseDifficulty === null || courseDifficulty === '') {
                        var errorMsg = `
                        <span class="text-red-600">*Please select a Course Difficulty*</span>
                        `;
    
                        $('#course_difficulty').before(errorMsg);
                    } 
            } else {
                var formData = new FormData(this);
    
                var courseID = $(this).data("course-id");
                var csrfToken = $('meta[name="csrf-token"]').attr('content');
    
                $.ajax({
                    type: 'POST',
                    url: '/instructor/course/manage/' + courseID,
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if(response && response.redirect_url) {
                            window.location.href= response.redirect_url
                        } else {
                            
                        }
                    }
                });
            }
        })


        // enrollees form
        $("#enrolleeForm").attr("data-course-id", course_id);
        $("#filterDate").attr("data-course-id", course_id);
        $("#filterStatus").attr("data-course-id", course_id);
        $("#searchBy").attr("data-course-id", course_id);
        $("#searchVal").attr("data-course-id", course_id);

        
        $("#deleteCourse").attr("data-course-id", course_id);

        $("#showDeleteModal").click(function () {
            $("#deleteCourseModal").removeClass("hidden");
        });
    
        $("#cancelDelete").click(function () {
            $("#deleteCourseModal").addClass("hidden");
        });
    
    
    
    
        $("#deleteCourse").submit(function (e) {
            e.preventDefault();
            var courseID = $(this).data("course-id");
            var csrfToken = $('meta[name="csrf-token"]').attr('content'); // Get the CSRF token from the meta tag
    
            $.ajax({
                type: 'POST',
                url: '/instructor/course/delete/' + courseID,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function (response) {
                    if (response && response.redirect_url) {
                        window.location.href = response.redirect_url;
                    } else {
                    
                    }
                },
                error: function (xhr, status, error) {
    
                    console.log(xhr.responseText);
                }
            });
        });
     
    }

    $('#filterStatus').on('change', function(e) {
        e.preventDefault();
    
        var courseID = $(this).data("course-id");

        const filterStatus = $('#filterStatus').val();
        const filterDate = $('#filterDate').val();
        const searchBy = $('#searchBy').val();
        const searchVal = $('#searchVal').val();

        var url = '/instructor/course/manage/' + courseID + '?filterStatus=' + filterStatus;

        if (filterDate !== null) {
            url += '&filterDate=' + filterDate;
        }
        if (searchBy !== null || searchVal !== null) {
            url += '&searchBy=' + searchBy + "&searchValue=" + searchVal;
        }
    
            $.ajax({
                type: 'GET',
                url: url,
                success: function(response) {
                    // console.log(response);
    
                    $('#course_info').addClass('hidden');
                    $('#enrolled_learners').removeClass('hidden');
                    $('#course_summary').addClass('hidden');

                    displayCourseAndEnrolleeData(response);
                },
                error: function(error) {
                    console.log(error);
                }
            });
    });
    

    $('#filterDate').on('change', function(e) {
        e.preventDefault();

        var courseID = $(this).data("course-id");

        const filterStatus = $('#filterStatus').val();
        const filterDate = $('#filterDate').val();
        const searchBy = $('#searchBy').val();
        const searchVal = $('#searchVal').val();

        var url = '/instructor/course/manage/' + courseID + '?filterDate=' + filterDate;

        if (filterStatus !== null) {
            url += '&filterStatus=' + filterStatus;
        }
        if (searchBy !== null || searchVal !== null) {
            url += '&searchBy=' + searchBy + "&searchValue=" + searchVal;
        }

        $.ajax({
            type: 'GET',
            url: url,
            success: function(response) {
                // console.log(response);

                $('#course_info').addClass('hidden');
                $('#enrolled_learners').removeClass('hidden');
                $('#course_summary').addClass('hidden');

                displayCourseAndEnrolleeData(response);
            },
            error: function(error) {
                console.log(error);
            }
        });
    })

    $('#searchVal').on('change', function(e) {
        e.preventDefault();

        var courseID = $(this).data("course-id");
        const searchBy = $('#searchBy').val();
        const searchVal = $('#searchVal').val();
        const filterStatus = $('#filterStatus').val();
        const filterDate = $('#filterDate').val();

        if(searchBy !== null) {
            var url = '/instructor/course/manage/' + courseID + '?searchBy=' + searchBy + '&searchVal=' + searchVal;

            if (filterStatus !== null) {
                url += '&filterStatus=' + filterStatus;
            }
            if (filterDate !== null) {
                url += '&filterDate=' + filterDate;
            }

        
            $.ajax({
                type: 'GET',
                url: url,
                success: function(response) {
                    $('#course_info').addClass('hidden');
                    $('#enrolled_learners').removeClass('hidden');
                    $('#course_summary').addClass('hidden');
    
                    displayCourseAndEnrolleeData(response);
                },
                error: function(error) {
                console.log(error);
                }
            })
        }
    })






    
})