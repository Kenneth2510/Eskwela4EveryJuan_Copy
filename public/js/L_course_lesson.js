$(document).ready(function() {
    // {{ url("/learner/course/content/$syllabus->course_id/$syllabus->learner_course_id/lesson/$syllabus->syllabus_id/finish") }}
    var baseUrl = window.location.href;

    var csrfToken = $('meta[name="csrf-token"]').attr('content');
    getLearnerData()

    $('#finishLessonBtn').on('click', function(e) {
        e.preventDefault();

        $('#finishLessonModal').removeClass('hidden');
    })

    $('.cancelFinishLessonBtn').on('click', function(e) {
        e.preventDefault();

        $('#finishLessonModal').addClass('hidden');
    })





    $('#confirmFinishLessonBtn').on('click', function(e) {
        
        var courseID = $(this).data('course-id');
        var learnerCourseID = $(this).data('learner-course-id');
        var syllabusID = $(this).data('syllabus-id');
        
        var csrfToken = $('meta[name="csrf-token"]').attr('content'); // Get the CSRF token from the meta tag
    
        var url = "/learner/course/content/"+ courseID +"/"+ learnerCourseID +"/lesson/"+ syllabusID +"/finish";


        
        $('#finishLessonModal').addClass('hidden');
        $('#loaderModal').removeClass('hidden');
        $.ajax ({
            type: "POST",
            url: url,
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            success: function (response){
                console.log(response)
                if (response && response.redirect_url) {
                    
                    $('#loaderModal').addClass('hidden');
                    window.location.href = response.redirect_url;
                } else {
                
                }
            },
            error: function(error) {
                console.log(error);
            }
      })
    })









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
        var course_id = $('#lesson_title').data('course-id');
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
        var course_id = $('#lesson_title').data('course-id');
        var syllabus_id = $('#lesson_title').data('syllabus-id');
        var url = `/chatbot/syllabusData/${course_id}/${syllabus_id}`;
        
        $.ajax({
            type: "GET",
            url: url,
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            success: function(response) {
                console.log(response);

                var syllabusData = response['syllabus'];

                var learner_id = learner['learner_id'];
                process_files(learner_id)

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
        message = message.replace(/\n/g, '<br>');
        
        var botMessageDisp = ``
        botMessageDisp += `
        
        <div class="chat chat-start">
            <div class="chat-image avatar">
                <div class="w-10 rounded-full">
                <img class="bg-white" alt="" src="../../storage/images/chatbot.png" />
                </div>
            </div>
            <div class="whitespace-pre-wrap chat-bubble ">${message}</div>
        </div>
        `;

        $('.botloader').addClass('hidden')
        $('.chatContainer').append(botMessageDisp);
    }
    

})