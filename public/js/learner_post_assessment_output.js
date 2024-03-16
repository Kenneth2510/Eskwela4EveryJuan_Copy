$(document).ready(function () {

    var postAssessmentLearnerOutputData = [];
    var currentPage = 1;
    var questionsPerPage = 5;
    var baseUrl = window.location.href;

    var csrfToken = $('meta[name="csrf-token"]').attr('content');
    
    getLearnerData()
    getLearnerPostAssessmentOutputData();


    function getLearnerPostAssessmentOutputData() {

        var url = baseUrl + "/json";

        $.ajax({
            type: "GET",
            url: url,

            success: function(response) {
                console.log(response);

                postAssessmentLearnerOutputData = response['postAssessmentOutputData'];
                displayLearnerPostAssessmentOutputData(postAssessmentLearnerOutputData);
            },
            error: function(error) {
                console.log(error);
            }
        });
    }


    function displayLearnerPostAssessmentOutputData(postAssessmentLearnerOutputData) {

        // console.log(postAssessmentLearnerOutputData)
        var questionDataDisp = ``;
        var questionIndicatorDisp = ``;

        $('#isAnsweredMeter').empty();

        for (let i = 0; i < postAssessmentLearnerOutputData.length; i++) {
            const learner_post_assessment_output_id = postAssessmentLearnerOutputData[i]['learner_post_assessment_output_id'];
            const syllabus_id = postAssessmentLearnerOutputData[i]['syllabus_id'];
            const course_id = postAssessmentLearnerOutputData[i]['course_id'];
            const question_id = postAssessmentLearnerOutputData[i]['question_id'];
            const category = postAssessmentLearnerOutputData[i]['category'];
            const question = postAssessmentLearnerOutputData[i]['question'];
            const choices = postAssessmentLearnerOutputData[i]['all_choices'];
            const correct_answer = postAssessmentLearnerOutputData[i]['correct_answer'];
            const answer = postAssessmentLearnerOutputData[i]['answer'];
            const isCorrect = postAssessmentLearnerOutputData[i]['isCorrect'];

    
            const correctAns = JSON.parse(correct_answer);
            const choicesArray = JSON.parse(choices);

            const questionCount = i + 1; // Start counting from 1


            const check_answer = (isCorrect == 1)
            ? '<span class="text-left text-xl text-darthmouthgreen"><i class="fa-solid fa-check" style="color: #00693e;"></i> Correct</span>'
            : '<span class="text-left text-xl text-red-600"><i class="fa-solid fa-xmark" style="color: #b30000;"></i> Incorrect</span>';

            const check_answer_style = (isCorrect == 1)
            ? 'border-darthmouthgreen'
            : 'border-red-600';


            if (category == 'MULTIPLECHOICE') {
                questionDataDisp += `
                    <div class="my-5 py-5 px-3 questionData ${check_answer_style} border-2 rounded-lg">
                        <div class="questionContent">
                        ${check_answer}
                            <h6 class="opacity-40 text-right">Question ${questionCount}</h6>
                            <p class="text-xl font-normal p-2 font-semibold">${question}</p>
                        </div>
                        <div class="questionChoices mt-2 text-lg">
                `;
    
                for (let x = 0; x < choicesArray.length; x++) {
                    const choice = choicesArray[x];
                    const isChecked = choice === answer ? 'checked' : '';
    
                    questionDataDisp += `
                        <input type="radio" name="${questionCount}" class="w-5 h-5 questionChoice mx-3" value="${choice}" ${isChecked} disabled>
                        ${choice}<br>`;
                }
    
                questionDataDisp += `
                        </div>
                        <div class="mt-5">
                            <h1 class="text-xl font-semibold"> Answer: ${correctAns[0]}</h1>
                        </div>
                    </div>
                `;
            } else if (category == 'IDENTIFICATION') {
                questionDataDisp += `
                    <div class="my-5 py-5 px-3 questionData ${check_answer_style} border-2 rounded-lg">
                        <div class="questionContent">
                        ${check_answer}
                            <h6 class="opacity-40 text-right">Question ${questionCount}</h6>
                            <p class="text-xl font-normal p-2 font-semibold">${question}</p>
                        </div>
                        <div class="questionChoices mt-2 text-lg">
                            <textarea disabled type="text" class="border-2 border-gray-400 p-3 text-lg w-full identificationAns rounded-lg" placeholder="">${answer}</textarea>
                        </div>
                        <div class="mt-5">
                            <h1 class="text-xl font-semibold"> Answer: ${correctAns[0]}</h1>
                        </div>
                    </div>
                `;
            }

            const check_answer_flag = (isCorrect == 1)
            ? '<span><i class="fa-solid fa-check" style="color: #ffffff;"></i></span>'
            : '<span><i class="fa-solid fa-xmark" style="color: #ffffff;"></i></span>';

            const check_answer_flag_style = (isCorrect == 1)
            ? 'bg-darthmouthgreen'
            : 'bg-red-600';


            questionIndicatorDisp += `
                <div class="flex items-center justify-center question_isAnswered w-[35px] h-[45px] hover:cursor-pointer 
                            border border-darthmouthgreen text-white transition-all duration-300 ${check_answer_flag_style}">
                    ${questionCount}
                    ${check_answer_flag}
                </div>
            `;
        }
            $('#questionContainer').prepend(questionDataDisp);
            $('#isAnsweredMeter').append(questionIndicatorDisp);








        displayQuestionsByPage(currentPage);

        // Pagination event listeners
        $('#prevPage').on('click', function () {
            if (currentPage > 1) {
                currentPage--;
                displayQuestionsByPage(currentPage);
            }
        });

        $('#nextPage').on('click', function () {
            if (currentPage < Math.ceil(postAssessmentLearnerOutputData.length / questionsPerPage)) {
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

                    $('.loaderArea').addClass('hidden');
                    $('.mainchatbotarea').removeClass('hidden');


                },
                error: function(error) {
                    console.log(error);
                }
            });
    }

    function getCourseData(learner) {
        var course_id = $('#titleArea').data('course-id');
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
                    var lesson = 'POST ASSESSMENT';
    
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


})