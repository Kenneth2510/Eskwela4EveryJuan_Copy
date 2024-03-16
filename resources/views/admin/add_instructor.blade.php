@include('partials.header')

<section class="flex flex-row w-screen text-sm main-container bg-mainwhitebg md:text-base">
@include('partials.sidebar')




<section class="w-screen px-2 pt-[40px] mx-2 mt-2  overscroll-auto md:overflow-auto">
    <div class="flex justify-between px-10">
        <h1 class="text-6xl font-bold text-darthmouthgreen">Instructor Management</h1>
        <div class="">
            <p class="text-xl font-semibold text-darthmouthgreen">{{$admin->admin_codename}}</p>
        </div>
    </div>

    <div class="w-full px-3 pb-4 mt-10 rounded-lg shadow-lg b">
        <div class="mb-5">
            <a href="/admin/instructors" class="">
                <i class="text-2xl md:text-3xl fa-solid fa-arrow-left" style="color: #000000;"></i>
            </a>
        </div>
        <div id="AD002_IA_maincontainer" class="relative w-full px-2 text-black shadow-lg rounded-2xl">
            <div class="mb-5">
                <a href="/admin/instructors" class="">
                    <i class="text-xl fa-solid fa-arrow-left" style="color: #000000;"></i>
                </a>
            </div>

        <div class="flex justify-between">
            <div class="flex flex-col items-center justify-start w-3/12 h-full py-10 mx-5 bg-white rounded-lg shadow-lg" id="upper_left_container">
                <div class="relative flex flex-col items-center justify-start"  style="margin:0 auto; padding: auto;">
                    <img class="z-0 w-40 h-40 rounded-full" src="{{ asset('storage/images/default_profile.png')}}" alt="Profile Picture">
                </div>

                <div class="mt-10" id="name_area">
                    <h1 class="text-2xl font-semibold text-center" id="nameDisp">NAME</h1>
                </div>

                <div class="mt-5 text-center" id="account_status_area">
                    <h1 class="text-xl" id="roleDisp">INSTRUCTOR</h1>
                </div>

                
                <div class="flex justify-center w-full px-5 mt-5">
                    @if($admin->role === 'IT_DEPT' || $admin->role === 'SUPER_ADMIN' || $admin->role === 'USER_MANAGER')
                    <button type="button" class="px-5 py-3 mx-2 text-lg text-white bg-darthmouthgreen hover:border-2 hover:bg-white hover:border-darthmouthgreen hover:text-darthmouthgreen rounded-xl" id="submit_new_instructor">Save new instructor</button>
                    @endif
                </div>

            </div> 
            <div class="w-9/12 h-full" id="upper_right_container">
                <div class="w-full px-5 py-10 bg-white shadow-lg rounded-xl" id="upper_right_1">
                    <h1 class="text-4xl font-semibold text-darthmouthgreen">User Details</h1>

                    <hr class="my-6 border-t-2 border-gray-300">

                    <div class="flex w-full mt-5" id="userInfo">
                        <div class="w-1/2 mx-2" id="userInfo_left">
                            <div class="mt-3" id="firstNameArea">
                                <label for="instructor_fname">First Name</label><br>
                                <input class="w-full h-12 px-5 py-1 border-2 rounded-lg border-darthmouthgreen" type="text" name="instructor_fname" id="instructor_fname" value="">
                                <span id="firstNameError" class="text-red-500"></span>
                            </div>
                            <div class="mt-3" id="bdayArea">
                                <label for="instructor_bday ">Birthday</label><br>
                                <input class="w-full h-12 px-5 py-1 border-2 rounded-lg border-darthmouthgreen" type="date" name="instructor_bday" id="instructor_bday" value="">
                                <span id="bdayError" class="text-red-500"></span>
                            </div>
                            <div class="mt-3" id="contactArea">
                                <label for="instructor_contactno">Contact Number</label><br>
                                <input class="w-full h-12 px-5 py-1 border-2 rounded-lg border-darthmouthgreen" type="text" maxlength="11" name="instructor_contactno" id="instructor_contactno" value="" placeholder="09">
                                <span id="contactError" class="text-red-500"></span>
                            </div>
                        </div>
                        <div class="w-1/2 mx-2" id="userInfo_right">
                            <div class="mt-3" id="lastNameArea">
                                <label for="instructor_lname">Last Name</label><br>
                                <input class="w-full h-12 px-5 py-1 border-2 rounded-lg border-darthmouthgreen" type="text" name="instructor_lname" id="instructor_lname" value="">
                                <span id="lastNameError" class="text-red-500"></span>
                            </div>
                            <div class="mt-3" id="genderArea">
                                <label for="instructor_gender">Gender</label><br>
                                <select class="w-full h-12 px-5 py-1 border-2 rounded-lg border-darthmouthgreen" name="instructor_gender" id="instructor_gender" >
                                    <option value="">-- select an option --</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Others">Preferred not to say</option>
                                </select>
                                <span id="genderError" class="text-red-500"></span>
                            </div>
                            <div class="mt-3" id="emailArea">
                                <label for="instructor_email">Email Address</label><br>
                                <input class="w-full h-12 px-5 py-1 border-2 rounded-lg border-darthmouthgreen" type="email" name="instructor_email" id="instructor_email" value="">
                                <span id="emailError" class="text-red-500"></span>
                            </div>
                        </div>
                    </div>
    
                </div>

                         <div class="w-full px-5 py-10 mt-5 bg-white shadow-lg rounded-xl" id="courseProgress">
                            <h1 class="text-4xl font-semibold text-darthmouthgreen">Your Credentials</h1>

                            <hr class="my-6 border-t-2 border-gray-300">

                            {{-- <a href="">sample</a> --}}
                            <input class="w-full h-12 px-5 py-1 rounded-lg" type="file" name="instructor_credentials" id="instructor_credentials" value="">
                            <span id="credentialsError" class="text-red-500"></span>

                        </div>


                <div class="w-full px-5 py-10 mt-5 bg-white shadow-lg rounded-xl" id="upper_right_3">
                    <h1 class="text-4xl font-semibold text-darthmouthgreen">Account Details</h1>

                    <hr class="my-6 border-t-2 border-gray-300">

                    <div class="mt-3" id="instructor_usernameArea">
                        <label for="instructor_username">Username</label><br>
                        <input class="w-full h-12 px-5 py-1 border-2 rounded-lg border-darthmouthgreen" type="text" name="instructor_username" id="instructor_username" value="">
                        <span id="usernameError" class="text-red-500"></span>
                    </div>

                    <div class="mt-3" id="instructorPasswordArea">
                        <label for="password">Password</label><br>
                        <input class="w-full h-12 px-5 py-1 border-2 rounded-lg border-darthmouthgreen" type="password" name="password" id="password">
                        <span id="passwordError" class="text-red-500"></span>
                    </div>
                    
                    <div id="passwordCheckbox" class="mt-3">
                        <input type="checkbox" id="showNewPassword" class="mr-2">
                        <label for="showNewPassword" class="cursor-pointer">Show New Password</label>
                    </div>
                    
                    <div class="mt-3" id="instructorPasswordConfirmArea">
                        <label for="instructorNewPasswordConfirm">Confirm New Password</label><br>
                        <input class="w-full h-12 px-5 py-1 border-2 rounded-lg border-darthmouthgreen" type="password" name="instructorNewPasswordConfirm" id="instructorNewPasswordConfirm">
                        
                        <span id="newPasswordConfirmError" class="text-red-500"></span>
                    </div>
                    

                    <div class="mt-3" id="securityCodeArea">
                        <label for="instructor_security_code">Enter your Security Code</label><br>
                        <input class="w-full h-12 px-5 py-1 border-2 rounded-lg border-darthmouthgreen" type="password" maxlength="6" name="instructor_security_code" id="instructor_security_code">
                        <span id="securityCodeGuide" class="text-gray-500">Enter 6 characters of security code</span>
                        <span id="securityCodeError" class="text-red-500"></span>
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
    

        $('#showNewPassword').on('change', function() {
        var passwordInput = $('#password');
        var passwordConfirmInput = $('#instructorNewPasswordConfirm');
        var instructor_security_code = $('#instructor_security_code');
        if ($(this).is(':checked')) {
            passwordInput.attr('type', 'text');
            passwordConfirmInput.attr('type', 'text');            
            instructor_security_code.attr('type', 'text');
        } else {
            passwordInput.attr('type', 'password');
            passwordConfirmInput.attr('type', 'password');           
            instructor_security_code.attr('type', 'password');
        }
        });

        $('#instructor_fname, #instructor_lname').on('input', function() {
            var fname = $('#instructor_fname').val();
            var lname = $(' #instructor_lname').val(); 

            var name = fname + ' ' + lname;

            $('#nameDisp').text(name);
        })

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




        $('#submit_new_instructor').on('click', function() {
            var instructor_fname = $('#instructor_fname').val()
            var instructor_bday = $('#instructor_bday').val()
            var instructor_lname = $('#instructor_lname').val()
            var instructor_gender = $('#instructor_gender').val()
            var instructor_contactno = $('#instructor_contactno').val()
            var instructor_email = $('#instructor_email').val()

            var credentials = $('#instructor_credentials');
            var instructor_credentials = credentials.prop('files')[0];

            var instructor_username = $('#instructor_username').val()
            var password = $('#password').val()
            var instructorNewPasswordConfirm = $('#instructorNewPasswordConfirm').val()
            var instructor_security_code = $('#instructor_security_code').val()
            
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

            if (instructor_contactno === '') {
                $('#contactError').text('Please enter your contact number.');
                isValid = false;
            } else {
                $('#contactError').text('');
            }

            if (instructor_email === '') {
                $('#emailError').text('Please enter your email address.');
                isValid = false;
            } else {
                $('#emailError').text('');
            }

            if (!instructor_credentials) {
                $('#credentialsError').text('Please select a file.');
                isValid = false;
            } else {
                $('#credentialsError').text('');
            }


            if (instructor_username === '') {
                $('#usernameError').text('Please enter your username.');
                isValid = false;
            } else {
                $('#usernameError').text('');
            }
        

            if (password === '') {
                $('#passwordError').text('Please enter a password.');
                isValid = false;
            } else {
                $('#passwordError').text('');
            }
        
            if (instructorNewPasswordConfirm === '') {
                $('#newPasswordConfirmError').text('Please enter a password confirmation.');
                isValid = false;
            } else if (instructorNewPasswordConfirm !== password) {
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
                var formData = new FormData();
                formData.append('instructor_fname', instructor_fname);
                formData.append('instructor_bday', instructor_bday);
                formData.append('instructor_lname', instructor_lname);
                formData.append('instructor_gender', instructor_gender);
                formData.append('instructor_contactno', instructor_contactno);
                formData.append('instructor_email', instructor_email);
                formData.append('instructor_username', instructor_username);
                formData.append('password', password);
                formData.append('instructorNewPasswordConfirm', instructorNewPasswordConfirm);
                formData.append('instructor_security_code', instructor_security_code);
                formData.append('instructor_credentials', instructor_credentials);

            
            var url = baseUrl;
    
            $('#loaderModal').removeClass('hidden');
            $.ajax({

                url: baseUrl,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    // Handle success response
                    console.log(response);
                    if (response.redirect_url) {
                        
        $('#loaderModal').addClass('hidden');
                        window.location.href = response.redirect_url;
                    }
                },
                error: function(xhr, status, error) {
                    // Handle error
                    console.error(error);
                }
            })
            }
        })
    })
</script>
@include('partials.footer')
