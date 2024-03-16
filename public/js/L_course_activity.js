$(document).ready(function() {
    var baseUrl = window.location.href;

    var csrfToken = $('meta[name="csrf-token"]').attr('content');
    getLearnerData()
    var courseID
    var syllabusID


    const modal = $('#confirmationModal');
    const confirmButton = $('#confirmButton');
    const cancelButton = $('#cancelButton');

    // Show the modal
    function showModal() {
        modal.removeClass('hidden');
    }

    // Hide the modal
    function hideModal() {
        modal.addClass('hidden');
    }

    // Show modal when the submit button is clicked
    $('#submitButton').on('click', function(event) {
        event.preventDefault(); // Prevent the default form submission
        showModal();
    });

    // Hide modal when the cancel button is clicked
    cancelButton.on('click', function() {
        hideModal();
    });

    // Handle the actual form submission when the confirm button is clicked
    confirmButton.on('click', function() {
        // Uncomment the line below if you want to submit the form programmatically
        // $('#yourFormId').submit();
        var learnerCourseID = $(this).data('learner-course-id');
        courseID = $(this).data('course-id');
        syllabusID = $(this).data('syllabus-id');
        var activityID = $(this).data('activity-id');
        var activityContentID = $(this).data('activity-content-id');
        var attemptID = $(this).data('attempt');


        console.log('learnerCourse ' , learnerCourseID)
        console.log('courseID ' , courseID)
        console.log('syllabusID ' , syllabusID)
        console.log('activityID ' , activityID)
        console.log('activityContentID ' , activityContentID)
        
        var answer = $('#activity_answer').val();

        var answerData = {
            answer: answer
        }
        
        if(answer !== null && answer !== "") {
            var csrfToken = $('meta[name="csrf-token"]').attr('content'); // Get the CSRF token from the meta tag
            var url = "/learner/course/content/"+ courseID +"/"+ learnerCourseID +"/activity/"+ syllabusID +"/answer/"+ attemptID + "/" + activityID +"/" + activityContentID; 


            $('#loaderModal').removeClass('hidden');
            hideModal()
            $.ajax({
                type: 'POST',
                url: url,
                data: answerData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function (response) {
                    if (response && response.redirect_url) {
                        $('#loaderModal').addClass('hidden');
                        window.location.href = response.redirect_url;
                    } 
                    // else {
                    
                    // }
                    // alert('done')
                },
                error: function (xhr, status, error) {
        
                    console.log(xhr.responseText);
                }
            });
        } else {
            hideModal();
            alert("Please enter your answer");
        }
        // For now, just hide the modal
        // hideModal();
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
                    // init_chatbot(learner);
                    getCourseData(learner)



                },
                error: function(error) {
                    console.log(error);
                }
            });
    }

    function getCourseData(learner) {
        var course_id = $('#activity_title').data('course-id');
        var url = `/chatbot/courseData/${course_id}`;
        $.ajax({
            type: "GET",
            url: url,
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            success: function(response) {
                console.log(response);
    
                var courseData = response['course'];

                getSyllabusData(learner, courseData)

            },
            error: function(error) {
                console.log(error);
            }
        });
    }

    function getSyllabusData(learner, courseData) {
        var course_id = $('#activity_title').data('course-id');
        var syllabus_id = $('#activity_title').data('syllabus-id');
        var url = `/chatbot/syllabusData/${course_id}/${syllabus_id}`;
        
        $.ajax({
            type: "GET",
            url: url,
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            success: function(response) {
                console.log(response);

                var syllabusData = response['syllabus']
                
                $('.loaderArea').addClass('hidden');
                $('.mainchatbotarea').removeClass('hidden');


                $('.submitQuestion').on('click', function(e) {
                    e.preventDefault();
                    submitQuestion();
                });
    
                $('.question').on('keydown', function(e) {
                    if (e.keyCode === 13) {
                        e.preventDefault();
                        submitQuestion();
                    }
                });
    
                function submitQuestion() {
                    var learner_id = learner['learner_id'];
                    var question = $('.question').val();
                    var course = courseData['course_name'];
                    var lesson = `${syllabusData['category']} - ${syllabusData['topic_title']}`;
    
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
                            $('.question').val('')
                        },
                        error: function(error) {
                            console.log(error);
                        }
                    });
                }
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
                <img class="bg-white" alt="" src="/storage/app/public/images/chatbot.png" />
                </div>
            </div>
            <div class="chat-bubble ">${message}</div>
        </div>
        `;

        $('.botloader').addClass('hidden')
        $('.chatContainer').append(botMessageDisp);
    }
    
});