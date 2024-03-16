@include('partials.header')
<section class="flex flex-row w-full h-screen text-sm bg-mainwhitebg md:text-base">
    @include('partials.instructorNav')
    @include('partials.instructorSidebar')
    
    {{-- MAIN --}}
    <section class="w-full pt-[120px] mx-2  overscroll-auto md:overflow-auto">
        <div class="pb-4 mb-8 rounded-lg shadow-lg">
            {{-- header --}}
            <div class="relative px-2 rounded-t-lg bg-seagreen text-mainwhitebg">
                <button class="my-2 bg-gray-400 rounded-full ">
                    <svg  xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M560-240 320-480l240-240 56 56-184 184 184 184-56 56Z"/></svg>
                </button>
                <h1 class="w-1/2 text-xl font-semibold">Business Administration</h1>
                <p>Instructor 1</p>
                <p class="opacity-50">000000</p>
                <div class="flex justify-end">
                    <x-forms.primary-button
                    color="white"
                    name="Edit"
                    id="addLesson_now"/>
                </div>

                {{-- <button class="absolute bottom-0 right-0 w-16 py-2 m-2 text-black rounded bg-mainwhitebg">
                    <h1>Edit</h1>
                </button> --}}
            </div>

            {{-- main content --}}
            <div class="px-2">
                {{-- overview --}}
                <div class="mb-4">
                    <div class="flex items-center justify-between my-4 border-b-2 border-seagreen">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.615 20H7a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v8m-3 5l2 2l4-4M9 8h4m-4 4h2"/></svg>
                        
                            <h1 class="text-base font-medium">Syllabus</h1>
                        </div>
                        
                        <svg class="cursor-pointer" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M80 0v-160h800V0H80Zm80-240v-150l362-362 150 150-362 362H160Zm80-80h36l284-282-38-38-282 284v36Zm477-326L567-796l72-72q11-12 28-11.5t28 11.5l94 94q11 11 11 27.5T789-718l-72 72ZM240-320Z"/></svg>
                    </div>
                </div>
                
                {{-- course --}}
                <div class="">
                    {{-- description --}}
                    <div class="my-2 mb-4">
                        <h1 class="text-lg font-medium">Course Description</h1>
                        <p class="text-justify">This course provides an overview of the fundamentals of business administration, including key concepts, functions, and processes. Students will develop a solid understanding of various aspects of business operations, management, and decision-making.</p>
                    </div>
                    
                    {{-- breakdown --}}
                    <div class="flex flex-col">
                        <h1 class="text-lg font-medium">Course Breakdown</h1>

                        <table class="table-fixed ">
                            <thead>
                                <th>Name</th>
                                <th>Topic</th>
                            </thead>
                            <tbody class="text-center">
                                <tr>
                                    <td>1</td>
                                    <td>Introduction to eme eme</td>
                                    <td>
                                        <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M504-480 320-664l56-56 240 240-240 240-56-56 184-184Z"/></svg>
                                    </td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Introduction to eme eme</td>
                                    <td>
                                        <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M504-480 320-664l56-56 240 240-240 240-56-56 184-184Z"/></svg>
                                    </td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>Introduction to eme eme</td>
                                    <td>
                                        <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M504-480 320-664l56-56 240 240-240 240-56-56 184-184Z"/></svg>
                                    </td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>Introduction to eme eme</td>
                                    <td>
                                        <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M504-480 320-664l56-56 240 240-240 240-56-56 184-184Z"/></svg>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="flex justify-center w-full">
                            <x-forms.primary-button
                            color="seagreen"
                            name="Add Content"
                            class="text-mainwhitebg"
                            id="lessonAddContent"/>
                        </div>
                        
                        {{-- <button class="self-center w-1/2 py-4 mt-4 text-white rounded-lg shadow-lg bg-seagreen">
                            <h1>Add Content</h1>
                        </button> --}}
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    
    {{-- <div class="fixed z-50 flex items-center hidden w-full h-screen bg-white bg-opacity-50" aria-hidden="true" id="lessonNewContent">
        <div class="relative w-full h-auto pt-8 m-auto mx-4 rounded shadow-lg bg-seagreen" id="lessonChildContent">
            <h1 class="text-xl font-medium text-center text-white">Add New Content</h1>
            <div class="flex flex-col m-4">
                <select class="h-8 px-2 rounded" name="" id="">
                    <option value="" disabled selected>--select lesson/quiz/assignment--</option>
                    <option value="">Lesson 1</option>
                    <option value="">Quiz 1</option>
                </select>
            </div>
            <div class="flex flex-col m-4">
                <input class="h-8 px-2 rounded" type="text" placeholder="Enter Title">
            </div>
            <div class="m-4">
                <textarea class="w-full h-32 px-2 rounded resize-none" name="" id=""  placeholder="Enter Content"></textarea>
            </div>

            <div class="flex items-center justify-end mx-4 mb-4">
                <x-forms.secondary-button name="Close" id="lessonNewContentCloseBtn"/>
                {{-- <button class="px-4 py-2 mx-1 bg-gray-300 rounded-lg shadow-lg" id="lessonNewContentCloseBtn">
                    <h1>Close</h1>
                </button> --}}
                {{-- <button class="w-16 py-2 mx-1 text-white rounded-lg shadow-lg bg-darthmouthgreen">
                    <h1>Save</h1>
                </button> --}}
                <x-forms.primary-button color="amber" name="Save"/>
            </div>
        </div>
    </div>
</section>
@include('partials.footer')