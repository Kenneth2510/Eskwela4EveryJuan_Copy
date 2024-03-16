$(document).ready(function() {
    var baseUrl = window.location.href
    var csrfToken = $('meta[name="csrf-token"]').attr('content'); // Get the CSRF token from the meta tag
    getLearnerData()

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

        $('#learner_fname').prop('disabled', false).focus()
        $('#learner_bday').prop('disabled', false)
        $('#learner_lname').prop('disabled', false)
        $('#learner_gender').prop('disabled', false)
    })

    $('#cancel_user_info_btn').on('click', function() {

        $('#edit_user_info_btn').removeClass('hidden')
        $('#submit_user_info_btn').addClass('hidden')
        $('#cancel_user_info_btn').addClass('hidden')

        $('#learner_fname').prop('disabled', true)
        $('#learner_bday').prop('disabled', true)
        $('#learner_lname').prop('disabled', true)
        $('#learner_gender').prop('disabled', true)
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
        $('#learnerPasswordConfirmArea').removeClass('hidden')
        $('#securityCodeArea').removeClass('hidden')
        $('#passwordCheckbox').removeClass('hidden')

    })

    $('#cancel_login_info_btn').on('click', function() {

        $('#edit_login_info_btn').removeClass('hidden')
        $('#submit_login_info_btn').addClass('hidden')
        $('#cancel_login_info_btn').addClass('hidden')

        $('#new_passwordArea').addClass('hidden')
        $('#learnerPasswordConfirmArea').addClass('hidden')
        $('#securityCodeArea').addClass('hidden')
        $('#passwordCheckbox').addClass('hidden')
    })

    $('#showNewPassword').change(function() {
        var isChecked = $(this).is(':checked');
        $('#learnerNewPassword').attr('type', isChecked ? 'text' : 'password');
    });

    $('#submit_user_info_btn').on('click', function(){

        var learner_fname = $('#learner_fname').val()
        var learner_bday = $('#learner_bday').val()
        var learner_lname = $('#learner_lname').val()
        var learner_gender = $('#learner_gender').val()

        var isValid = true;

        if (learner_fname === '') {
            $('#firstNameError').text('Please enter a first name.');
            isValid = false;
        } else {
            $('#firstNameError').text('');
        }
    
        if (learner_bday === '') {
            $('#bdayError').text('Please enter a birthday.');
            isValid = false;
        } else {
            $('#bdayError').text('');
        }
    
        if (learner_lname === '') {
            $('#lastNameError').text('Please enter a last name.');
            isValid = false;
        } else {
            $('#lastNameError').text('');
        }
    
        if (learner_gender === '') {
            $('#genderError').text('Please select a gender.');
            isValid = false;
        } else {
            $('#genderError').text('');
        }
    

        if(isValid) {
            var  userInfo = {
                learner_fname: learner_fname,
                learner_bday: learner_bday,
                learner_lname: learner_lname,
                learner_gender: learner_gender,
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



    $('#submit_business_info_btn').on('click', function(){

        var business_name = $('#business_name').val()
        var business_address = $('#business_address').val()
        var business_owner_name = $('#business_owner_name').val()
        // var bplo_account_number = $('#bplo_account_number').val()
        var business_category = $('#business_category').val()
        var business_classification = $('#business_classification').val()
        var business_description = $('#business_description').val()

        var isValid = true;

        if (business_name === '') {
            $('#businessNameError').text('Please enter a business name.');
            isValid = false;
        } else {
            $('#businessNameError').text('');
        }
    
        if (business_address === '') {
            $('#businessAddressError').text('Please enter a business address.');
            isValid = false;
        } else {
            $('#businessAddressError').text('');
        }
    
        if (business_owner_name === '') {
            $('#businessOwnerNameError').text('Please enter a owner name.');
            isValid = false;
        } else {
            $('#businessOwnerNameError').text('');
        }
    
        if (business_category === '') {
            $('#businessCategoryError').text('Please select a category.');
            isValid = false;
        } else {
            $('#businessCategoryError').text('');
        }
    

        // if (business_classification === '') {
        //     $('#businessClassificationError').text('Please select a classification.');
        //     isValid = false;
        // } else {
        //     $('#businessClassificationError').text('');
        // }
    

        // if (business_description === '') {
        //     $('#businessDescriptionError').text('Please select a description.');
        //     isValid = false;
        // } else {
        //     $('#businessDescriptionError').text('');
        // }
    
    

        if(isValid) {
            var  businessInfo = {
                business_name: business_name,
                business_address: business_address,
                business_owner_name: business_owner_name,
                business_category: business_category,
                business_classification: business_classification,
                business_description: business_description,
            }
    
            var url = baseUrl + "/update_business_info";
            
        $('#loaderModal').removeClass('hidden');
    
            $.ajax ({
                type: "POST",
                url: url,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                data: businessInfo,
                success: function (response){
                    // console.log(response)
                    
        $('#loaderModal').addClass('hidden');
                    window.location.reload();
                },
                error: function(error) {
                    console.log(error);
                }
          })
    
        }
        
    })


    $('#learnerNewPassword').keyup(function() {
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
        var learnerNewPassword = $('#learnerNewPassword').val()
        var learnerNewPasswordConfirm = $('#learnerNewPasswordConfirm').val()
        var learner_security_code = $('#learner_security_code').val()
    
        // Trigger keyup event for learnerNewPassword to perform password complexity validation
        $('#learnerNewPassword').trigger('keyup');
    
        var isValid = true;
    
        if (learnerNewPassword === '') {
            $('#newPasswordError').text('Please enter a password.');
            isValid = false;
        } else {
            $('#newPasswordError').text('');
        }
    
        if (learnerNewPasswordConfirm === '') {
            $('#newPasswordConfirmError').text('Please enter a password confirmation.');
            isValid = false;
        } else if (learnerNewPasswordConfirm !== learnerNewPassword) {
            $('#newPasswordConfirmError').text('Your password does not match');
            isValid = false;
        } else {
            $('#newPasswordConfirmError').text('');
        }
    
        if (learner_security_code === '') {
            $('#securityCodeError').text('Please enter a security code.');
            isValid = false;
        } else {
            $('#securityCodeError').text('');
        }
    
        if(isValid) {
            var userInfo = {
                learnerNewPassword: learnerNewPassword,
                learnerNewPasswordConfirm: learnerNewPasswordConfirm,
                learner_security_code: learner_security_code,
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









    
    function getLearnerData() {
        var url = `/learner/learnerData`;
            $.ajax({
                type: "GET",
                url: url,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(response) {
                    console.log(response);

                    var learner = response['learner']
                    var session_id = learner['learner_id']


                    $.when (
                        init_chatbot(session_id),
                        add_learner_data(session_id)
                    ).then (function() {
                        process_files(session_id)

                        $('.submitQuestion').on('click', function(e) {
                            e.preventDefault();
                            submitQuestion();
                        });
            
                        $('.question_input').on('keydown', function(e) {
                            if (e.keyCode === 13) {
                                e.preventDefault();
                                submitQuestion();
                            }
                        });
            
                        function submitQuestion() {
                            var learner_id = learner['learner_id'];
                            var question = $('.question_input').val();
                            var course = 'ALL';
                            var lesson = 'ALL';
            
                            displayUserMessage(question, learner);
                            $('.botloader').removeClass('hidden');
                            var chatData = {
                                question: question,
                                course: course,
                                lesson: lesson,
                            };
            
                            var url = `/chatbot/chat/${learner_id}`;
                            $.ajax({
                                type: "POST",
                                url: url,
                                data: chatData,
                                headers: {
                                    'X-CSRF-TOKEN': csrfToken
                                },
                                success: function(response) {
                                    console.log(response);
                                    displayBotMessage(response);
                                    $('.question_input').val('')
                                },
                                error: function(error) {
                                    console.log(error);
                                }
                            });
                        }
                    })

                },
                error: function(error) {
                    console.log(error);
                }
            });
    }

    
    function init_chatbot(learner_id) {
        // var learner_id = learner['learner_id'];
        var url = `/chatbot/init/${learner_id}`;
        $.ajax({
            type: "GET",
            url: url,
            success: function(response) {
                console.log(response);
            },
            error: function(error) {
                console.log(error);
            }
        });
    }


    
    function add_learner_data(learner_id) {
        // console.log(learner);
        var url = `/chatbot/learner/${learner_id}`;
        $.ajax({
            type: "GET",
            url: url,
            success: function(response) {
                console.log(response);

                 },
                 error: function(error) {
                     console.log(error);
                 }
             });
}

    function process_files(session_id) {
        var url = `/chatbot/process/${session_id}`;
        $.ajax({
            type: "GET",
            url: url,
            success: function(response) {
                console.log(response);

                $('.loaderArea').addClass('hidden');
                $('.mainchatbotarea').removeClass('hidden');
            },
            error: function(error) {
                console.log(error);
            }
        });
    }



    function displayUserMessage(question, learner) {
        var userMessageDisp = ``;
        var profile = learner['profile_picture']
        var currentTime = new Date();
        var hours = currentTime.getHours();
        var minutes = currentTime.getMinutes();

        minutes = minutes < 10 ? '0' + minutes : minutes;

        var timeString = hours + ':' + minutes;
    
        userMessageDisp += `
        
        <div class="mx-3 chat chat-end">
            <div class="chat-image avatar">
                <div class="w-10 rounded-full">
                <img class="bg-red-500" alt="" src="/storage/${profile}" />
                </div>
            </div>
            <div class="mx-3 chat-header">
                You
            </div>
            <div class="whitespace-pre-wrap chat-bubble chat-bubble-primary">${question}</div>
            <div class="opacity-50 chat-footer">
            ${timeString}
            </div>
        </div>
        `;

        $('.chatContainer').append(userMessageDisp);
    }


    function displayBotMessage(response) {

        var message = response['message']

        var botMessageDisp = ``
        botMessageDisp += `
        
        <div class="chat chat-start">
            <div class="chat-image avatar">
                <div class="w-10 rounded-full">
                <img class="bg-white" alt="" src="../../storage/images/chatbot.png" />
                </div>
            </div>
            <div class="chat-bubble ">${message}</div>
        </div>
        `;

        $('.botloader').addClass('hidden')
        $('.chatContainer').append(botMessageDisp);
    }

});