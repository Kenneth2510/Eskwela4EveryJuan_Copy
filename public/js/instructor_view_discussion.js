$(document).ready(function() {

    var baseUrl = window.location.href;
    
    var csrfToken = $('meta[name="csrf-token"]').attr('content');

    tinymce.init({
        selector: '#commentInput',
        width: '90%', // Set TinyMCE width to 100%
        height: $('#commentInput').css('height'), // Set TinyMCE height dynamically
        setup: function (editor) {
            editor.on('ResizeEditor', function (e) {
                // Adjust the width dynamically when the editor is resized
                editor.theme.resizeTo($('#commentInput').width(), null);
            });
        },
    });

    getThreadComments();

    function getThreadComments() {
        var url = baseUrl + "/comments";
        var sortVal = $('#sortComments').val();
 
        $.ajax({
            type: "GET",
            url: url,
            data: {
                sortVal: sortVal
            },
            success: function(response) {
                console.log(response)

                var threadData = response['threadData']
                displayThreadComments(threadData)
            },
            error: function(error) {
                console.log(error);
            }
        });
    }

    $('#sortComments').on('click', function() {
        getThreadComments();
    })

    function formatCreatedAt(created_at) {
        const date = new Date(created_at);
        
        // Month names array
        const monthNames = [
            'January', 'February', 'March', 'April',
            'May', 'June', 'July', 'August',
            'September', 'October', 'November', 'December'
        ];
    
        // Format the date
        const formattedDate = `${monthNames[date.getMonth()]} ${date.getDate()}, ${date.getFullYear()} ${formatTime(date)}`;
    
        return formattedDate;
    }
    
    function formatTime(date) {
        let hours = date.getHours();
        const minutes = date.getMinutes();
        const seconds = date.getSeconds();
        const ampm = hours >= 12 ? 'PM' : 'AM';
    
        // Convert hours to 12-hour format
        hours = hours % 12;
        hours = hours ? hours : 12; // the hour '0' should be '12'
    
        // Format the time
        const formattedTime = `${addZeroBefore(hours)}:${addZeroBefore(minutes)}:${addZeroBefore(seconds)} ${ampm}`;
    
        return formattedTime;
    }
    
    function addZeroBefore(number) {
        return number < 10 ? `0${number}` : number;
    }

    function displayThreadComments(threadData) {
        threadCommentsDisp =``;

        for (let i = 0; i < threadData.length; i++) {
            const thread_comment_id = threadData[i]['thread_comment_id'];
            const user_id = threadData[i]['user_id'];
            const user_type = threadData[i]['user_type'];
            const thread_comment = threadData[i]['thread_comment'];
            const created_at = threadData[i]['created_at'];
            const formatted_created_at = formatCreatedAt(created_at);
            const base_upvote = threadData[i]['base_upvote'];
            const randomized_display_upvote = threadData[i]['randomized_display_upvote'];
            const first_name = threadData[i]['first_name'];
            const last_name = threadData[i]['last_name'];
            const profile_picture = threadData[i]['profile_picture'];
            const comment_reply_count = threadData[i]['comment_reply_count'];
            const reply_reply_count = threadData[i]['reply_reply_count'];
            const total_replies_count = threadData[i]['total_replies_count'];
            const replies = threadData[i]['replies'];

            threadCommentsDisp += `
            <div data-thread-comment-id="${thread_comment_id}" class="my-10 commentContainer">
                <div class="w-full flex items-center" id="userInfoArea">
                    <div class="rounded-full w-[35px] h-[35px] bg-green-950 mx-3">
                        <img class="rounded-full w-[35px] h-[35px]" src="/storage/${profile_picture}" alt="">
                    </div>
                    <h1 class="ml-1 text-lg font-semibold">${first_name} ${last_name}</h1>
                    <h1 class="mx-5 text-md font-normal opacity-60">${formatted_created_at}</h1>
                </div>
                <div class="mx-7 px-10 w-full border-l-2 border-darthmouthgreen border-opacity-60" id="commentContentArea">
                    <div class="" id="comment">${thread_comment}</div>
                    <div class="flex items-center mt-5" id="commentUpvoteArea">
                        <div class="flex items-center">
                            <button data-thread-comment-id="${thread_comment_id}" class="mr-3 comment_upvote_button">
                                <i class="text-darthmouthgreen fa-regular fa-circle-up text-2xl"></i>
                            </button>
                            <span class="text-darthmouthgreen comment_upvote_count" id="comment_upvote_count_${thread_comment_id}">${randomized_display_upvote}</span>
                            <button data-thread-comment-id="${thread_comment_id}" class="ml-3 comment_downvote_button">
                                <i class="text-darthmouthgreen fa-regular fa-circle-down text-2xl"></i>
                            </button>
                        </div>
                        <div class="mx-10 flex items-center" id="replyCount">
                            <i class="fa-regular fa-comment text-darthmouthgreen text-2xl"></i>
                            <span class="mx-3 text-darthmouthgreen">${comment_reply_count}</span>
                            <button class="px-3 py-1 bg-darthmouthgreen hover:bg-green-950 rounded-lg text-white attemptReplyBtn">reply</button>
                        </div>
                    </div>
            
                    <div class="mt-3" id="replyArea">
                        `;
                        for (let j = 0; j < replies.length; j++) {
                            const thread_comment_reply_id = replies[j]['thread_comment_reply_id'];
                            const user_id = replies[j]['user_id'];
                            const user_type = replies[j]['user_type'];
                            const created_at = replies[j]['created_at'];
                            const formatted_created_at = formatCreatedAt(created_at);
                            const thread_comment_reply = replies[j]['thread_comment_reply'];
                            const base_upvote = replies[j]['base_upvote'];
                            const randomized_display_upvote = replies[j]['randomized_display_upvote'];
                            const first_name = replies[j]['first_name'];
                            const last_name = replies[j]['last_name'];
                            const profile_picture = replies[j]['profile_picture'];
                            const reply_reply_count = replies[j]['reply_reply_count'];
                            const nestedReplies = replies[j]['nestedReplies'];
                            const totalCount = nestedReplies.length;

                            threadCommentsDisp += `
                            <div data-thread-comment-id="${thread_comment_id}" data-thread-comment-reply-id="${thread_comment_reply_id}" class="replyContainer" id="replyContainer_${thread_comment_reply_id}">
                                <div class="w-full flex items-center" id="userInfoArea">
                                    <div class="rounded-full w-[35px] h-[35px] bg-green-950 mx-3">
                                        <img class="rounded-full w-[35px] h-[35px]" src="/storage/${profile_picture}" alt="">
                                    </div>
                                    <h1 class="ml-1 text-lg font-semibold">${first_name}  ${last_name}</h1>
                                    <h1 class="mx-5 text-md font-normal opacity-60">${formatted_created_at}</h1>
                                </div>
                                <div class="mx-7 px-10 w-full border-l-2 border-darthmouthgreen border-opacity-60" id="commentContentArea">
                                    <div class="" id="comment">${thread_comment_reply}</div>
                                    <div class="flex items-center mt-5" id="commentUpvoteArea">
                                        <div class="flex items-center">
                                            <button data-thread-comment-id="${thread_comment_id}" data-thread-comment-reply-id="${thread_comment_reply_id}" class="mr-3 reply_upvote_button">
                                                <i class="text-darthmouthgreen fa-regular fa-circle-up text-2xl"></i>
                                            </button>
                                            <span class="text-darthmouthgreen reply_upvote_count" id="reply_upvote_count_${thread_comment_reply_id}">${randomized_display_upvote}</span>
                                            <button data-thread-comment-id="${thread_comment_id}" data-thread-comment-reply-id="${thread_comment_reply_id}" class="ml-3 reply_downvote_button">
                                                <i class="text-darthmouthgreen fa-regular fa-circle-down text-2xl"></i>
                                            </button>
                                        </div>
                                        <div class="mx-10 flex items-center" id="replyCount">
                                            <i class="fa-regular fa-comment text-darthmouthgreen text-2xl"></i>
                                            <span class="mx-3 text-darthmouthgreen">${totalCount}</span>
                                            <button data-thread-comment-reply-id="${thread_comment_reply_id}" class="px-3 py-1 bg-darthmouthgreen hover:bg-green-950 rounded-lg text-white attemptCommentReplyBtn">reply</button>
                                        </div>
                                    </div>
                
                                    <div class="mt-5" id="replyReplyArea">
                                    `;

                                    for (let k = 0; k < nestedReplies.length; k++) {
                                        const thread_reply_reply_id = nestedReplies[k]['thread_reply_reply_id'];
                                        const user_id = nestedReplies[k]['user_id'];
                                        const user_type = nestedReplies[k]['user_type'];
                                        const thread_reply_reply = nestedReplies[k]['thread_reply_reply'];
                                        const created_at = nestedReplies[k]['created_at'];
                                        const formatted_created_at = formatCreatedAt(created_at);
                                        const base_upvote = nestedReplies[k]['base_upvote'];
                                        const randomized_display_upvote = nestedReplies[k]['randomized_display_upvote'];
                                        const first_name = nestedReplies[k]['first_name'];
                                        const last_name = nestedReplies[k]['last_name'];
                                        const profile_picture = nestedReplies[k]['profile_picture'];
                                        
                                        threadCommentsDisp += `
                                        <div data-thread-reply-reply-id="${thread_reply_reply_id}" class="replyReplyContainer" id="replyReplyContainer_${thread_reply_reply_id}">
                                            <div class="w-full flex items-center" id="userInfoArea">
                                                <div class="rounded-full w-[35px] h-[35px] bg-green-950 mx-3">
                                                    <img class="rounded-full w-[35px] h-[35px]" src="/storage/${profile_picture}" alt="">
                                                </div>
                                                <h1 class="ml-1 text-lg font-semibold">${first_name} ${last_name}</h1>
                                                <h1 class="mx-5 text-md font-normal opacity-60">${formatted_created_at}</h1>
                                            </div>
                                            <div class="mx-7 px-10 w-full border-l-2 border-darthmouthgreen border-opacity-60" id="commentContentArea">
                                                <div class="" id="comment">${thread_reply_reply}</div>
                                                <div class="flex items-center mt-5" id="commentUpvoteArea">
                                                    <div class="flex items-center">
                                                        <button data-thread-comment-id="${thread_comment_id}" data-thread-comment-reply-id="${thread_comment_reply_id}" data-thread-reply-reply-id="${thread_reply_reply_id}" class="mr-3 reply_reply_upvote_button">
                                                            <i class="text-darthmouthgreen fa-regular fa-circle-up text-2xl"></i>
                                                        </button>
                                                        <span class="text-darthmouthgreen reply_reply_upvote_count" id="reply_reply_upvote_count_${thread_reply_reply_id}">${randomized_display_upvote}</span>
                                                        <button data-thread-comment-id="${thread_comment_id}" data-thread-comment-reply-id="${thread_comment_reply_id}" data-thread-reply-reply-id="${thread_reply_reply_id}" class="ml-3 reply_reply_downvote_button">
                                                            <i class="text-darthmouthgreen fa-regular fa-circle-down text-2xl"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        `;
                                    }

                                    threadCommentsDisp += `
                                    </div>
                                    <div data-thread-comment-id="${thread_comment_id}" data-thread-comment-reply-id="${thread_comment_reply_id}" class="mt-5 w-full replyReplyInputArea hidden" id="replyReplyInputArea_${thread_comment_reply_id}">
                                    <label for="replyReplyInput" class="text-lg">Your Reply:</label>
                                    <textarea name="" class="w-full h-[100px] p-3 rounded-lg replyReplyInput" id="replyReplyInput_${thread_comment_reply_id}" placeholder="reply"></textarea>
                                    <div class="text-right">
                                        <button class="px-5 py-3 bg-darthmouthgreen hover:bg-green-950 text-white rounded-xl mt-3 replyReplyInputBtn"  data-thread-comment-reply-id="${thread_comment_reply_id}" id="">Reply</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                            `;
                        }

            threadCommentsDisp += `
            </div>
            <div class="mt-5 w-full commentReplyInputArea hidden" data-thread-comment-id="${thread_comment_id}" id="commentReplyInputArea_${thread_comment_id}">
                <label for="commentReplyInput" class="text-lg">Your Reply:</label>
                <textarea name="" class="w-full h-[100px] p-3 rounded-lg commentReplyInput" id="commentReplyInput_${thread_comment_id}" placeholder="comment"></textarea>
                <div class="text-right">
                    <button class="px-5 py-3 bg-darthmouthgreen hover:bg-green-950 text-white rounded-xl mt-3 commentReplyInputBtn" data-thread-comment-id="${thread_comment_id}" id="">Reply</button>
                </div>
            </div>
        </div>
    </div>

            `;
        }

        // Append the HTML to the DOM
        $('#commentMainContainer').empty();
        $('#commentMainContainer').append(threadCommentsDisp);

        $('.attemptReplyBtn').on('click', function () {
            var commentContainer = $(this).closest('.commentContainer');
            var thread_comment_id = commentContainer.data('thread-comment-id');
        
            // Ensure all commentReplyInputArea elements are hidden initially
            $('.commentReplyInputArea').addClass('hidden');
            $('.replyReplyInputArea').addClass('hidden');
            // Show the commentReplyInputArea corresponding to the clicked attemptReplyBtn
            $(`#commentReplyInputArea_${thread_comment_id}`).removeClass('hidden');
        
            // Focus on the textarea within the shown commentReplyInputArea
            $(`#commentReplyInput_${thread_comment_id}`).focus();
        
            // console.log(thread_comment_id);
        });

        $('.commentReplyInputBtn').on('click', function() {
            var commentContainer = $(this).closest('.commentContainer');
            var thread_comment_id = commentContainer.data('thread-comment-id');

            var thread_comment_reply = $(`#commentReplyInput_${thread_comment_id}`).val();

            if (!thread_comment_reply.trim()) {
                alert('Please enter a valid reply')
            } else {

                $('#loaderModal').removeClass('hidden');

                    var csrfToken = $('meta[name="csrf-token"]').attr('content');
                    
                    var url = `${baseUrl}/commentReply`;
        
                        $.ajax({
                            type: "POST",
                            url: url,
                            data: {
                                thread_comment_id: thread_comment_id,
                                thread_comment_reply: thread_comment_reply,
                            },
                            headers: {
                                'X-CSRF-TOKEN': csrfToken
                            },
                            success: function(response) {
                                // Hide loader on success
                                $('#loaderModal').addClass('hidden');
        
                                    // Show success indicator (you can customize this based on your needs)
                                    $('#successModal').removeClass('hidden');
                                    // Redirect to the specified URL after a brief delay
                                    setTimeout(function () {
                                        window.location.reload();
                                    }, 1000); // Delay for 2 seconds (adjust as needed)
                                
                            },
                            error: function(error) {
                                // Hide loader on error
                                $('#loaderModal').addClass('hidden');
                                $('#errorModal').removeClass('hidden');
                                setTimeout(function () {
                                    $('#errorModal').addClass('hidden');
                                }, 1000);
                                
                                console.log(error);
                            }
                        });
            }
        })

        $('.attemptCommentReplyBtn').on('click', function () {
            var commentContainer = $(this).closest('.commentContainer');
            var thread_comment_id = commentContainer.data('thread-comment-id');

            var thread_comment_reply_id = $(this).data('thread-comment-reply-id')

            $('.commentReplyInputArea').addClass('hidden');
            $('.replyReplyInputArea').addClass('hidden');
        
            $(`#replyReplyInputArea_${thread_comment_reply_id}`).removeClass('hidden');
        
            $(`#replyReplyInput_${thread_comment_reply_id}`).focus();
        
        });


        $('.replyReplyInputBtn').on('click', function() {
            var commentContainer = $(this).closest('.commentContainer');
            var thread_comment_id = commentContainer.data('thread-comment-id');

            var thread_comment_reply_id = $(this).data('thread-comment-reply-id')

            var thread_reply_reply = $(`#replyReplyInput_${thread_comment_reply_id}`).val();

            if (!thread_reply_reply.trim()) {
                alert('Please enter a valid reply')
            } else {
                // alert(`
                //     thread_comment_id = ${thread_comment_id}
                //     thread_comment_reply_id = ${thread_comment_reply_id}
                //     thread_reply_reply = ${thread_reply_reply}
                // `)
                $('#loaderModal').removeClass('hidden');

                    var csrfToken = $('meta[name="csrf-token"]').attr('content');
                    
                    var url = `${baseUrl}/replyReply`;
        
                        $.ajax({
                            type: "POST",
                            url: url,
                            data: {
                                thread_comment_id: thread_comment_id,
                                thread_comment_reply_id: thread_comment_reply_id,
                                thread_reply_reply: thread_reply_reply
                            },
                            headers: {
                                'X-CSRF-TOKEN': csrfToken
                            },
                            success: function(response) {
                                // Hide loader on success
                                $('#loaderModal').addClass('hidden');
        
                                    // Show success indicator (you can customize this based on your needs)
                                    $('#successModal').removeClass('hidden');
                                    // Redirect to the specified URL after a brief delay
                                    setTimeout(function () {
                                        window.location.reload();
                                    }, 1000); // Delay for 2 seconds (adjust as needed)
                                
                            },
                            error: function(error) {
                                // Hide loader on error
                                $('#loaderModal').addClass('hidden');
                                $('#errorModal').removeClass('hidden');
                                setTimeout(function () {
                                    $('#errorModal').addClass('hidden');
                                }, 1000);
                                
                                console.log(error);
                            }
                        });
            }
        })




    $('.comment_upvote_button, .comment_downvote_button, .reply_upvote_button, .reply_downvote_button, .reply_reply_upvote_button, .reply_reply_downvote_button').mouseenter(function () {
        changeIconOnHover($(this));
    }).mouseleave(function () {
        resetIconOnLeave($(this));
    }).click(function () {
        toggleIconOnClick($(this));
    });

    $('.comment_upvote_button').on('click', function(e) {
        e.preventDefault();

        var thread_comment_id = $(this).data('thread-comment-id');
        var comment_upvote = $(`#comment_upvote_count_${thread_comment_id}`)
        var comment_upvote_count = comment_upvote.text();
    
        var url = `${baseUrl}/comment/${thread_comment_id}/upvote`;

            $.ajax({
                type: "POST",
                url: url,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(response) {
                    console.log(response);
                    var updated_comment_upvote_count = parseInt(comment_upvote_count) + 1; // Corrected here
                    comment_upvote.text(updated_comment_upvote_count);
                },
                error: function(error) {

                    console.log(error);
                }
            });
    
        
    });
    
    $('.comment_downvote_button').on('click', function(e) {
        e.preventDefault();

        var thread_comment_id = $(this).data('thread-comment-id');
        var comment_upvote = $(`#comment_upvote_count_${thread_comment_id}`)
        var comment_upvote_count = comment_upvote.text();
    
        var url = `${baseUrl}/comment/${thread_comment_id}/downvote`;

            $.ajax({
                type: "POST",
                url: url,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(response) {
                    console.log(response);
                    var updated_comment_upvote_count = parseInt(comment_upvote_count) - 1; // Corrected here
                    comment_upvote.text(updated_comment_upvote_count);
                },
                error: function(error) {

                    console.log(error);
                }
            });
    });








    $('.reply_upvote_button').on('click', function(e) {
        e.preventDefault();

        var thread_comment_id = $(this).data('thread-comment-id');
        var thread_comment_reply_id = $(this).data('thread-comment-reply-id');
        var comment_reply_upvote = $(`#reply_upvote_count_${thread_comment_reply_id}`)
        var comment_reply_upvote_count = comment_reply_upvote.text();
    
        var url = `${baseUrl}/comment/${thread_comment_id}/reply/${thread_comment_reply_id}/upvote`;

            $.ajax({
                type: "POST",
                url: url,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(response) {
                    console.log(response);
                    var updated_comment_reply_upvote_count = parseInt(comment_reply_upvote_count) + 1; // Corrected here
                    comment_reply_upvote.text(updated_comment_reply_upvote_count);
                },
                error: function(error) {

                    console.log(error);
                }
            });
    
        
    });
    
    $('.reply_downvote_button').on('click', function(e) {
        e.preventDefault();

        var thread_comment_id = $(this).data('thread-comment-id');
        var thread_comment_reply_id = $(this).data('thread-comment-reply-id');
        var comment_reply_upvote = $(`#reply_upvote_count_${thread_comment_reply_id}`)
        var comment_reply_upvote_count = comment_reply_upvote.text();
    
        var url = `${baseUrl}/comment/${thread_comment_id}/reply/${thread_comment_reply_id}/downvote`;

            $.ajax({
                type: "POST",
                url: url,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(response) {
                    console.log(response);
                    var updated_comment_reply_upvote_count = parseInt(comment_reply_upvote_count) - 1; // Corrected here
                    comment_reply_upvote.text(updated_comment_reply_upvote_count);
                },
                error: function(error) {

                    console.log(error);
                }
            });
    });





    $('.reply_reply_upvote_button').on('click', function(e) {
        e.preventDefault();

        var thread_comment_id = $(this).data('thread-comment-id');
        var thread_comment_reply_id = $(this).data('thread-comment-reply-id');
        var thread_reply_reply_id = $(this).data('thread-reply-reply-id');
        var reply_reply_upvote = $(`#reply_reply_upvote_count_${thread_reply_reply_id}`)
        var reply_reply_upvote_count = reply_reply_upvote.text();
    
        var url = `${baseUrl}/comment/${thread_comment_id}/reply/${thread_comment_reply_id}/reply/${thread_reply_reply_id}/upvote`;

            $.ajax({
                type: "POST",
                url: url,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(response) {
                    console.log(response);
                    var updated_reply_reply_upvote_count = parseInt(reply_reply_upvote_count) + 1; // Corrected here
                    reply_reply_upvote.text(updated_reply_reply_upvote_count);
                },
                error: function(error) {

                    console.log(error);
                }
            });
    
        
    });
    
    $('.reply_reply_downvote_button').on('click', function(e) {
        e.preventDefault();

      var thread_comment_id = $(this).data('thread-comment-id');
        var thread_comment_reply_id = $(this).data('thread-comment-reply-id');
        var thread_reply_reply_id = $(this).data('thread-reply-reply-id');
        var reply_reply_upvote = $(`#reply_reply_upvote_count_${thread_reply_reply_id}`)
        var reply_reply_upvote_count = reply_reply_upvote.text();
    
        var url = `${baseUrl}/comment/${thread_comment_id}/reply/${thread_comment_reply_id}/reply/${thread_reply_reply_id}/downvote`;

            $.ajax({
                type: "POST",
                url: url,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(response) {
                    console.log(response);
                    var updated_reply_reply_upvote_count = parseInt(reply_reply_upvote_count) - 1; // Corrected here
                    reply_reply_upvote.text(updated_reply_reply_upvote_count);
                },
                error: function(error) {

                    console.log(error);
                }
            });
    });


    }

    $('.upvote_button, .downvote_button').mouseenter(function () {
        changeIconOnHover($(this));
    }).mouseleave(function () {
        resetIconOnLeave($(this));
    }).click(function () {
        toggleIconOnClick($(this));
    });
    
    $('.upvote_button').on('click', function(e) {
        e.preventDefault();
        // Find the closest .thread container
        var threadContainer = $(this).closest('.thread');
    
        // Extract the data attributes
        var thread_id = threadContainer.data('thread-id');
        var upvote_count = threadContainer.find('.upvote_count').text();
        
        var url = `${baseUrl}/upvote`;

            $.ajax({
                type: "POST",
                url: url,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(response) {
                    console.log(response);
                    var updated_upvote_count = parseInt(upvote_count) + 1; // Corrected here
                    threadContainer.find('.upvote_count').text(updated_upvote_count);
                },
                error: function(error) {
                    // Hide loader on error
                    $('#loaderModal').addClass('hidden');
                    $('#errorModal').removeClass('hidden');
                    console.log(error);
                }
            });
    
        
    });
    
    $('.downvote_button').on('click', function(e) {
        e.preventDefault();
        // Find the closest .thread container
        var threadContainer = $(this).closest('.thread');
    
        // Extract the data attributes
        var thread_id = threadContainer.data('thread-id');
        var upvote_count = threadContainer.find('.upvote_count').text();
    
        var url = `${baseUrl}/downvote`;

            $.ajax({
                type: "POST",
                url: url,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(response) {
                    console.log(response);
    
                    var updated_upvote_count = parseInt(upvote_count) - 1; // Corrected here
                    threadContainer.find('.upvote_count').text(updated_upvote_count);
                },
                error: function(error) {
                    // Hide loader on error
                    $('#loaderModal').addClass('hidden');
                    $('#errorModal').removeClass('hidden');
                    console.log(error);
                }
            });

    });


    
    function changeIconOnHover(button) {
        const iconElement = button.find('i');
        iconElement.removeClass('fa-regular').addClass('fa-solid');
        if (button.hasClass('upvote_button')) {
            iconElement.removeClass('fa-circle-up').addClass('fa-circle-up');
        } else if (button.hasClass('downvote_button')) {
            iconElement.removeClass('fa-circle-down').addClass('fa-circle-down');
        }
    }

    function resetIconOnLeave(button) {
        const iconElement = button.find('i');
        iconElement.removeClass('fa-solid').addClass('fa-regular');
        if (button.hasClass('upvote_button')) {
            iconElement.removeClass('fa-circle-up').addClass('fa-circle-up');
        } else if (button.hasClass('downvote_button')) {
            iconElement.removeClass('fa-circle-down').addClass('fa-circle-down');
        }
    }

    function toggleIconOnClick(button) {
        const iconElement = button.find('i');
        iconElement.toggleClass('fa-regular fa-solid');
        if (button.hasClass('upvote_button')) {
            iconElement.toggleClass('fa-circle-up fa-circle-up');
        } else if (button.hasClass('downvote_button')) {
            iconElement.toggleClass('fa-circle-down fa-circle-down');
        }
    }

    $('#submitCommentBtn').on('click', function(e) {
        e.preventDefault();

        var commentContent = tinyMCE.get("commentInput").getContent();
        
        if(!commentContent.trim()) {
            alert('please enter a comment')
        } else {

            $('#loaderModal').removeClass('hidden');

            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            
            var url = `${baseUrl}/comment`;

                $.ajax({
                    type: "POST",
                    url: url,
                    data: {
                        thread_comment: commentContent,
                    },
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function(response) {
                        // Hide loader on success
                        $('#loaderModal').addClass('hidden');

                            // Show success indicator (you can customize this based on your needs)
                            $('#successModal').removeClass('hidden');

                            // Redirect to the specified URL after a brief delay
                            setTimeout(function () {
                                window.location.reload();
                            }, 2000); // Delay for 2 seconds (adjust as needed)
                        
                    },
                    error: function(error) {
                        // Hide loader on error
                        $('#loaderModal').addClass('hidden');
                        $('#errorModal').removeClass('hidden');
                        setTimeout(function () {
                            $('#errorModal').addClass('hidden');
                        }, 1000);
                        
                        console.log(error);
                    }
                });

        }
    })
})

