@include('partials.header')

<section class="flex flex-row w-screen text-sm main-container bg-mainwhitebg md:text-base">
@include('partials.sidebar')




<section class="w-screen px-2 pt-[40px] mx-2 mt-2  overscroll-auto md:overflow-auto">
    <div class="flex justify-between px-10">
        <h1 class="text-6xl font-bold text-darthmouthgreen">Message</h1>
        <div class="">
            <p class="text-xl font-semibold text-darthmouthgreen">{{$admin->admin_codename}}</p>
        </div>
    </div>

    <div class="flex mt-10" style="height: 87%;" id="upper_container">

        <div class="w-3/12 h-full py-10 overflow-y-auto bg-white shadow-lg" id="upper_left_container">
            
            <div class="flex flex-col items-center justify-start w-full pb-5 border-b-2 border-b-darthmouthgreen" id="search_area">
                <input type="text" name="search" id="search" class="px-5 py-3 bg-gray-200 rounded-full" placeholder="search">

                <button id="createNewMessageBtn" class="px-5 py-3 mt-3 text-white bg-darthmouthgreen rounded-2xl hover:border-2 hover:bg-white hover:border-darthmouthgreen hover:text-darthmouthgreen">Create Message</button>
            </div>

            <div class="w-full" id="message_list_area">
                <ul id="sideMessageArea">



                   
                    
                </ul>
            </div>

        </div> 
        <div class="w-9/12 h-full bg-white shadow-lg rounded-xl" id="upper_right_container">
            <h1 class="px-5 pt-10 text-2xl font-semibold text-darthmouthgreen" id="subjectArea"></h1>
            
            <hr class="px-5 pt-10 border-t-2 border-gray-300">
            
            <div class="flex flex-col justify-between" style="height: 80%;" id="mainMessageContainer">

                <div class="h-full px-5 overflow-y-auto " id="messageContentArea">
                    <div class="flex-grow overflow-y-auto" id="messageContainer">

                        <div class="pb-20 border-b border-darthmouthgreen" id="mainMessage">
              
                        </div> 
            
                        {{-- replies area --}}
                        <div class="pb-20 mt-3" id="mainMessageReplyContainer">
                            
                        </div>
                        



                    </div>
                </div>


                
                <div class="w-full px-5" style="height: 35%;" id="conversationReplyArea">
                    <span id="replyError" class="text-red-500"></span>
                    <div class="flex items-end">
                        <label for="reply_photo_upload" class="px-5 py-3 mx-1 text-white rounded-full bg-darthmouthgreen hover:bg-white hover:text-darthmouthgreen hover:border hover:border-darthmouthgreen"><i class="fa-solid fa-image" style="color: #ffffff;"></i></label>
                        <input type="file" id="reply_photo_upload" name="reply_photo_upload[]" accept="image/*" multiple style="display: none;">
                        <label for="reply_document_upload" class="px-5 py-3 mx-1 text-white rounded-full bg-darthmouthgreen hover:bg-white hover:text-darthmouthgreen hover:border hover:border-darthmouthgreen"><i class="fa-solid fa-file" style="color: #ffffff;"></i></label>
                        <input type="file" id="reply_document_upload" name="reply_document_upload[]" accept=".pdf,.doc,.docx" multiple style="display: none;">
                        
                        <textarea style="height: 280px;" name="reply_textarea" id="reply_textarea" class="w-9/12 p-3 border rounded-lg max-w-10/12 border-darthmouthgreen"></textarea>
                        <button id="replyNowBtn" class="px-5 py-3 ml-2 text-white bg-darthmouthgreen hover:bg-white hover:text-darthmouthgreen hover:border hover:border-darthmouthgreen rounded-xl">Send</button>
                    </div>
                    <div id="replyNowFileList"></div>
                </div>
                
                
            </div>
        </div>
    </div>



</section>
</section>

<div id="createNewMessage" class="fixed top-0 left-0 flex items-center justify-center hidden w-full h-full bg-gray-200 bg-opacity-75">
    <div class="modal-content bg-white p-4 rounded-lg shadow-lg w-[750px]">
        <div class="flex justify-end w-full">
            <button class="closeCreateNewMessage">
                <i class="text-xl fa-solid fa-xmark" style="color: #949494;"></i>
            </button>
        </div>
        <h2 class="mb-2 text-2xl font-semibold text-darthmouthgreen">Create Message</h2>
        <div class="mt-4">
            <label for="recipient" class="text-lg font-semibold">Send to</label>
            <div class="flex items-center justify-between recipientArea">
                <div class="block w-10/12 h-12 px-4 py-2 mt-2 overflow-y-auto border border-gray-300 rounded-md focus:ring focus:ring-seagreen focus:ring-opacity-50" id="recipientArea">
                    <table class="">
                        <tbody id="mainRecipientListArea">
        
                        </tbody>
                    </table>
                </div>
                <button id="selectRecipientBtn" class="py-3 mt-3 text-white px-7 bg-darthmouthgreen rounded-xl hover:bg-white hover:text-darthmouthgreen hover:border hover:border-darthmouthgreen">Select</button>
            </div>
            <span id="recipientError" class="text-red-500"></span>
           </div>

        <div class="mt-4">
            <label for="subject" class="text-lg font-semibold">Enter Subject</label>
            <input type="text" name="subject" id="subject" class="block w-full px-4 py-2 mt-2 border border-gray-300 rounded-md focus:ring focus:ring-seagreen focus:ring-opacity-50">
            <span id="subjectError" class="text-red-500"></span>
        </div>

        <div class="mt-4">
            <label for="messageContent" class="text-lg font-semibold">Enter Message</label>
            <textarea
              name="createNewMessageArea"
                id="createNewMessageArea"
                class="block w-full px-4 py-2 mt-2 border border-gray-300 rounded-md focus:ring focus:ring-seagreen focus:ring-opacity-50"
                cols="30"
                rows="10"
                style="white-space: pre;"
              ></textarea>
              <span id="contentError" class="text-red-500"></span>
        </div>
        
        <div class="mt-4">
            <label for="attachments" class="text-lg font-semibold">Attach Photos/Documents</label>
            <input type="file" name="attachments" id="attachments" class="block w-full px-4 py-2 mt-2 border border-gray-300 rounded-md focus:ring focus:ring-seagreen focus:ring-opacity-50" multiple>
            <div id="fileList"></div>
        </div>
        
        <div class="flex justify-center w-full mt-5">
            <button id="confirmSendMessageBtn" class="py-3 mx-1 mt-3 text-white px-7 bg-darthmouthgreen rounded-xl hover:bg-white hover:text-darthmouthgreen hover:border hover:border-darthmouthgreen">Confirm</button>
            <button id="" class="py-3 mx-1 mt-3 text-white bg-red-500 px-7 rounded-xl hover:bg-white hover:text-red-500 hover:border hover:border-red-500 closeCreateNewMessage">Cancel</button>
        </div>
    </div>
</div>


<div id="selectRecipientsModal" class="fixed top-0 left-0 flex items-center justify-center hidden w-full h-full bg-gray-200 bg-opacity-75">
    <div class="modal-content bg-white p-4 rounded-lg shadow-lg w-[750px]">
        <div class="flex justify-end w-full">
            <button class="closeSelectRecipientsModal">
                <i class="text-xl fa-solid fa-xmark" style="color: #949494;"></i>
            </button>
        </div>
        <h2 class="mb-2 text-2xl font-semibold text-darthmouthgreen">Select Recipients</h2>
  
        <input type="text" name="recipientName" id="recipientName" class="w-full px-3 py-1 bg-gray-100 text-md rounded-xl" placeholder="Enter email">
        <div class="hidden searchResultsArea">
            <ul class="overflow-y-auto max-h-40" id="searchResultsUlArea">
      
            </ul>
        </div>
        <div class="block w-full px-4 py-2 mt-2 overflow-y-auto border border-gray-300 rounded-md max-h-40 focus:ring focus:ring-seagreen focus:ring-opacity-50" id="recipientArea">
            <table class="">
                <tbody class="overflow-y-auto max-h-60 tempSelectedRecipientArea">

                </tbody>
            </table>
        </div>
        <div class="flex justify-center w-full mt-5">
            <button id="confirmRecipientsBtn" class="py-3 mx-1 mt-3 text-white px-7 bg-darthmouthgreen rounded-xl hover:bg-white hover:text-darthmouthgreen hover:border hover:border-darthmouthgreen">Add</button>
            <button id="" class="py-3 mx-1 mt-3 text-white bg-red-500 px-7 rounded-xl hover:bg-white hover:text-red-500 hover:border hover:border-red-500 closeSelectRecipientsModal">Cancel</button>
        </div>
    </div>
</div>
@include('partials.footer')