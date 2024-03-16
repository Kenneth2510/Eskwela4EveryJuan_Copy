$(document).ready(function() {
    var quizLearnerQuestions = [];
    var quizLearnerAnswers = [];
    var currentPage = 1;
    var questionsPerPage = 5;
    var baseUrl = window.location.href;
    var durationVal;
    

    var csrfToken = $('meta[name="csrf-token"]').attr('content');

    getLearnerData()

getLearnerQuizData();

    function getLearnerQuizData () {

        var url = baseUrl + "/json";

        $.ajax({
            type: "GET",
            url: url,

            success: function(response) {
                console.log(response);

                durationVal = response['learnerSyllabusProgressData']['duration'];
                quizLearnerQuestions = response['quizLearnerData'];
                // console.log(quizLearnerQuestions);

                displayLearnerQuizData(quizLearnerQuestions);
            },
            error: function(error) {
                console.log(error);
            }
        });
    }


     // Function to convert milliseconds to H:MM:SS format
     function formatTime(milliseconds) {
        const totalSeconds = Math.floor(milliseconds / 1000);
        const hours = Math.floor(totalSeconds / 3600);
        const minutes = Math.floor((totalSeconds % 3600) / 60);
        const seconds = totalSeconds % 60;

        return `${hours}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
    }

    // Function to update the timer display
    function updateTimerDisplay() {
        const timerElement = $('#timerArea'); // Updated to target #timerArea
        const formattedTime = formatTime(durationVal);

        timerElement.html('<h1>Time remaining: ' + formattedTime + '</h1>');
    }

    function displayLearnerQuizData(quizLearnerQuestions) {
        var questionDataDisp = ``;
        var questionIndicatorDisp = ``;
    
        // $('#questionContainer').empty();
        $('#isAnsweredMeter').empty();
    
        for (let i = 0; i < quizLearnerQuestions.length; i++) {
            const learner_quiz_output_id = quizLearnerQuestions[i]['learner_quiz_output_id'];
            const quiz_content_id = quizLearnerQuestions[i]['quiz_content_id'];
            const quiz_id = quizLearnerQuestions[i]['quiz_id'];
            const syllabus_id = quizLearnerQuestions[i]['syllabus_id'];
            const course_id = quizLearnerQuestions[i]['course_id'];
            const question_id = quizLearnerQuestions[i]['question_id'];
            const category = quizLearnerQuestions[i]['category'];
            const question = quizLearnerQuestions[i]['question'];
            const answers = quizLearnerQuestions[i]['answers'];
    
            const answersArray = JSON.parse(answers);
    
            const questionCount = i + 1; // Start counting from 1


            const learnerAnswerRowData = {
                learner_quiz_output_id: learner_quiz_output_id,
                quiz_id: quiz_id,
                questionCount: questionCount,
                quiz_content_id: quiz_content_id,
                question_id: question_id,
                answer: ''
            }

            quizLearnerAnswers.push(learnerAnswerRowData);
    
            if (category == 'MULTIPLECHOICE') {
                questionDataDisp += `
                    <div class="my-5 py-5 px-3 questionData border-darthmouthgreen border-2 rounded-lg">
                        <div class="questionContent">
                            <h6 class="opacity-40 text-right">Question ${questionCount}</h6>
                            <p class="text-xl font-normal p-2 font-semibold">${question}</p>
                        </div>
                        <div class="questionChoices mt-2 text-lg">
                `;
    
                for (let x = 0; x < answersArray.length; x++) {
                    const choice = answersArray[x];
    
                    questionDataDisp += `
                        <input type="radio" name="${questionCount}" class="w-5 h-5 questionChoice mx-3" value="${choice}">
                        ${choice}<br>`;
                }
    
                questionDataDisp += `
                        </div>
                    </div>
                `;
            } else if (category == 'IDENTIFICATION') {
                questionDataDisp += `
                    <div class="my-5 py-5 px-3 questionData border-darthmouthgreen border-2 rounded-lg">
                        <div class="questionContent">
                            <h6 class="opacity-40 text-right">Question ${questionCount}</h6>
                            <p class="text-xl font-normal p-2 font-semibold">${question}</p>
                        </div>
                        <div class="questionChoices mt-2 text-lg">
                            <textarea type="text" class="border-2 border-gray-400 p-3 text-lg w-full identificationAns rounded-lg" placeholder=""></textarea>
                        </div>
                    </div>
                `;
            }
    
            // Assume isAnswered is false initially for each question
            const isAnswered = false;
    
            // Conditionally add the bg-darthmouthgreen class based on the isAnswered variable
            const questionIsAnsweredClass = isAnswered ? 'bg-darthmouthgreen' : '';
    
            questionIndicatorDisp += `
                <div class="flex items-center justify-center question_isAnswered w-[35px] h-[45px] hover:cursor-pointer 
                            border border-darthmouthgreen transition-all duration-300 ${questionIsAnsweredClass}">
                    ${questionCount}
                </div>
            `;
        }
    
        $('#questionContainer').prepend(questionDataDisp);
        $('#isAnsweredMeter').append(questionIndicatorDisp);


        // Update the timer display initially
        updateTimerDisplay();
        setTimeout(submitQuizContent, durationVal);

        // Start the timer
        const timerInterval = setInterval(function () {
            // Decrease the remaining time
            durationVal -= 1000;

            // Update the timer display
            updateTimerDisplay();

            // Check if the time has run out
            if (durationVal <= 0) {
                clearInterval(timerInterval);
                // Perform actions when time is up
                console.log('Time is up!');
            }
        }, 1000);


    
        $('input[type="radio"]').on('change', function () {
            const questionCount = $(this).attr('name');
            const selectedAnswer = $(this).val();
    
            // Update learnerAnswerRowData for the specific question
            updateLearnerAnswer(questionCount, selectedAnswer);
    
            isAnswered = true;
            updateQuestionIsAnsweredClass(questionCount);
        });
    
        $('.identificationAns').on('input', function () {
            const questionCount = $(this).closest('.questionData').find('.opacity-40').text().replace('Question ', '');
            const enteredText = $(this).val().trim();
    
            // Update learnerAnswerRowData for the specific question
            updateLearnerAnswer(questionCount, enteredText);
    
            if (enteredText !== '') {
                updateQuestionIsAnsweredClass(questionCount);
            }
        });


        function updateLearnerAnswer(questionCount, answer) {
            // Find the corresponding learnerAnswerRowData and update the answer
            const learnerAnswer = quizLearnerAnswers.find(answer => answer.questionCount == questionCount);
            if (learnerAnswer) {
                learnerAnswer.answer = answer;
            } else {
                console.error('Learner answer not found for questionCount: ' + questionCount);
            }
        }
    
        function updateQuestionIsAnsweredClass(questionCount) {
            const questionIsAnsweredElement = $('.question_isAnswered:eq(' + (questionCount - 1) + ')');
            questionIsAnsweredElement.addClass('bg-darthmouthgreen');
        }



        displayQuestionsByPage(currentPage);

        // Pagination event listeners
        $('#prevPage').on('click', function () {
            if (currentPage > 1) {
                currentPage--;
                displayQuestionsByPage(currentPage);
            }
        });

        $('#nextPage').on('click', function () {
            if (currentPage < Math.ceil(quizLearnerQuestions.length / questionsPerPage)) {
                currentPage++;
                displayQuestionsByPage(currentPage);
            }
        });
    }


    function displayQuestionsByPage(page) {
        // Hide all questions
        $('.questionData').hide();

        // Calculate the starting index for the current page
        const startIndex = (page - 1) * questionsPerPage;

        // Display questions for the current page
        $('.questionData').slice(startIndex, startIndex + questionsPerPage).show();

        // Update current page indicator
        $('#currentPage').text('Page ' + page);
    }



    $('#quizSubmitBtn').on('click', function(e) {
        e.preventDefault();

        $('#confirmSubmitQuizModal').removeClass('hidden');
    })

    $('.cancelConfirmSubmitQuiz').on('click', function(e) {
        e.preventDefault();

        $('#confirmSubmitQuizModal').addClass('hidden');
    })


    function submitQuizContent() {
        let loopCounter = 0;
        // console.log(quizLearnerAnswers);
        
        const csrfToken = $('meta[name="csrf-token"]').attr('content');

        for (let i = 0; i < quizLearnerAnswers.length; i++) {
            const rowData = quizLearnerAnswers[i];
            
            // console.log(element);
            $('#confirmSubmitQuizModal').addClass('hidden');
            $('#loaderModal').removeClass('hidden');

            var url = baseUrl + "/submit";
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: rowData,
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        },
                        success: function (response) {
                            // Handle success if needed
                            var baseUrl = window.location.href;
                            // var modifiedUrl = baseUrl.replace(/\/answer$/, '');
                    
                            // Continue with the rest of your logic
                            loopCounter++;
                            if (loopCounter == quizLearnerAnswers.length) {
                                // alert('finished submitting quiz output');

                                // window.location.href = modifiedUrl;
                                compute_score(rowData);
                            }
                    
                            // You can use the modifiedUrl variable as needed
                            // console.log('Modified URL:', modifiedUrl);
                        },
                        error: function (error) {
                            console.log(error);
                        }
                    });
        }
    }

    function compute_score(rowData) {
        var url = baseUrl + "/score";

        const csrfToken = $('meta[name="csrf-token"]').attr('content');



        $.ajax({
            type: "POST",
            url: url,
            data: rowData,
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            success: function (response) {
                // Handle success if needed
                $('#loaderModal').addClass('hidden');
                var baseUrl = window.location.href;
                var modifiedUrl = baseUrl.replace(/\/answer\/\d+$/, '');
        
                console.log(response)
                    window.location.href = modifiedUrl;
                
            },
            error: function (error) {
                console.log(error);
            }
        });

    }

       $('#confirmSubmitQuizBtn').on('click', function(e) {
        e.preventDefault();

        submitQuizContent();
        $('#confirmSubmitQuizModal').addClass('hidden');
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
        
        var course_id = $('#quiz_title').data('course-id');
        var learner_id = learner['learner_id'];
        var url = `/chatbot/learner/${learner_id}/course/${course_id}`;
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
        var course_id = $('#quiz_title').data('course-id');
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
        var course_id = $('#quiz_title').data('course-id');
        var syllabus_id = $('#quiz_title').data('syllabus-id');
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