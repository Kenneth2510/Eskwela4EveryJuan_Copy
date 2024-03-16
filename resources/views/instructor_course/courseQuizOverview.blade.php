@extends('layouts.instructor_layout')

@section('content')
    {{-- MAIN --}}
    <section class="w-full h-screen md:w-3/4 lg:w-10/12">
        <div class="relative w-full h-full px-2 py-4 pt-24 overflow-hidden overflow-y-scroll rounded-lg shadow-lg md:pt-4">
            <div style="background-color:{{$mainBackgroundCol}}" class="p-2 text-white fill-white rounded-xl">
                <a href="{{ url("/instructor/course/content/$course->course_id") }}" class="my-2 bg-gray-300 rounded-full ">
                    <svg  xmlns="http://www.w3.org/2000/svg" height="30" viewBox="0 -960 960 960" width="24"><path d="M560-240 320-480l240-240 56 56-184 184 184 184-56 56Z"/></svg>
                </a>
                <h1 class="w-1/2 py-4 text-2xl font-bold md:text-4xl"><span class="">{{ $course->course_name }}</span></h1>
                {{-- subheaders --}}
                <div class="flex flex-col fill-mainwhitebg">
                    <h1 class="w-1/2 py-4 text-lg"><span class="">{{ $quizInfo->quiz_title }}</span></h1>
                </div>
            </div>
            
            <div class="mx-2">
                <div class="mt-1 text-gray-600 text-l">
                    <a href="{{ url('/instructor/courses') }}" class="">course></a>
                    <a href="{{ url("/instructor/course/$course->course_id") }}">{{$course->course_name}}></a>
                    <a href="{{ url("/instructor/course/content/$course->course_id") }}">content></a>
                    <a href="">{{ $quizInfo->quiz_title }}</a>
                </div>
                {{-- head --}}
                <div class="flex flex-row items-center py-4 border-b-2">
                    <svg width="40" height="40" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M26.6391 8.59801L21.402 3.36207C21.2278 3.18792 21.0211 3.04977 20.7936 2.95551C20.5661 2.86126 20.3223 2.81274 20.076 2.81274C19.8297 2.81274 19.5859 2.86126 19.3584 2.95551C19.1308 3.04977 18.9241 3.18792 18.75 3.36207L4.29962 17.8125C4.12475 17.9859 3.98611 18.1924 3.89176 18.42C3.79741 18.6475 3.74922 18.8915 3.75001 19.1379V24.375C3.75001 24.8722 3.94755 25.3492 4.29918 25.7008C4.65081 26.0524 5.12773 26.25 5.62501 26.25H10.8621C11.1084 26.2508 11.3525 26.2026 11.58 26.1082C11.8075 26.0139 12.014 25.8752 12.1875 25.7004L21.9926 15.8964L22.4004 17.5254L18.0879 21.8367C17.912 22.0124 17.8131 22.2509 17.813 22.4995C17.8129 22.7482 17.9116 22.9867 18.0873 23.1627C18.2631 23.3386 18.5015 23.4375 18.7502 23.4376C18.9988 23.4377 19.2374 23.339 19.4133 23.1632L24.1008 18.4757C24.2154 18.3613 24.2985 18.2191 24.3418 18.063C24.3851 17.9069 24.3873 17.7423 24.3481 17.5851L23.5395 14.3496L26.6391 11.25C26.8132 11.0758 26.9514 10.8691 27.0456 10.6416C27.1399 10.4141 27.1884 10.1702 27.1884 9.92398C27.1884 9.67772 27.1399 9.43387 27.0456 9.20635C26.9514 8.97884 26.8132 8.77212 26.6391 8.59801ZM5.62501 21.0129L8.98712 24.375H5.62501V21.0129ZM11.25 23.9871L6.0129 18.75L15.9375 8.82535L21.1746 14.0625L11.25 23.9871ZM22.5 12.7371L17.2641 7.49996L20.0766 4.68746L25.3125 9.92457L22.5 12.7371Z" fill="black"/>
                        </svg>
                    <h1 class="mx-2 text-xl font-semibold">{{$quizInfo->quiz_title}}</h1>
                </div>

                {{-- body --}}
                <div class="py-4 " id="defaultView">      
                    
                    <div class="flex flex-row items-center">
                        <h3 class="my-2 text-xl font-medium">Coverage:</h3>
                        {{-- <button id="editCoverageBtn" class="hidden">
                            <svg class="mx-2" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M80 0v-160h800V0H80Zm80-240v-150l362-362 150 150-362 362H160Zm80-80h36l284-282-38-38-282 284v36Zm477-326L567-796l72-72q11-12 28-11.5t28 11.5l94 94q11 11 11 27.5T789-718l-72 72ZM240-320Z"/></svg>
                        </button> --}}
                    </div>
                    <div id="coverageArea" class="mt-5">
                        <table class="w-full">
                            <thead class="text-xl text-white bg-green-700 rounded-xl">
                                
                                <th class="w-4/5">Title</th>
                                <th class="w-3/5"></th>
                            </thead>

                            <tbody class="referenceTable">
                    
                                @forelse ($quizReference as $reference)
                                <tr>
                                    
                                    <td class="w-4/5">
                                        <select class="w-4/5 m-5 h-14 referenceRow" name="" id="" disabled>
                                            <option value="">{{$reference->topic_title}}</option>
                                        </select>
                                    </td>
                                    <td class="w-1/5">
                                        <button class="hidden px-3 py-1 mx-2 font-semibold text-white bg-green-600 rounded-xl editReferenceBtn hover:bg-green-900">Edit</button>
                                        <div class="flex hidden editReference_clickedBtns">
                                            <button class="px-3 py-1 mx-2 font-semibold text-white bg-green-600 rounded-xl saveReferenceBtn hover:bg-green-900">Save</button>
                                            <button class="px-3 py-1 mx-2 font-semibold text-white bg-red-600 rounded-xl deleteReferenceBtn hover:bg-red-900">Delete</button>
                                            <button class="px-3 py-1 mx-2 font-semibold text-white bg-red-600 rounded-xl cancelReferenceBtn hover:bg-red-900">Cancel</button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td rowspan="3">No Criterias Found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <button id="addNewReference" class="hidden px-5 py-3 my-5 text-white rounded-xl bg-darthmouthgreen hover:bg-green-900">Add Reference</button>
                            <div class="hidden my-5 mt-3" id="addNewReference_clickedBtns"> 
                                <button id="saveAddNewReferenceBtn" class="px-5 py-3 text-white rounded-xl bg-darthmouthgreen hover:bg-green-900">Save Reference</button>
                                <button id="cancelAddNewReferenceBtn" class="px-5 py-3 text-white bg-red-600 hover:bg-red-900 rounded-xl">Cancel</button>
                            </div>
                    </div>
                    
                            <span id="" class="text-gray-500">Make sure to add the references before going to the editor</span>

                        <div class="mt-16" id="durationArea">
                            <h3 class="my-2 text-xl font-medium">Quiz Attempt Duration:</h3>
                            <div class="">
                                <label class="text-lg" for="hours">Hours:</label>
                                <input disabled class="w-2/12 px-1 mx-3 text-lg border-2 border-gray-400 rounded-lg duration_input" type="number" id="hours" name="hours" min="0" placeholder="0" value="0" required>
                        
                                <label class="text-lg" for="minutes">Minutes:</label>
                                <input disabled class="w-2/12 px-1 mx-3 text-lg border-2 border-gray-400 rounded-lg duration_input" type="number" id="minutes" name="minutes" min="0" max="59" placeholder="0" value="0" required>
                        
                                <label class="text-lg" for="seconds">Seconds:</label>
                                <input disabled class="w-2/12 px-1 mx-3 text-lg border-2 border-gray-400 rounded-lg duration_input" type="number" id="seconds" name="seconds" min="0" max="59" placeholder="0" value="0" required>
                        
                                <button id="saveDurationBtn" class="hidden px-3 py-1 text-lg text-white bg-darthmouthgreen hover:bg-green-950 rounded-xl">Set Duration</button>
                            </div>
                        </div>
                        
                            <span id="" class="text-gray-500">Make sure to set the duration time before going to the editor</span>
                        
                    <button id="editQuizInfoBtn" class="w-32 px-5 py-3 mx-3 mt-10 text-white bg-darthmouthgreen hover:bg-green-900 rounded-xl">Edit</button>
                    <div class="hidden mt-5" id="editQuizInfo_clickedBtns">
                        <button id="saveQuizInfoBtn" class="p-3 m-2 text-white bg-darthmouthgreen hover:bg-green-900 rounded-xl">Finish Editing</button>
                    </div>
                    <div class="flex w-full mt-5">
                        <button id="viewResponsesBtn" class="w-1/2 px-5 py-5 mx-3 mt-3 text-white bg-darthmouthgreen hover:bg-green-900 rounded-xl">
                            View Responses
                        </button>
                        <a href="{{ url("/instructor/course/content/$quizInfo->course_id/$quizInfo->syllabus_id/quiz/$quizInfo->topic_id/$quizInfo->quiz_id/content") }}" class="w-1/2 px-5 py-5 mx-3 mt-3 text-center text-white bg-darthmouthgreen hover:bg-green-900 rounded-xl">
                            Enter Quiz Editor
                        </a>
                    </div>
                </div>
            </div>         
        </div>
    </section>

    {{-- area for modals --}}
    <div id="responsesModal" class="fixed top-0 left-0 z-50 flex items-center justify-center hidden w-full h-full bg-gray-200 bg-opacity-75">
        <div class="w-full p-4 overflow-x-auto bg-white rounded-lg shadow-lg md:w-3/5 modal-content">
            <div class="flex justify-end w-full">
                <button class="exitResponsesModalBtn">
                    <i class="text-xl fa-solid fa-xmark" style="color: #949494;"></i>
                </button>
            </div>


            <h1 class="text-2xl font-bold ">View All Responses</h1>
            
            <div id="responsesContent" class="mt-5 overflow-x-auto overflow-y-auto text-sm">
                <table class="table table-fixed w-[500px]">
                    <thead class="text-white bg-darthmouthgreen">
                        <th class="w-1/6 py-3 font-semibold">Enrollee ID</th>
                        <th class="w-1/6 py-3 font-semibold">Name</th>
                        <th class="w-1/6 py-3 font-semibold">Attempt</th>
                        <th class="w-1/6 py-3 font-semibold">Attempt Taken</th>
                        <th class="w-1/6 py-3 font-semibold">Score</th>
                        <th class="w-1/6 py-3 font-semibold">Remarks</th>
                        <th class="w-1/6 py-3 font-semibold"></th>
                    </thead>

                    <tbody id="responsesRowDataArea" class="">  
                        {{-- <tr class="text-center">
                            <td class="py-5 my-3">1</td>
                            <td>Kenneth Timblaco</td>
                            <td>1</td>
                            <td>December 10, 2023</td>
                            <td>5/6</td>
                            <td>
                                <button class="px-5 py-3 text-white bg-darthmouthgreen hover:bg-green-950 rounded-xl">View</button>
                            </td>
                        </tr> --}}
                    </tbody>
                </table>
            </div>

        </div>
    </div>
    
        <div id="loaderModal" class="fixed top-0 left-0 z-50 flex items-center justify-center hidden w-full h-full bg-gray-200 bg-opacity-75 ">
        <div class="flex flex-col items-center justify-center w-full h-screen p-4 bg-white rounded-lg shadow-lg modal-content md:h-1/3 lg:w-1/3">
            <span class="loading loading-spinner text-primary loading-lg"></span> 
                
            <p class="mt-5 text-xl text-darthmouthgreen">loading</p>  
        </div>
    </div>
@endsection