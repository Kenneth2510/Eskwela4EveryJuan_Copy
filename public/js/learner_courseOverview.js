$(document).ready(function() {
    var baseUrl = window.location.href
    var csrfToken = $('meta[name="csrf-token"]').attr('content');

    $('#enrollBtn').on('click', function(e) {
        e.preventDefault();

        $('#enrollCourseModal').removeClass('hidden');
    });

    $('.cancelEnroll').on('click', function(e) {
        e.preventDefault();

        $('#enrollCourseModal').addClass('hidden');
    });

    $("#enrollCourse").on('click', function (e) {
        e.preventDefault();
        var courseID = $(this).data("course-id");
        var button = $(this)
    
        button.prop('disabled', true)
        $('#enrollCourseModal').addClass('hidden');
        $('#loaderModal').removeClass('hidden');
        $.ajax({
            type: 'POST',
            url: '/learner/course/enroll/' + courseID,
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            success: function (response) {
                if (response && response.redirect_url) {
                    $('#loaderModal').addClass('hidden');
                    button.prop('disabled', false)
                    window.location.href = response.redirect_url;
                } else {
                
                }
            },
            error: function (xhr, status, error) {
    
                button.prop('disabled', false)
                console.log(xhr.responseText);
            }
        });
        });

    $('#unenrollBtn').on('click', function(e) {
        e.preventDefault();

        $('#unenrollCourseModal').removeClass('hidden');
    });

    $('.cancelUnenroll').on('click', function(e) {
        $('#unenrollCourseModal').addClass('hidden');
    });

    $('#unenrollCourse').on('click',function(e) {
        
        e.preventDefault();
        var button = $(this)
        var lessonCourseID = $(this).data("learner-course-id");

        $('#unenrollCourseModal').addClass('hidden');
        $('#loaderModal').removeClass('hidden');
        button.prop('disabled', true)
    
        $.ajax({
            type: 'POST',
            url: '/learner/course/unEnroll/' + lessonCourseID,
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            success: function (response) {
                button.prop('disabled', false)
                if (response && response.redirect_url) {
                    
                    $('#loaderModal').addClass('hidden');
                    button.prop('disabled', false)
                    window.location.href = response.redirect_url;
                } else {
                
                }
            },
            error: function (xhr, status, error) {
                button.prop('disabled', false)
                console.log(xhr.responseText);
            }

        })
    })


    $('#courseDetailsBtn').css({
        'background-color': '#FFFFFF',
        'color': '#025C26',
    });

    $('#courseDetailsBtn').on('click', function(e) {
        e.preventDefault();

        $('#courseInfoArea').removeClass('hidden')
        $('#learnersEnrolledArea').addClass('hidden')
        $('#gradesheetArea').addClass('hidden')
        $('#filesArea').addClass('hidden')

        $(this).css({
            'background-color': '#FFFFFF',
            'color': '#025C26',
        });

        $('#learnersEnrolledBtn , #gradesheetBtn , #courseFilesBtn').css({
            'background-color': '#025C26',
            'color': '#ffffff',
        });
    })

    $('#learnersEnrolledBtn').on('click', function(e) {
        e.preventDefault();

        $('#courseInfoArea').addClass('hidden')
        $('#learnersEnrolledArea').removeClass('hidden')
        $('#gradesheetArea').addClass('hidden')
        $('#filesArea').addClass('hidden')
        $(this).css({
            'background-color': '#FFFFFF',
            'color': '#025C26',
        });

        $('#courseDetailsBtn , #gradesheetBtn , #courseFilesBtn').css({
            'background-color': '#025C26',
            'color': '#ffffff',
        });
    })

    $('#gradesheetBtn').on('click', function(e) {
        e.preventDefault();

        $('#courseInfoArea').addClass('hidden')
        $('#learnersEnrolledArea').addClass('hidden')
        $('#gradesheetArea').removeClass('hidden')
        $('#filesArea').addClass('hidden')
        $(this).css({
            'background-color': '#FFFFFF',
            'color': '#025C26',
        });

        $('#courseDetailsBtn , #learnersEnrolledBtn , #courseFilesBtn').css({
            'background-color': '#025C26',
            'color': '#ffffff',
        });
    })

    $('#courseFilesBtn').on('click', function(e) {
        e.preventDefault();

        $('#courseInfoArea').addClass('hidden')
        $('#learnersEnrolledArea').addClass('hidden')
        $('#gradesheetArea').addClass('hidden')
        $('#filesArea').removeClass('hidden')
        $(this).css({
            'background-color': '#FFFFFF',
            'color': '#025C26',
        });

        $('#courseDetailsBtn , #learnersEnrolledBtn , #gradesheetBtn').css({
            'background-color': '#025C26',
            'color': '#ffffff',
        });
    })


    $('#viewDetailsBtn').on('click', function() {

        $('#courseDetailsModal').removeClass('hidden')
    })

    
    $('.closeCourseDetailsModal').on('click', function() {

        $('#courseDetailsModal').addClass('hidden')
    })




    getLearnerData()

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
                    

                    $.when(
                        add_learner_data(learner)
                    ).then(function() {
                        getCourseData(learner)
                    })



                },
                error: function(error) {
                    console.log(error);
                }
            });
    }

    
    function add_learner_data(learner) {
        
        var learner_id = learner['learner_id'];
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

    function getCourseData(learner) {
        var course_id = $('#enrollCourse').data("course-id");
        var url = `/chatbot/courseData/${course_id}`;
        $.ajax({
            type: "GET",
            url: url,
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            success: function(response) {
                console.log(response);
    
                
                var learner_id = learner['learner_id'];
                process_files(learner_id)


                var courseData = response['course'];
    
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
                    var course = courseData['course_name'];
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