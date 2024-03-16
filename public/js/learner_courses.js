$(document).ready(function() {
    var baseUrl = window.location.href;
    
    var csrfToken = $('meta[name="csrf-token"]').attr('content');
    getLearnerData()

    $('#searchVal').on('input', function() {

        var courseVal = $('#searchVal').val()

        var url = baseUrl + "/searchCourse";


        $.ajax({
            type: "GET",
            data: {
                courseVal: courseVal
            },
            url: url,
            success: function(response) {
                console.log(response);

                var courseData = response['courses']
                updateCourseDisp(courseData)
            },
            error: function(error) {
                console.log(error);
            }
        });
    })

    function updateCourseDisp(courseData) {
        courseDisp = ``

        for (let i = 0; i < courseData.length; i++) {
            const course_id = courseData[i]['course_id'];
            const course_name = courseData[i]['course_name'];
            const course_code = courseData[i]['course_code'];
            const instructor_lname = courseData[i]['instructor_lname'];
            const instructor_fname = courseData[i]['instructor_fname'];
            const profile_picture = courseData[i]['profile_picture'];
            

            courseDisp += `
            <div style="background-color: #00693e" class="px-3 py-2 relative m-4 rounded-lg shadow-lg h-72 w-52">
                <div style="background-color: #9DB0A3" class="relative h-32 mx-auto my-4 rounded w-44">
                    <img class="absolute w-16 h-16 bg-yellow-500 rounded-full right-3 -bottom-4" src="../storage/app/public/${profile_picture}" alt="">
                </div>
                
                <div class="px-4">
                    <h1 class="mb-2 overflow-hidden text-lg font-bold text-white whitespace-no-wrap">${course_name}</h1>

                    <div class="text-sm text-gray-100 ">
                        <p>${course_code}</p>
                        <h3>${instructor_fname} ${instructor_lname}</h3>
                    </div>
                </div>
                
                <a href="/learner/course/${course_id}" style="background-color: #00693e; right:0; bottom: 0;" class="absolute float-right mx-4 mb-3 rounded">
                    <svg class="w-10 h-10" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M504-480 320-664l56-56 240 240-240 240-56-56 184-184Z"/></svg>
                </a>
            </div>
            `
        }

        $('#courses').empty();
        $('#courses').append(courseDisp);
    }



        
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

        message = message.replace(/\n/g, '<br>');
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