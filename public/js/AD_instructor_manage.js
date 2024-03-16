$(document).ready(function() {
    $('#instructor_edit_data').on('click', function(e) {
        e.preventDefault();

        $('#button').removeClass('hidden');
        $('#instructor_update_data').removeClass('hidden');
        $('#instructor_delete_data').removeClass('hidden');
        $('#instructor_edit_data').addClass('hidden');
        $('#cancel').removeClass('hidden');
        $('#return').addClass('hidden');


            $('#instructor_fname').prop("disabled", false).focus();
            $('#instructor_lname').prop("disabled", false);
            $('#instructor_bday').prop("disabled", false);
            $('#instructor_gender').prop("disabled", false);
            // $('#instructor_email').prop("disabled", false);           
            $('#instructor_contactno').prop("disabled", false);
                    
            $('#instructor_credentials').prop("disabled", false);

            $('#instructor_username').prop("disabled", false);
            $('#instructor_username').prop("readonly", true);
            $('#instructor_password').prop("disabled", false);
            $('#instructor_password').prop("readonly", true);
            $('#instructor_password_confirmation').prop("disabled", false);
            $('#instructor_password_confirmation').prop("readonly", true);
            $('#instructor_security_code').prop("disabled", false);
            $('#instructor_security_code').prop("readonly", true);
    })

    $('#cancel').on('click', function(e) {
        e.preventDefault();

        $('#button').addClass('hidden');
        $('#instructor_update_data').addClass('hidden');
        $('#instructor_delete_data').addClass('hidden');
        $('#instructor_edit_data').removeClass('hidden');
        $('#cancel').addClass('hidden');
        $('#return').removeClass('hidden');

        $('#instructor_fname').prop("disabled", true).focus();
            $('#instructor_lname').prop("disabled", true);
            $('#instructor_bday').prop("disabled", true);
            $('#instructor_gender').prop("disabled", true);
            // $('#instructor_email').prop("disabled", false);           
            $('#instructor_contactno').prop("disabled", true);
                    
            $('#instructor_credentials').prop("disabled", true);

            $('#instructor_username').prop("disabled", true);
            $('#instructor_username').prop("readonly", true);
            $('#instructor_password').prop("disabled", true);
            $('#instructor_password').prop("readonly", true);
            $('#instructor_password_confirmation').prop("disabled", true);
            $('#instructor_password_confirmation').prop("readonly", true);
            $('#instructor_security_code').prop("disabled", true);
            $('#instructor_security_code').prop("readonly", true);

    })


    
$("#instructor_delete_data").click(function () {
        $("#deleteInstructorModal").removeClass("hidden");
    });

    $("#instructor_cancelDelete").click(function () {
        $("#deleteInstructorModal").addClass("hidden");
    });
 $("#instructor_deleteCourse").submit(function (e) {
    e.preventDefault();
    var instructorID = $(this).data("instructor-id");
    var csrfToken = $('meta[name="csrf-token"]').attr('content'); 

    $.ajax({
        type: 'POST',
        url: '/admin/delete_instructor/' + instructorID,
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

    $("#instructor_update_data").click(function () {
        $("#updateInstructorModal").removeClass("hidden");
    });

    $("#instructor_cancelUpdate").click(function () {
        $("#updateInstructorModal").addClass("hidden");
    });

    // $('#instructor_updateData_form').submit(function(e) {
    //     e.preventDefault();
    //     $("#updateInstructorModal").addClass("hidden");


    //     const instructorFname = $('#instructor_fname').val();
    //     const instructorLname = $('#instructor_lname').val();
    //     const instructorBday = $('#instructor_bday').val();
    //     const instructorGender = $('#instructor_gender').val();
    //     const instructorContactNo = $('#instructor_contactno').val();

    //     if(instructorFname == ''|| instructorLname == '' || instructorBday == '' || instructorGender == '' || instructorContactNo == '') {
    //         $('.error-msg').remove();
    //         alert("Please fill all fields");

    //             if(instructorFname === '') {
    //                 var errorMsg = `
    //                 <span class="text-red-600 error-msg">*Please enter a Course Name*</span>
    //                 `;

    //                 $('#instructor_fname').before(errorMsg);
    //             }
    //             if (instructorLname === '') {
    //                 var errorMsg = `
    //                 <span class="text-red-600 error-msg">*Please enter a Course Description*</span>
    //                 `;

    //                 $('#instructor_lname').before(errorMsg);
    //             }
    //             if (instructorGender === null || instructorGender === '') {
    //                 var errorMsg = `
    //                 <span class="text-red-600 error-msg">*Please select a Course Difficulty*</span>
    //                 `;

    //                 $('#instructor_gender').before(errorMsg);
    //             }

    //             if (instructorBday === null || instructorBday === '') {
    //                 var errorMsg = `
    //                 <span class="text-red-600 error-msg">*Please select an Instructor*</span>
    //                 `;

    //                 $('#instructor_bday').before(errorMsg);
    //             }
    //     } else {
    //         // console.log('test');
    //         // var formData = new FormData(this);

    //         // var courseID = $(this).data("course-id");

    //         // $.ajax({
    //         //     type: 'POST',
    //         //     url: '/admin/view_course/' + courseID,
    //         //     data: formData,
    //         //     contentType: false,
    //         //     processData: false,
    //         //     success: function(response) {
    //         //         if(response && response.redirect_url) {
    //         //             window.location.href= response.redirect_url
    //         //         } else {
                        
    //         //         }
    //         //     }
    //         // });
    //     }
    // })

})