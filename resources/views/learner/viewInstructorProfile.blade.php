@extends('layouts.learner_layout')

@section('content')
    {{-- MAIN --}}
<section class="w-full h-screen md:w-3/4 lg:w-10/12">
    <div class="h-full px-2 py-4 pt-24 overflow-hidden overflow-y-scroll rounded-lg shadow-lg md:pt-6">
        <a href="{{ back()->getTargetUrl() }}" class="w-8 h-8 m-2">
            <svg xmlns="http://www.w3.org/2000/svg" height="25" viewBox="0 -960 960 960" width="24"><path d="m313-440 224 224-57 56-320-320 320-320 57 56-224 224h487v80H313Z"/></svg>
        </a>
        <div class="flex flex-col space-y-2 lg:flex-row" id="upper_container">

            <div class="flex flex-col items-center justify-start bg-white rounded-lg shadow-lg lg:w-3/12" id="upper_left_container">
                <div class="relative flex flex-col items-center justify-start">
                    <img class="z-0 w-16 h-16 rounded-full lg:h-40 lg:w-40" src="{{ asset('storage/' . $instructor->profile_picture) }}" alt="Profile Picture">
                </div>

                <div class="" id="name_area">
                    <h1 class="text-2xl font-semibold text-center">{{$instructor->instructor_fname}} {{$instructor->instructor_lname}}</h1>
                </div>

                <div class="text-center " id="account_status_area">
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

                <div class="text-center " id="email_area">
                    <h1 class="text-xl">Email</h1>
                    <h2 class="text-md">{{$instructor->instructor_email}}</h2>

                    <a href="{{ url('/learner/message') }}?email={{ $instructor->instructor_email }}&type=Instructor" class="px-5 py-3 mt-10 text-lg text-white bg-darthmouthgreen hover:border-2 hover:bg-white hover:border-darthmouthgreen hover:text-darthmouthgreen rounded-xl">Send Message</a>
                </div>
            </div> 

            
            <div class="h-full lg:w-9/12" id="upper_right_container">
                <div class="w-full px-5 py-10 bg-white shadow-lg rounded-xl" id="upper_right_1">
                    <h1 class="text-4xl font-semibold text-darthmouthgreen">User Details</h1>

                    <hr class="my-6 border-t-2 border-gray-300">

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
        
                </div>
                
                <div class="w-full px-5 py-10 mt-5 bg-white shadow-lg rounded-xl" id="courseProgress">
                    <h1 class="text-4xl font-semibold text-darthmouthgreen">Courses Managed</h1>

                    <hr class="my-6 border-t-2 border-gray-300">

                    <table class="w-full">
                        <thead>
                            <th>Course Name</th>
                            <th>Learners Enrolled</th>
                        </thead>
                        <tbody>
                            @foreach ($courses as $course)
                            <tr>
                                <td class="py-5">{{$course->course_name}}</td>
                                <td>{{$course->learner_count}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
            
    </div>
</section>
@endsection
