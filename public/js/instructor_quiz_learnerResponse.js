$(document).ready(function() {


    var quizLearnerOutputData = [];
    var currentPage = 1;
    var questionsPerPage = 5;
    var baseUrl = window.location.href;


    getLearnerQuizOutputData();


    function getLearnerQuizOutputData() {

        var url = baseUrl + "/json";

        $.ajax({
            type: "GET",
            url: url,

            success: function(response) {
                console.log(response);

                quizLearnerOutputData = response['learnerQuizOutputData'];
                displayLearnerQuizOutputData(quizLearnerOutputData);
            },
            error: function(error) {
                console.log(error);
            }
        });
    }

    function displayLearnerQuizOutputData(quizLearnerOutputData) {

        // console.log(quizLearnerOutputData)
        var questionDataDisp = ``;
        var questionIndicatorDisp = ``;

        $('#isAnsweredMeter').empty();

        for (let i = 0; i < quizLearnerOutputData.length; i++) {
            const learner_quiz_output_id = quizLearnerOutputData[i]['learner_quiz_output_id'];
            const quiz_content_id = quizLearnerOutputData[i]['quiz_content_id'];
            const quiz_id = quizLearnerOutputData[i]['quiz_id'];
            const syllabus_id = quizLearnerOutputData[i]['syllabus_id'];
            const course_id = quizLearnerOutputData[i]['course_id'];
            const question_id = quizLearnerOutputData[i]['question_id'];
            const category = quizLearnerOutputData[i]['category'];
            const question = quizLearnerOutputData[i]['question'];
            const choices = quizLearnerOutputData[i]['all_choices'];
            const correct_answer = quizLearnerOutputData[i]['correct_answer'];
            const answer = quizLearnerOutputData[i]['answer'];
            const isCorrect = quizLearnerOutputData[i]['isCorrect'];

    
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
            if (currentPage < Math.ceil(quizLearnerOutputData.length / questionsPerPage)) {
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


})