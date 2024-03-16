$(document).ready(function() {
   
    var baseUrl = window.location.href;

    getThreads();

    function getThreads() {
        var url = baseUrl + "/threads";

        $.ajax({
            type: "GET",
            url: url,
            success: function(response) {
                console.log(response)

                var threads = response['threads']
                displayThreads(threads);
            },
            error: function(error) {
                console.log(error);
            }
        });
    }


    function displayThreads(threads) {
        threadContainerDisp = ``;
        
        for (let i = 0; i < threads.length; i++) {
            const thread_id = threads[i]['thread_id'];
            const community_id = threads[i]['community_id'];
            const user_id = threads[i]['user_id'];
            const user_type = threads[i]['user_type'];
            const created_at = threads[i]['created_at'];
            const formatted_created_at = formatCreatedAt(created_at);
            const first_name = threads[i]['first_name'];
            const last_name = threads[i]['last_name'];
            const profile_picture = threads[i]['profile_picture'];
            const community_name = threads[i]['community_name'];
            const thread_content_id = threads[i]['thread_content_id'];
            const thread_type = threads[i]['thread_type'];
            const thread_title = threads[i]['thread_title'];
            const thread_content = threads[i]['thread_content'];
            const thread_upvote_id = threads[i]['thread_upvote_id'];
            const base_upvote = threads[i]['base_upvote'];
            const randomized_display_upvote = threads[i]['randomized_display_upvote'];
            const last_randomize_datetime = threads[i]['last_randomize_datetime'];
            const comment_count = threads[i]['comment_count'];
            const comment_reply_count = threads[i]['comment_reply_count'];
            const reply_reply_count = threads[i]['reply_reply_count'];
            const total_count = threads[i]['total_count'];
            

            threadContainerDisp += `
            <div data-thread-id="${thread_id}" class="w-full flex border-2 my-5 border-darthmouthgreen rounded-lg border-opacity-75 thread" id="thread_${thread_id}">
                <div class="w-1/12 border-r-2 border-darthmouthgreen border-opacity-50" id="upvoteArea">
                <div class="flex flex-col items-center mt-5">
                    <button class="my-3 upvote_button">
                        <i class="text-darthmouthgreen fa-regular fa-circle-up text-4xl"></i>
                    </button>
                    <span class="text-darthmouthgreen upvote_count" id="">${randomized_display_upvote}</span>
                    <button class="my-3 downvote_button">
                        <i class="text-darthmouthgreen fa-regular fa-circle-down text-4xl"></i>
                    </button>
                </div>
                    
                </div>
                <div class="w-11/12 mx-5 py-3" id="threadMainContentArea">
                    <div class="w-full flex items-center" id="userInfoArea">
                        <div class="rounded-full w-[35px] h-[35px] bg-green-950 mx-3">
                            <img class="rounded-full w-[35px] h-[35px]" src="/storage/${profile_picture}" alt="">
                        </div>
                        <h1 class="ml-1 text-lg font-semibold">${first_name} ${last_name}</h1>
                        <h1 class="mx-5 text-lg font-normal">${community_name}</h1>
                        <h1 class="text-md font-normal opacity-60">${formatted_created_at}</h1>
                    </div>

                    <a href="/instructor/discussions/thread/${thread_id}">
                        <div class="w-full mx-5 mt-5" id="threadTitleArea">
                            <h1 class="text-4xl font-bold">${thread_title}</h1>
                        </div>

                        <div class="w-full mx-5 mt-5 px-3" id="threadContentArea">
                        `;
                    if(thread_type === 'POST') {
                        threadContainerDisp += `
                        <div class="h-[150px]" id="threadContent">
                            <h1>${thread_content}</h1>
                        </div>
                        `;
                    } else if(thread_type === 'PHOTO') {
                        threadContainerDisp += `
                        <div class="h-[350px] flex justify-center" id="threadContent">
                            <img class="h-[350px]" src="/storage/${thread_content}" alt="">
                        </div>
                        `;
                    } else {
                        threadContainerDisp += `
                        <div class="h-[150px]" id="threadContent">
                            <a href="${thread_content}">${thread_content}</a>
                        </div>
                        `;
                    }
                            

                threadContainerDisp += `    
                        </div>

                        <div class="w-full mx-5 mt-5" id="commentsArea">
                            <i class="fa-regular fa-comment text-darthmouthgreen"></i>
                            <span class="">${total_count}</span>
                            <span class="">comments</span>
                        </div>
                    </a>

                </div>
            </div>
            `;
        }

        $('#threadMainContainer').append(threadContainerDisp);

        // Event handling
        $('.upvote_button, .downvote_button').mouseenter(function () {
            changeIconOnHover($(this));
        }).mouseleave(function () {
            resetIconOnLeave($(this));
        }).click(function () {
            toggleIconOnClick($(this));
        });

        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        
        $('.upvote_button').on('click', function(e) {
            e.preventDefault();
            // Find the closest .thread container
            var threadContainer = $(this).closest('.thread');
        
            // Extract the data attributes
            var thread_id = threadContainer.data('thread-id');
            var upvote_count = threadContainer.find('.upvote_count').text();
            
            var url = `${baseUrl}/thread/${thread_id}/upvote`;

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
        
            var url = `${baseUrl}/thread/${thread_id}/downvote`;

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

    }

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

})