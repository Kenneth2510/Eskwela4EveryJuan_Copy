$(document).ready(function() {
    $('#enrollBtn').on('click', function(e) {
        e.preventDefault();

        $('#enrollCourseModal').removeClass('hidden');
    });

    $('#cancelEnroll').on('click', function(e) {
        e.preventDefault();

        $('#enrollCourseModal').addClass('hidden');
    });

    $("#enrollCourse").submit(function (e) {
        e.preventDefault();
        var courseID = $(this).data("course-id");
        var csrfToken = $('meta[name="csrf-token"]').attr('content'); // Get the CSRF token from the meta tag
    
        $.ajax({
            type: 'POST',
            url: '/learner/course/enroll/' + courseID,
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

    $('#unenrollBtn').on('click', function(e) {
        e.preventDefault();

        $('#unenrollCourseModal').removeClass('hidden');
    });

    $('#cancelUnenroll').on('click', function(e) {
        $('#unenrollCourseModal').addClass('hidden');
    });

    $('#unenrollCourse').submit(function(e) {
        
        e.preventDefault();

        var lessonCourseID = $(this).data("learner_course-id");
        var csrfToken = $('meta[name="csrf-token"]').attr('content'); // Get the CSRF token from the meta tag
        // console.log('/learner/course/unenroll/' + lessonCourseID);
        $.ajax({
            type: 'POST',
            url: '/learner/course/unEnroll/' + lessonCourseID,
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

        })
    })


    $('#unenrollBtn2').on('click', function(e) {
        e.preventDefault();

        $('#unenrollCourseModal2').removeClass('hidden');
    });

    $('#cancelUnenroll2').on('click', function(e) {
        $('#unenrollCourseModal2').addClass('hidden');
    });

    $('#unenrollCourse2').submit(function(e) {
        
        e.preventDefault();

        var lessonCourseID = $(this).data("learner_course-id");
        var csrfToken = $('meta[name="csrf-token"]').attr('content'); // Get the CSRF token from the meta tag
        // console.log('/learner/course/unenroll/' + lessonCourseID);
        $.ajax({
            type: 'POST',
            url: '/learner/course/unEnroll/' + lessonCourseID,
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

        })
    })

        $('#display_info_btn').on('click', function() {
            $('#learner_course_info').removeClass('hidden');
            $('#learner_enrolled_learners').addClass('hidden');
            $('#enrollment_summary').addClass('hidden');
        });
        $('#enrolled_learners_btn').on('click', function() {
            $('#learner_course_info').addClass('hidden');
            $('#learner_enrolled_learners').removeClass('hidden');
            $('#enrollment_summary').addClass('hidden');
        });
        $('#enrollment_summary_btn').on('click', function() {
            $('#learner_course_info').addClass('hidden');
            $('#learner_enrolled_learners').addClass('hidden');
            $('#enrollment_summary').removeClass('hidden');
        })
    
   
        $('.showCourseManageModal').on('click', function(e) {
            e.preventDefault();

            $('#L_courseManageModal').removeClass('hidden');
    
            var courseID = $(this).data("course-id");

            $.ajax({
                type: 'GET',
                url: '/learner/course/manage/' + courseID,
                dataType: 'json',
                contentType: 'application/json', // Set content type to JSON
                processData: false,
                success: function(data) {
                    console.log(data);
                    displayCourseAndEnrolleeData(data);
                },
                error: function(error) {
                    console.log(error);
                }
            });
        })


        function displayCourseAndEnrolleeData(data) {
            console.log(data);

            var courseData = data['course'];
            var enrolleesData = data['enrollees'];
            var isEmptyEnrollees = $.isEmptyObject(enrolleesData);
            var isEnrolled = data['isEnrolled'];

            var l_courseDataDisp = ``;
            var l_enrolleesTableDisp = ``;
            var l_courseSummaryDisp = ``;

            var course_id = courseData['course_id'];
            var course_name = courseData['course_name'];
            var course_code = courseData['course_code'];
            var course_description = courseData['course_description'];
            var course_difficulty = courseData['course_difficulty'];
            var course_status = courseData['course_status'];
            var created_at = courseData['created_at'];
            var instructor_fname = courseData['instructor_fname'];
            var instructor_lname = courseData['instructor_lname'];
            var updated_at = courseData['updated_at'];

            // for displaying enrolled learners
            if(isEmptyEnrollees) {
                l_enrolleesTableDisp += `
                <tr><td colspan="4">No Enrolled Students</td></tr>
                `;
            } else {
                for (let i = 0; i < enrolleesData.length; i++) {
                    var learner_course_id = enrolleesData[i]['learner_course_id'];
                    var learner_fname = enrolleesData[i]['learner_fname'];
                    var learner_lname = enrolleesData[i]['learner_lname'];
                    var learner_id = enrolleesData[i]['learner_id'];
                    var learner_email = enrolleesData[i]['learner_email'];
                    var status = enrolleesData[i]['status'];
                    var l_created_at = enrolleesData[i]['created_at'];
                
                    l_enrolleesTableDisp += `

                        <tr>
                            <td>`+ learner_course_id +`</td>
                            <td>`+ learner_id +`</td>
                            <td>
                                <h1>`+ learner_fname +` `+ learner_lname +` </h1>
                                <p>`+ learner_email +`</p>
                            </td>
                            <td>`+ l_created_at +`</td>
                            <td>`+ status +`</td>
                            <td>
                            </td>
                        </tr>
                    `;
                }
            }

            // append the l_enrolleesTable
            $('#l_enrolleeTable').empty();
            $('#l_enrolleeTable').append(l_enrolleesTableDisp);


            l_courseDataDisp += `
            <h1 class="text-2xl font-semibold border-black border-b-2">Course Information</h1>
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
                        <div id="l_course_status" class="flex justify-normal my-1 ">
                            <h1 class="text-lg w-2/6">Course Status:</h1>
                           
                        </div>

                        <div class="flex justify-normal my-1 py-1">
                            <label for="" class="text-lg w-2/5">Course Difficulty:</label>
                            <select name="course_difficulty" id="course_difficulty" class="w-2/5" disabled>
                                <option value="Beginner" ${course_difficulty === 'Beginner' ? 'selected' : ''}>Beginner</option>
                                <option value="Intermediate" ${course_difficulty === 'Intermediate' ? 'selected' : ''}>Intermediate</option>
                                <option value="Advanced" ${course_difficulty === 'Advanced' ? 'selected' : ''}>Advanced</option>
                            </select>
                        </div>

                    </div>

                    <div class="mt-5">
                        <h1>Created `+ created_at +` by `+ instructor_fname +` `+ instructor_lname +`</h1>
                        <h1>Last Modified `+ updated_at +`</h1>
                    </div>
                </div>

                <div class="mt-1">
                    <h1>Course Description</h1>
                    <textarea name="course_description" id="course_description" class="max-h-24 h-24 max-w-full w-full" disabled>`+ course_description +`</textarea>
                </div>

            </div>
        
            `;


                // check status and display
        

        l_courseSummaryDisp += `
        <h1 class="text-2xl font-semibold border-black border-b-2">Course Summary</h1>
        <div class="flex justify-normal mt-3">
                            <div class="w-2/5">
                                <h1>Course Name: `+ course_name +`</h1>
                                <h1>Course ID: `+ course_id +`</h1>
                                <h1>Course Code: `+ course_code +`</h1>
                            </div>
                            <div class="w-2/5">
                                <h1>Instructor: `+ instructor_fname +` `+ instructor_lname +`</h1>
                                <h1>Course Difficulty: `+course_difficulty+`</h1>
                                <div id="l_course_status2" class="flex">
                                    <h1>Course Status: </h1>
                                 
                                </div>
                                
                            </div>
                            <div class="w-2/5">
                            ${isEnrolled ? `
                            <h1>Enrollee ID: ${isEnrolled['learner_course_id']}</h1>
                            <h1>Enrolled on: ${isEnrolled['created_at']}</h1>
                        ` : ''}
                                <h1>Course Created at: `+ created_at +`</h1>
                                <h1>Course Updated at: ` + updated_at + `</h1>
                            </div>
                        </div>
                        
                        <div class="mt-3">
                            <h1>Course Description</h1>
                            <p class=" h-24 overflow-y-auto">`+ course_description +`</p>
                        </div>
        `;

        var statusDisp2 = ``;
        if(course_status == 'Approved') {
            statusDisp2 += `
            <p class="px-5 rounded-full bg-green-600">Approved</p>
            `;
        } else if(course_status == 'Pending') {
            statusDisp2 += `
            <p class="px-5 rounded-full bg-yellow-400">Pending</p>
            `;
        } else {
            statusDisp2 += `
            <p class="px-5 rounded-full bg-red-600">Rejected</p>
            `
        }
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

        $('#learner_course_info').empty();
        $('#enrollment_summary').empty();
        $('#learner_course_info').append(l_courseDataDisp);
        $('#enrollment_summary').append(l_courseSummaryDisp);

        $('#l_course_status').append(statusDisp2);
        $('#l_course_status2').append(statusDisp2);

        $("#enrolleeForm").attr("data-course-id", course_id);
        $("#filterDate").attr("data-course-id", course_id);
        $("#filterStatus").attr("data-course-id", course_id);
        $("#searchBy").attr("data-course-id", course_id);
        $("#searchVal").attr("data-course-id", course_id);


        }

        $('#filterStatus').on('change', function(e) {
            e.preventDefault();
        
            var courseID = $(this).data("course-id");
    
            const filterStatus = $('#filterStatus').val();
            const filterDate = $('#filterDate').val();
            const searchBy = $('#searchBy').val();
            const searchVal = $('#searchVal').val();
    
            var url = '/learner/course/manage/' + courseID + '?filterStatus=' + filterStatus;
    
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
        
                        $('#learner_course_info').addClass('hidden');
                        $('#learner_enrolled_learners').removeClass('hidden');
                        $('#enrollment_summary').addClass('hidden');
    
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
    
            var url = '/learner/course/manage/' + courseID + '?filterDate=' + filterDate;
    
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
                    console.log(response);
    
                    $('#learner_course_info').addClass('hidden');
                    $('#learner_enrolled_learners').removeClass('hidden');
                    $('#enrollment_summary').addClass('hidden');
    
    
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
                var url = '/learner/course/manage/' + courseID + '?searchBy=' + searchBy + '&searchVal=' + searchVal;
    
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
                        $('#learner_course_info').addClass('hidden');
                        $('#learner_enrolled_learners').removeClass('hidden');
                        $('#enrollment_summary').addClass('hidden');
    
        
                        displayCourseAndEnrolleeData(response);
                    },
                    error: function(error) {
                    console.log(error);
                    }
                })
            }
    
            
        })
})




