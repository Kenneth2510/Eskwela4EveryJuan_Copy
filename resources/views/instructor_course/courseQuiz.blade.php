@include('partials.header')

<section class="main-container">
    @include('partials.instructorNav')
    @include('partials.instructorSidebar')
    
    <section class="w-full px-2 pt-[120px] mx-2 mt-2 md:w-3/4 lg:w-9/12  overscroll-auto md:overflow-auto">
        <div class="p-3 pb-4 overflow-auto rounded-lg shadow-lg overscroll-auto" id="quizMainContainer">
            <x-forms.primary-button color="darthmouthgreen" name="Add Question" type="button" class="mx-auto text-white w-max" id="addQuestionBtn"/>
            {{-- quiz main content --}}
            
            <div class="flex flex-col items-center justify-center" id="formContainer">
                {{-- <div class="w-4/5 p-4 mx-auto my-2 border-2 border-gray-200 rounded lg:w-2/3" id="IDQuestionCont">
                    <form class="flex flex-col lg:flex-row-reverse lg:justify-between" action="" id="IDForm">
                        <div class="flex flex-col items-center mx-2" id="typeOfQuestion">
                            <label for="questionType">Type of Question:</label>
                            <select class="h-10 px-2 pl-2 my-2 bg-transparent focus:bg-gray-200" name="questionType" id="typeOptions">
                                <option value="" selected disabled>--select type--</option>
                                <option value="ID">Identification</option>
                                <option value="MC">Multiple Choice</option>
                                <option value="EZ">Essay</option>
                            </select>
                        </div>
                        <div>
                            <input class="w-full h-10 pl-2 my-2 mb-5 bg-transparent border-b-2 border-b-seagreen focus:bg-gray-200" type="text" placeholder="Question...">
                            <input class="w-full h-10 pl-2 my-2 bg-transparent border-b-2 border-b-seagreen focus:bg-gray-200" type="text" placeholder="Answer...">
                            <input class="w-full h-10 pl-2 my-2 bg-transparent border-b-2 border-b-seagreen focus:bg-gray-200" type="text" placeholder="Points...">
                        </div>
                    </form>
                </div>
                
                <div class="w-4/5 p-4 mx-auto my-2 border-2 border-gray-200 rounded lg:w-2/3" id="MCQuestionCont">
                    <form class="flex flex-col lg:flex-row-reverse lg:justify-between" action="" id="MCForm">
                        <div>
                            <input class="w-full h-10 pl-2 my-2 mb-5 bg-transparent border-b-2 border-b-seagreen focus:bg-gray-200" type="text" placeholder="Question...">
                        
                            <div id="choices">
                                <input class="w-full h-10 pl-2 my-2 bg-transparent border-b-2 border-b-seagreen focus:bg-gray-200" type="text" value="Option 1">
                                <input class="w-full h-10 pl-2 my-2 bg-transparent border-b-2 border-b-seagreen focus:bg-gray-200" type="text" value="Option 2">
                                <input class="w-full h-10 pl-2 my-2 bg-transparent border-b-2 border-b-seagreen focus:bg-gray-200" type="text" value="Option 3">
                            </div>
                            
                            
                            <div class="flex flex-row items-center my-2 mb-5">
                                <svg class="mr-2" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><g fill="currentColor" fill-rule="evenodd" clip-rule="evenodd"><path d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10s-4.477 10-10 10S2 17.523 2 12Zm10-8a8 8 0 1 0 0 16a8 8 0 0 0 0-16Z"/><path d="M13 7a1 1 0 1 0-2 0v4H7a1 1 0 1 0 0 2h4v4a1 1 0 1 0 2 0v-4h4a1 1 0 1 0 0-2h-4V7Z"/></g></svg>
                                <p>Add new option</p>
                            </div>
                            
                            <select class="w-2/3 h-10" name="" id="">
                                <option value="" selected disabled>Answer Key</option>
                                <option value="">Option 1</option>
                                <option value="">Option 2</option>
                                <option value="">Option 3</option>
                            </select>

                            <input class="w-full h-10 pl-2 my-2 bg-transparent border-b-2 border-b-seagreen focus:bg-gray-200" type="text" placeholder="Points...">
                            
                        </div>
                        
                    </form>
                </div>
                
                <div class="w-4/5 p-4 mx-auto my-2 border-2 border-gray-200 rounded lg:w-2/3" id="EZQuestionCont">
                    <form class="flex flex-col lg:flex-row lg:justify-between" action="" id="EZForm">
                        <div>
                            <input class="w-full h-10 max-w-full pl-2 my-2 mb-5 bg-transparent border-b-2 border-b-seagreen focus:bg-gray-200" type="text" placeholder="Question...">
                            <input class="w-full h-10 pl-2 my-2 bg-transparent border-b-2 border-b-seagreen focus:bg-gray-200" type="text" placeholder="Points...">
                        </div>
                            
                    </form>
                </div> --}}
                
           
                
            </div>

            {{-- <div class="flex flex-row items-center mx-auto my-2 cursor-pointer w-max">
                <svg class="mr-2" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><g fill="currentColor" fill-rule="evenodd" clip-rule="evenodd"><path d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10s-4.477 10-10 10S2 17.523 2 12Zm10-8a8 8 0 1 0 0 16a8 8 0 0 0 0-16Z"/><path d="M13 7a1 1 0 1 0-2 0v4H7a1 1 0 1 0 0 2h4v4a1 1 0 1 0 2 0v-4h4a1 1 0 1 0 0-2h-4V7Z"/></g></svg>
                <p>Add new question</p>
            </div> --}}

            <x-forms.primary-button color="amber" name="Publish Quiz" class="hidden float-right" id="publishQuiz"/>
            
        </div>
    </section>
    
</section>

@include('partials.footer')