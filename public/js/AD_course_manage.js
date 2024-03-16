$(document).ready(function() {
    $('#edit_data').on('click', function(e) {
        e.preventDefault();

        $('#button').removeClass('hidden');
        $('#update_data').removeClass('hidden');
        $('#delete_data').removeClass('hidden');
        $('#edit_data').addClass('hidden');
        $('#cancel').removeClass('hidden');
        $('#return').addClass('hidden');

        $('#course_name').prop('disabled', false);
        $('#course_name').focus();
        $('#course_difficulty').prop('disabled', false);
        $('#course_description').prop('disabled', false);
        $('#instructor_id').prop('disabled', false);
    })

    $('#cancel').on('click', function(e) {
        
        $('#button').addClass('hidden');
        $('#update_data').addClass('hidden');
        $('#delete_data').addClass('hidden');
        $('#edit_data').removeClass('hidden');
        $('#cancel').addClass('hidden');
        $('#return').removeClass('hidden');

        $('#course_name').prop('disabled', false);
        $('#course_name').focus();
        $('#course_difficulty').prop('disabled', false);
        $('#course_description').prop('disabled', false);
        $('#instructor_id').prop('disabled', false);
    })


    
$("#delete_data").click(function () {
        $("#deleteCourseModal").removeClass("hidden");
    });

    $("#admin_cancelDelete").click(function () {
        $("#deleteCourseModal").addClass("hidden");
    });
 $("#admin_deleteCourse").submit(function (e) {
    e.preventDefault();
    var courseID = $(this).data("course-id");
    var csrfToken = $('meta[name="csrf-token"]').attr('content'); // Get the CSRF token from the meta tag

    $.ajax({
        type: 'POST',
        url: '/admin/delete_course/' + courseID,
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

    $("#update_data").click(function () {
        $("#updateCourseModal").removeClass("hidden");
    });

    $("#cancelUpdate").click(function () {
        $("#updateCourseModal").addClass("hidden");
    });

    $('#course_updateData_form').submit(function(e) {
        e.preventDefault();
        $("#updateCourseModal").addClass("hidden");

        const courseName = $('#course_name').val();
        const courseDifficulty = $('#course_difficulty').val();
        const courseDescription = $('#course_description').val();
        const instructorId = $('#instructor_id').val();

        if(courseName == '' || courseDescription == '' || courseDifficulty == '' || instructorId == '') {
            $('.error-msg').remove();
            alert("Please fill all fields");

                if(courseName === '') {
                    var errorMsg = `
                    <span class="text-red-600 error-msg">*Please enter a Course Name*</span>
                    `;

                    $('#course_name').before(errorMsg);
                }
                if (courseDescription === '') {
                    var errorMsg = `
                    <span class="text-red-600 error-msg">*Please enter a Course Description*</span>
                    `;

                    $('#course_description').before(errorMsg);
                }
                if (courseDifficulty === null || courseDifficulty === '') {
                    var errorMsg = `
                    <span class="text-red-600 error-msg">*Please select a Course Difficulty*</span>
                    `;

                    $('#course_difficulty').before(errorMsg);
                }

                if (instructorId === null || instructorId === '') {
                    var errorMsg = `
                    <span class="text-red-600 error-msg">*Please select an Instructor*</span>
                    `;

                    $('#instructor_id').before(errorMsg);
                }
        } else {
            console.log('test');
            var formData = new FormData(this);

            var courseID = $(this).data("course-id");

            $.ajax({
                type: 'POST',
                url: '/admin/view_course/' + courseID,
                data: formData,
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

})