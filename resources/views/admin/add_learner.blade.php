@include('partials.header')

<section class="flex flex-row w-screen text-sm main-container bg-mainwhitebg md:text-base">
@include('partials.sidebar')




<section class="w-screen px-2 pt-[40px] mx-2 mt-2  overscroll-auto md:overflow-auto">
            <h1 class="text-6xl font-bold text-darthmouthgreen">Learner Management</h1>
        <div class="">
            <p class="text-xl font-semibold text-darthmouthgreen">{{$admin->admin_codename}}</p>
        </div>
    <div class="flex justify-between px-10">


    <div class="w-full px-3 pb-4 mt-10 rounded-lg shadow-lg b">
        <div class="mb-5">
            <a href="/admin/learners" class="">
                <i class="text-2xl md:text-3xl fa-solid fa-arrow-left" style="color: #000000;"></i>
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
                    <h1 class="text-xl" id="roleDisp">LEARNER</h1>
                </div>

                
                <div class="flex justify-center w-full px-5 mt-5">
                    @if($admin->role === 'IT_DEPT' || $admin->role === 'SUPER_ADMIN' || $admin->role === 'USER_MANAGER')
                    <button type="button" class="px-5 py-3 mx-2 text-lg text-white bg-darthmouthgreen hover:border-2 hover:bg-white hover:border-darthmouthgreen hover:text-darthmouthgreen rounded-xl" id="submit_new_learner">Save new Learner</button>
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
                                <label for="learner_fname">First Name</label><br>
                                <input class="w-full h-12 px-5 py-1 border-2 rounded-lg border-darthmouthgreen" type="text" name="learner_fname" id="learner_fname" value="">
                                <span id="firstNameError" class="text-red-500"></span>
                            </div>
                            <div class="mt-3" id="bdayArea">
                                <label for="learner_bday ">Birthday</label><br>
                                <input class="w-full h-12 px-5 py-1 border-2 rounded-lg border-darthmouthgreen" type="date" name="learner_bday" id="learner_bday" value="">
                                <span id="bdayError" class="text-red-500"></span>
                            </div>
                            <div class="mt-3" id="contactArea">
                                <label for="learner_contactno">Contact Number</label><br>
                                <input class="w-full h-12 px-5 py-1 border-2 rounded-lg border-darthmouthgreen" type="text" maxlength="11" name="learner_contactno" id="learner_contactno" value="" placeholder="09">
                                <span id="contactError" class="text-red-500"></span>
                            </div>
                        </div>
                        <div class="w-1/2 mx-2" id="userInfo_right">
                            <div class="mt-3" id="lastNameArea">
                                <label for="learner_lname">Last Name</label><br>
                                <input class="w-full h-12 px-5 py-1 border-2 rounded-lg border-darthmouthgreen" type="text" name="learner_lname" id="learner_lname" value="">
                                <span id="lastNameError" class="text-red-500"></span>
                            </div>
                            <div class="mt-3" id="genderArea">
                                <label for="learner_gender">Gender</label><br>
                                <select class="w-full h-12 px-5 py-1 border-2 rounded-lg border-darthmouthgreen" name="learner_gender" id="learner_gender" >
                                    <option value="">-- select an option --</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Others">Preferred not to say</option>
                                </select>
                                <span id="genderError" class="text-red-500"></span>
                            </div>
                            <div class="mt-3" id="emailArea">
                                <label for="learner_email">Email Address</label><br>
                                <input class="w-full h-12 px-5 py-1 border-2 rounded-lg border-darthmouthgreen" type="email" name="learner_email" id="learner_email" value="">
                                <span id="emailError" class="text-red-500"></span>
                            </div>
                        </div>
                    </div>
    
                </div>

                <div class="w-full px-5 py-10 mt-5 bg-white shadow-lg rounded-xl" id="upper_right_2">
                    <h1 class="text-4xl font-semibold text-darthmouthgreen">Business Details</h1>

                    <hr class="my-6 border-t-2 border-gray-300">

                    <div class="mt-3" id="businessNameArea">
                        <label for="business_name">Business Name</label><br>
                        <input class="w-full h-12 px-5 py-1 border-2 rounded-lg border-darthmouthgreen" type="text" name="business_name" id="business_name" value="">
                        <span id="businessNameError" class="text-red-500"></span>
                    </div>

                    <div class="mt-3" id="businessAddressArea">
                        <label for="business_address">Business Address</label><br>
                        <input class="w-full h-12 px-5 py-1 border-2 rounded-lg border-darthmouthgreen" type="text" name="business_address" id="business_address" value="">
                        <span id="businessAddressError" class="text-red-500"></span>
                    </div>

                    <div class="mt-3" id="businessOwnerArea">
                        <label for="business_owner_name">Business Owner Name</label><br>
                        <input class="w-full h-12 px-5 py-1 border-2 rounded-lg border-darthmouthgreen" type="text" name="business_owner_name" id="business_owner_name" value="">
                        <span id="businessOwnerNameError" class="text-red-500"></span>
                    </div>

                    <div class="mt-3" id="bplo_account_numberArea">
                        <label for="bplo_account_number">BPLO Account Number</label><br>
                        <input class="w-full h-12 px-5 py-1 border-2 rounded-lg border-darthmouthgreen" maxlength="13" type="text" name="bplo_account_number" id="bplo_account_number" value="">
                        <span id="bploError" class="text-red-500"></span>
                    </div>

                    <div class="flex justify-between w-full">
                                                
                        <div class="w-full mt-3 mr-2" id="business_categoryArea">
                            <label for="business_category">Business Category</label><br>
                            <select class="w-full h-12 px-5 py-1 border-2 rounded-lg border-darthmouthgreen" name="business_category" id="business_category">
                                <option value="" disabled>-- select an option --</option>
                                <option value="Micro">Micro</option>
                                <option value="Small">Small</option>
                                <option value="Medium">Medium</option>
                            </select>
                            <span id="businessCategoryError" class="text-red-500"></span>
                        </div>

                        <div class="w-full mt-3 ml-2" id="business_classificationArea">
                            <label for="business_classification">Business Classification</label><br>
                            <select class="w-full h-12 px-5 py-1 border-2 rounded-lg border-darthmouthgreen" name="business_classification" id="business_classification">
                                <option value=""disabled>-- select an option --</option>
                                <option value="Retail">Retail</option>
                                <option value="Wholesale">Wholesale</option>
                                <option value="Financial Services">Financial Services</option>
                                <option value="Real Estate">Real Estate</option>
                                <option value="Transportation and Logistics">Transportation and Logistics</option>
                                <option value="Technology">Technology</option>
                                <option value="Healthcare">Healthcare</option>
                                <option value="Education and Training">Education and Training</option>
                                <option value="Entertainment and Media">Entertainment and Media</option>
                                <option value="Hospitality and Tourism">Hospitality and Tourism</option>
                                <option value="Others">Others</option>
                            </select>
                            <span id="businessClassificationError" class="text-red-500"></span>
                        </div>
        
                    </div>

                    <div class="mt-3" id="business_descriptionArea">
                        <label for="business_description">Business Description</label><br>
                        <textarea name="business_description" class="w-full px-5 py-1 border-2 rounded-lg h-36 border-darthmouthgreen" id="business_description"></textarea>
                        <span id="businessDescriptionError" class="text-red-500"></span>
                    </div>

                </div>

                <div class="w-full px-5 py-10 mt-5 bg-white shadow-lg rounded-xl" id="upper_right_3">
                    <h1 class="text-4xl font-semibold text-darthmouthgreen">Account Details</h1>

                    <hr class="my-6 border-t-2 border-gray-300">

                    <div class="mt-3" id="learner_usernameArea">
                        <label for="learner_username">Username</label><br>
                        <input class="w-full h-12 px-5 py-1 border-2 rounded-lg border-darthmouthgreen" type="text" name="learner_username" id="learner_username" value="">
                        <span id="usernameError" class="text-red-500"></span>
                    </div>

                    <div class="mt-3" id="learnerPasswordArea">
                        <label for="password">Password</label><br>
                        <input class="w-full h-12 px-5 py-1 border-2 rounded-lg border-darthmouthgreen" type="password" name="password" id="password">
                        <span id="passwordError" class="text-red-500"></span>
                    </div>
                    
                    <div id="passwordCheckbox" class="mt-3">
                        <input type="checkbox" id="showNewPassword" class="mr-2">
                        <label for="showNewPassword" class="cursor-pointer">Show New Password</label>
                    </div>
                    
                    <div class="mt-3" id="learnerPasswordConfirmArea">
                        <label for="learnerNewPasswordConfirm">Confirm New Password</label><br>
                        <input class="w-full h-12 px-5 py-1 border-2 rounded-lg border-darthmouthgreen" type="password" name="learnerNewPasswordConfirm" id="learnerNewPasswordConfirm">
                        
                        <span id="newPasswordConfirmError" class="text-red-500"></span>
                    </div>
                    

                    <div class="mt-3" id="securityCodeArea">
                        <label for="learner_security_code">Enter your Security Code</label><br>
                        <input class="w-full h-12 px-5 py-1 border-2 rounded-lg border-darthmouthgreen" type="password" maxlength="6" name="learner_security_code" id="learner_security_code">
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
        var passwordConfirmInput = $('#learnerNewPasswordConfirm');
        var learner_security_code = $('#learner_security_code');
        if ($(this).is(':checked')) {
            passwordInput.attr('type', 'text');
            passwordConfirmInput.attr('type', 'text');            
            learner_security_code.attr('type', 'text');
        } else {
            passwordInput.attr('type', 'password');
            passwordConfirmInput.attr('type', 'password');           
            learner_security_code.attr('type', 'password');
        }
        });

        $('#learner_fname, #learner_lname').on('input', function() {
            var fname = $('#learner_fname').val();
            var lname = $(' #learner_lname').val(); 

            var name = fname + ' ' + lname;

            $('#nameDisp').text(name);
        })

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




        $('#submit_new_learner').on('click', function() {
            var learner_fname = $('#learner_fname').val()
            var learner_bday = $('#learner_bday').val()
            var learner_lname = $('#learner_lname').val()
            var learner_gender = $('#learner_gender').val()
            var learner_contactno = $('#learner_contactno').val()
            var learner_email = $('#learner_email').val()

            var business_name = $('#business_name').val()
            var business_address = $('#business_address').val()
            var business_owner_name = $('#business_owner_name').val()
            var bplo_account_number = $('#bplo_account_number').val()
            var business_category = $('#business_category').val()
            var business_classification = $('#business_classification').val()
            var business_description = $('#business_description').val()

            
            var learner_username = $('#learner_username').val()
            var password = $('#password').val()
            var learnerNewPasswordConfirm = $('#learnerNewPasswordConfirm').val()
            var learner_security_code = $('#learner_security_code').val()
            
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

            if (learner_contactno === '') {
                $('#contactError').text('Please enter your contact number.');
                isValid = false;
            } else {
                $('#contactError').text('');
            }

            if (learner_email === '') {
                $('#emailError').text('Please enter your email address.');
                isValid = false;
            } else {
                $('#emailError').text('');
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

            if (bplo_account_number === '') {
                $('#bploError').text('Please enter your BPLO id.');
                isValid = false;
            } else {
                $('#bploError').text('');
            }
        
            if (business_category === '') {
                $('#businessCategoryError').text('Please select a category.');
                isValid = false;
            } else {
                $('#businessCategoryError').text('');
            }
        

            if (business_classification === '') {
                $('#businessClassificationError').text('Please select a classification.');
                isValid = false;
            } else {
                $('#businessClassificationError').text('');
            }
        

            if (business_description === '') {
                $('#businessDescriptionError').text('Please select a description.');
                isValid = false;
            } else {
                $('#businessDescriptionError').text('');
            }

            if (learner_username === '') {
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
        
            if (learnerNewPasswordConfirm === '') {
                $('#newPasswordConfirmError').text('Please enter a password confirmation.');
                isValid = false;
            } else if (learnerNewPasswordConfirm !== password) {
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
                var learnerData = { 
                    learner_fname: learner_fname,
                    learner_lname: learner_lname,
                    learner_gender: learner_gender,
                    learner_contactno: learner_contactno,
                    learner_email: learner_email,
                    learner_bday: learner_bday,

                    business_name: business_name,
                    business_address: business_address,
                    business_owner_name: business_owner_name,
                    bplo_account_number: bplo_account_number,
                    business_category: business_category,
                    business_classification: business_classification,
                    business_description: business_description,

                    learner_username: learner_username,
                    password: password,
                    learnerNewPasswordConfirm:learnerNewPasswordConfirm,
                    learner_security_code: learner_security_code,
                }
    
            var url = baseUrl;
    
            $('#loaderModal').removeClass('hidden');
            $.ajax ({
                type: "POST",
                url: url,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                data: learnerData,
                success: function(response) {
                    console.log(response);

                    if (response.redirect_url) {
                        
        $('#loaderModal').addClass('hidden');
                        window.location.href = response.redirect_url;
                    }

                    if (response.message === 'Validation failed') {
                        if (response.errors.includes('Username')) {
                            $('#usernameError').text('Username is already taken');
                        } else {
                            $('#usernameError').text('');
                        }
                        if (response.errors.includes('Contact Number')) {
                            $('#contactError').text('Contact Number is already taken');
                        } else {
                            $('#contactError').text('');
                        }
                        if (response.errors.includes('Email')) {
                            $('#emailError').text('Email is already taken');
                        } else {
                            $('#emailError').text('');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    console.log(error);
                    if (xhr.status === 422) {
                        var response = xhr.responseJSON;
                        if (response.errors) {
                            // Display validation errors to the user
                            if (response.errors.includes('Username')) {
                                $('#usernameError').text('Username is already taken');
                            } else {
                                $('#usernameError').text('');
                            }
                            if (response.errors.includes('Contact Number')) {
                                $('#contactError').text('Contact Number is already taken');
                            } else {
                                $('#contactError').text('');
                            }
                            if (response.errors.includes('Email')) {
                                $('#emailError').text('Email is already taken');
                            } else {
                                $('#emailError').text('');
                            }
                        }
                    } else {
                        // Handle other types of errors
                        console.log('An error occurred:', error);
                    }
                }

            })
            }
        })
    })
</script>
@include('partials.footer')
