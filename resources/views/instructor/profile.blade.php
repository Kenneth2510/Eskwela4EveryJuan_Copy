@include('partials.header')

<section class="flex flex-row w-full h-screen text-sm main-container bg-mainwhitebg md:text-base">

    @include('partials.instructorNav')
    @include('partials.instructorSidebar')

        
    {{-- MAIN --}}
    <section class="w-full px-2 pt-[70px] mx-2 mt-2 md:w-3/4 lg:w-9/12  overscroll-auto md:overflow-auto">
        <div class="px-3 pb-4 rounded-lg shadow-lg b">

            <div class="flex" id="upper_container">

                <div class="flex flex-col items-center justify-start w-3/12 h-full py-10 mx-5 bg-white rounded-lg shadow-lg" id="upper_left_container">
                    <div class="relative flex flex-col items-center justify-start"  style="margin:0 auto; padding: auto;">
                        <img class="z-0 w-40 h-40 rounded-full" src="{{ asset('storage/' . $instructor->profile_picture) }}" alt="Profile Picture">
                        <button id="update_profile_photo_btn" style="position: absolute; bottom: -6px; right: 10px;" class="w-12 h-12 text-white rounded-full z-5 bg-darthmouthgreen hover:bg-white hover:border-darthmouthgreen hover:border-2 hover:text-darthmouthgreen"><i class="fa-solid fa-camera"></i></button>
                    </div>

                    <div class="mt-10" id="name_area">
                        <h1 class="text-2xl font-semibold text-center">{{$instructor->instructor_fname}} {{$instructor->instructor_lname}}</h1>
                    </div>

                    <div class="mt-5 text-center" id="account_status_area">
                        <h1 class="text-xl">INSTRUCTOR</h1>
                        {{-- <h1 class="text-xl">ID: 1</h1> --}}

                        @if ($instructor->status == 'Approved')
                        <div class="px-5 py-2 text-white bg-darthmouthgreen rounded-xl">Approved</div>
                        @elseif ($instructor->status == 'Pending')
                        <div class="px-5 py-2 text-white bg-yellow-600 rounded-xl">Pending</div>
                        @else
                        <div class="px-5 py-2 text-white bg-red-500 rounded-xl">Rejected</div>
                        @endif
                    </div>

                    <div class="mt-10 text-center" id="email_area">
                        <h1 class="text-xl">Email</h1>
                        <h2 class="text-md">{{$instructor->instructor_email}}</h2>

                        {{-- <button class="px-5 py-3 text-lg text-white bg-darthmouthgreen hover:border-2 hover:bg-white hover:border-darthmouthgreen hover:text-darthmouthgreen rounded-xl">Send Message</button> --}}
                    </div>
                </div> 

                
                <div class="w-9/12 h-full" id="upper_right_container">
                    <div class="w-full px-5 py-10 bg-white shadow-lg rounded-xl" id="upper_right_1">
                        <h1 class="text-4xl font-semibold text-darthmouthgreen">User Details</h1>

                        <hr class="my-6 border-t-2 border-gray-300">
                        {{-- <form id="user_info_form" enctype="multipart/form-data" action="{{ url('/instructor/profile/update_user_info') }}" method="POST">
                            @method('PUT')
                            @csrf --}}
                        <div class="flex w-full mt-5" id="userInfo">
                            
                            <div class="w-1/2 mx-2" id="userInfo_left">
                                <div class="mt-3" id="firstNameArea">
                                    <label for="instructor_fname">First Name</label><br>
                                    <input class="w-full h-12 px-5 py-1 border-2 rounded-lg border-darthmouthgreen" type="text" name="instructor_fname" id="instructor_fname" value="{{$instructor->instructor_fname}}" disabled>
                                    <span id="firstNameError" class="text-red-500"></span>
                                </div>
                                <div class="mt-3" id="bdayArea">
                                    <label for="instructor_bday ">Birthday</label><br>
                                    <input class="w-full h-12 px-5 py-1 border-2 rounded-lg border-darthmouthgreen" type="date" name="instructor_bday" id="instructor_bday" value="{{$instructor->instructor_bday}}" disabled>
                                    <span id="bdayError" class="text-red-500"></span>
                                </div>
                                <div class="mt-3" id="contactArea">
                                    <label for="instructor_contactno">Contact Number</label><br>
                                    <input class="w-full h-12 px-5 py-1 border-2 rounded-lg border-darthmouthgreen" type="text" maxlength="11" name="instructor_contactno" id="instructor_contactno" value="{{$instructor->instructor_contactno}}" disabled>
                                </div>
                            </div>
                            <div class="w-1/2 mx-2" id="userInfo_right">
                                <div class="mt-3" id="lastNameArea">
                                    <label for="instructor_lname">Last Name</label><br>
                                    <input class="w-full h-12 px-5 py-1 border-2 rounded-lg border-darthmouthgreen" type="text" name="instructor_lname" id="instructor_lname" value="{{$instructor->instructor_lname}}" disabled>
                                    <span id="lastNameError" class="text-red-500"></span>
                                </div>
                                <div class="mt-3" id="genderArea">
                                    <label for="instructor_gender">Gender</label><br>
                                    <select class="w-full h-12 px-5 py-1 border-2 rounded-lg border-darthmouthgreen" name="instructor_gender" id="instructor_gender" disabled>
                                        <option value="" {{$instructor->instructor_gender == "" ? 'selected' : ''}}>-- select an option --</option>
                                        <option value="Male" {{$instructor->instructor_gender == "Male" ? 'selected' : ''}}>Male</option>
                                        <option value="Female" {{$instructor->instructor_gender == "Female" ? 'selected' : ''}}>Female</option>
                                        <option value="Others" {{$instructor->instructor_gender == "Others" ? 'selected' : ''}}>Preferred not to say</option>
                                    </select>
                                    <span id="genderError" class="text-red-500"></span>
                                </div>
                                <div class="mt-3" id="emailArea">
                                    <label for="instructor_email">Email Address</label><br>
                                    <input class="w-full h-12 px-5 py-1 border-2 rounded-lg border-darthmouthgreen" type="email" name="instructor_email" id="instructor_email" value="{{$instructor->instructor_email}}" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-end w-full px-5 mt-5">
                            <button type="button" class="px-5 py-3 text-lg text-white bg-darthmouthgreen hover:border-2 hover:bg-white hover:border-darthmouthgreen hover:text-darthmouthgreen rounded-xl" id="edit_user_info_btn">edit</button>
                            <button type="button" class="hidden px-5 py-3 mx-2 text-lg text-white bg-darthmouthgreen hover:border-2 hover:bg-white hover:border-darthmouthgreen hover:text-darthmouthgreen rounded-xl" id="submit_user_info_btn">apply changes</button>
                            <button type="button" class="hidden px-5 py-3 text-lg text-white bg-gray-500 hover:border-2 hover:bg-white hover:border-gray-500 hover:text-gray-500 rounded-xl" id="cancel_user_info_btn">cancel</button>
                        </div>
                        {{-- </form> --}}
                    </div>

                    

                        <div class="w-full px-5 py-10 mt-5 bg-white shadow-lg rounded-xl" id="upper_right_3">
                            <h1 class="text-4xl font-semibold text-darthmouthgreen">Account Details</h1>

                            <hr class="my-6 border-t-2 border-gray-300">
     
                            <div class="mt-3" id="instructor_usernameArea">
                                <label for="instructor_username">Username</label><br>
                                <input class="w-full h-12 px-5 py-1 border-2 rounded-lg border-darthmouthgreen" type="text" name="instructor_username" id="instructor_username" value="{{$instructor->instructor_username}}" disabled>
                            </div>

                            <div class="mt-3" id="instructorPasswordArea">
                                <label for="password">Password</label><br>
                                <input class="w-full h-12 px-5 py-1 border-2 border-gray-300 rounded-lg" type="text" name="password" id="password" disabled>
                            </div>
                            
                            <div class="hidden mt-3" id="new_passwordArea">
                                <label for="instructorNewPassword">New Password</label><br>
                                <input class="w-full h-12 px-5 py-1 border-2 border-gray-300 rounded-lg" type="password" name="instructorNewPassword" id="instructorNewPassword">
                                
                                <span id="newPasswordError" class="text-red-500"></span><br>
                                <span id="passwordRequirements" class="text-sm text-gray-500">Password must contain at least 8 characters, including uppercase, lowercase, numbers, and special characters.</span>
                            </div>
                            
                            <div id="passwordCheckbox" class="hidden mt-3">
                                <input type="checkbox" id="showNewPassword" class="mr-2">
                                <label for="showNewPassword" class="cursor-pointer">Show New Password</label>
                            </div>
                            
                            <div class="hidden mt-3" id="instructorPasswordConfirmArea">
                                <label for="instructorNewPasswordConfirm">Confirm New Password</label><br>
                                <input class="w-full h-12 px-5 py-1 border-2 border-gray-300 rounded-lg" type="password" name="instructorNewPasswordConfirm" id="instructorNewPasswordConfirm">
                                
                                <span id="newPasswordConfirmError" class="text-red-500"></span>
                            </div>
                            

                            <div class="hidden mt-3" id="securityCodeArea">
                                <label for="instructor_security_code">Enter your Security Code</label><br>
                                <input class="w-full h-12 px-5 py-1 border-2 rounded-lg border-darthmouthgreen" type="password" maxlength="6" name="instructor_security_code" id="instructor_security_code">
                                
                                <span id="securityCodeError" class="text-red-500"></span>
                            </div>

                            <div class="flex justify-end w-full px-5 mt-5">
                                <button type="button" class="px-5 py-3 text-lg text-white bg-darthmouthgreen hover:border-2 hover:bg-white hover:border-darthmouthgreen hover:text-darthmouthgreen rounded-xl" id="edit_login_info_btn">change password</button>
                                <button type="button" class="hidden px-5 py-3 mx-2 text-lg text-white bg-darthmouthgreen hover:border-2 hover:bg-white hover:border-darthmouthgreen hover:text-darthmouthgreen rounded-xl" id="submit_login_info_btn">apply changes</button>
                                <button type="button" class="hidden px-5 py-3 text-lg text-white bg-gray-500 hover:border-2 hover:bg-white hover:border-gray-500 hover:text-gray-500 rounded-xl" id="cancel_login_info_btn">cancel</button>
                            </div>
     
                        </div>

                        
                        <div class="w-full px-5 py-10 mt-5 bg-white shadow-lg rounded-xl" id="courseProgress">
                            <h1 class="text-4xl font-semibold text-darthmouthgreen">Your Credentials</h1>

                            <hr class="my-6 border-t-2 border-gray-300">
                            @if($instructor->instructor_credentials)
                            @php
                                $pathParts = explode('/', $instructor->instructor_credentials);
                                $filename = end($pathParts);
                                $fileurl = asset("storage/$instructor->instructor_credentials");
                            @endphp
                            <p class="text-lg">File: <a href="{{ $fileurl }}" target="_blank">{{$filename}}</a></p>
                            @else
                            <p class="text-lg">No file Uploaded</p>
                            @endif
                            {{-- <a href="">sample</a> --}}
                        </div>


                        <div class="w-full px-5 py-10 mt-5 bg-white shadow-lg rounded-xl" id="courseProgress">
                            <h1 class="text-4xl font-semibold text-darthmouthgreen">Courses Managed</h1>

                            <hr class="my-6 border-t-2 border-gray-300">

                            <table class="w-full">
                                <thead>
                                    <th>Course Name</th>
                                    <th>Course Approval Status</th>
                                    <th></th>
                                </thead>
                                <tbody>
                                    @foreach ($courses as $course)
                                    <tr>
                                        <td class="py-5">{{$course->course_name}}</td>
                                        <td>{{$course->course_status}}</td>
                                        <td>
                                            <a href="{{ url("/instructor/course/$course->course_id") }}" class="px-3 py-1 text-white bg-darthmouthgreen rounded-xl hover:bg-white hover:text-darthmouthgreen hover:border hover:border-darthmouthgreen">view</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>


                    </div>
                </div>
            </div>
            
        </div>
    </section>


    @include('partials.instructorProfile')
        
    </section>


    <div id="profilePicturePopup" class="fixed top-0 left-0 flex items-center justify-center hidden w-full h-full bg-gray-200 bg-opacity-75">
        <div class="modal-content bg-white p-4 rounded-lg shadow-lg w-[500px]">
            <div class="flex justify-end w-full">
                <button class="cancelUpdate">
                    <i class="text-xl fa-solid fa-xmark" style="color: #949494;"></i>
                </button>
            </div>
            <h2 class="mb-2 text-2xl font-semibold">Upload Profile Picture</h2>
            
            <form id="profilePictureForm" enctype="multipart/form-data" action="{{ url('/instructor/profile/update_profile_photo') }}" method="POST">
                @method('PUT')
                @csrf
                <!-- Add the hidden input field for the method -->
                <input type="hidden" name="_method" value="PUT">
                <div class="mb-4">
                    <input type="file" name="profile_picture" id="profile_picture" class=""><br>
                    <label for="profile_picture" class="px-4 py-2 text-white rounded-lg cursor-pointer bg-darthmouthgreen hover:border hover:border-darthmouthgreen hover:bg-white hover:text-darthmouthgreen">
                        Select Image
                    </label>
                    @error('profile_picture')
                        <p class="p-1 mt-2 text-xs text-red-500">
                            {{$message}}
                        </p>
                    @enderror
                </div>
                <div class="flex justify-center mt-5 mb-4">
                    <button type="submit" class="px-5 py-3 mx-1 text-white rounded-lg bg-darthmouthgreen hover:border hover:border-darthmouthgreen hover:bg-white hover:text-darthmouthgreen">Upload</button>
                    <button type="button" class="px-5 py-3 mx-1 text-white bg-red-500 rounded-lg cancelUpdate hover:bg-white hover:text-red-500 hover:border-2 hover:border-red-500">Cancel</button>
                </div>
            </form>
            
            
        </div>
    </div>


    <div id="loaderModal" class="fixed top-0 left-0 z-50 flex items-center justify-center hidden w-full h-full bg-gray-200 bg-opacity-75 ">
        <div class="flex flex-col items-center justify-center w-full h-screen p-4 bg-white rounded-lg shadow-lg modal-content md:h-1/3 lg:w-1/3">
            <span class="loading loading-spinner text-primary loading-lg"></span> 
                
            <p class="mt-5 text-xl text-darthmouthgreen">loading</p>  
        </div>
    </div>

@include('partials.footer')