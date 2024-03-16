$(document).ready(function () {
    var baseUrl = window.location.href
    var csrfToken = $('meta[name="csrf-token"]').attr('content'); // Get the CSRF token from the meta tag
    
    const nextBtn = $("#nxtBtn");
    const nextBtn2 = $("#nxtBtn2");
    const backBtn = $("#bckBtn");
    const prevBtn = $("#prevBtn");
    const prevBtn2 = $("#prevBtn2");
    const firstForm = $("#first-form");
    const secondForm = $("#resumeForm");
    const thirdForm = $("#security_code");
    const header = $("#ins-head");
    const footer = $("#ins-foot");

    nextBtn.on("click", function (event) {
        event.preventDefault();

        firstForm.addClass("hidden");
        secondForm.removeClass("hidden");
        header.addClass("hidden");
        footer.addClass("hidden");
    });

    nextBtn2.on("click", function (event) {
        event.preventDefault();

        secondForm.addClass("hidden");
        thirdForm.removeClass("hidden");
        header.addClass("hidden");
        footer.addClass("hidden");
    });

    prevBtn.on("click", function (event) {
        event.preventDefault();

        secondForm.addClass("hidden");
        firstForm.removeClass("hidden");
        header.removeClass("hidden");
        footer.removeClass("hidden");
    });

    prevBtn2.on("click", function (event) {
        event.preventDefault();

        thirdForm.addClass("hidden");
        secondForm.removeClass("hidden");
        header.addClass("hidden");
        footer.addClass("hidden");
    });

    backBtn.on("click", function (event) {
        event.preventDefault();

        firstForm.removeClass("hidden");
        secondForm.addClass("hidden");
        header.removeClass("hidden");
        footer.removeClass("hidden");
    });

    const imgSlides = $(".slides");
    const carouselBtn = $("#carouselBtn button");
    let crrntSlide = 0;
    let slideInterval;

    function initCarousel() {
        imgSlides.hide();
        imgSlides.eq(crrntSlide).show();
        carouselBtn.removeClass("bg-slate-500");
        carouselBtn.eq(crrntSlide).addClass("bg-slate-500");
    }

    function showSlide(index) {
        imgSlides.hide();
        imgSlides.eq(index).show();
        carouselBtn.removeClass("bg-slate-500");
        carouselBtn.eq(index).addClass("bg-slate-500");
        crrntSlide = index;
    }

    function nextSlide() {
        const nextSlide = (crrntSlide + 1) % imgSlides.length;
        showSlide(nextSlide);
    }

    function startSlideInterval() {
        slideInterval = setInterval(nextSlide, 5000);
    }

    function stopSlideInterval() {
        clearInterval(slideInterval);
    }

    // Automatically switch to the next slide every 5 seconds
    startSlideInterval();

    // Event listener for next button
    $("#l-nextBtn").click(function () {
        nextSlide();
        stopSlideInterval();
        startSlideInterval();
    });

    // Event listener for previous button
    $("#l-prevBtn").click(function () {
        const prevSlide =
            (crrntSlide - 1 + imgSlides.length) % imgSlides.length;
        showSlide(prevSlide);
        stopSlideInterval();
        startSlideInterval();
    });

    // Event listener for carousel buttons
    carouselBtn.each(function (index) {
        $(this).click(function () {
            showSlide(index);
            stopSlideInterval();
            startSlideInterval();
        });
    });

    // Initialize the carousel on page load
    initCarousel();


    $('#learner_contactno').on('input', function() {
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


    $('#password').keyup(function() {
        var password = $(this).val();

        // Remove previous error message
        $('#passwordError').empty();

        // Validate password complexity
        var hasUpperCase = /[A-Z]/.test(password);
        var hasLowerCase = /[a-z]/.test(password);
        var hasNumbers = /\d/.test(password);
        var hasSpecialChars = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password);

        var isValid = true;

        if (!hasUpperCase) {
            isValid = false;
            $('#passwordError').append('Password must contain at least one uppercase letter.<br>');
        }
        if (!hasLowerCase) {
            isValid = false;
            $('#passwordError').append('Password must contain at least one lowercase letter.<br>');
        }
        if (!hasNumbers) {
            isValid = false;
            $('#passwordError').append('Password must contain at least one number.<br>');
        }
        if (!hasSpecialChars) {
            isValid = false;
            $('#passwordError').append('Password must contain at least one special character.<br>');
        }
        if (password.length < 8) {
            isValid = false;
            $('#newPasswordError').append('Password must be at least 8 characters long.<br>');
        }
    });

    $('#showPassword').change(function() {
        var isChecked = $(this).is(':checked');
        if (isChecked) {
            $('#password').attr('type', 'text');
        } else {
            $('#password').attr('type', 'password');
        }
    });

    $('#register_submit_btn').on('click', function() {
        var learner_fname = $('#learner_fname').val()
        var learner_lname = $('#learner_lname').val()
        var learner_bday = $('#learner_bday').val()
        var learner_gender = $('#learner_gender').val()
        var learner_email = $('#learner_email').val()
        var learner_contactno = $('#learner_contactno').val()
        var learner_username = $('#learner_username').val()
        var password = $('#password').val()
        var password_confirmation = $('#password_confirmation').val()

        
        var business_name = $('#business_name').val()
        var business_address = $('#business_address').val()
        var business_owner_name = $('#business_owner_name').val()
        var bplo_account_number = $('#bplo_account_number').val()
        var business_category = $('#business_category').val()
        var business_classification = $('#business_classification').val()
        var business_description = $('#business_description').val()

        
        var security_code_1 = $('#security_code_1').val()
        var security_code_2 = $('#security_code_2').val()
        var security_code_3 = $('#security_code_3').val()
        var security_code_4 = $('#security_code_4').val()
        var security_code_5 = $('#security_code_5').val()
        var security_code_6 = $('#security_code_6').val()


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
    

        if (learner_email === '') {
            $('#emailError').text('Please enter your email.');
            isValid = false;
        } else {
            $('#emailError').text('');
        }

        if (learner_contactno === '') {
            $('#contactnoError').text('Please enter your contact number.');
            isValid = false;
        } else {
            $('#contactError').text('');
        }

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

        if (bplo_account_number === '') {
            $('#bploCategoryError').text('Please enter your id.');
            isValid = false;
        } else {
            $('#bploCategoryError').text('');
        }

        
        if (business_classification === '') {
            $('#businessClassificationError').text('Please select a classification.');
            isValid = false;
        } else {
            $('#businessClassificationError').text('');
        }
    

        if (business_description === '') {
            $('#businessDescriptionError').text('Please enter your business description.');
            isValid = false;
        } else {
            $('#businessDescriptionError').text('');
        }



        if (learner_username === '') {
            $('#usernameError').text('Please enter a username.');
            isValid = false;
        } else {
            $('#usernameError').text('');
        }

        $('#password').trigger('keyup');
    
        if (password === '') {
            $('#passwordError').text('Please enter a password.');
            isValid = false;
        } else {
            $('#passwordError').text('');
        }
    
        if (password_confirmation === '') {
            $('#passwordConfirmationError').text('Please enter a password confirmation.');
            isValid = false;
        } else if (password !== password_confirmation) {
            $('#passwordConfirmationError').text('Your password does not match');
            isValid = false;
        } else {
            $('#passwordConfirmationError').text('');
        }
    
        if (security_code_1 === '' ||
        security_code_2 === '' ||
        security_code_3 === '' ||
        security_code_4 === '' ||
        security_code_5 === '' ||
        security_code_6 === '') {
            $('#securityCodeError').text('Please enter a security code.');
            isValid = false;
        } else {
            $('#securityCodeError').text('');
        }



        if(isValid) {
            var userInfo = {
                learner_fname: learner_fname,
                learner_lname: learner_lname,
                learner_bday: learner_bday,
                learner_gender: learner_gender,
                learner_email: learner_email,
                learner_contactno: learner_contactno,
                learner_username: learner_username,
                password: password,
                password_confirmation: password_confirmation,

                business_name: business_name,
                business_address: business_address,
                business_owner_name: business_owner_name,
                bplo_account_number: bplo_account_number,
                business_category: business_category,
                business_classification: business_classification,
                business_description: business_description,

                security_code_1: security_code_1,
                security_code_2: security_code_2,
                security_code_3: security_code_3,
                security_code_4: security_code_4,
                security_code_5: security_code_5,
                security_code_6: security_code_6,
            }

            var learner_fname = $('#learner_fname').val()
            var learner_lname = $('#learner_lname').val()
            var learner_bday = $('#learner_bday').val()
            var learner_gender = $('#learner_gender').val()
            var learner_email = $('#learner_email').val()
            var learner_contactno = $('#learner_contactno').val()
            var learner_username = $('#learner_username').val()
            var password = $('#password').val()
            var password_confirmation = $('#password_confirmation').val()
    
            
            var business_name = $('#business_name').val()
            var business_address = $('#business_address').val()
            var business_owner_name = $('#business_owner_name').val()
            var bplo_account_number = $('#bplo_account_number').val()
            var business_category = $('#business_category').val()
            var business_classification = $('#business_classification').val()
            var business_description = $('#business_description').val()
    
            
            var security_code_1 = $('#security_code_1').val()
            var security_code_2 = $('#security_code_2').val()
            var security_code_3 = $('#security_code_3').val()
            var security_code_4 = $('#security_code_4').val()
            var security_code_5 = $('#security_code_5').val()
            var security_code_6 = $('#security_code_6').val()
    
            var url = "/learner/register";
    
            $.ajax ({
                type: "POST",
                url: url,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                data: userInfo,
                success: function (response){
                    console.log(response)
                    window.location.href = "/learner";
                },
                error: function(error) {
                    console.log(error);
                    alert("Error Processing your registration")
                    if (error.responseJSON && error.responseJSON.errors) {
                        if (error.responseJSON.errors.learner_email) {
                            $('#emailError').text(error.responseJSON.errors.learner_email[0]);
                            $("#first-form").removeClass('hidden')
                            $("#resumeForm").addClass('hidden')
                            $("#security_code").addClass('hidden')
                        }
                        if (error.responseJSON.errors.learner_contactno) {
                            $('#contactnoError').text(error.responseJSON.errors.learner_contactno[0]);
                            $("#first-form").removeClass('hidden')
                            $("#resumeForm").addClass('hidden')
                            $("#security_code").addClass('hidden')
                        }
                        if (error.responseJSON.errors.learner_username) {
                            $('#usernameError').text(error.responseJSON.errors.learner_username[0]);
                             $("#first-form").removeClass('hidden')
                            $("#resumeForm").addClass('hidden')
                            $("#security_code").addClass('hidden')
                        }
                    }
                }
            })
        }
    })
});
