$(document).ready(function() {
    var baseUrl = window.location.href;

    var csrfToken = $('meta[name="csrf-token"]').attr('content');
    getLearnerData()
    
    tinymce.init({  
        selector: '#createNewMessageArea',  
        width: 730
       }); 

       tinymce.init({
        selector: '#reply_textarea',
        width: '100%', // Set TinyMCE width to 100%
        height: $('#reply_textarea').css('height'), // Set TinyMCE height dynamically
        setup: function (editor) {
            editor.on('ResizeEditor', function (e) {
                // Adjust the width dynamically when the editor is resized
                editor.theme.resizeTo($('#reply_textarea').width(), null);
            });
        },
    });

    var mainRecipientList = [];
    checkUrlParam();

    function checkUrlParam() {
        const urlParams = new URLSearchParams(window.location.search);
        const email = urlParams.get('email');
        const type = urlParams.get('type');
        if (email && type) {
            // console.log('Email:', email);
            paramData = {
                email: email,
                type: type
            }

            mainRecipientList.push(paramData);
            // console.log(mainRecipientList);
        tinymce.get('reply_textarea').remove();
        
        displayMainRecipientList(mainRecipientList)
            $('#createNewMessage').removeClass('hidden');
        } else {
            console.log('Email parameter is not present in the URL');
        }
    }



    $('#createNewMessageBtn').on('click', function(){
        $('#createNewMessage').removeClass('hidden');

        
        tinymce.get('reply_textarea').remove();
    })

    $('.closeCreateNewMessage').on('click', function(){
        $('#createNewMessage').addClass('hidden');

        tinymce.init({
            selector: '#reply_textarea',
            width: '100%', // Set TinyMCE width to 100%
            height: $('#reply_textarea').css('height'), // Set TinyMCE height dynamically
            setup: function (editor) {
                editor.on('ResizeEditor', function (e) {
                    // Adjust the width dynamically when the editor is resized
                    editor.theme.resizeTo($('#reply_textarea').width(), null);
                });
            },
        });

        const urlParams = new URLSearchParams(window.location.search);
        const email = urlParams.get('email');
        const type = urlParams.get('type');
        if (email && type) {
    
            var url = window.location.href.split('?')[0]; // Get the URL without query parameters
            window.history.replaceState({}, document.title, url);
            window.location.reload();
        }

    })


    $('#selectRecipientBtn').on('click', function() {
        $('#selectRecipientsModal').removeClass('hidden');    
        tempRecipientList = [];

    })

    $('.closeSelectRecipientsModal').on('click', function() {
        $('#selectRecipientsModal').addClass('hidden');
    })



    $('#recipientName').on('input', function() {
        
        
        $('.searchResultsArea').addClass('hidden')
        var val = $(this).val();

        if (val.includes('@')) {


            var url = baseUrl + "/search_recipient";
    
    
            $.ajax({
                type: "GET",
                data: {
                    search_recipient: val
                },
                url: url,
                success: function(response) {
                    // console.log(response);
                    
                    var results = response['results'];
                    displaySearchResults(results);
                },
                error: function(error) {
                    console.log(error);
                }
            });


            $('.searchResultsArea').removeClass('hidden')
        }
    });

    var tempRecipientList = [];
    function displaySearchResults(results) {
        var searchResultDisp = ``;
        var searchRecipientList = [];
        
        for (let i = 0; i < results.length; i++) {
            const fname = results[i]['fname'];
            const lname = results[i]['lname'];
            const email = results[i]['email'];
            const profile = results[i]['profile'];
            const type = results[i]['type'];
    
            let resultData = {

                email: email,
                type: type
            };
    
            searchRecipientList.push(resultData);
            // console.log(searchRecipientList);
            searchResultDisp += `
                <li>
                    <button data-user-email="${email}" data-user-type="${type}" class="choose_search w-3/5 hover:bg-gray-200">
                        <div class="flex mx-5 my-2">
                            <div class="w-2/12" id="profile_photo_area">
                                <img class="rounded-full w-[35px] h-[35px]" src="/storage/${profile}" alt="">
                            </div>
                            <div class="w-10/12">
                                <div class="flex flex-col items-start justify-start" id="searchuserInfoArea">
                                    <h1 class="font-semibold text-md">${fname} ${lname}</h1>
                                    <h1 class="text-sm font-regular">${email}</h1>
                                </div>
                            </div>
                        </div>   
                    </button>
                </li>
            `;
        }
    
        $('#searchResultsUlArea').empty();
        $('#searchResultsUlArea').append(searchResultDisp);
    
        $('#searchResultsUlArea').on('click', '.choose_search', function(e) {
            e.preventDefault();

            let userEmail = $(this).data('user-email');
            let userType = $(this).data('user-type');
            console.log(searchRecipientList);
            // console.log(tempRecipientList);
            let isExisting = tempRecipientList.some(result => result?.email === userEmail && result.type === userType);
        
            if (!isExisting) {
                let selectedResultData = searchRecipientList.find(result => result.email === userEmail);
                if (selectedResultData) {
                    tempRecipientList.push(selectedResultData);
                    // console.log(tempRecipientList);
                    dispTempResultsData(tempRecipientList);
                } else {
                    console.log("Email not found in searchRecipientList");
                }
            } else {
                console.log("Already exists in tempRecipientList");
            }
        });
        
    }
    
    function dispTempResultsData(tempRecipientList) {
        let tempRecipientDisp = ``;
        // console.log(tempRecipientList);
        for (let j = 0; j < tempRecipientList.length; j++) {
            const email = tempRecipientList[j]['email'];
            const type = tempRecipientList[j]['type'];
    
            tempRecipientDisp += `
                <tr>
                    <td>${email}</td>
                    <td>
                        <button data-user-email="${email}" data-user-type="${type}" class="tempListRemove">
                            <i class="fa-solid fa-xmark" style="color: #025c26;"></i>
                        </button>
                    </td>
                </tr>
            `;
        }
    
        $('.tempSelectedRecipientArea').empty();
        $('.tempSelectedRecipientArea').append(tempRecipientDisp);
    }
    
    // Event delegation for tempListRemove buttons
    $('.tempSelectedRecipientArea').on('click', '.tempListRemove', function() {
        const emailToRemove = $(this).data('user-email');
        const typeToRemove = $(this).data('user-type');
        
        // Remove the element from tempRecipientList
        tempRecipientList = tempRecipientList.filter(item => item.email !== emailToRemove);
    
        // Re-display the updated tempRecipientDisp
        dispTempResultsData(tempRecipientList);
    });
    

    $('#confirmRecipientsBtn').on('click', function() {
        console.log(tempRecipientList);
    
        // Loop through tempRecipientList
        tempRecipientList.forEach(tempRecipient => {
            // Check if the email is already in mainRecipientList
            let isExisting = mainRecipientList.some(mainRecipient => mainRecipient.email === tempRecipient.email && mainRecipient.type === tempRecipient.type);
    
            // If the email is not in mainRecipientList, add it
            if (!isExisting) {
                mainRecipientList.push(tempRecipient);
            }
        });
    
        // Empty tempRecipientList
        tempRecipientList = [];
        displayMainRecipientList(mainRecipientList)
        console.log(mainRecipientList);
        $('#selectRecipientsModal').addClass('hidden');
    });
    

    function displayMainRecipientList(mainRecipientList) {
        // console.log(tempRecipientList);
        var mainRecipientListDisp = ``

        for (let i = 0; i < mainRecipientList.length; i++) {
            const email = mainRecipientList[i]['email'];
            const type = mainRecipientList[i]['type'];
            
            mainRecipientListDisp += `
            <tr>
                <td>${email}</td>
                <td>
                    <button data-user-email="${email}" data-user-type="${type}" class="mainListRemove">
                        <i class="fa-solid fa-xmark" style="color: #025c26;"></i>
                    </button>
                </td>
            </tr>
            `

            $('#mainRecipientListArea').empty()
            $('#mainRecipientListArea').append(mainRecipientListDisp)
        }
    }
    
        $('#mainRecipientListArea').on('click', '.mainListRemove', function() {
        const emailToRemove = $(this).data('user-email');
        const typeToRemove = $(this).data('user-type');
        
        // Remove the element from tempRecipientList
        mainRecipientList = mainRecipientList.filter(item => item.email !== emailToRemove);
        displayMainRecipientList(mainRecipientList)
    });


    var filesArray = []; // Array to store files

    $('#attachments').on('change', function() {
        var fileList = $('#fileList');

        $.each(this.files, function(i, file) {
            filesArray.push(file);
            var fileItem = $('<div>').text(file.name);
            var removeButton = $('<button>').html('<i class="fa-solid fa-xmark" style="color: #025c26;"></i>')
                                            .addClass('removeFileBtn')
                                            .attr('data-file-index', filesArray.length - 1); // Set data attribute to track file index
            fileList.append(fileItem.append(removeButton));
        });
    });

    // Remove file when remove button is clicked
    $(document).on('click', '.removeFileBtn', function() {
        var fileIndex = $(this).attr('data-file-index');
        filesArray.splice(fileIndex, 1); // Remove file from array
        $(this).parent().remove(); // Remove file item from list

        // Update data-file-index attribute for remaining file items
        $('.removeFileBtn').each(function(index) {
            $(this).attr('data-file-index', index);
        });
    });



    $('#confirmSendMessageBtn').on('click', function() {
        var subject = $('#subject').val();
        var content = tinyMCE.get("createNewMessageArea").getContent();
        var emailToReceive = JSON.stringify(mainRecipientList); // Convert array to JSON string
        var filesToSend = filesArray;
    
        var isValid = true;
    
        if (emailToReceive.length === 0) {
            $('#recipientError').text('Please choose a recipient');
            isValid = false;
        } else {
            $('#recipientError').text('');
        }
    
        if (subject === '') {
            $('#subjectError').text('Please enter a message subject.');
            isValid = false;
        } else {
            $('#subjectError').text('');
        }
    
        if (content === '') {
            $('#contentError').text('Please enter a message.');
            isValid = false;
        } else {
            $('#contentError').text('');
        }
    
        if (isValid) {
            var formData = new FormData();
    
            formData.append('subject', subject);
            formData.append('emailToReceive', emailToReceive);
            formData.append('content', content);
    
            if (filesToSend.length > 0) {
                for (var i = 0; i < filesToSend.length; i++) {
                    formData.append('filesToSend[]', filesToSend[i]);
                }
            }
    
            $('#loaderModal').removeClass('hidden');
            var url = baseUrl + "/send";
    
            $.ajax({
                type: "POST",
                url: url,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    // console.log(response);
                    
        $('#loaderModal').addClass('hidden');
                    window.location.reload();
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }
    
    });
    




    getMessages();
    var searchVal  = "";
    function getMessages(searchVal) {

        $('#loaderModal').removeClass('hidden');
        var url = baseUrl + "/getMessages";

        $.ajax({
            type: "GET",
            url: url,
            data: {
                searchVal: searchVal,
            },
            success: function(response) {
                // console.log(response)

                
        $('#loaderModal').addClass('hidden');
                var learnerData = response['learner']
                var messageData = response['messageData'];
                dispSideMessageArea(messageData, learnerData)
            },
            error: function(error) {
                console.log(error);
            }
        });
    }

    $('#search').on('input', function() {
        searchVal = $(this).val();

        getMessages(searchVal)
    })


    function dispSideMessageArea(messageData, learnerData) {
        var sideMessageAreaDisp = ``;

        for (let i = 0; i < messageData.length; i++) {
            const message_id = messageData[i]['message_id'];
            const message_content_id = messageData[i]['message_content_id'];
            const sender_user_type = messageData[i]['sender_user_type'];
            const sender_user_email = messageData[i]['sender_user_email'];
            const sender_name = messageData[i]['sender_name'];
            const sender_profile_picture = messageData[i]['sender_profile_picture'];
            // const receiver_user_type = messageData[i]['receiver_user_type'];
            // const receiver_user_email = messageData[i]['receiver_user_email'];
            // const receiver_name = messageData[i]['receiver_name'];
            // const receiver_profile_picture = messageData[i]['receiver_profile_picture'];
            const date_sent = messageData[i]['date_sent'];
            const isRead = messageData[i]['isRead'];
            const date_read = messageData[i]['date_read'];
            const message_subject = messageData[i]['message_subject'];
            const message_content = messageData[i]['message_content'];
            
            if(learnerData['learner_email'] === sender_user_email) {
                // src="/storage/${profile}"
                sideMessageAreaDisp += `
                <li class="border-b border-darthmouthgreen hover:bg-gray-200">
                    <button data-message-content-id="${message_content_id}" class="w-full selectThisMessage">
                        <div class="flex mx-5 my-2">
                            <div class="w-1/4" id="profile_photo_area">
                                <img class="z-0 w-10 h-10 rounded-full" src="/storage/${sender_profile_picture}" alt="Profile Picture">
                            </div>
                            <div class="w-3/4">
                                <div class="flex flex-col items-start justify-start userInfoArea" id="">
                                    
                                    <h1 class="text-sm font-regular">You</h1>
                                    <h4 class="text-xs text-gray-700">${date_sent}</h4>
                                </div>
                                <div class="text-left " id="previewmessge">
                                    <h1 class="font-semibold text-md">${message_subject}</h1>
                                </div>
                            </div>
                            <div class="">
                                ${isRead === 0 ? `<div class="w-2 h-2 rounded-full bg-darthmouthgreen"></div>` : ``}
                            </div>
                        </div>   
                    </button>
                </li>
                `
            } else {
                sideMessageAreaDisp += `
                <li class="border-b border-darthmouthgreen hover:bg-gray-200">
                    <button data-message-content-id="${message_content_id}" class="w-full selectThisMessage">
                        <div class="flex mx-5 my-2">
                            <div class="w-1/4" id="profile_photo_area">
                                <img class="z-0 w-10 h-10 rounded-full" src="/storage/${sender_profile_picture}" alt="Profile Picture">
                            </div>
                            <div class="w-3/4">
                                <div class="flex flex-col items-start justify-start userInfoArea" id="">
                                    
                                    <h1 class="text-sm font-regular">${sender_name}</h1>
                                    <h4 class="text-xs text-gray-700">${date_sent}</h4>
                                </div>
                                <div class="text-left " id="previewmessge">
                                    <h1 class="font-semibold text-md">${message_subject}</h1>
                                </div>
                            </div>
                            <div class="">
                                ${isRead === 0 ? `<div class="w-2 h-2 rounded-full bg-darthmouthgreen"></div>` : ``}
                            </div>
                        </div>   
                    </button>
                </li>
                `
            }

     
        }

        $('#sideMessageArea').empty();
        $('#sideMessageArea').append(sideMessageAreaDisp);
        setTimeout(getFirstMessage, 100);


        $('.selectThisMessage').on('click', function() {

            var message_content_id = $(this).data('message-content-id')
            // alert(message_content_id)
            messageContentID = message_content_id
            getSelectedMessage(message_content_id)
        })
    }

    
    var messageContentID
    function getFirstMessage() {
        var firstLi = $('#sideMessageArea li:first-child')

        var message_content_id = firstLi.find('.selectThisMessage').data('message-content-id')
        messageContentID = message_content_id
        // alert(message_content_id)
        getSelectedMessage(message_content_id)
    }




    function getSelectedMessage(messageContent) {

            var url = baseUrl + "/getSelectedMessage";
    
            $.ajax({
                type: "GET",
                url: url,
                data: {
                    messageContent: messageContent,
                },
                success: function(response) {
                    // console.log(response)

                    var learnerData = response['learner']
                    var messageData = response['messageData']
                    var replyData = response['replyData']

                    dispMessageArea(messageData, learnerData)
                    dispReplyArea(replyData, learnerData)
                },
                error: function(error) {
                    console.log(error);
                }
            });
    }



    function dispMessageArea(messageData, learner) {
        var messageAreaDisp = ``;
        
            const message_content_id = messageData['message_content_id'];
            const message_subject = messageData['message_subject'];
            const message_content = messageData['message_content'];
            const message_has_file = messageData['message_has_file'];
            const files = messageData['files'];
            const date_sent = messageData['date_sent'];
            const sender_user_type = messageData['sender_user_type'];
            const sender_user_email = messageData['sender_user_email'];
            const sender_profile_picture = messageData['sender_profile_picture'];
            const messages = messageData['messages'];
            const sender_name = messageData['sender_name'];
            

        $('#subjectArea').text(message_subject);
        // $('#replyNowBtn').data('message-content-id' , message_content_id)
        messageContentID = message_content_id

        messageAreaDisp += `
            <div class="flex items-center justify-between" id="userInfoArea">
                <div class="flex items-start">
                    <div class="" id="profile_photo_area">
                        <img class="z-0 w-12 h-12 rounded-full" src="/storage/${sender_profile_picture}" alt="Profile Picture">
                    </div>
                    <div class="ml-3">
                        <h1 class="text-lg font-semibold">${sender_name}</h1>
                        <h4 class="text-gray-700 text-md">to `
                        messages.forEach((message, index) => {
                            const type = message['receiver_user_type'].toLowerCase();
                            const receiverEmail = message['receiver_user_email'];
                            if (index !== messages.length - 1) {
                                if (receiverEmail !== learner['learner_email']) {
                                    messageAreaDisp += `<a href="/learner/profile/${type}/${receiverEmail}">${receiverEmail}</a>, `;
                                } else {
                                    messageAreaDisp += `<a href="/learner/profile">${receiverEmail}</a>, `;
                                }
                            } else {
                                messageAreaDisp += `<a href="/learner/profile/${type}/${receiverEmail}">${receiverEmail}</a>`;
                            }
                        });
                        


        messageAreaDisp +=        `</h4>
                    </div>
                </div>

                <div class="flex items-start justify-between pr-5 " id="userInfoArea">
                        <h4 class="text-gray-700 text-md">${date_sent}</h4>
                </div>
            </div>

            <div class="px-16 mt-10" id="messageContent">
                <div class="contentArea text-xl font-normal mt-5 px-5" style="white-space: pre-wrap">${message_content}</div>
            </div>

            <div class="px-16 mt-40 mb-16" id="messageContentPhotos">
                <div class="flex flex-wrap photoArea">`
                if (files && files.length > 0) {
                files.forEach(file => {
                    const filePath = file['message_content_file'];
                    if (filePath.endsWith('.png') || filePath.endsWith('.jpg') || filePath.endsWith('.jpeg') || filePath.endsWith('.gif')) {
                        // Display photo
                        messageAreaDisp += `
                            <img src="/storage/${filePath}" alt="Photo" class="w-48 h-48 m-2">
                        `;
                    }
                });
                }
                
                messageAreaDisp +=    `</div>
            </div>

            <div class="px-16 mt-40" id="messageContentFiles">
                <div class="flex flex-wrap fileArea">`
                if (files && files.length > 0) {
                files.forEach(file => {
                    const filePath = file['message_content_file'];
                    if (!(filePath.endsWith('.png') || filePath.endsWith('.jpg') || filePath.endsWith('.jpeg') || filePath.endsWith('.gif'))) {
                        // Display downloadable link for document
                        messageAreaDisp += `
                            <a href="/storage/${filePath}" class="hover:text-darthmouthgreen" download="${filePath.split('/').pop()}">Download ${filePath.split('/').pop()}</a>
                        `;
                    }
                });
            }
                messageAreaDisp +=    `</div>
            </div>
        
        `


            $('#mainMessage').empty();
            $('#mainMessage').append(messageAreaDisp);
    }


    function dispReplyArea(replyData, learner) {
        var replyAreaDisp = ``;
    
        for (let i = 0; i < replyData.length; i++) {
            const reply_user_email = replyData[i]['reply_user_email'];
            const reply_user_type = replyData[i]['reply_user_type'];
            const reply_profile_picture = replyData[i]['reply_profile_picture'];
            const reply_name = replyData[i]['reply_name'];
            const message_reply_content = replyData[i]['message_reply_content'];
            const date_sent = replyData[i]['date_sent'];
            const fileContents = replyData[i]['fileContents'];
            
    
            replyAreaDisp += `
            <div class="border-b border-darthmouthgreen mainMessageReplyArea">
                <div class="flex items-center justify-between" id="userInfoArea">
                    <div class="flex items-start">
                        <div class="" id="profile_photo_area">
                            <img class="z-0 w-12 h-12 rounded-full" src="/storage/${reply_profile_picture}" alt="Profile Picture">
                        </div>
                        <div class="ml-3">
                            <h1 class="text-lg font-semibold">${reply_name}</h1>
                            <h4 class="text-gray-700 text-md">`
                            
                            const type = reply_user_type.toLowerCase();
                            const receiverEmail = reply_user_email;
                            if (receiverEmail !== learner['learner_email']) {
                                replyAreaDisp += `<a href="/learner/profile/${type}/${reply_user_email}">${reply_user_email}</a>, `;
                            } else {
                                replyAreaDisp += `<a href="/learner/profile">${receiverEmail}</a>, `;
                            }
                            
            replyAreaDisp +=      `</h4>
                        </div>
                    </div>
        
                    <div class="flex items-start justify-between pr-5 " id="userInfoArea">
                            <h4 class="text-gray-700 text-md">${date_sent}</h4>
                    </div>
                </div>
        
                <div class="px-16 mt-10" id="messagereplyContent">
                    <div class="contentArea text-xl font-normal mt-5 px-5" style="white-space: pre-wrap">${message_reply_content}</div>
                </div>
        
                <div class="px-16 mt-40 mb-16" id="messagereplyContentPhotos">
                    <div class="flex flex-wrap replyPhotoArea">`
                    if (fileContents && fileContents.length > 0) {
                    fileContents.forEach(file => {
                        const filePath = file['message_reply_content_file'];
                        if (filePath.endsWith('.png') || filePath.endsWith('.jpg') || filePath.endsWith('.jpeg') || filePath.endsWith('.gif')) {
                            // Display photo
                            replyAreaDisp += `
                                <img src="/storage/${filePath}" alt="Photo" class="w-48 h-48 m-2">
                            `;
                        }
                    });
                }
                    replyAreaDisp +=    `</div>
                </div>
        
                <div class="px-16 mt-40" id="messagereplyContentFiles">
                    <div class="flex flex-wrap replyFileArea">`
                    if (fileContents && fileContents.length > 0) {
                    fileContents.forEach(file => {
                        const filePath = file['message_reply_content_file'];
                        if (!(filePath.endsWith('.png') || filePath.endsWith('.jpg') || filePath.endsWith('.jpeg') || filePath.endsWith('.gif'))) {
                            // Display downloadable link for document
                            replyAreaDisp += `
                                <a href="/storage/${filePath}" class="hover:text-darthmouthgreen" download="${filePath.split('/').pop()}">Download ${filePath.split('/').pop()}</a>
                            `;
                        }
                    });
                }
                    replyAreaDisp +=     `</div>
                </div>
            </div>
        
        `
    
    
        }
    
        $('#mainMessageReplyContainer').empty();
        $('#mainMessageReplyContainer').append(replyAreaDisp);
    }
    



    var replyNowFilesArray = [];

    $('#reply_photo_upload, #reply_document_upload').on('change', function() {
        var replyNowFileList = $('#replyNowFileList');
    
        $.each(this.files, function(i, file) {
            replyNowFilesArray.push(file);
            var replyNowFileItem = $('<div>').text(file.name);
            var removeButton = $('<button>').html('<i class="fa-solid fa-xmark" style="color: #025c26;"></i>')
                                            .addClass('removeReplyNowFileBtn')
                                            .attr('data-file-index', replyNowFilesArray.length - 1); // Set data attribute to track file index
            replyNowFileList.append(replyNowFileItem.append(removeButton));
        });
        // console.log(replyNowFilesArray);
    });
    
    $(document).on('click', '.removeReplyNowFileBtn', function() {
        var fileIndex = $(this).attr('data-file-index');
        replyNowFilesArray.splice(fileIndex, 1); // Remove file from array
        $(this).parent().remove(); // Remove file item from list
    
        // Update data-file-index attribute for remaining file items
        $('.removeReplyNowFileBtn').each(function(index) {
            $(this).attr('data-file-index', index);
        });

        // console.log(replyNowFilesArray);
    });
    



    $('#replyNowBtn').on('click', function() {
        // alert(messageContentID)
        var content = tinyMCE.get("reply_textarea").getContent();
        var filesToSend = replyNowFilesArray;
    
        var isValid = content.trim().length > 0 || filesToSend.length > 0;
        $('#loaderModal').removeClass('hidden')
    
        if (!isValid) {
            $('#replyError').text('Please enter your message');
        } else {
            var formData = new FormData();
    
            formData.append('messageContentID', messageContentID);
            formData.append('content', content);
    
            if (filesToSend.length > 0) {
                for (var i = 0; i < filesToSend.length; i++) {
                    formData.append('filesToSend[]', filesToSend[i]);
                }
            }
    
            var url = baseUrl + "/reply";
    
            $.ajax({
                type: "POST",
                url: url,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    // console.log(response);
                    $('#loaderModal').addClass('hidden')
                    window.location.reload();
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }
    
    });



        
    function getLearnerData() {
        var url = `/learner/learnerData`;
            $.ajax({
                type: "GET",
                url: url,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(response) {
                    console.log(response);

                    var learner = response['learner']
                    var session_id = learner['learner_id']


                    $.when (
                        init_chatbot(session_id),
                        add_learner_data(session_id)
                    ).then (function() {
                        process_files(session_id)

                        $('.submitQuestion').on('click', function(e) {
                            e.preventDefault();
                            submitQuestion();
                        });
            
                        $('.question_input').on('keydown', function(e) {
                            if (e.keyCode === 13) {
                                e.preventDefault();
                                submitQuestion();
                            }
                        });
            
                        function submitQuestion() {
                            var learner_id = learner['learner_id'];
                            var question = $('.question_input').val();
                            var course = 'ALL';
                            var lesson = 'ALL';
            
                            displayUserMessage(question, learner);
                            $('.botloader').removeClass('hidden');
                            var chatData = {
                                question: question,
                                course: course,
                                lesson: lesson,
                            };
            
                            var url = `/chatbot/chat/${learner_id}`;
                            $.ajax({
                                type: "POST",
                                url: url,
                                data: chatData,
                                headers: {
                                    'X-CSRF-TOKEN': csrfToken
                                },
                                success: function(response) {
                                    console.log(response);
                                    displayBotMessage(response);
                                    $('.question_input').val('')
                                },
                                error: function(error) {
                                    console.log(error);
                                }
                            });
                        }
                    })

                },
                error: function(error) {
                    console.log(error);
                }
            });
    }

    
    function init_chatbot(learner_id) {
        // var learner_id = learner['learner_id'];
        var url = `/chatbot/init/${learner_id}`;
        $.ajax({
            type: "GET",
            url: url,
            success: function(response) {
                console.log(response);
            },
            error: function(error) {
                console.log(error);
            }
        });
    }


    
    function add_learner_data(learner_id) {
        // console.log(learner);
        var url = `/chatbot/learner/${learner_id}`;
        $.ajax({
            type: "GET",
            url: url,
            success: function(response) {
                console.log(response);

                 },
                 error: function(error) {
                     console.log(error);
                 }
             });
}

    function process_files(session_id) {
        var url = `/chatbot/process/${session_id}`;
        $.ajax({
            type: "GET",
            url: url,
            success: function(response) {
                console.log(response);

                $('.loaderArea').addClass('hidden');
                $('.mainchatbotarea').removeClass('hidden');
            },
            error: function(error) {
                console.log(error);
            }
        });
    }



    function displayUserMessage(question, learner) {
        var userMessageDisp = ``;
        var profile = learner['profile_picture']
        var currentTime = new Date();
        var hours = currentTime.getHours();
        var minutes = currentTime.getMinutes();

        minutes = minutes < 10 ? '0' + minutes : minutes;

        var timeString = hours + ':' + minutes;
    
        userMessageDisp += `
        
        <div class="mx-3 chat chat-end">
            <div class="chat-image avatar">
                <div class="w-10 rounded-full">
                <img class="bg-red-500" alt="" src="/storage/${profile}" />
                </div>
            </div>
            <div class="mx-3 chat-header">
                You
            </div>
            <div class="whitespace-pre-wrap chat-bubble chat-bubble-primary">${question}</div>
            <div class="opacity-50 chat-footer">
            ${timeString}
            </div>
        </div>
        `;

        $('.chatContainer').append(userMessageDisp);
    }


    function displayBotMessage(response) {

        var message = response['message']

        var botMessageDisp = ``
        botMessageDisp += `
        
        <div class="chat chat-start">
            <div class="chat-image avatar">
                <div class="w-10 rounded-full">
                <img class="bg-white" alt="" src="../../storage/images/chatbot.png" />
                </div>
            </div>
            <div class="chat-bubble ">${message}</div>
        </div>
        `;

        $('.botloader').addClass('hidden')
        $('.chatContainer').append(botMessageDisp);
    }
    
})


