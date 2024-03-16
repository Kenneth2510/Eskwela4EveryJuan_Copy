@extends('layouts.instructor_layout')

@section('content')
    {{-- MAIN --}}
    <section class="w-full h-screen md:w-3/4 lg:w-10/12">
        <div class="relative w-full h-full px-2 py-4 pt-24 overflow-hidden overflow-y-scroll rounded-lg shadow-lg md:pt-4">
            @php
                $mainBackgroundCol = '#00693e';
                $darkenedColor = '#00592e';
            @endphp

            <div class="page_content">
                <div style="background-color:{{$mainBackgroundCol}};" class="z-50 p-2 text-white rounded-xl">
                    <a href="{{ url("/instructor/course/content/$course->course_id") }}" class="my-2 bg-gray-300 rounded-full ">
                        <svg  xmlns="http://www.w3.org/2000/svg" height="30" viewBox="0 -960 960 960" width="24"><path d="M560-240 320-480l240-240 56 56-184 184 184 184-56 56Z"/></svg>
                    </a>
                    <h1 class="w-1/2 py-4 text-2xl font-bold md:text-4xl"><span class="">{{ $course->course_name }}</span></h1>
                    {{-- subheaders --}}
                    <div class="flex flex-col fill-mainwhitebg">
                    
                        <h1 class="w-1/2 py-4 text-lg "><span class="">{{ $lessonInfo->lesson_title }}</span></h1>
                    </div>
                </div>

                {{-- main content --}}
                <div class="px-2">
                    <div class="mt-1 text-gray-600 text-l">
                        <a href="{{ url('/instructor/courses') }}" class="">course ></a>
                        <a href="{{ url("/instructor/course/$course->course_id") }}">{{$course->course_name}} ></a>
                        <a href="{{ url("/instructor/course/content/$course->course_id") }}">content ></a>
                        <a href="">{{ $lessonInfo->lesson_title }}</a>
                    </div>
                    {{-- overview --}}
                    <div id="lesson_title_area" class="mb-4">
                        <div class="flex items-center justify-between pb-3 my-4 mt-5 border-b-2 border-seagreen">
                            <div class="relative flex items-center">
                                <svg class="absolute left-0 border-2 border-black rounded-full p-[2px]" xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 32 32">
                                    <path fill="currentColor" d="M19 10h7v2h-7zm0 5h7v2h-7zm0 5h7v2h-7zM6 10h7v2H6zm0 5h7v2H6zm0 5h7v2H6z"/>
                                    <path fill="currentColor" d="M28 5H4a2.002 2.002 0 0 0-2 2v18a2.002 2.002 0 0 0 2 2h24a2.002 2.002 0 0 0 2-2V7a2.002 2.002 0 0 0-2-2ZM4 7h11v18H4Zm13 18V7h11v18Z"/>
                                </svg>
                                <div id="lesson_title" class="p-2 ml-12 text-xl font-bold border-none md:py-4 md:text-2xl" contenteditable="false">{{ $lessonInfo->lesson_title }}</div>
                            </div>
                            <button id="edit_lesson_title" class="hidden">
                                <svg class="cursor-pointer" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                                    <path d="M80 0v-160h800V0H80Zm80-240v-150l362-362 150 150-362 362H160Zm80-80h36l284-282-38-38-282 284v36Zm477-326L567-796l72-72q11-12 28-11.5t28 11.5l94 94q11 11 11 27.5T789-718l-72 72ZM240-320Z"/>
                                </svg>
                            </button>
                            <div id="edit_lesson_btns" class="hidden text-right">
                                <button id="save_lesson_btn" data-lesson-id="{{$lessonInfo->lesson_id}}" data-course-id="{{$lessonInfo->course_id}}" data-topic_id="{{$lessonInfo->topic_id}}" data-syllabus-id="{{$lessonInfo->syllabus_id}}" class="px-5 py-3 m-1 text-white rounded-xl" style="background-color:{{$mainBackgroundCol}}" onmouseover="this.style.backgroundColor='{{$darkenedColor}}'" onmouseout="this.style.backgroundColor='{{$mainBackgroundCol}}'">
                                    Save
                                </button>
                                <button id="cancel_lesson_btn" class="px-5 py-3 m-1 text-white rounded-xl" style="background-color:{{$mainBackgroundCol}}" onmouseover="this.style.backgroundColor='{{$darkenedColor}}'" onmouseout="this.style.backgroundColor='{{$mainBackgroundCol}}'">
                                    Cancel
                                </button>
                            </div>
                        </div>
                        <div id="estimatedCompletionTime" class="">
                            <h1 class="my-5 text-xl font-semibold">Estimated Time to Finish</h1>
                            <label for="hours" class="mr-5 text-lg">Hours:</label>
                            <input type="text" id="hours" name="hours" class="w-1/12 py-1 text-center border border-darthmouthgreen rounded-xl" placeholder="0" value="{{ isset($formattedDuration) ? explode(':', $formattedDuration)[0] : '' }}" required>
                        
                            <label class="mx-5 text-lg" for="minutes">Minutes:</label>
                            <input type="text" id="minutes" class="w-1/12 py-1 text-center border border-darthmouthgreen rounded-xl" name="minutes" placeholder="0" value="{{ isset($formattedDuration) ? explode(':', $formattedDuration)[1] : '' }}" required>
                            <br>
                            <button id="saveEstTimeCompletion" class="px-5 py-3 mt-3 text-white bg-darthmouthgreen rounded-xl hover:ring-2 hover:ring-darthmouthgreen hover:text-darthmouthgreen hover:bg-white">Save</button>
                        </div>
                        
                        <hr class="my-6 border-t-2 border-gray-300">
                    </div>
                    
                    
                    
                    {{-- course --}}
                    <div class="mt-5">
                        @if ($lessonInfo->picture !== null)
                        <div id="lesson_img" class="flex justify-center w-full h-[400px] my-4 rounded-lg shadow">
                            <div class="w-full h-[400px] overflow-hidden rounded-lg">
                                <img src="{{ asset("storage/$lessonInfo->picture") }}" class="object-contain w-[1000px] h-[1000px]" alt="">
                            </div>
                        </div>
                        
                        
                        {{-- @if ($lessonInfo->picture !== null)
                        <div id="lesson_img" class="flex justify-center w-full h-[400px] my-4 rounded-lg shadow">
                            <div class="w-full h-[400px] overflow-hidden rounded-lg">
                                <img src="{{ asset("storage/$lessonInfo->picture") }}" class="object-contain w-full h-full" alt="">
                            </div>
                        </div> --}}
                        
                        
                        <div id="edit_lesson_picture_btns" style="position: relative; top: 75%;" class="flex justify-end hidden">
                            <button id="" data-lesson-id="{{$lessonInfo->lesson_id}}" data-course-id="{{$lessonInfo->course_id}}" data-topic_id="{{$lessonInfo->topic_id}}" data-syllabus-id="{{$lessonInfo->syllabus_id}}" class="flex px-5 py-3 mr-3 text-white add_lesson_picture_btn rounded-xl" style="background-color:{{$mainBackgroundCol}}" onmouseover="this.style.backgroundColor='{{$darkenedColor}}'" onmouseout="this.style.backgroundColor='{{$mainBackgroundCol}}'">
                                Change Photo
                            </button>
                        </div>
                        @else
                        
                        <div id="edit_lesson_picture_btns" style="position: relative; top: 75%;" class="flex justify-end hidden">
                            <button id="" data-lesson-id="{{$lessonInfo->lesson_id}}" data-course-id="{{$lessonInfo->course_id}}" data-topic_id="{{$lessonInfo->topic_id}}" data-syllabus-id="{{$lessonInfo->syllabus_id}}" class="flex px-5 py-3 mr-3 text-white add_lesson_picture_btn rounded-xl" style="background-color:{{$mainBackgroundCol}}" onmouseover="this.style.backgroundColor='{{$darkenedColor}}'" onmouseout="this.style.backgroundColor='{{$mainBackgroundCol}}'">
                                Add Photo
                            </button>
                        </div>
                        @endif
                    </div>



                    {{-- lesson content area --}}
                    <div id="main_content_area" class="">
                        @forelse ($lessonContent as $lesson)
                        <div data-content-order="{{$lesson->lesson_content_order}}" class="w-full px-10 my-2 mb-8 lesson_content_area">
                            <button class="hidden edit_lesson_content">
                                <svg class="cursor-pointer" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                                    <path d="M80 0v-160h800V0H80Zm80-240v-150l362-362 150 150-362 362H160Zm80-80h36l284-282-38-38-282 284v36Zm477-326L567-796l72-72q11-12 28-11.5t28 11.5l94 94q11 11 11 27.5T789-718l-72 72ZM240-320Z"/>
                                </svg>
                            </button>
                            
                            <input type="text" class="w-10/12 text-2xl font-bold border-none lesson_content_title_input" disabled name="lesson_content_title_input" id="" value="{{ $lesson->lesson_content_title }}">
                            
                            @if ($lesson->picture !== null)
                                <img src="{{ asset("storage/$lesson->picture") }}" class="object-contain w-[1000px] h-[1000px] my-10" alt="">
                            @else
                            @endif
                            
                            {{-- <p class="w-[80%] max-w-full min-w-full text-xl lesson_content_input_disp" style="white-space: pre-line">{{$lesson->lesson_content}}</p> --}}
                
                            <div class="px-5 mt-5 text-xl font-normal contentArea lesson_content_input_disp" style="white-space: pre-wrap">
                                {!! $lesson->lesson_content !!}
                            </div>         {{-- <textarea name="lesson_content_input" id="" class="hidden text-xl lesson_content_input w-[80%] min-w-[80%] max-w-[80%] h-[120px] resize-none" disabled>{{ $lesson->lesson_content }}</textarea> --}}
                            
                            @if ($lesson->video_url !== null)
                            <div id="lesson_content_url" class="flex justify-center w-full h-[400px] my-4 rounded-lg shadow">
                                <div class="url_embed_area w-full h-[400px] flex justify-center overflow-hidden rounded-lg">
                                    {!! $lesson->video_url !!}
                                </div>
                            </div>    
                            @else
                            @endif

                            <div class="flex justify-end hidden w-full edit_lesson_content_btns">
                                <button id="" class="px-5 py-3 mx-1 text-white save_lesson_content_btn rounded-xl" style="background-color:{{$mainBackgroundCol}}" onmouseover="this.style.backgroundColor='{{$darkenedColor}}'" onmouseout="this.style.backgroundColor='{{$mainBackgroundCol}}'">
                                    Save
                                </button>
                                <button id="" class="px-5 py-3 mx-1 text-white bg-red-600 delete_lesson_content_btn rounded-xl hover:bg-red-800">
                                    Delete
                                </button>
                                <button id="" class="px-5 py-3 mx-1 text-white cancel_lesson_content_btn rounded-xl" style="background-color:{{$mainBackgroundCol}}" onmouseover="this.style.backgroundColor='{{$darkenedColor}}'" onmouseout="this.style.backgroundColor='{{$mainBackgroundCol}}'">
                                    Cancel
                                </button>
                            </div>
                        </div>
                        @empty
                        <div class="my-2 mb-8">
                            {{-- <h1 class="text-lg font-medium">What is business?</h1> --}}
                            <p class="pl-4 text-justify">No Lesson content</p>
                        </div>
                        @endforelse
                    </div>

                    <button class="flex items-center hidden w-full py-2 mt-4 rounded-lg shadow-lg md:py-4 ring-2 ring-seagreen" id="lessonAddContent">
                        <svg class="mx-2" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M440-440H200v-80h240v-240h80v240h240v80H520v240h-80v-240Z"/></svg>
                        <h1>Add New Content</h1>
                    </button>

                    <div class="flex justify-center w-full">
                        <button id="editLessonBtn" data-course-id="{{$lessonInfo->course_id}}" data-syllabus-id="{{$lessonInfo->syllabus_id}}" data-topic_id="{{$lessonInfo->topic_id}}" style="background-color:{{$mainBackgroundCol}}" onmouseover="this.style.backgroundColor='{{$darkenedColor}}'"
                        onmouseout="this.style.backgroundColor='{{$mainBackgroundCol}}'" class="w-1/2 p-3 mx-1 mt-4 text-white rounded-lg shadow-lg bg-seagreen hover:bg-green-800 hover:text-white">
                            <h1>Edit</h1>
                        </button>
                        <div id="editBtns" class="flex justify-end hidden w-full">
                            <button id="cancelEditBtn" style="background-color:{{$mainBackgroundCol}}" onmouseover="this.style.backgroundColor='{{$darkenedColor}}'"
                            onmouseout="this.style.backgroundColor='{{$mainBackgroundCol}}'" class="w-1/2 p-3 mx-1 mt-4 text-white rounded-lg shadow-lg md:w-auto bg-seagreen hover:bg-green-800 hover:text-white">
                                <h1>Cancel</h1>
                            </button>
                            <button id="saveEditBtn" style="background-color:{{$mainBackgroundCol}}" onmouseover="this.style.backgroundColor='{{$darkenedColor}}'"
                            onmouseout="this.style.backgroundColor='{{$mainBackgroundCol}}'" class="w-1/2 p-3 mx-1 mt-4 text-white rounded-lg shadow-lg md:w-auto bg-seagreen hover:bg-green-800 hover:text-white" data-lesson-id="{{$lessonInfo->lesson_id}}" data-course-id="{{$lessonInfo->course_id}}" data-syllabus-id="{{$lessonInfo->syllabus_id}}" data-topic_id="{{$lessonInfo->topic_id}}">
                                <h1>Apply All Changes</h1>
                            </button>
                        </div>

                    </div>             
                </div>
            <div class="hidden">
                <!-- start-generate-pdf -->
                <h1 style="font-size: 3rem; font-weight: bold;">{{ $course->course_name }}</h1>
                <h3 style="font-size: 1.5rem; font-weight: semibold;">{{ $course->course_code }}</h3>
                <h3>{{ $course->course_difficulty }}</h3>
                <h3>{{ $course->course_status }}</h3>
                <hr>
                <hr>
                <br>

                <div style="position: relative; display: flex; align-items: center; border-bottom: 2px solid black;">
                    <svg style="position: absolute; left: 0; border: 2px solid black; border-radius: 50%; padding: 2px;" xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 32 32">
                        <path fill="currentColor" d="M19 10h7v2h-7zm0 5h7v2h-7zm0 5h7v2h-7zM6 10h7v2H6zm0 5h7v2H6zm0 5h7v2H6z"/>
                        <path fill="currentColor" d="M28 5H4a2.002 2.002 0 0 0-2 2v18a2.002 2.002 0 0 0 2 2h24a2.002 2.002 0 0 0 2-2V7a2.002 2.002 0 0 0-2-2ZM4 7h11v18H4Zm13 18V7h11v18Z"/>
                    </svg>
                    <div id="lesson_title" style="padding-left: 50px; font-size: 1.5rem; font-weight: bold; border: none;" contenteditable="false">{{ $lessonInfo->lesson_title }}</div>
                </div>
                {{-- <h1 style="margin-top: 1.25rem; font-size: 1.25rem; font-weight: semibold;">Estimated Time to Finish: {{ $formattedDuration }}</h1> --}}

                @if ($lessonInfo->picture !== null)
                    <div id="lesson_img" style="display: flex; justify-content: center; width: 100%; margin-top: 1rem; border-radius: 0.375rem; box-shadow: 0 0.25rem 0.75rem rgba(0, 0, 0, 0.1);">
                        <div style="width: 100%; height: 400px; overflow: hidden; border-radius: 0.375rem;">
                            <img src="{{ asset("storage/$lessonInfo->picture") }}" style="object-fit: contain; width: 100%; height: 100%;" alt="">
                        </div>
                    </div>
                @endif

                {{-- <img src="{{ asset('storage/' . $lessonInfo->picture) }}" alt="" width="250px" height="250px"> --}}

                <hr>
                <br>
                <br>
                @forelse ($lessonContent as $lesson)
                    <h4 style="font-size: 1.5rem; font-weight: bold; border: none;">{{ $lesson->lesson_content_title }}</h4>
                    @if ($lesson->picture !== null)
                        {{-- <img src="storage/app/public/{{$lesson->picture}}" alt="" width="250px" height="250px"> --}}
                        <img src="{{ asset("storage/$lesson->picture") }}" alt="">
                    @endif

                    <div style="padding: 0.625rem; margin-top: 1.25rem; font-size: 1rem; font-weight: normal; white-space: pre-wrap;">{!! $lesson->lesson_content !!}</div>
                @empty
                    <h5>No Content</h5>
                @endforelse
                <!-- end-generate-pdf -->
            </div>
        {{-- <div id="editLessonContentUrlModal" class="fixed top-0 left-0 flex items-center justify-center hidden w-full h-full bg-gray-200 bg-opacity-75">
            <div class="modal-content bg-white p-4 rounded-lg shadow-lg w-[750px]">
                <div class="flex justify-end w-full">
                    <button class="closeEditLessonContentUrlModal">
                        <i class="text-xl fa-solid fa-xmark" style="color: #949494;"></i>
                    </button>
                </div>
                <h2 class="mb-2 text-2xl font-semibold">Embed Video from Youtube</h2>
                <div class="mt-4">
                    <label for="insertEditLessonContentUrl" class="text-lg font-semibold">Enter Embed Code copied</label>
                    <input type="text" name="insertEditLessonContentUrl" id="insertEditLessonContentUrl" class="block w-full px-4 py-2 mt-2 border border-gray-300 rounded-md focus:ring focus:ring-seagreen focus:ring-opacity-50">
                </div>

                <div class="flex justify-center w-full mt-5">
                    <button id="confirmEditLessonContentUrlBtn" data-content-order="" data-lesson-id="{{$lessonInfo->lesson_id}}" class="px-4 py-2 mx-2 mt-4 text-white rounded bg-seagreen hover:bg-darkenedColor">Confirm</button>
                    <button id="" class="px-4 py-2 mx-2 mt-4 text-white bg-red-500 rounded closeEditLessonContentUrlModal">Cancel</button>
                </div>
            </div>
        </div> --}}

        </div>
</section>


<div id="pictureModal" class="fixed inset-0 z-[99] flex items-center justify-center hidden">
    <div class="z-50 w-11/12 mx-auto overflow-y-auto bg-white rounded shadow-lg modal-container md:max-w-md">
        <div class="px-6 py-4 text-left modal-content">
            <!-- Modal header -->
            <div class="flex items-center justify-between pb-3">
                <p class="text-2xl font-bold">Upload Picture</p>
                <button id="closeModal" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path d="M12.293 7.293a1 1 0 00-1.414 0L10 8.586 8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 001.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 000-1.414z"/>
                    </svg>
                </button>
            </div>
            <!-- Modal body -->
            <div class="mb-4">
                <!-- Your form for uploading pictures goes here -->
                <form id="pictureUploadForm" data-lesson-id="{{$lessonInfo->lesson_id}}" data-course-id="{{$lessonInfo->course_id}}" data-topic_id="{{$lessonInfo->topic_id}}" data-syllabus-id="{{$lessonInfo->syllabus_id}}" enctype="multipart/form-data" method="POST">
                    <input type="file" name="picture" id="lesson_title_picture" accept=".jpeg, .png, .jpg, .gif" />

                    <div class="flex justify-between mt-4">
                        <button type="submit" class="px-4 py-2 text-white bg-green-600 rounded hover:bg-green-900">Confirm</button>
                        <button id="cancelUpload" class="px-4 py-2 text-gray-700 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<div id="lesson_content_pictureModal" class="z-[99] fixed inset-0  flex items-center justify-center hidden">
    <div class="z-50 w-11/12 mx-auto overflow-y-auto bg-white rounded shadow-lg modal-container md:max-w-md">
        <div class="px-6 py-4 text-left modal-content">
            <!-- Modal header -->
            <div class="flex items-center justify-between pb-3">
                <p class="text-2xl font-bold">Upload Picture</p>
                <button id="closeModal_lesson_content_picture" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path d="M12.293 7.293a1 1 0 00-1.414 0L10 8.586 8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 001.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 000-1.414z"/>
                    </svg>
                </button>
            </div>
            <!-- Modal body -->
            <div class="mb-4">
                <!-- Your form for uploading pictures goes here -->
                <form id="lesson_content_pictureUploadForm" data-lesson-id="{{$lessonInfo->lesson_id}}" data-course-id="{{$lessonInfo->course_id}}" data-topic_id="{{$lessonInfo->topic_id}}" data-syllabus-id="{{$lessonInfo->syllabus_id}}" enctype="multipart/form-data" method="POST">
                    <input type="file" name="picture" id="lesson_title_picture" accept=".jpeg, .png, .jpg, .gif" />

                    <div class="flex justify-between mt-4">
                        <button type="submit" class="px-4 py-2 text-white bg-green-600 rounded hover:bg-green-900">Confirm</button>
                        <button id="cancelUpload_lesson_content_picture" class="px-4 py-2 text-gray-700 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="deleteLessonContentModal" class=" z-[99] fixed top-0 left-0 flex items-center justify-center hidden w-screen h-screen bg-black bg-opacity-50">
    {{-- <form id="deleteCourse" action="" data-course-id="{{ $course->course_id }}"> --}}
        {{-- @csrf --}}
        <div class="p-5 text-center bg-white rounded-lg">
            <p>Are you sure you want to delete this content?</p>
            <button type="button" id="confirmDelete" data-course-id="{{$course->course_id}}" class="px-4 py-2 m-2 text-white bg-red-600 rounded-md">Confirm</button>
            <button type="button" id="cancelDelete" class="px-4 py-2 m-2 text-gray-700 bg-gray-400 rounded-md">Cancel</button>
        </div>
    {{-- </form> --}}
    
</div>

<div id="deleteLessonContentPictureModal" class="z-[99] fixed top-0 left-0 flex items-center justify-center hidden w-screen h-screen bg-black bg-opacity-50">
    {{-- <form id="deleteCourse" action="" data-course-id="{{ $course->course_id }}"> --}}
        {{-- @csrf --}}
        <div class="p-5 text-center bg-white rounded-lg">
            <p>Are you sure you want to delete this content?</p>
            <button type="button" id="confirmDelete_lessonContentPicture" data-course-id="{{$course->course_id}}" data-syllabus-id="{{$lessonInfo->syllabus_id}}" data-topic_id="{{$lessonInfo->topic_id}}" class="px-4 py-2 m-2 text-white bg-red-600 rounded-md">Confirm</button>
            <button type="button" id="cancelDelete_lessonContentPicture" class="px-4 py-2 m-2 text-gray-700 bg-gray-400 rounded-md">Cancel</button>
        </div>
    {{-- </form> --}}
    
</div>



<div id="addLessonContentModal" class="fixed z-[99] top-0 left-0 flex items-center justify-center hidden w-full h-full bg-gray-200 bg-opacity-75">
    <div class="modal-content bg-white p-4 rounded-lg shadow-lg w-[750px]">
        <div class="flex justify-end w-full">
            <button id="closeAddLessonContentModal">
                <i class="text-xl fa-solid fa-xmark" style="color: #949494;"></i>
            </button>
        </div>
        <h2 class="mb-2 text-2xl font-semibold">Add Lesson Content</h2>
        <div class="mt-4">
            <label for="insertLessonContentTitle" class="text-lg font-semibold">Enter Title:</label>
            <input type="text" name="insertLessonContentTitle" id="insertLessonContentTitle" class="block w-full px-4 py-2 mt-2 border border-gray-300 rounded-md focus:ring focus:ring-seagreen focus:ring-opacity-50">
        </div>

        <div class="mt-4">
            <label for="insertLessonContent" class="text-lg font-semibold">Enter Content:</label>
            {{-- <input type="text" name="insertLessonContent" id="insertLessonContentTitle" class="block w-full px-4 py-2 mt-2 border border-gray-300 rounded-md focus:ring focus:ring-seagreen focus:ring-opacity-50"> --}}
            <textarea
              name="insertLessonContent"
                id="insertLessonContent"
                class="block w-full px-4 py-2 mt-2 border border-gray-300 rounded-md focus:ring focus:ring-seagreen focus:ring-opacity-50"
                cols="30"
                rows="10"
                style="white-space: pre;"
              ></textarea>
          
        </div>

        <div class="mt-4">
            <label for="insertLocation" class="text-lg font-semibold">Insert Location:</label>
            <select id="insertLocation" class="block w-full px-4 py-2 mt-2 border border-gray-300 rounded-md focus:ring focus:ring-seagreen focus:ring-opacity-50">
                    <option value="START">At the Beginning</option>
                @forelse ($lessonContent as $lesson)
                    <option value="{{ $lesson->lesson_content_title }}">AFTER {{ $lesson->lesson_content_title }}</option>
                @empty
                    <option value="">At the Beginning</option>
                @endforelse
                    <option value="END">In the End</option>
            </select>
        </div>
        
        <div class="flex justify-center w-full mt-5">
            <button id="confirmAddLessonContentBtn" data-lesson-id="{{$lessonInfo->lesson_id}}" class="px-4 py-2 mx-2 mt-4 text-white rounded bg-seagreen hover:bg-darkenedColor">Confirm</button>
            <button id="cancelAddLessonContentBtn" class="px-4 py-2 mx-2 mt-4 text-white bg-red-500 rounded">Cancel</button>
        </div>
    </div>
</div>




<div id="editLessonContentModal" class="fixed top-0 z-[99] left-0 flex items-center justify-center hidden w-full h-full bg-gray-200 bg-opacity-75">
    <div class="modal-content bg-white p-4 rounded-lg shadow-lg w-[750px]">
        <div class="flex justify-end w-full">
            <button class="closeEditLessonContentModal">
                <i class="text-xl fa-solid fa-xmark" style="color: #949494;"></i>
            </button>
        </div>
        <h2 class="mb-2 text-2xl font-semibold">Edit Lesson Content</h2>
        <div class="mt-4">
            <label for="insertEditLessonContentTitle" class="text-lg font-semibold">Enter Title:</label>
            <input type="text" name="insertLessonContentTitle" id="insertEditLessonContentTitle" class="block w-full px-4 py-2 mt-2 border border-gray-300 rounded-md focus:ring focus:ring-seagreen focus:ring-opacity-50">
        </div>

        <div class="mt-4">
            <label for="insertEditLessonContent" class="text-lg font-semibold">Enter Content:</label>
            {{-- <input type="text" name="insertLessonContent" id="insertLessonContentTitle" class="block w-full px-4 py-2 mt-2 border border-gray-300 rounded-md focus:ring focus:ring-seagreen focus:ring-opacity-50"> --}}
            <textarea
              name="insertEditLessonContent"
                id="insertEditLessonContent"
                class="block w-full px-4 py-2 mt-2 border border-gray-300 rounded-md focus:ring focus:ring-seagreen focus:ring-opacity-50"
                cols="30"
                rows="10"
                style="white-space: pre;"
              ></textarea>
          
        </div>

        <div class="flex justify-center w-full mt-5">
            <button id="confirmEditLessonContentBtn" data-content-order="" data-lesson-id="{{$lessonInfo->lesson_id}}" class="px-4 py-2 mx-2 mt-4 text-white rounded bg-seagreen hover:bg-darkenedColor">Confirm</button>
            <button id="" class="px-4 py-2 mx-2 mt-4 text-white bg-red-500 rounded closeEditLessonContentModal">Cancel</button>
        </div>
    </div>
</div>


<div id="addLessonContentUrlModal" class="fixed top-0 left-0 z-[99] flex items-center justify-center hidden w-full h-full bg-gray-200 bg-opacity-75">
    <div class="modal-content bg-white p-4 rounded-lg shadow-lg w-[750px]">
        <div class="flex justify-end w-full">
            <button class="closeAddLessonContentUrlModal">
                <i class="text-xl fa-solid fa-xmark" style="color: #949494;"></i>
            </button>
        </div>
        <h2 class="mb-2 text-2xl font-semibold">Embed Video from Youtube</h2>
        <div class="mt-4">
            <label for="insertAddLessonContentUrl" class="text-lg font-semibold">Enter Embed Code copied</label>
            <input type="text" name="insertAddLessonContentUrl" id="insertAddLessonContentUrl" class="block w-full px-4 py-2 mt-2 border border-gray-300 rounded-md focus:ring focus:ring-seagreen focus:ring-opacity-50">
        </div>
    
        <div class="flex justify-center w-full mt-5">
            <button id="confirmAddLessonContentUrlBtn" data-content-order="" data-lesson-id="{{$lessonInfo->lesson_id}}" class="px-4 py-2 mx-2 mt-4 text-white rounded bg-seagreen hover:bg-darkenedColor">Confirm</button>
            <button id="cancelAddLessonContentUrlBtn" class="px-4 py-2 mx-2 mt-4 text-white bg-red-500 rounded closeAddLessonContentUrlModal">Cancel</button>
        </div>
    </div>
</div>

{{-- <div id="editLessonContentUrlModal" class="fixed top-0 left-0 flex items-center justify-center hidden w-full h-full bg-gray-200 bg-opacity-75">
    <div class="modal-content bg-white p-4 rounded-lg shadow-lg w-[750px]">
        <div class="flex justify-end w-full">
            <button class="closeEditLessonContentUrlModal">
                <i class="text-xl fa-solid fa-xmark" style="color: #949494;"></i>
            </button>
        </div>
        <h2 class="mb-2 text-2xl font-semibold">Embed Video from Youtube</h2>
        <div class="mt-4">
            <label for="insertEditLessonContentUrl" class="text-lg font-semibold">Enter Embed Code copied</label>
            <input type="text" name="insertEditLessonContentUrl" id="insertEditLessonContentUrl" class="block w-full px-4 py-2 mt-2 border border-gray-300 rounded-md focus:ring focus:ring-seagreen focus:ring-opacity-50">
        </div>

        <div class="flex justify-center w-full mt-5">
            <button id="confirmEditLessonContentUrlBtn" data-content-order="" data-lesson-id="{{$lessonInfo->lesson_id}}" class="px-4 py-2 mx-2 mt-4 text-white rounded bg-seagreen hover:bg-darkenedColor">Confirm</button>
            <button id="" class="px-4 py-2 mx-2 mt-4 text-white bg-red-500 rounded closeEditLessonContentUrlModal">Cancel</button>
        </div>
    </div>
</div> --}}


<div id="deleteLessonContentUrlModal" class="fixed top-0 z-[99] left-0 flex items-center justify-center hidden w-screen h-screen bg-black bg-opacity-50">
    {{-- <form id="deleteCourse" action="" data-course-id="{{ $course->course_id }}"> --}}
        {{-- @csrf --}}
        <div class="p-5 text-center bg-white rounded-lg">
            <p>Are you sure you want to delete this content?</p>
            <button type="button" id="confirmDelete_lessonContentUrl" data-content-order="" data-lesson-content-id="" data-lesson-id="" data-course-id="{{$course->course_id}}" data-syllabus-id="{{$lessonInfo->syllabus_id}}" data-topic_id="{{$lessonInfo->topic_id}}" class="px-4 py-2 m-2 text-white bg-red-600 rounded-md">Confirm</button>
            <button type="button" id="cancelDelete_lessonContentUrl" class="px-4 py-2 m-2 text-gray-700 bg-gray-400 rounded-md">Cancel</button>
        </div>
    {{-- </form> --}}
    
</div>



<div id="loaderModal" class="fixed top-0 left-0 z-50 flex items-center justify-center hidden w-full h-full bg-gray-200 bg-opacity-75 ">
    <div class="flex flex-col items-center justify-center w-full h-screen p-4 bg-white rounded-lg shadow-lg modal-content md:h-1/3 lg:w-1/3">
        <span class="loading loading-spinner text-primary loading-lg"></span> 
            
        <p class="mt-5 text-xl text-darthmouthgreen">loading</p>  
    </div>
</div>

@endsection
