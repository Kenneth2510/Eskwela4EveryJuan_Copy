@include('partials.header')

    <section class="flex flex-row w-full text-sm lg:h-screen bg-mainwhitebg">
        @include('partials.instructorNav')

        @include('partials.learnerSidebar')

        {{-- MAIN --}}
        <section class="relative w-full mx-2 overflow-auto shadow-lg text-darthmouthgreen pt-[50px] h-auto">
            <button class="w-8 h-8 m-2">
                <svg xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 -960 960 960" width="24"><path d="m313-440 224 224-57 56-320-320 320-320 57 56-224 224h487v80H313Z"/></svg>
            </button>

            <h1 class="mb-4 text-lg font-semibold text-center">Learner Settings</h1>

            
            <div class="flex flex-col items-center justify-center mb-4">
                <div class="w-20 h-20 bg-teal-500 rounded-full">
                  
                    <img class="w-20 h-20 rounded-full" src="{{ asset('storage/' . $learner->profile_picture) }}
                    " alt="Profile Picture">
              </div>
               

                <h1 class="text-lg font-medium">{{ $learner->learner_fname }} {{ $learner->learner_lname }} </h1>
                
                <h3 class="mx-3 text-lg font-semibold text-center">Account Status: 
                    @if ($learner->status == 'Approved')
                    <div id="status" class="mx-1 text-lg text-center bg-green-500 py-auto w-28 rounded-xl">Approved</div>
                    @elseif ($learner->status == 'Rejected')
                    <div id="status" class="mx-1 text-lg text-center bg-red-500 py-auto w-28 rounded-xl">Rejected</div>
                    @else 
                    <div id="status" class="mx-1 text-lg text-center bg-yellow-300 py-auto w-28 rounded-xl">pending</div>
                    @endif
                </h3>
                  

                <div id="profilePicturePopup" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-gray-800 bg-opacity-50">
                    <div class="p-6 bg-white rounded-lg shadow-lg w-96">
                        <h1 class="mb-4 text-lg font-semibold">Upload Profile Picture</h1>
                        
                        <form id="profilePictureForm" enctype="multipart/form-data" action="{{ url('/learner/update_profile') }}" method="POST">
                            @method('PUT')
                            @csrf
                            <!-- Add the hidden input field for the method -->
                            <input type="hidden" name="_method" value="PUT">
                            <div class="mb-4">
                                <input type="file" name="profile_picture" id="profile_picture" class="">
                                <label for="profile_picture" class="px-4 py-2 text-white bg-blue-500 rounded-lg cursor-pointer hover:bg-blue-600">
                                    Select Image
                                </label>
                                @error('profile_picture')
                                    <p class="p-1 mt-2 text-xs text-red-500">
                                        {{$message}}
                                    </p>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <button type="submit" class="px-4 py-2 text-white bg-green-500 rounded-lg hover:bg-green-600">
                                    Upload
                                </button>
                            </div>
                        </form>
                        
                        
                        <button id="closePopup" class="text-sm text-gray-600 cursor-pointer hover:text-gray-800">Close</button>
                    </div>
                </div>

                
                <button id="updatePictureBtn" type="button" class="underline text-darthmouthgreen">Update Picture</button>

            </div>


                <form class="pb-4 mx-4 text-sm text-black" action="{{ url('/learner/settings') }}" method="POST">
                    @method('PUT')
                    @csrf

                <div class="flex flex-row items-center justify-start w-full h-10 px-2 my-2 border-2 rounded shadow-lg cursor-pointer border-seagreen" id="showLearnerPersonal">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M440-440H200v-80h240v-240h80v240h240v80H520v240h-80v-240Z"/></svg>
                    <h1>Personal Information</h1>
                </div>
                
                <div class="hidden" id="learnerPersonal">
                    <div class="flex flex-col">
                        <div class="IN-FORM-CTNR">
                            <label for="learner_fname">Firstname:</label>
                            <input class="IN-V-INP" type="text" name="learner_fname" id="learner_fname" value="{{ $learner->learner_fname }}" disabled>
                            @error('fname')
                        <p class="p-1 mt-2 text-lg text-red-500">
                            {{$message}}
                        </p>
                        @enderror
                        </div>
                        <div class="IN-FORM-CTNR">
                            <label for="learner_lname">Lastname:</label>
                            <input class="IN-V-INP" type="text" name="learner_lname" id="learner_lname" value="{{ $learner->learner_lname }}" disabled>
                            @error('learner_lname')
                        <p class="p-1 mt-2 text-lg text-red-500">
                            {{$message}}
                        </p>
                        @enderror
                        </div>
                    </div>
                    
                    <div>
                        <div class="IN-FORM-CTNR">
                            <label for="learner_bday">Birthday:</label>
                            <input class="IN-V-INP" type="date" name="learner_bday" id="learner_bday" value="{{ $learner->learner_bday }}" disabled>
                            @error('learner_bday')
                        <p class="p-1 mt-2 text-lg text-red-500">
                            {{$message}}
                        </p>
                        @enderror
                        </div>
                        
                        <div class="IN-FORM-CTNR">
                            <label for="learner_gender">Gender</label>
                            <select class="IN-V-INP" name="learner_gender" id="learner_gender" disabled>
                                <option value="" {{ $learner->learner_gender == "" ? 'selected': '' }} disabled>-- select an option --</option>
                                <option value="Male" {{ $learner->learner_gender == "Male" ? 'selected': '' }} >Male</option>
                                <option value="Female" {{ $learner->learner_gender == "Female" ? 'selected': '' }} >Female</option>
                                <option value="Others" {{ $learner->learner_gender == "Others" ? 'selected': '' }} >Preferred not to say</option>
                            </select>
                            @error('learner_gender')
                        <p class="p-1 mt-2 text-lg text-red-500">
                            {{$message}}
                        </p>
                        @enderror
                        </div>
                    </div>

                    <div class="IN-FORM-CTNR">
                        <label for="learner_email">Email:</label>
                        <input class="IN-V-INP" type="email" name="learner_email" id="learner_email" value="{{ $learner->learner_email }}" disabled>
                        @error('learner_email')
                        <p class="p-1 mt-2 text-lg text-red-500">
                            {{$message}}
                        </p>
                        @enderror
                    </div>
                    <div class="IN-FORM-CTNR">
                        <label for="learner_contactno">Contact Number:</label>
                        <input class="IN-V-INP" type="text" name="learner_contactno" id="learner_contactno" value="{{ $learner->learner_contactno }}" disabled>
                        @error('learner_contactno')
                        <p class="p-1 mt-2 text-lg text-red-500">
                            {{$message}}
                        </p>
                        @enderror
                    </div>
                </div>

                <div class="flex flex-row items-center justify-start w-full h-10 px-2 my-2 border-2 rounded shadow-lg cursor-pointer border-seagreen" id="showLearnerBusiness">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M440-440H200v-80h240v-240h80v240h240v80H520v240h-80v-240Z"/></svg>
                    <h1>Edit Business Information</h1>
            </div>
            
            <div class="hidden" id="learnerBusiness">
                <div class="IN-FORM-CTNR">
                    <label for="business_name">Business Name:</label>
                    <input class="IN-V-INP" type="text" name="business_name" id="business_name" value="{{ $business->business_name }}" disabled>
                    @error('business_name')
                        <p class="p-1 mt-2 text-lg text-red-500">
                            {{$message}}
                        </p>
                        @enderror
                </div>
                <div class="IN-FORM-CTNR">
                    <label for="bplo_account_number">Account Number:</label>
                    <input class="IN-V-INP" type="text" name="bplo_account_number" id="bplo_account_number" value="{{ $business->bplo_account_number }}" disabled>
                    @error('bplo_account_numnber')
                        <p class="p-1 mt-2 text-lg text-red-500">
                            {{$message}}
                        </p>
                        @enderror
                </div>
                <div class="IN-FORM-CTNR">
                    <label for="business_address">Business Address:</label>
                    <input class="IN-V-INP" type="text" name="business_address" id="business_address" value="{{ $business->business_address }}" disabled>
                    @error('business_address')
                        <p class="p-1 mt-2 text-lg text-red-500">
                            {{$message}}
                        </p>
                        @enderror
                </div>
                <div class="IN-FORM-CTNR">
                    <label for="business_owner_name">Business Owner:</label>
                    <input class="IN-V-INP" type="text" name="business_owner_name" id="business_owner_name" value="{{ $business->business_owner_name }}" disabled>
                    @error('business_owner_name')
                        <p class="p-1 mt-2 text-lg text-red-500">
                            {{$message}}
                        </p>
                        @enderror
                </div>
                <div class="IN-FORM-CTNR">
                    <label for="business_category">Business Category:</label>
                    <input class="IN-V-INP" type="text" name="business_category" id="business_category" value="{{ $business->business_category }}" disabled>
                    @error('business_category')
                        <p class="p-1 mt-2 text-lg text-red-500">
                            {{$message}}
                        </p>
                        @enderror
                </div>
            </div>

                <div class="flex flex-row items-center justify-start w-full h-10 px-2 my-2 border-2 rounded shadow-lg cursor-pointer border-seagreen" id="showLearnerLogin">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M440-440H200v-80h240v-240h80v240h240v80H520v240h-80v-240Z"/></svg>
                        <h1>Login Information</h1>
                </div>

                <div class="hidden" id="learnerLogin">
                    <div class="IN-FORM-CTNR">
                        <label for="learner_username">Username:</label>
                        <input class="IN-V-INP" type="text" name="learner_username" id="learner_username" value="{{ $learner->learner_username }}" disabled>
                        @error('learner_username')
                        <p class="p-1 mt-2 text-lg text-red-500">
                            {{$message}}
                        </p>
                        @enderror
                    </div>
                    <div class="IN-FORM-CTNR">
                        <label for="password">Password:</label>
                        <input class="IN-V-INP" type="password" name="old_password" id="password" value="{{ $learner->password }}" disabled>
                    </div>
                    <div id="password_confirmForm" class="hidden IN-FORM-CTNR">
                        <label for="password_confirmation">Confirm Password:</label>
                        <input class="IN-V-INP" type="password" name="password_confirmation" id="" required>
                        @error('password_confirmation')
                        <p class="p-1 mt-2 text-lg text-red-500">
                            {{$message}}
                        </p>
                        @enderror
                    </div>
                </div>
                
                
                
                    
                    
                    <div class="flex justify-end h-auto my-10 text-black place-items-end" >
                        <x-forms.primary-button color="amber" name="Update" type="button" id="editBtn"/>
                        <x-forms.primary-button color="red" name="Cancel" type="button" class="hidden bg-red-400 hover:bg-red-500" id="cancelBtn"/>
                        <x-forms.primary-button color="green" name="Save Changes" class="hidden bg-green-400 hover:bg-green-500" id="updateBtn"/>
                        {{-- <button type="button" class="flex flex-row items-center justify-center w-24 h-10 rounded-lg bg-amber-400 hover:bg-amber-500" id="editBtn">
                            Update
                        </button>
                        <a href="" id="cancelBtn" class="flex flex-row items-center justify-center hidden w-24 h-10 mx-2 bg-red-500 rounded-lg hover:bg-red-600">Cancel</a>
                        <button type="submit" class="flex flex-row items-center justify-center hidden w-24 h-10 mx-2 bg-green-500 rounded-lg hover:bg-green-600" id="updateBtn">
                            Save Changes
                        </button> --}}
                        
                    </div>
                </div>
            </form>
        </section>
    </section>


@include('partials.footer')
