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
                <div class="flex flex-row items-center justify-between py-4 border-b-2">
                    <div class="flex flex-row">
                        <svg width="40" height="40" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M26.6391 8.59801L21.402 3.36207C21.2278 3.18792 21.0211 3.04977 20.7936 2.95551C20.5661 2.86126 20.3223 2.81274 20.076 2.81274C19.8297 2.81274 19.5859 2.86126 19.3584 2.95551C19.1308 3.04977 18.9241 3.18792 18.75 3.36207L4.29962 17.8125C4.12475 17.9859 3.98611 18.1924 3.89176 18.42C3.79741 18.6475 3.74922 18.8915 3.75001 19.1379V24.375C3.75001 24.8722 3.94755 25.3492 4.29918 25.7008C4.65081 26.0524 5.12773 26.25 5.62501 26.25H10.8621C11.1084 26.2508 11.3525 26.2026 11.58 26.1082C11.8075 26.0139 12.014 25.8752 12.1875 25.7004L21.9926 15.8964L22.4004 17.5254L18.0879 21.8367C17.912 22.0124 17.8131 22.2509 17.813 22.4995C17.8129 22.7482 17.9116 22.9867 18.0873 23.1627C18.2631 23.3386 18.5015 23.4375 18.7502 23.4376C18.9988 23.4377 19.2374 23.339 19.4133 23.1632L24.1008 18.4757C24.2154 18.3613 24.2985 18.2191 24.3418 18.063C24.3851 17.9069 24.3873 17.7423 24.3481 17.5851L23.5395 14.3496L26.6391 11.25C26.8132 11.0758 26.9514 10.8691 27.0456 10.6416C27.1399 10.4141 27.1884 10.1702 27.1884 9.92398C27.1884 9.67772 27.1399 9.43387 27.0456 9.20635C26.9514 8.97884 26.8132 8.77212 26.6391 8.59801ZM5.62501 21.0129L8.98712 24.375H5.62501V21.0129ZM11.25 23.9871L6.0129 18.75L15.9375 8.82535L21.1746 14.0625L11.25 23.9871ZM22.5 12.7371L17.2641 7.49996L20.0766 4.68746L25.3125 9.92457L22.5 12.7371Z" fill="black"/>
                            </svg>
                        <h1 class="mx-2 text-2xl font-semibold">{{$quizInfo->quiz_title}}</h1>
                    </div>
                    <button class="p-3 font-medium text-white saveQuizContent bg-darthmouthgreen rounded-2xl hover:bg-green-950">Save Changes</button>
                </div>

                <div class="border-b-2 border-black">
                    <h1 class="mx-2 mt-5 text-2xl font-semibold">Quiz Content:</h1>
                </div>

                <div class="w-full mt-10 quizContentArea">
                    {{-- <x-forms.primary-button color="darthmouthgreen" name="Add Question" type="button" class="mx-auto text-white w-max" id="addQuestionBtn"/> --}}
                    
                    <div class="flex flex-col items-center justify-center w-full" id="formContainer">

                     

                            {{-- sample multiple choice item --}}
                            {{-- <div class="w-4/5 p-5 my-5 border-2 rounded-lg questionContainer border-darthmouthgreen">
                                
                                <div class="flex justify-end">
                                    <button class="text-2xl removeQuestionBtn">
                                        <i class="fa-solid fa-xmark" style="color: #00693e;"></i>
                                    </button>
                                </div>

                                <div class="questionContent">

                                    <div class="flex justify-between pb-3 border-b-2 border-black question_category_reference">
                                        <textarea class="w-3/4 px-2 py-3 text-xl font-medium" placeholder="Question"></textarea>
                                        
                                        <div class="flex flex-col justify-between">
                                            <select name="questionCategory" id="questionCategory" class="w-48 py-2 my-1 text-md">
                                                <option value="MULTIPLECHOICE" selected>Multiple Choice</option>
                                                <option value="IDENTIFICATION">Identification</option>
                                                <option value="LONGANSWER">Long Answer</option>
                                            </select>
        
                                            <select name="questionReference" id="questionReference" class="w-48 py-2 my-1 text-md">
                                                <option value="" disabled></option>
                                            </select>
                                        </div>     
                                    </div>
        
                                    <div class="question_choices">
                                        <table class="w-full mt-5">
        
                                            <tbody>
                                                <tr class="h-10 rounded-xl">
                                                    <td class="w-4/5">
                                                        <div class="flex items-center w-full text-lg choice">
                                                            <input type="radio" class="w-6 h-6">
                                                            <input type="text" class="w-full mx-5" value="Option A">
                                                        </div>
                                                    </td>
                                                    <td class="w-1/5">
                                                        <i class="text-xl fa-solid fa-check" style="color: #00693e;"></i>
                                                        <span>correct</span>
                                                    </td>
                                                    <td>
                                                        <button class="text-2xl removeQuestionBtn">
                                                            <i class="fa-solid fa-xmark" style="color: #00693e;"></i>
                                                        </button>
                                                    </td>    
                                                </tr>
                                            
                                                <tr class="h-10 rounded-xl">
                                                    <td class="w-4/5">
                                                        <div class="flex items-center w-full text-lg choice ">
                                                            <input type="radio" class="w-6 h-6">
                                                            <input type="text" class="w-full mx-5" value="Option A">
                                                        </div>
                                                    </td>
                                                    <td class="w-1/5">
                                            
                                                        <span></span>
                                                    </td>
                                                    <td>
                                                        <button class="text-2xl removeQuestionBtn">
                                                            <i class="fa-solid fa-xmark" style="color: #00693e;"></i>
                                                        </button>
                                                    </td>  
                                                </tr>
                                            </tbody>
        
                                        </table>
                                        <button class="py-5 text-lg ">
                                            <i class="fa-solid fa-circle-plus" style="color: #00693e;"></i>
                                            <span class="mx-3">add option</span>
                                        </button>
                                    </div>
        
                                    <div class="question_correct_answer">
                                        <span>Correct Answer</span>
                                        <select name="" id="">
                                            <option value="" disabled></option>
                                        </select>
                                    </div>


                                </div>                             

                                
                            </div> --}}
                        {{-- end sample multiple choice item --}}
                        {{--             

                        {{-- sample identification item --}}
                        {{-- <div class="w-4/5 p-5 my-5 border-2 rounded-lg questionContainer border-darthmouthgreen">
                            
                            <div class="flex justify-end">
                                <button class="text-2xl removeQuestionBtn">
                                    <i class="fa-solid fa-xmark" style="color: #00693e;"></i>
                                </button>
                            </div>

                            <div class="questionContent">

                                <div class="flex justify-between pb-3 border-b-2 border-black question_category_reference">
                                    <textarea class="w-3/4 px-2 py-3 text-xl font-medium" placeholder="Question"></textarea>
                                    
                                    <div class="flex flex-col justify-between">
                                        <select name="questionCategory" id="questionCategory" class="w-48 py-2 my-1 text-md">
                                            <option value="MULTIPLECHOICE">Multiple Choice</option>
                                            <option value="IDENTIFICATION" selected>Identification</option>
                                            <option value="LONGANSWER">Long Answer</option>
                                        </select>
    
                                        <select name="questionReference" id="questionReference" class="w-48 py-2 my-1 text-md">
                                            <option value="" disabled></option>
                                        </select>
                                    </div>     
                                </div>
    
                                <div class="flex mt-5 question_answer">
                                    <textarea type="text" class="w-4/5" placeholder="Answer here..."></textarea>
                                    <div class="">
                                        <i class="text-xl fa-solid fa-check" style="color: #00693e;"></i>
                                                    <span>correct</span>
                                    </div>
                                </div>

                            </div>

                            
                        </div> --}}
                        {{-- end sample identification item --}}


                        {{-- sample longanswer item --}}
                        {{-- <div class="w-4/5 p-5 my-5 border-2 rounded-lg questionContainer border-darthmouthgreen">
                            
                            <div class="flex justify-end">
                                <button class="text-2xl removeQuestionBtn">
                                    <i class="fa-solid fa-xmark" style="color: #00693e;"></i>
                                </button>
                            </div>

                            <div class="questionContent">

                                <div class="flex justify-between pb-3 border-b-2 border-black question_category_reference">
                                    <textarea class="w-3/4 px-2 py-3 text-xl font-medium" placeholder="Question"></textarea>
                                    
                                    <div class="flex flex-col justify-between">
                                        <select name="questionCategory" id="questionCategory" class="w-48 py-2 my-1 text-md">
                                            <option value="MULTIPLECHOICE">Multiple Choice</option>
                                            <option value="IDENTIFICATION">Identification</option>
                                            <option value="LONGANSWER" selected>Long Answer</option>
                                        </select>
    
                                        <select name="questionReference" id="questionReference" class="w-48 py-2 my-1 text-md">
                                            <option value="" disabled></option>
                                        </select>
                                    </div>     
                                </div>
    
                                <div class="flex mt-5 question_answer">
                                    <textarea type="text" class="w-4/5 h-15" placeholder="Answer here..." disabled></textarea>
                                </div>

                            </div>

                            
                            
                        </div> --}}
                        {{-- end sample longanswer item --}}

                    </div>

                    <div class="flex justify-center w-full py-5 quizOptions">
                        <button class="p-3 mx-3 text-white rounded-xl bg-darthmouthgreen hover:bg-green-950 w-max" id="addExistingQuestionBtn">
                            Add Existing Question
                        </button>
                        <button class="p-3 mx-3 text-white rounded-xl bg-darthmouthgreen hover:bg-green-950 w-max" id="addNewQuestionBtn">
                            Add New Question
                        </button>
                    </div>
                    <div class="flex justify-center w-full py-5 mt-5 border-t-2 border-gray-400">
                        <button class="w-1/2 p-3 mx-3 text-lg font-medium text-center text-white bg-darthmouthgreen rounded-2xl hover:bg-green-950">
                            <a id="cancelQuizBuild" href="{{ url("/instructor/course/content/$course->course_id/$quizInfo->syllabus_id/quiz/$quizInfo->topic_id") }}" class="">
                        Cancel
                            </a>
                        </button>
                        <button class="w-1/2 p-3 mx-3 text-lg font-medium text-white saveQuizContent bg-darthmouthgreen rounded-2xl hover:bg-green-950">
                            Save Changes
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section> 


            <div id="addExistingQuestionModal" class="fixed top-0 left-0 z-50 flex items-center justify-center hidden w-full h-full bg-gray-200 bg-opacity-75">
                <div class="modal-content bg-white p-4 rounded-lg shadow-lg w-[500px]">
                    <div class="flex justify-end w-full">
                        <button class="cancelAddExistingQuestionBtn">
                            <i class="text-xl fa-solid fa-xmark" style="color: #949494;"></i>
                        </button>
                    </div>
                    <h2 class="mb-2 text-2xl font-semibold">Add Topic</h2>
                    <hr>
                    <div id="existingQuestionArea" class="w-full mt-5 ">
                        <label for="questionSelection">Question</label><br>
                        <select name="questionSelection" id="questionSelection" class="w-full py-3 text-lg">
                            <option value="" disabled>Choose Existing Question</option>
                        </select>
                    </div>

                    <div class="flex justify-center w-full mt-5">
                        <button id="confirmAddExistingQuestionBtn" class="p-3 mx-2 mt-4 text-white rounded bg-seagreen hover:bg-darkenedColor">Confirm</button>
                        <button id="" class="p-3 mx-2 mt-4 text-white bg-red-500 rounded cancelAddExistingQuestionBtn">Cancel</button>
                    </div>
                    
                </div>
            </div>

            <div id="confirmSaveQuizContentModal" class="fixed top-0 left-0 z-50 flex items-center justify-center hidden w-full h-full bg-gray-200 bg-opacity-75">
                <div class="modal-content bg-white p-4 rounded-lg shadow-lg w-[500px]">
                    <div class="flex justify-end w-full">
                        <button class="cancelSaveQuizContentBtn">
                            <i class="text-xl fa-solid fa-xmark" style="color: #949494;"></i>
                        </button>
                    </div>

                    <h2 class="mb-2 text-xl font-semibold">Are you sure to apply changes to the quiz?</h2>

                    <div class="flex justify-center w-full mt-5">
                        <button id="confirmSaveQuizContentBtn" class="p-3 mx-2 mt-4 text-white rounded bg-seagreen hover:bg-darkenedColor">Confirm</button>
                        <button id="" class="p-3 mx-2 mt-4 text-white bg-red-500 rounded cancelSaveQuizContentBtn">Cancel</button>
                    </div>
                </div>
            </div>

@endsection

            <div id="loaderModal" class="fixed top-0 left-0 z-[99] flex items-center justify-center hidden w-full h-full bg-gray-200 bg-opacity-75 ">
              <div class="flex flex-col items-center justify-center w-full h-screen p-4 bg-white rounded-lg shadow-lg modal-content md:h-1/3 lg:w-1/3">
                  <span class="loading loading-spinner text-primary loading-lg"></span> 
                  <p class="mt-5 text-xl text-darthmouthgreen">loading</p>  
              </div>
            </div>

