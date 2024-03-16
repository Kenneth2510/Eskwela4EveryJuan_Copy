@extends('layouts.instructor_layout')

@section('content')
    {{-- MAIN --}}
    <section class="w-full h-screen md:w-3/4 lg:w-10/12">
        <div class="h-full px-2 py-4 pt-24 overflow-hidden overflow-y-scroll rounded-lg shadow-lg md:pt-0">
            <div class="relative space-y-4">
                <a href="{{ url("/instructor/discussions") }}" class="my-2 bg-gray-300 rounded-full ">
                    <svg  xmlns="http://www.w3.org/2000/svg" height="30" viewBox="0 -960 960 960" width="24"><path d="M560-240 320-480l240-240 56 56-184 184 184 184-56 56Z"/></svg>
                </a>
                <h1 class="text-2xl font-semibold md:text-3xl">DISCUSSION FORUMS</h1>
                <h1 class="text-xl font-semibold md:text-2xl">Create a Discussion</h1>                
            </div>

            
            <div class="w-full pt-5" id="mainContainer">
                <div class="w-3/5" id="selectCommunityArea">
                    <label for="">Choose a group to post:</label>
                    <select name="" class="w-1/2 p-2 border-2 border-darthmouthgreen rounded-xl" id="selectCommunity">
                        <option value="0" selected>All</option>
                        @foreach ($courses as $course)
                            <option value="{{ $course->course_id }}">{{ $course->course_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="w-full p-5 mt-5 border-2 rounded-lg border-darthmouthgreen border-opacity-60" id="threadContentArea">
                    <div class="flex w-full divide-x-2" id="threadContentCategoryArea">
                        <button class="w-1/3 py-3 text-white px3 bg-darthmouthgreen hover:bg-green-950 discussionBtn_selected rounded-s-xl" id="textCategoryBtn">Post/Text</button>
                        <button class="w-1/3 py-3 text-white px3 bg-darthmouthgreen hover:bg-green-950" id="photoCategoryBtn">Photo</button>
                        <button class="w-1/3 py-3 text-white rounded-r-xl px3 bg-darthmouthgreen hover:bg-green-950" id="urlCategoryBtn">Link</button>
                    </div>

                    <div class="mt-5" id="threadTitleArea" style="display: flex; flex-direction: column;">
                        <label class="px-3 " for="threadTitle_text" id="threadTitle_lbl">Title</label>
                        <div style="display: flex; position: relative;">
                            <textarea maxlength="300" class="w-full px-3 py-3 mt-1 border-2 rounded-lg border-darthmouthgreen border-opacity-60" id="threadTitle_text" placeholder="Title" oninput="updateCharacterCount(this)"></textarea>
                            <span id="characterCount" class="px-3 py-2 text-sm text-gray-500" style="position: absolute; bottom: 0; right: 0;">0/300</span>
                        </div>
                    </div>
                    
                    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
                    <script>
                        function updateCharacterCount(textarea) {
                            const maxLength = 300;
                            const currentLength = $(textarea).val().length;
                            const remainingLength = maxLength - currentLength;
                    
                            if (remainingLength >= 0) {
                                $("#characterCount").text(currentLength + '/' + maxLength);
                            } else {
                                // If the user exceeds the limit, truncate the textarea value
                                $(textarea).val($(textarea).val().substring(0, maxLength));
                            }
                        }
                    </script>


                    <div class="mt-5" id="threadContent">
                        <div class="" id="textContent">
                            <textarea name="" class="p-3 w-full h-[300px] min-h-[300px] border-2 border-darthmouthgreen border-opacity-60 rounded-lg " id="threadContent_text" placeholder="text"></textarea>
                        </div>
                    </div>
                    <div class="flex justify-end w-full mt-5" id="postBtnArea">
                        <button class="p-4 text-white bg-darthmouthgreen hover:bg-green-950 rounded-xl" id="postBtn">POST</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
{{-- @include('partials.chatbot') --}}

<div id="loaderModal" class="fixed top-0 left-0 z-50 flex items-center justify-center hidden w-full h-full bg-gray-200 bg-opacity-75 ">
    <div class="flex flex-col items-center justify-center w-full h-screen p-4 bg-white rounded-lg shadow-lg modal-content md:h-1/3 lg:w-1/3">
        <span class="loading loading-spinner text-primary loading-lg"></span> 
        <p class="mt-5 text-xl text-darthmouthgreen">loading</p>  
    </div>
</div>


  <div id="successModal" class="fixed top-0 left-0 z-50 flex items-center justify-center hidden w-full h-full bg-gray-200 bg-opacity-75 ">
      <div class="modal-content flex flex-col justify-center items-center p-20 bg-white p-4 rounded-lg shadow-lg w-[500px]">
          <i class="fa-regular fa-circle-check text-[75px] text-darthmouthgreen"></i>
          <p class="mt-5 text-xl text-darthmouthgreen">Successful</p>  
      </div>
  </div>


    <div id="errorModal" class="fixed top-0 left-0 z-50 flex items-center justify-center hidden w-full h-full bg-gray-200 bg-opacity-75 ">
        <div class="modal-content flex flex-col justify-center items-center p-20 bg-white p-4 rounded-lg shadow-lg w-[500px]">
            <i class="fa-regular fa-circle-xmark text-[75px] text-red-500"></i>
            <p class="mt-5 text-xl text-darthmouthgreen">Error</p>  
        </div>
    </div>
@endsection
