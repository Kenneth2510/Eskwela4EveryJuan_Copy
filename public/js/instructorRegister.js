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
    
    
$("#first-form")
$("#resumeForm")
$("#security_code")

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




    $('#instructor_contactno').on('input', function() {
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
        var instructor_fname = $('#instructor_fname').val()
        var instructor_lname = $('#instructor_lname').val()
        var instructor_bday = $('#instructor_bday').val()
        var instructor_gender = $('#instructor_gender').val()
        var instructor_email = $('#instructor_email').val()
        var instructor_contactno = $('#instructor_contactno').val()
        var instructor_username = $('#instructor_username').val()
        var password = $('#password').val()
        var password_confirmation = $('#password_confirmation').val()
        
        var security_code_1 = $('#security_code_1').val()
        var security_code_2 = $('#security_code_2').val()
        var security_code_3 = $('#security_code_3').val()
        var security_code_4 = $('#security_code_4').val()
        var security_code_5 = $('#security_code_5').val()
        var security_code_6 = $('#security_code_6').val()


        var fileInput = $('#instructor_credentials')[0];

        var isValid = true;

        if (fileInput.files.length === 0) {
            $('#credentialsError').text('Please upload your CV or resume.');
            isValid = false;
            $("#first-form").addClass('hidden')
            $("#resumeForm").removeClass('hidden')
            $("#security_code").addClass('hidden')
        } else {
            $('#credentialsError').text('');
        }


        if (instructor_fname === '') {
            $('#firstNameError').text('Please enter a first name.');
            isValid = false;
            $("#first-form").removeClass('hidden')
            $("#resumeForm").addClass('hidden')
            $("#security_code").addClass('hidden')
        } else {
            $('#firstNameError').text('');
        }
    
        if (instructor_bday === '') {
            $('#bdayError').text('Please enter a birthday.');
            isValid = false;
            $("#first-form").removeClass('hidden')
            $("#resumeForm").addClass('hidden')
            $("#security_code").addClass('hidden')
        } else {
            $('#bdayError').text('');
        }
    
        if (instructor_lname === '') {
            $('#lastNameError').text('Please enter a last name.');
            isValid = false;
            $("#first-form").removeClass('hidden')
            $("#resumeForm").addClass('hidden')
            $("#security_code").addClass('hidden')
        } else {
            $('#lastNameError').text('');
        }
    
        if (instructor_gender === '') {
            $('#genderError').text('Please select a gender.');
            isValid = false;
            $("#first-form").removeClass('hidden')
            $("#resumeForm").addClass('hidden')
            $("#security_code").addClass('hidden')
        } else {
            $('#genderError').text('');
        }
    

        if (instructor_email === '') {
            $('#emailError').text('Please enter your email.');
            isValid = false;
            $("#first-form").removeClass('hidden')
            $("#resumeForm").addClass('hidden')
            $("#security_code").addClass('hidden')
        } else {
            $('#emailError').text('');
        }

        if (instructor_contactno === '') {
            $('#contactnoError').text('Please enter your contact number.');
            isValid = false;
            $("#first-form").removeClass('hidden')
            $("#resumeForm").addClass('hidden')
            $("#security_code").addClass('hidden')
        } else {
            $('#contactError').text('');
        }

        if (instructor_username === '') {
            $('#usernameError').text('Please enter a username.');
            isValid = false;
            $("#first-form").removeClass('hidden')
            $("#resumeForm").addClass('hidden')
            $("#security_code").addClass('hidden')
        } else {
            $('#usernameError').text('');
        }

        $('#password').trigger('keyup');
    
        if (password === '') {
            $('#passwordError').text('Please enter a password.');
            isValid = false;
            $("#first-form").removeClass('hidden')
            $("#resumeForm").addClass('hidden')
            $("#security_code").addClass('hidden')
        } else {
            $('#passwordError').text('');
        }
    
        if (password_confirmation === '') {
            $('#passwordConfirmationError').text('Please enter a password confirmation.');
            isValid = false;
            $("#first-form").removeClass('hidden')
            $("#resumeForm").addClass('hidden')
            $("#security_code").addClass('hidden')
        } else if (password !== password_confirmation) {
            $('#passwordConfirmationError').text('Your password does not match');
            isValid = false;
            $("#first-form").removeClass('hidden')
            $("#resumeForm").addClass('hidden')
            $("#security_code").addClass('hidden')
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



        if (isValid) {
            var formData = new FormData();
            formData.append('instructor_fname', instructor_fname);
            formData.append('instructor_lname', instructor_lname);
            formData.append('instructor_bday', instructor_bday);
            formData.append('instructor_gender', instructor_gender);
            formData.append('instructor_email', instructor_email);
            formData.append('instructor_contactno', instructor_contactno);
            formData.append('instructor_username', instructor_username);
            formData.append('password', password);
            formData.append('password_confirmation', password_confirmation);
            formData.append('security_code_1', security_code_1);
            formData.append('security_code_2', security_code_2);
            formData.append('security_code_3', security_code_3);
            formData.append('security_code_4', security_code_4);
            formData.append('security_code_5', security_code_5);
            formData.append('security_code_6', security_code_6);
            formData.append('instructor_credentials', $('#instructor_credentials')[0].files[0]);
        
            var url = "/instructor/register";
        
            $.ajax({
                type: "POST",
                url: url,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    console.log(response);
                    window.location.href = "/instructor";
                    

                },
                error: function(error) {
                    console.log(error);
                    alert("Error Processing your registration")
                    if (error.responseJSON && error.responseJSON.errors) {
                        if (error.responseJSON.errors.instructor_email) {
                            $('#emailError').text(error.responseJSON.errors.instructor_email[0]);
                            $("#first-form").removeClass('hidden')
                            $("#resumeForm").addClass('hidden')
                            $("#security_code").addClass('hidden')
                        }
                        if (error.responseJSON.errors.instructor_contactno) {
                            $('#contactnoError').text(error.responseJSON.errors.instructor_contactno[0]);
                            $("#first-form").removeClass('hidden')
                            $("#resumeForm").addClass('hidden')
                            $("#security_code").addClass('hidden')
                        }
                        if (error.responseJSON.errors.instructor_username) {
                            $('#usernameError').text(error.responseJSON.errors.instructor_username[0]);
                             $("#first-form").removeClass('hidden')
                            $("#resumeForm").addClass('hidden')
                            $("#security_code").addClass('hidden')
                        }
                    }
                }
            });
        }
        
    })
});
