$(document).ready(function() {
    var baseUrl = window.location.href
    var csrfToken = $('meta[name="csrf-token"]').attr('content'); // Get the CSRF token from the meta tag
    

    $('#contactNumber').on('input', function() {
        var phoneNumber = $(this).val();
        // Replace any non-digit characters with empty string
        phoneNumber = phoneNumber.replace(/\D/g, '');
        // Check if the input starts with '09'
        if (phoneNumber.length >= 2 && phoneNumber.substring(0, 2) !== '09') {
            phoneNumber = '09' + phoneNumber.substring(2);
        }
        // Update the input value
        $(this).val(phoneNumber);
    });

    $('#bplo_account_number').on('input', function() {
        var phoneNumber = $(this).val();
        // Replace any non-digit characters with empty string
        phoneNumber = phoneNumber.replace(/\D/g, '');
        // Check if the input starts with '09'
        if (phoneNumber.length >= 2 && phoneNumber.substring(0, 2) !== '09') {
            phoneNumber = '09' + phoneNumber.substring(2);
        }
        // Update the input value
        $(this).val(phoneNumber);
    });


    $('#edit_user_info_btn').on('click', function() {

        $('#edit_user_info_btn').addClass('hidden')
        $('#submit_user_info_btn').removeClass('hidden')
        $('#cancel_user_info_btn').removeClass('hidden')

        $('#instructor_fname').prop('disabled', false).focus()
        $('#instructor_bday').prop('disabled', false)
        $('#instructor_lname').prop('disabled', false)
        $('#instructor_gender').prop('disabled', false)
    })

    $('#cancel_user_info_btn').on('click', function() {

        $('#edit_user_info_btn').removeClass('hidden')
        $('#submit_user_info_btn').addClass('hidden')
        $('#cancel_user_info_btn').addClass('hidden')

        $('#instructor_fname').prop('disabled', true)
        $('#instructor_bday').prop('disabled', true)
        $('#instructor_lname').prop('disabled', true)
        $('#instructor_gender').prop('disabled', true)
    })


    $('#edit_business_info_btn').on('click', function() {

        $('#edit_business_info_btn').addClass('hidden')
        $('#submit_business_info_btn').removeClass('hidden')
        $('#cancel_business_info_btn').removeClass('hidden')

        $('#business_name').prop('disabled', false).focus()
        $('#business_address').prop('disabled', false)
        $('#business_owner_name').prop('disabled', false)
        $('#business_category').prop('disabled', false)
        $('#business_classification').prop('disabled', false)
        $('#business_description').prop('disabled', false)
    })

    $('#cancel_business_info_btn').on('click', function() {

        $('#edit_business_info_btn').removeClass('hidden')
        $('#submit_business_info_btn').addClass('hidden')
        $('#cancel_business_info_btn').addClass('hidden')

        $('#business_name').prop('disabled', true)
        $('#business_adddress').prop('disabled', true)
        $('#business_owner_name').prop('disabled', true)
        $('#business_category').prop('disabled', true)
        $('#business_classification').prop('disabled', true)
        $('#business_description').prop('disabled', true)
    })

    $('#edit_login_info_btn').on('click', function() {

        $('#edit_login_info_btn').addClass('hidden')
        $('#submit_login_info_btn').removeClass('hidden')
        $('#cancel_login_info_btn').removeClass('hidden')

        
        $('#new_passwordArea').removeClass('hidden')
        $('#instructorPasswordConfirmArea').removeClass('hidden')
        $('#securityCodeArea').removeClass('hidden')
        $('#passwordCheckbox').removeClass('hidden')

    })

    $('#cancel_login_info_btn').on('click', function() {

        $('#edit_login_info_btn').removeClass('hidden')
        $('#submit_login_info_btn').addClass('hidden')
        $('#cancel_login_info_btn').addClass('hidden')

        $('#new_passwordArea').addClass('hidden')
        $('#instructorPasswordConfirmArea').addClass('hidden')
        $('#securityCodeArea').addClass('hidden')
        $('#passwordCheckbox').addClass('hidden')
    })

    $('#showNewPassword').change(function() {
        var isChecked = $(this).is(':checked');
        $('#instructorNewPassword').attr('type', isChecked ? 'text' : 'password');
    });

    $('#submit_user_info_btn').on('click', function(){

        var instructor_fname = $('#instructor_fname').val()
        var instructor_bday = $('#instructor_bday').val()
        var instructor_lname = $('#instructor_lname').val()
        var instructor_gender = $('#instructor_gender').val()

        var isValid = true;

        if (instructor_fname === '') {
            $('#firstNameError').text('Please enter a first name.');
            isValid = false;
        } else {
            $('#firstNameError').text('');
        }
    
        if (instructor_bday === '') {
            $('#bdayError').text('Please enter a birthday.');
            isValid = false;
        } else {
            $('#bdayError').text('');
        }
    
        if (instructor_lname === '') {
            $('#lastNameError').text('Please enter a last name.');
            isValid = false;
        } else {
            $('#lastNameError').text('');
        }
    
        if (instructor_gender === '') {
            $('#genderError').text('Please select a gender.');
            isValid = false;
        } else {
            $('#genderError').text('');
        }
    

        if(isValid) {
            var  userInfo = {
                instructor_fname: instructor_fname,
                instructor_bday: instructor_bday,
                instructor_lname: instructor_lname,
                instructor_gender: instructor_gender,
            }
    
            var url = baseUrl + "/update_user_info";
    
            $('#loaderModal').removeClass('hidden');
            $.ajax ({
                type: "POST",
                url: url,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                data: userInfo,
                success: function (response){
                    console.log(response)
                    // if (response && response.redirect_url) {
                    //     window.location.href = response.redirect_url;
                    // } else {
                    
                    // }
                    
        $('#loaderModal').addClass('hidden');
                    window.location.reload();
                },
                error: function(error) {
                    console.log(error);
                }
          })
    
        }
        
    })




    $('#instructorNewPassword').keyup(function() {
        var password = $(this).val();

        // Remove previous error message
        $('#newPasswordError').empty();

        // Validate password complexity
        var hasUpperCase = /[A-Z]/.test(password);
        var hasLowerCase = /[a-z]/.test(password);
        var hasNumbers = /\d/.test(password);
        var hasSpecialChars = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password);

        var isValid = true;

        if (!hasUpperCase) {
            isValid = false;
            $('#newPasswordError').append('Password must contain at least one uppercase letter.<br>');
        }
        if (!hasLowerCase) {
            isValid = false;
            $('#newPasswordError').append('Password must contain at least one lowercase letter.<br>');
        }
        if (!hasNumbers) {
            isValid = false;
            $('#newPasswordError').append('Password must contain at least one number.<br>');
        }
        if (!hasSpecialChars) {
            isValid = false;
            $('#newPasswordError').append('Password must contain at least one special character.<br>');
        }
        if (password.length < 8) {
            isValid = false;
            $('#newPasswordError').append('Password must be at least 8 characters long.<br>');
        }
    });

    $('#submit_login_info_btn').on('click', function(){
        // alert('test')
        var instructorNewPassword = $('#instructorNewPassword').val()
        var instructorNewPasswordConfirm = $('#instructorNewPasswordConfirm').val()
        var instructor_security_code = $('#instructor_security_code').val()
    
        // Trigger keyup event for instructorNewPassword to perform password complexity validation
        $('#instructorNewPassword').trigger('keyup');
    
        var isValid = true;
    
        if (instructorNewPassword === '') {
            $('#newPasswordError').text('Please enter a password.');
            isValid = false;
        } else {
            $('#newPasswordError').text('');
        }
    
        if (instructorNewPasswordConfirm === '') {
            $('#newPasswordConfirmError').text('Please enter a password confirmation.');
            isValid = false;
        } else if (instructorNewPasswordConfirm !== instructorNewPassword) {
            $('#newPasswordConfirmError').text('Your password does not match');
            isValid = false;
        } else {
            $('#newPasswordConfirmError').text('');
        }
    
        if (instructor_security_code === '') {
            $('#securityCodeError').text('Please enter a security code.');
            isValid = false;
        } else {
            $('#securityCodeError').text('');
        }
    
        if(isValid) {
            var userInfo = {
                instructorNewPassword: instructorNewPassword,
                instructorNewPasswordConfirm: instructorNewPasswordConfirm,
                instructor_security_code: instructor_security_code,
            }
    
            var url = baseUrl + "/update_login_info";
    
            $('#loaderModal').removeClass('hidden');
            $.ajax ({
                type: "POST",
                url: url,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                data: userInfo,
                success: function (response){
                    console.log(response)
                    
        $('#loaderModal').addClass('hidden');
    
                    window.location.reload();
                },
                error: function(error) {
                    console.log(error);
                }
            })
        }
    
    });
    


    
    $('#update_profile_photo_btn').on('click', function() {
        
        $('#profilePicturePopup').removeClass('hidden')

    })

    $('.cancelUpdate').on('click', function() {
        
        $('#profilePicturePopup').addClass('hidden')
    })
});