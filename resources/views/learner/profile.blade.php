@extends('layouts.learner_layout')

@section('content')  
    {{-- MAIN --}}
    <section class="w-full h-screen md:w-3/4 lg:w-10/12">
        <div class="h-full px-2 py-4 pt-24 rounded-lg shadow-lg md:overflow-hidden md:overflow-y-scroll md:pt-0">

            <div class="py-4 space-y-2 lg:flex lg:space-y-0 lg:space-x-2" id="upper_container">

                <div class="flex flex-col items-center justify-start h-full py-4 bg-white rounded-lg shadow-lg lg:w-3/12" id="upper_left_container">
                    <div class="relative flex flex-col items-center justify-start"  style="margin:0 auto; padding: auto;">
                        <img class="z-0 w-40 h-40 bg-red-500 rounded-full" src="{{ asset('storage/' . $learner->profile_picture) }}" alt="Profile Picture">
                        <button id="update_profile_photo_btn" style="position: absolute; bottom: -6px; right: 10px;" class="w-12 h-12 text-white rounded-full z-5 bg-darthmouthgreen hover:bg-white hover:border-darthmouthgreen hover:border-2 hover:text-darthmouthgreen"><i class="fa-solid fa-camera"></i></button>
                    </div>

                    <div class="mt-10" id="name_area">
                        <h1 class="text-2xl font-semibold text-center">{{$learner->learner_fname}} {{$learner->learner_lname}}</h1>
                    </div>

                    <div class="mt-5 text-center" id="account_status_area">
                        <h1 class="text-xl">LEARNER</h1>
                        {{-- <h1 class="text-xl">ID: 1</h1> --}}

                        @if ($learner->status == 'Approved')
                        <div class="px-5 py-2 text-white bg-darthmouthgreen rounded-xl">Approved</div>
                        @elseif ($learner->status == 'Pending')
                        <div class="px-5 py-2 text-white bg-yellow-600 rounded-xl">Pending</div>
                        @else
                        <div class="px-5 py-2 text-white bg-red-500 rounded-xl">Rejected</div>
                        @endif
                    </div>

                    <div class="mt-10 text-center" id="email_area">
                        <h1 class="text-xl">Email</h1>
                        <h2 class="text-md">{{$learner->learner_email}}</h2>

                        {{-- <button class="px-5 py-3 text-lg text-white bg-darthmouthgreen hover:border-2 hover:bg-white hover:border-darthmouthgreen hover:text-darthmouthgreen rounded-xl">Send Message</button> --}}
                    </div>
                </div> 

                
                <div class="h-full lg:w-9/12" id="upper_right_container">
                    <div class="w-full px-5 py-10 bg-white shadow-lg rounded-xl" id="upper_right_1">
                        <h1 class="text-4xl font-semibold text-darthmouthgreen">User Details</h1>

                        <hr class="my-6 border-t-2 border-gray-300">
                        {{-- <form id="user_info_form" enctype="multipart/form-data" action="{{ url('/learner/profile/update_user_info') }}" method="POST">
                            @method('PUT')
                            @csrf --}}
                        <div class="flex w-full mt-5" id="userInfo">
                            
                            <div class="w-1/2 mx-2" id="userInfo_left">
                                <div class="mt-3" id="firstNameArea">
                                    <label for="learner_fname">First Name</label><br>
                                    <input class="w-full h-12 px-5 py-1 border-2 rounded-lg border-darthmouthgreen" type="text" name="learner_fname" id="learner_fname" value="{{$learner->learner_fname}}" disabled>
                                    <span id="firstNameError" class="text-red-500"></span>
                                </div>
                                <div class="mt-3" id="bdayArea">
                                    <label for="learner_bday ">Birthday</label><br>
                                    <input class="w-full h-12 px-5 py-1 border-2 rounded-lg border-darthmouthgreen" type="date" name="learner_bday" id="learner_bday" value="{{$learner->learner_bday}}" disabled>
                                    <span id="bdayError" class="text-red-500"></span>
                                </div>
                                <div class="mt-3" id="contactArea">
                                    <label for="learner_contactno">Contact Number</label><br>
                                    <input class="w-full h-12 px-5 py-1 border-2 rounded-lg border-darthmouthgreen" type="text" maxlength="11" name="learner_contactno" id="learner_contactno" value="{{$learner->learner_contactno}}" disabled>
                                </div>
                            </div>
                            <div class="w-1/2 mx-2" id="userInfo_right">
                                <div class="mt-3" id="lastNameArea">
                                    <label for="learner_lname">Last Name</label><br>
                                    <input class="w-full h-12 px-5 py-1 border-2 rounded-lg border-darthmouthgreen" type="text" name="learner_lname" id="learner_lname" value="{{$learner->learner_lname}}" disabled>
                                    <span id="lastNameError" class="text-red-500"></span>
                                </div>
                                <div class="mt-3" id="genderArea">
                                    <label for="learner_gender">Gender</label><br>
                                    <select class="w-full h-12 px-5 py-1 border-2 rounded-lg border-darthmouthgreen" name="learner_gender" id="learner_gender" disabled>
                                        <option value="" {{$learner->learner_gender == "" ? 'selected' : ''}}>-- select an option --</option>
                                        <option value="Male" {{$learner->learner_gender == "Male" ? 'selected' : ''}}>Male</option>
                                        <option value="Female" {{$learner->learner_gender == "Female" ? 'selected' : ''}}>Female</option>
                                        <option value="Others" {{$learner->learner_gender == "Others" ? 'selected' : ''}}>Preferred not to say</option>
                                    </select>
                                    <span id="genderError" class="text-red-500"></span>
                                </div>
                                <div class="mt-3" id="emailArea">
                                    <label for="learner_email">Email Address</label><br>
                                    <input class="w-full h-12 px-5 py-1 border-2 rounded-lg border-darthmouthgreen" type="email" name="learner_email" id="learner_email" value="{{$learner->learner_email}}" disabled>
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

                        <div class="w-full px-5 py-10 mt-5 bg-white shadow-lg rounded-xl" id="upper_right_2">
                            <h1 class="text-4xl font-semibold text-darthmouthgreen">Business Details</h1>

                            <hr class="my-6 border-t-2 border-gray-300">
                            {{-- <form id="business_info_form" enctype="multipart/form-data" action="{{ url('/learner/update_business_info') }}" method="POST">
                                @method('PUT')
                                @csrf --}}
                            <div class="mt-3" id="businessNameArea">
                                <label for="business_name">Business Name</label><br>
                                <input class="w-full h-12 px-5 py-1 border-2 rounded-lg border-darthmouthgreen" type="text" name="business_name" id="business_name" value="{{$business->business_name}}" disabled>
                                <span id="businessNameError" class="text-red-500"></span>
                            </div>

                            <div class="mt-3" id="businessAddressArea">
                                <label for="business_address">Business Address</label><br>
                                <input class="w-full h-12 px-5 py-1 border-2 rounded-lg border-darthmouthgreen" type="text" name="business_address" id="business_address" value="{{$business->business_address}}" disabled>
                                <span id="businessAddressError" class="text-red-500"></span>
                            </div>

                            <div class="mt-3" id="businessOwnerArea">
                                <label for="business_owner_name">Business Owner Name</label><br>
                                <input class="w-full h-12 px-5 py-1 border-2 rounded-lg border-darthmouthgreen" type="text" name="business_owner_name" id="business_owner_name" value="{{$business->business_owner_name}}" disabled>
                                <span id="businessOwnerNameError" class="text-red-500"></span>
                            </div>

                            <div class="mt-3" id="bplo_account_numberArea">
                                <label for="bplo_account_number">BPLO Account Number</label><br>
                                <input class="w-full h-12 px-5 py-1 border-2 rounded-lg border-darthmouthgreen" maxlength="13" type="text" name="bplo_account_number" id="bplo_account_number" value="{{$business->bplo_account_number}}" disabled>
                            </div>

                            <div class="flex justify-between w-full">
                                                        
                                <div class="w-full mt-3 mr-2" id="business_categoryArea">
                                    <label for="business_category">Business Category</label><br>
                                    <select class="w-full h-12 px-5 py-1 border-2 rounded-lg border-darthmouthgreen" name="business_category" id="business_category" disabled>
                                        <option value="" {{$business->business_category == "" ? 'selected' : ''}}>-- select an option --</option>
                                        <option value="Micro" {{$business->business_category == "Micro" ? 'selected' : ''}}>Micro</option>
                                        <option value="Small" {{$business->business_category == "Small" ? 'selected' : ''}}>Small</option>
                                        <option value="Medium" {{$business->business_category == "Medium" ? 'selected' : ''}}>Medium</option>
                                    </select>
                                    <span id="businessCategoryError" class="text-red-500"></span>
                                </div>

                                <div class="w-full mt-3 ml-2" id="business_classificationArea">
                                    <label for="business_classification">Business Classification</label><br>
                                    <select class="w-full h-12 px-5 py-1 border-2 rounded-lg border-darthmouthgreen" name="business_classification" id="business_classification" disabled>
                                        <option value="" {{$business->business_classification == "" ? 'selected' : ''}}>-- select an option --</option>
                                        <option value="Retail" {{$business->business_classification == "Retail" ? 'selected' : ''}}>Retail</option>
                                        <option value="Wholesale" {{$business->business_classification == "Wholesale" ? 'selected' : ''}}>Wholesale</option>
                                        <option value="Financial Services" {{$business->business_classification == "Financial Services" ? 'selected' : ''}}>Financial Services</option>
                                        <option value="Real Estate" {{$business->business_classification == "Real Estate" ? 'selected' : ''}}>Real Estate</option>
                                        <option value="Transportation and Logistics" {{$business->business_classification == "Transportation and Logistics" ? 'selected' : ''}}>Transportation and Logistics</option>
                                        <option value="Technology" {{$business->business_classification == "Technology" ? 'selected' : ''}}>Technology</option>
                                        <option value="Healthcare" {{$business->business_classification == "Healthcare" ? 'selected' : ''}}>Healthcare</option>
                                        <option value="Education and Training" {{$business->business_classification == "Education and Training" ? 'selected' : ''}}>Education and Training</option>
                                        <option value="Entertainment and Media" {{$business->business_classification == "Entertainment and Media" ? 'selected' : ''}}>Entertainment and Media</option>
                                        <option value="Hospitality and Tourism" {{$business->business_classification == "Hospitality and Tourism" ? 'selected' : ''}}>Hospitality and Tourism</option>

                                    </select>
                                    <span id="businessClassificationError" class="text-red-500"></span>
                                </div>
                            </div>

                            <div class="mt-3" id="business_descriptionArea">
                                <label for="business_description">Business Description</label><br>
                                <textarea name="business_description" class="w-full px-5 py-1 border-2 rounded-lg h-36 border-darthmouthgreen" id="business_description" disabled>{{$business->business_description}}</textarea>
                                <span id="businessDescriptionError" class="text-red-500"></span>
                            </div>

                            <div class="flex justify-end w-full px-5 mt-5">
                                <button type="button" class="px-5 py-3 text-lg text-white bg-darthmouthgreen hover:border-2 hover:bg-white hover:border-darthmouthgreen hover:text-darthmouthgreen rounded-xl" id="edit_business_info_btn">edit</button>
                                <button type="button" class="hidden px-5 py-3 mx-2 text-lg text-white bg-darthmouthgreen hover:border-2 hover:bg-white hover:border-darthmouthgreen hover:text-darthmouthgreen rounded-xl" id="submit_business_info_btn">apply changes</button>
                                <button type="button" class="hidden px-5 py-3 text-lg text-white bg-gray-500 hover:border-2 hover:bg-white hover:border-gray-500 hover:text-gray-500 rounded-xl" id="cancel_business_info_btn">cancel</button>
                            </div>
                            {{-- </form> --}}
                        </div>

                        <div class="w-full px-5 py-10 mt-5 bg-white shadow-lg rounded-xl" id="upper_right_3">
                            <h1 class="text-4xl font-semibold text-darthmouthgreen">Account Details</h1>

                            <hr class="my-6 border-t-2 border-gray-300">
                            {{-- <form id="login_info_form" enctype="multipart/form-data" action="{{ url('/learner/update_login_info') }}" method="POST">
                                @method('PUT')
                                @csrf --}}
                            <div class="mt-3" id="learner_usernameArea">
                                <label for="learner_username">Username</label><br>
                                <input class="w-full h-12 px-5 py-1 border-2 rounded-lg border-darthmouthgreen" type="text" name="learner_username" id="learner_username" value="{{$learner->learner_username}}" disabled>
                            </div>

                            <div class="mt-3" id="learnerPasswordArea">
                                <label for="password">Password</label><br>
                                <input class="w-full h-12 px-5 py-1 border-2 border-gray-300 rounded-lg" type="text" name="password" id="password" disabled>
                            </div>
                            
                            <div class="hidden mt-3" id="new_passwordArea">
                                <label for="learnerNewPassword">New Password</label><br>
                                <input class="w-full h-12 px-5 py-1 border-2 border-gray-300 rounded-lg" type="password" name="learnerNewPassword" id="learnerNewPassword">
                                
                                <span id="newPasswordError" class="text-red-500"></span><br>
                                <span id="passwordRequirements" class="text-sm text-gray-500">Password must contain at least 8 characters, including uppercase, lowercase, numbers, and special characters.</span>
                            </div>
                            
                            <div id="passwordCheckbox" class="hidden mt-3">
                                <input type="checkbox" id="showNewPassword" class="mr-2">
                                <label for="showNewPassword" class="cursor-pointer">Show New Password</label>
                            </div>
                            
                            <div class="hidden mt-3" id="learnerPasswordConfirmArea">
                                <label for="learnerNewPasswordConfirm">Confirm New Password</label><br>
                                <input class="w-full h-12 px-5 py-1 border-2 border-gray-300 rounded-lg" type="password" name="learnerNewPasswordConfirm" id="learnerNewPasswordConfirm">
                                
                                <span id="newPasswordConfirmError" class="text-red-500"></span>
                            </div>
                            

                            <div class="hidden mt-3" id="securityCodeArea">
                                <label for="learner_security_code">Enter your Security Code</label><br>
                                <input class="w-full h-12 px-5 py-1 border-2 rounded-lg border-darthmouthgreen" type="password" maxlength="6" name="learner_security_code" id="learner_security_code">
                                
                                <span id="securityCodeError" class="text-red-500"></span>
                            </div>

                            <div class="flex justify-end w-full px-5 mt-5">
                                <button type="button" class="px-5 py-3 text-lg text-white bg-darthmouthgreen hover:border-2 hover:bg-white hover:border-darthmouthgreen hover:text-darthmouthgreen rounded-xl" id="edit_login_info_btn">change password</button>
                                <button type="button" class="hidden px-5 py-3 mx-2 text-lg text-white bg-darthmouthgreen hover:border-2 hover:bg-white hover:border-darthmouthgreen hover:text-darthmouthgreen rounded-xl" id="submit_login_info_btn">apply changes</button>
                                <button type="button" class="hidden px-5 py-3 text-lg text-white bg-gray-500 hover:border-2 hover:bg-white hover:border-gray-500 hover:text-gray-500 rounded-xl" id="cancel_login_info_btn">cancel</button>
                            </div>
                            {{-- </form> --}}
                        </div>

                        {{-- <div class="w-full px-5 py-10 mt-5 bg-white shadow-lg rounded-xl" id="courseProgress">
                            <h1 class="text-4xl font-semibold text-darthmouthgreen">Courses Progress</h1>

                            <hr class="my-6 border-t-2 border-gray-300">

                            <table class="w-full">
                                <thead>
                                    <th>Course Name</th>
                                    <th>Status</th>
                                    <th>Start Period</th>
                                    <th>Finish Period</th>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="py-5">HTML</td>
                                        <td>IN PROGRESS</td>
                                        <td>02/14/2024</td>
                                        <td>02/14/2024</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div> --}}

                        
                        {{-- <div class="w-full px-5 py-10 mt-5 bg-white shadow-lg rounded-xl" id="courseProgress">
                            <h1 class="text-4xl font-semibold text-darthmouthgreen">Your Credentials</h1>

                            <hr class="my-6 border-t-2 border-gray-300">

                            <a href="">sample</a>

                            <div class="flex justify-end w-full px-5 mt-5">
                                <button class="px-5 py-3 text-lg text-white bg-darthmouthgreen hover:border-2 hover:bg-white hover:border-darthmouthgreen hover:text-darthmouthgreen rounded-xl">Change File</button>
                            </div>
                        </div> --}}


                                                
                        {{-- <div class="w-full px-5 py-10 mt-5 bg-white shadow-lg rounded-xl" id="courseProgress">
                            <h1 class="text-4xl font-semibold text-darthmouthgreen">Courses Managed</h1>

                            <hr class="my-6 border-t-2 border-gray-300">

                            <table class="w-full">
                                <thead>
                                    <th>Course Name</th>
                                    <th>Status</th>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="py-5">HTML</td>
                                        <td>IN PROGRESS</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div> --}}

                    </div>
                </div>
            </div>
            
        </div>
    </section>
  @include('partials.chatbot')

    <div id="profilePicturePopup" class="fixed top-0 left-0 flex items-center justify-center hidden w-full h-full bg-gray-200 bg-opacity-75">
        <div class="modal-content bg-white p-4 rounded-lg shadow-lg w-[500px]">
            <div class="flex justify-end w-full">
                <button class="cancelUpdate">
                    <i class="text-xl fa-solid fa-xmark" style="color: #949494;"></i>
                </button>
            </div>
            <h2 class="mb-2 text-2xl font-semibold">Upload Profile Picture</h2>
            
            <form id="profilePictureForm" enctype="multipart/form-data" action="{{ url('/learner/profile/update_profile_photo') }}" method="POST">
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
@endsection
