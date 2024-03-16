$(document).ready(function() {
    getQuizData();
    var formContainer = $('#formContainer');

   

// -------------------------------------------------------------------------------------
var quizReferenceData = {};
var quizInfoData = {};
var questionsData = [];
var temp_questionsData = [];
var questionCounter = 0;
var quizContent = {};
var questionsExistingData = [];

function getQuizData() {
    var baseUrl = window.location.href;
    var url = baseUrl + "/json";

    $.ajax({
        type: "GET",
        url: url,
        dataType: 'json',
        success: function (response) {
            console.log(response);
            quizInfoData = response['quizInfo'];
            quizReferenceData = response['quizReference'];
            questionsData = response['quizContent'];
            questionsExistingData = response['questionsData'];
            // displayQuestions(questionsData, quizReferenceData);
            resetQuestionNumber(questionsData, quizReferenceData);
        },
        error: function (error) {
            console.log(error);
        }
    });
}
    
    
    
function resetQuestionNumber(questionsData, quizReferenceData) {
    if (Object.keys(questionsData).length > 0) {
        for (let i = 0; i < questionsData.length; i++) {
            // console.log(questionsData[i])
            const question_id = questionsData[i]['question_id'];
            const syllabus_id = questionsData[i]['syllabus_id'];
            const course_id = questionsData[i]['course_id'];
            const question = questionsData[i]['question'];
            const category = questionsData[i]['category'];
            const topic_title = questionsData[i]['topic_title'];
            const answers = questionsData[i]['answers'];
            const isCorrect = questionsData[i]['isCorrect'];

            questionCounter++;
            const row_temp_questions = {
                questionCount: questionCounter,
                question_id: question_id,
                syllabus_id: syllabus_id,
                course_id: course_id,
                question: question,
                category: category,
                topic_title: topic_title,
                answer: answers,
                isCorrect: isCorrect
            };

            temp_questionsData.push(row_temp_questions);
        }

        // console.log(temp_questionsData);
        displayQuestions(temp_questionsData, quizReferenceData);
    } else {
        console.log('no values');
    }
}




    function displayQuestions(temp_questionsData, quizReferenceData) {
        console.log(temp_questionsData)
        var temp_question_id = 0;
        var questionData = '';
        var totalQuestions = 0;
        var totalMultipleChoiceQuestions = 0;
        var totalIdentificationQuestions = 0;
        var referencesUsed = new Set();
        var questionsPerReference = {};


        for (let i = 0; i < temp_questionsData.length; i++) {
            const questionCount = temp_questionsData[i]['questionCount'];
            const question_id = temp_questionsData[i]['question_id'];
            const question_syllabus_id = parseInt(temp_questionsData[i]['syllabus_id']);
            const question_topic_title = temp_questionsData[i]['topic_title'];
            const course_id = temp_questionsData[i]['course_id'];
            const question = temp_questionsData[i]['question'];
            const category = temp_questionsData[i]['category'];
            const answers = temp_questionsData[i]['answer'];
            const isCorrect = temp_questionsData[i]['isCorrect'] // use the correct property name
    
            const answersArray = JSON.parse(answers);
            const isCorrectArray = JSON.parse(isCorrect);

            console.log(question_syllabus_id)
            totalQuestions += 1;    

            if(category === 'MULTIPLECHOICE'){
                totalMultipleChoiceQuestions += 1;
            } else if (category === 'IDENTIFICATION'){
                totalIdentificationQuestions += 1;
            }

            referencesUsed.add(question_topic_title);

            if(!questionsPerReference[question_topic_title]) {
                questionsPerReference[question_topic_title] = 0;
            }

            questionsPerReference[question_topic_title] += 1;


            if (question_id !== temp_question_id) {
                if (temp_question_id !== 0) {
                    // Close the previous question div
                    questionData += '</div></div>';
                }
    
                questionData += `
                <div data-question-count="${questionCount}" class="questionContainer my-5 p-5 rounded-lg border-darthmouthgreen w-4/5 border-2">
                    <div class="flex justify-end">
                        <button class="text-2xl removeQuestionBtn">
                            <i class="fa-solid fa-xmark" style="color: #00693e;"></i>
                        </button>
                    </div>

                    <div class="questionContent" data-question-id="${question_id}">
                        <div class="question_category_reference flex justify-between border-b-2 pb-3 border-black">
                            <div class="flex flex-col w-3/4 px-2 py-3">
                            <label for="question">Question:</label>
                            <textarea class="question text-xl font-medium border border-gray-300 outline-none p-2" placeholder="Question">${question}</textarea>
                                <div class="mt-3 flex flex-col"> 
                                    <label for="questionReference">Reference</label>
                                    <select name="questionReference" id="" class="questionReference text-md my-1 py-2">
                                    `;
                                    console.log(question_syllabus_id)
                                    for (let x = 0; x < quizReferenceData.length; x++) {
                                        const reference_syllabus_id = parseInt(quizReferenceData[x]['syllabus_id']);
                                        const reference_topic_title = quizReferenceData[x]['topic_title'];
                                        
                                            if(reference_syllabus_id === question_syllabus_id) {
                                                questionData += `
                                                <option value="${reference_syllabus_id}" selected>${reference_topic_title}</option>
                                            `;
                                            } else {
                                                questionData += `
                                                <option value="${reference_syllabus_id}">${reference_topic_title}</option>
                                            `;
                                            }
                                            

                                        // if (question_syllabus_id === reference_syllabus_id) {
                                        //     questionData += `
                                        //         <option value="${reference_syllabus_id}" selected>${reference_topic_title}</option>
                                        //     `;
                                        // } else {
                                        //     questionData += `
                                        //         <option value="${reference_syllabus_id}">${reference_topic_title}</option>
                                        //     `;
                                        // }
                                    }
                                    
                questionData += `
                                    </select>
                                </div>
                            </div>

                            
                            <div class="pt-5 flex flex-col w-full md:w-1/2 sm:w-1/2">
                                <div class="flex flex-col">
                                    <label for="questionCategory">Category</label>
                                    <select name="questionCategory" id="" class="questionCategory w-48 text-md my-1 py-2">
                                        <option value="MULTIPLECHOICE" ${category === 'MULTIPLECHOICE' ? 'selected' : ''}>Multiple Choice</option>
                                        <option value="IDENTIFICATION" ${category === 'IDENTIFICATION' ? 'selected' : ''}>Identification</option>
                                    </select>
                                </div>
                            </div>     
                        </div>`;            
            }
    
            if (category === 'MULTIPLECHOICE') {
                // Process the multiple-choice answers
                questionData += `
                    <div class="question_choices">
                        <table class="w-full mt-5">
                            <tbody class="choicesArea">${getChoicesHTML(answersArray, isCorrectArray)}</tbody>
                        </table>
                        <button class="py-5 text-lg addNewOptionBtn">
                            <i class="fa-solid fa-circle-plus" style="color: #00693e;"></i>
                            <span class="mx-3">add option</span>
                        </button>
                    </div>

    
                    <div class="question_correct_answer">
                        <span>Correct Answer</span>
                        <select name="selectCorrectAnswer" class="selectCorrectAnswer">
                        `;
                        for (let y = 0; y < answersArray.length; y++) {
                            const answer = answersArray[y];
                            const correctAnswer = isCorrectArray[y]
                            
                            // console.log(answer + " : " + correctAnswer)
                            if(correctAnswer === 1) {
                                questionData += `
                            <option value="${answer}" selected>${answer}</option>
                            `
                            }
                            questionData += `
                            <option value="${answer}">${answer}</option>
                            `
                        }
                            
                questionData += `
                            </select>
                    </div>
                </div>`;
            } else if (category === 'IDENTIFICATION') {
                // Process the identification answers
                questionData += `
                    <div class="question_answer mt-5 flex">
                        <textarea type="text" class="identificationAns w-4/5" placeholder="Answer here...">${answersArray}</textarea>
                        <div class="isCorrectCheck ${isCorrectArray == 1 ? '' : 'hidden'}">
                            <i class="fa-solid fa-check text-xl" style="color: #00693e;"></i>
                            <span>correct</span>
                        </div>
                    </div>
                </div>`;
            } else {
                // Process other types of questions
                questionData += `
                    <div class="question_answer mt-5 flex">
                        <textarea type="text" class="w-4/5 h-15" placeholder="Answer here..." disabled></textarea>
                    </div>
                </div>`;
            }
    
            temp_question_id = question_id;
        }
    
        if (temp_question_id !== 0) {
            // Close the last question div
            questionData += '</div></div>';
        }

 
        // // alert(questionTotalCount)
        var quizContentSummaryDisp = `
            <div class="quizContentSummary mt-5 border-t border-gray-300 outline-none ">
                <h1 class="text-2xl font-semibold">Quiz Content Summary</h1>
                <table class="table-auto">
                    <thead>
                        <tr>
                            <th class="px-5 py-2">Category</th>
                            <th class="px-5 py-2">Count</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b-2 border-black">
                            <td>Total Questions in the Quiz:</td>
                            <td class="px-5 font-lg font-semibold">${totalQuestions}</td>
                        </tr>
                        <tr>
                            <td>Multiple Choice Questions:</td>
                            <td class="px-5 font-lg font-semibold">${totalMultipleChoiceQuestions}</td>
                        </tr>
                        <tr>
                            <td>Identification Questions:</td>
                            <td class="px-5 font-lg font-semibold">${totalIdentificationQuestions}</td>
                        </tr>
                        <tr class="border-b-2 border-black">
                            <td>References Used in the Quiz:</td>
                        </tr>
                `;

        for (const reference of referencesUsed) {
            quizContentSummaryDisp += `
                <tr>
                    <td>${reference}:</td>
                    <td class="px-5 font-lg font-semibold">${questionsPerReference[reference] || 0} questions</td>
                </tr>
            `;
        }

        quizContentSummaryDisp += `
                </tbody>
            </table>
        </div>
        `;

        $('.quizContentSummary').remove();
        $('.quizOptions').after(quizContentSummaryDisp);


        formContainer.empty();
        formContainer.append(questionData);



        // change question category
        $('.questionCategory').on('change', function(e) {
            e.preventDefault();
            var update_questionTemplate = '';
        
            const questionContainer = $(this).closest('.questionContainer')
            const questionContent = $(this).closest('.questionContent')
            const question_count = questionContainer.data('question-count')
            const category = $(this).val();
        
            
            const questionData = temp_questionsData.find(q => q.questionCount === question_count);

            // // Accessing individual keys
            // console.log(questionData.question); // Output: "What is a business?"
            // console.log(questionData.topic_title); // Output: "Lesson 1: Fundamentals of Business 1"
            // console.log(questionData.answer); // Output: "[\"Option B\", \"Option A\"]"
            // console.log(questionData.isCorrect); // Output: "[1, 0]"

            // If you want to convert the answer and isCorrect strings to arrays
            const answersArray = JSON.parse(questionData.answer);
            const isCorrectArray = JSON.parse(questionData.isCorrect);

            // console.log(answersArray); // Output: ["Option B", "Option A"]
            // console.log(isCorrectArray); // Output: [1, 0]

            if (category === 'MULTIPLECHOICE') {
                questionData.category = 'MULTIPLECHOICE'


                for (let i = 0; i < answersArray.length; i++) {
                    const answer = answersArray[i];
                    const isCorrect = isCorrectArray[i];

                    if(isCorrect === 1) {
                        var tempAnswer_correct = {
                            answer: answer,
                            isCorrect: isCorrect
                        }
                    } 
                }
                    answersArray.length = 0;
                    isCorrectArray.length = 0;
                // console.log(tempAnswer_correct);
                    answersArray.push(tempAnswer_correct.answer)
                    isCorrectArray.push(tempAnswer_correct.isCorrect)

                    questionData.answer = JSON.stringify(answersArray)
                    questionData.isCorrect = JSON.stringify(isCorrectArray)
                // console.log(questionData);
                // Process the multiple-choice answers
                // update_questionTemplate += `
                //     <div class="question_choices">
                //         <table class="w-full mt-5">
                //             <tbody class="choicesArea">${getChoicesHTML(answersArray, isCorrectArray)}</tbody>
                //         </table>
                //         <button class="py-5 text-lg addNewOptionBtn">
                //             <i class="fa-solid fa-circle-plus" style="color: #00693e;"></i>
                //             <span class="mx-3">add option</span>
                //         </button>
                //     </div>
            
                //     <div class="question_correct_answer">
                //         <span>Correct Answer</span>
                //         <select name="selectCorrectAnswer" class="selectCorrectAnswer">
                //         `;
            
                // for (let y = 0; y < answersArray.length; y++) {
                //     const answer = answersArray[y];
            
                //     update_questionTemplate += `
                //         <option value="${answer}">${answer}</option>
                //         `;
                // }

                // for (let y = 0; y < answersArray.length; y++) {
                //     const answer = answersArray[y];
                //     const correctAnswer = isCorrectArray[y]
                    
                //     console.log(answer + " : " + correctAnswer)
                //     if(correctAnswer === 1) {
                //         update_questionTemplate += `
                //     <option value="${answer}" selected>${answer}</option>
                //     `
                //     }
                //     update_questionTemplate += `
                //     <option value="${answer}">${answer}</option>
                //     `
                // }
            
                // update_questionTemplate += `
                //         </select>
                //     </div>
                // </div>`;
            
            
            } else if (category === 'IDENTIFICATION') {
                questionData.category = 'IDENTIFICATION'
                // console.log(questionData);
                // Process the identification answers
                for (let i = 0; i < answersArray.length; i++) {
                    const answer = answersArray[i];
                    const isCorrect = isCorrectArray[i];

                    if(isCorrect === 1) {
                        var tempAnswer_correct = {
                            answer: answer,
                            isCorrect: isCorrect
                        }
                    } 
                }
                    answersArray.length = 0;
                    isCorrectArray.length = 0;
                // console.log(tempAnswer_correct);
                    answersArray.push(tempAnswer_correct.answer)
                    isCorrectArray.push(tempAnswer_correct.isCorrect)

                    questionData.answer = JSON.stringify(answersArray)
                    questionData.isCorrect = JSON.stringify(isCorrectArray)


                //     // console.log(tempAnswer_x);
                
                // update_questionTemplate += `
                //     <div class="question_answer mt-5 flex">
                //         <textarea type="text" class="identificationAns w-4/5" placeholder="Answer here...">${tempAnswer_correct.answer}</textarea>
                //         <div class="isCorrectCheck ${tempAnswer_correct.isCorrect == 1 ? '' : 'hidden'}">
                //             <i class="fa-solid fa-check text-xl" style="color: #00693e;"></i>
                //             <span>correct</span>
                //         </div>
                //     </div>
                // </div>`;
            } else {
                // Process other types of questions
                update_questionTemplate += `
                    `;
            }
        
            // questionContent.find('.question_category_reference').siblings().remove();
            // questionContent.append(update_questionTemplate);

            
            displayQuestions(temp_questionsData, quizReferenceData)
        });


        $('.removeQuestionBtn').on('click', function(e) {
            e.preventDefault();

            const questionContainer = $(this).closest('.questionContainer')
            // const questionContent = $(this).closest('.questionContent')
            const question_count = questionContainer.data('question-count')

            
            const questionData = temp_questionsData.find(q => q.questionCount === question_count);

            const index = temp_questionsData.indexOf(questionData);
            // console.log(questionData)
            // console.log(index);

            if (index !== -1) {
                // Log the questionData and its index
                // console.log(questionData);
                // console.log(index);
        
                // Now you can remove the item from the array using splice
                temp_questionsData.splice(index, 1);
        
                displayQuestions(temp_questionsData, quizReferenceData);
                // Log the updated temp_questionsData
                // console.log(temp_questionsData);
            } else {
                console.log('Question not found in the array.');
            }
        })

        // Use event delegation for both confirm and cancel buttons
        $('.questionContainer').on('click', '.addNewOption_confirm', function(e) {
            e.preventDefault();
        
            // Find the parent container of the clicked button
            const newChoiceContainer = $(this).closest('.newChoice');
            const newOptionVal = newChoiceContainer.find('.newOptionVal').val();
        
            const questionContainer = $(this).closest('.questionContainer');
            const question_count = questionContainer.data('question-count');
            const questionData = temp_questionsData.find(q => q.questionCount === question_count);
        
            // Parse existing answers and isCorrect arrays
            const answers = JSON.parse(questionData.answer || '[]');
            const isCorrect = JSON.parse(questionData.isCorrect || '[]');
        
            // Add a new answer with the value and a default isCorrect of 0
            answers.push(newOptionVal);
            isCorrect.push(0);
        
            // Update the answers and isCorrect arrays in questionData
            questionData.answer = JSON.stringify(answers);
            questionData.isCorrect = JSON.stringify(isCorrect);
        
            const index = temp_questionsData.indexOf(questionData);
            
            console.log(questionData);
            console.log(index);
            
        displayQuestions(temp_questionsData, quizReferenceData)
        });
        


        $('.questionContainer').on('click', '.addNewOption_cancel', function(e) {
            e.preventDefault();

            const questionContainer = $(this).closest('.questionContainer');
            const optionsContainer = questionContainer.find('.choicesArea');
            const newChoiceContainer = $(this).closest('.newChoice');

            newChoiceContainer.remove();
            questionContainer.find('.addNewOptionBtn').removeClass('hidden');
        });


        $('.addNewOptionBtn').on('click', function(e) {
            e.preventDefault();

            const questionContainer = $(this).closest('.questionContainer');
            const optionsContainer = questionContainer.find('.choicesArea');
            const question_count = questionContainer.data('question-count');

            const questionData = temp_questionsData.find(q => q.questionCount === question_count);

            const index = temp_questionsData.indexOf(questionData);
            const newOptionDisp = `
                <div class="my-5 w-full newChoice text-lg items-center flex">
                    <input type="radio" class="w-6 h-6">
                    <input type="text" class="newOptionVal mx-5 w-full" placeholder="Option">
                    <button class="addNewOption_confirm p-2 mx-5"><i class=" text-2xl fa-solid fa-check" style="color: #00693e;"></i></button>
                    <button class="addNewOption_cancel p-2 mx-5"><i class=" text-2xl fa-solid fa-xmark" style="color: #fa0000;"></i></button>
                </div>
            `;

            // Create a new div element with the new options
            const newOptionsDiv = $('<div>').html(newOptionDisp);

            // Append the new options to the existing options
            optionsContainer.append(newOptionsDiv.html());
            questionContainer.find('.newOptionVal').focus();

            $(this).addClass('hidden');
        });

        

        $('.questionContainer').on('change', '.optionVal', function(e) {
            e.preventDefault();
        
            const questionContainer = $(this).closest('.questionContainer');
            const question_count = questionContainer.data('question-count');
        
            // Find the original questionData before making any changes
            const originalQuestionData = temp_questionsData.find(q => q.questionCount === question_count);
        
            // Make a copy of the original data to avoid modifying the original array
            const questionDataCopy = { ...originalQuestionData };
        
            // Parse the existing answers string into an array
            const originalAnswersArray = JSON.parse(originalQuestionData.answer || '[]');
        
            // Get the index of the changed option in the originalAnswersArray
            const index = $(this).closest('tr').index();
        
            const optionVal = $(this).val(); // Note the parentheses after val
        
            // Modify the array at the specific index
            originalAnswersArray[index] = optionVal;
        
            // Update the modified array back to the questionDataCopy
            questionDataCopy.answer = JSON.stringify(originalAnswersArray);
        
            // Update the original data in the temp_questionsData array
            const indexInTempQuestionsData = temp_questionsData.indexOf(originalQuestionData);
            temp_questionsData[indexInTempQuestionsData] = questionDataCopy;
        
            // Now you have both the original answers and the modified answers
            // console.log("Original Answers Array:", originalAnswersArray);
            // console.log("Modified Question Data:", questionDataCopy);
            // console.log("Index in Array:", index);
        
            displayQuestions(temp_questionsData, quizReferenceData)
            // If needed, you can save the updated data to your backend or perform other actions
            // For example: saveUpdatedQuestionData(questionDataCopy);
        });
        
        $('.questionContainer').on('click', '.removeChoiceBtn', function(e) {
            e.preventDefault();
        
            const questionContainer = $(this).closest('.questionContainer');
            const question_count = questionContainer.data('question-count');
        
            // Find the questionData before making any changes
            const questionData = temp_questionsData.find(q => q.questionCount === question_count);
        
            // Parse the existing answers and isCorrect strings into arrays
            const answersArray = JSON.parse(questionData.answer || '[]');
            const isCorrectArray = JSON.parse(questionData.isCorrect || '[]');
        
            // Get the index of the removed option in the arrays
            const index = $(this).closest('tr').index();
        
            // Remove the option and its corresponding isCorrect value at the specific index
            answersArray.splice(index, 1);
            isCorrectArray.splice(index, 1);
        
            // Update the modified arrays back to the questionData
            questionData.answer = JSON.stringify(answersArray);
            questionData.isCorrect = JSON.stringify(isCorrectArray);
        
            // Update the original data in the temp_questionsData array
            const indexInTempQuestionsData = temp_questionsData.indexOf(questionData);
            temp_questionsData[indexInTempQuestionsData] = questionData;
        
            // Now you have both the modified answers and isCorrect arrays
            // console.log("Modified Answers Array:", answersArray);
            // console.log("Modified isCorrect Array:", isCorrectArray);
            // console.log("Modified Question Data:", questionData);
            // console.log("Index in Array:", index);
        
    
            displayQuestions(temp_questionsData, quizReferenceData)
        });
        

        $('.questionContainer').on('change', '.selectCorrectAnswer', function(e) {
            e.preventDefault();
        
            const questionContainer = $(this).closest('.questionContainer');
            const question_count = questionContainer.data('question-count');
        
            // Find the questionData before making any changes
            const questionData = temp_questionsData.find(q => q.questionCount === question_count);
        
            // Parse the existing answers and isCorrect strings into arrays
            const answersArray = JSON.parse(questionData.answer || '[]');
            let isCorrectArray = JSON.parse(questionData.isCorrect || '[]');
        
            // Get the selected correct answer value
            const correctAnswerVal = $(this).val();
        
            // Find the index of the correct answer in the answersArray
            const correctAnswerIndex = answersArray.indexOf(correctAnswerVal);
        
            // Update the isCorrectArray based on the selected correct answer
            isCorrectArray = isCorrectArray.map((_, index) => (index === correctAnswerIndex ? 1 : 0));
        
            // Update the isCorrect property in questionData
            questionData.isCorrect = JSON.stringify(isCorrectArray);
        
            // Display the updated questions
            displayQuestions(temp_questionsData, quizReferenceData);
        });
        
        
        
        $('.questionContainer').on('change', '.question', function(e) {
            e.preventDefault();

            const questionContainer = $(this).closest('.questionContainer');
            const question_count = questionContainer.data('question-count');
        
            const question = $(this).val();
            if(question == null || question == '') {
                alert('please enter a value')

                question.focus();
            } else {
                // Find the questionData before making any changes
                const questionData = temp_questionsData.find(q => q.questionCount === question_count);
                    
                // console.log(questionData.question)


                questionData.question = question;
                // console.log(question);
                
            }

    
            displayQuestions(temp_questionsData, quizReferenceData);
        });


        $('.questionContainer').on('change', '.questionReference', function(e) {
            e.preventDefault();
        
            const questionContainer = $(this).closest('.questionContainer');
            const question_count = questionContainer.data('question-count');
            const questionReference = $(this).val();
        
            // Find the index of the questionData in temp_questionsData
            const questionData = temp_questionsData.find(q => q.questionCount === question_count);
        
            questionData.syllabus_id = parseInt(questionReference);
            
                displayQuestions(temp_questionsData, quizReferenceData);
        });
        


        $('.questionContainer').on('change', '.identificationAns', function(e) {
            e.preventDefault();
        
            const questionContainer = $(this).closest('.questionContainer');
            const question_count = questionContainer.data('question-count');
        
            const identificationAnswer = $(this).val();
        
            // Find the questionData before making any changes
            const questionData = temp_questionsData.find(q => q.questionCount === question_count);
        
            // Make a copy of the original data to avoid modifying the original array
            const questionDataCopy = { ...questionData };
        
            // Update the identification answer in the copied data
            questionDataCopy.answer = JSON.stringify([identificationAnswer]);
        
            // Update the original data in the temp_questionsData array
            const indexInTempQuestionsData = temp_questionsData.indexOf(questionData);
            temp_questionsData[indexInTempQuestionsData] = questionDataCopy;
        
            displayQuestions(temp_questionsData, quizReferenceData);
            // If needed, you can save the updated data to your backend or perform other actions
            // For example: saveUpdatedQuestionData(questionDataCopy);
        });
        


        // function for saving the correct answer in the identification

        // function for publishing to the backend
        // button for preview question
    }
    








    function getChoicesHTML(choices, isCorrectArray) {
        var choicesHTML = '';
    
        for (let i = 0; i < choices.length; i++) {
            const answer = choices[i];
            const isCorrect = isCorrectArray[i]; // Rename the variable
            choicesHTML += `
                <tr class="h-10 rounded-xl">
                    <td class="w-4/5">
                        <div class="w-full choice text-lg items-center flex">
                            <input type="radio" class="w-6 h-6">
                            <input type="text" class="optionVal mx-5 w-full" value="${answer}">
                        </div>
                    </td>
                    <td class="w-1/5">
                        <div class="isCorrectCheck ${isCorrect === 1 ? '' : 'hidden'}">
                            <i class="fa-solid fa-check text-xl" style="color: #00693e;"></i>
                            <span>correct</span>
                        </div>
                    </td>
                    <td>
                        <button class="text-2xl removeChoiceBtn">
                            <i class="fa-solid fa-xmark" style="color: #00693e;"></i>
                        </button>
                    </td>
                </tr>`;
        }
    
        return choicesHTML;
    }
    
    
    var temp_question_count = 0;
    $('#addNewQuestionBtn').on('click', function(e) {
        e.preventDefault();

        
        var currentCount = questionCounter;
        // console.log(currentCount);
        var updatedCount = currentCount + 1;
        // console.log(updatedCount);
        const newRow_temp_questionData = {
            questionCount: updatedCount,
            question_id: "temp " + temp_question_count++,
            syllabus_id: parseInt(quizReferenceData[0]['syllabus_id']),
            course_id: quizInfoData['course_id'],
            category: "MULTIPLECHOICE",
            question: " ",
            topic_title: quizReferenceData[0]['topic_title'],
            answer: "[\"Option 1\"]",
            isCorrect: "[1]"
        }

        questionCounter++;
        temp_questionsData.push(newRow_temp_questionData)
        displayQuestions(temp_questionsData, quizReferenceData)
        
        // formContainer.append(questionTemplate_multipleChoice);
    })

    $('#addExistingQuestionBtn').on('click', function(e) {
        e.preventDefault();
        var questionSelectionDisp = ``;
        // alert('test')
        $('#addExistingQuestionModal').removeClass('hidden');

        questionSelectionDisp += `
        <label for="questionSelection">Question</label><br>
            <select name="questionSelection" id="questionSelection" class="w-full text-lg py-3">
                <option value="" disabled>Choose Existing Question</option>
        `;

        for (let i = 0; i < questionsExistingData.length; i++) {
            const question_id = questionsExistingData[i]['question_id'];
            const syllabus_id = questionsExistingData[i]['syllabus_id'];
            const course_id = questionsExistingData[i]['course_id'];
            const question = questionsExistingData[i]['question'];
            const category = questionsExistingData[i]['category'];
            const topic_title = questionsExistingData[i]['topic_title'];
            const answers = questionsExistingData[i]['answers'];
            const isCorrect = questionsExistingData[i]['isCorrect'];
            
            // console.log(questionsExistingData[i]);
            questionSelectionDisp += `
            <option value="${question_id}">${question}</option>
            `;
        }
        questionSelectionDisp += `
        </select>
        `;

        $('#existingQuestionArea').empty();
        $('#existingQuestionArea').append(questionSelectionDisp);
    })

    $('#confirmAddExistingQuestionBtn').on('click', function(e) {
        e.preventDefault();

        const questionID = $('#questionSelection').val();

        const questionData = questionsExistingData.find(q => q.question_id === parseInt(questionID));
        
        // console.log(questionData);
        var currentCount = questionCounter;
        // console.log(currentCount);
        var updatedCount = currentCount + 1;
        // console.log(updatedCount);
        const newRow_temp_questionData = {
            questionCount: updatedCount,
            question_id: questionData['question_id'],
            syllabus_id: questionData['syllabus_id'],
            course_id: questionData['course_id'],
            category: questionData['category'],
            question: questionData['question'],
            topic_title: questionData['topic_title'],
            answer: questionData['answers'],
            isCorrect: questionData['isCorrect']
        }

        questionCounter++;
        temp_questionsData.push(newRow_temp_questionData)
        displayQuestions(temp_questionsData, quizReferenceData)
        $('#addExistingQuestionModal').addClass('hidden');
    })

    $('.cancelAddExistingQuestionBtn').on('click', function(e) {
        e.preventDefault();

        $('#addExistingQuestionModal').addClass('hidden');
    })





    
    $('.saveQuizContent').on('click', function(e) {
        e.preventDefault();

        $('#confirmSaveQuizContentModal').removeClass('hidden');

    })


    $('.cancelSaveQuizContentBtn').on('click', function(e) {
        e.preventDefault();

        
        $('#confirmSaveQuizContentModal').addClass('hidden');
    })
    
    
    $('#confirmSaveQuizContentBtn').on('click', function (e) {
        e.preventDefault();
    
        
        $('#loaderModal').removeClass('hidden');
        let loopCounter = 0;
        let completedRequests = 0;
    
        if (temp_questionsData.length === 0) {
            alert("Please add at least one question to the Quiz");
        } else {
            temp_questionsData.forEach(function (questionData) {
                const {
                    questionCount,
                    question_id,
                    syllabus_id,
                    topic_title,
                    course_id,
                    question,
                    category,
                    answer,
                    isCorrect
                } = questionData;
    
                const rowData = {
                    syllabus_id: parseInt(syllabus_id),
                    course_id: course_id,
                    question: question,
                    category: category,
                    answer: answer,
                    isCorrect: isCorrect,
                    question_id: question_id,
                };
    
                const csrfToken = $('meta[name="csrf-token"]').attr('content');
                const baseUrl = window.location.href;
    
                if (loopCounter === 0) {
                    // AJAX request for emptying the quiz content
                    var urlEmpty = baseUrl + "/empty";
                    $.ajax({
                        type: "POST",
                        url: urlEmpty,
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        },
                        success: function (response) {
                            // Handle success if needed
                            loopCounter++;
                            sendRequest();
                        },
                        error: function (error) {
                            console.log(error);
                        }
                    });
                } else {
                    sendRequest();
                }
    
                function sendRequest() {
                    const urlAction = isNaN(question_id) ? "/add" : "/update";
                    const url = baseUrl + urlAction;
    
                    // AJAX request for either adding or updating a question
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: rowData,
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        },
                        success: function (response) {
                            // Handle success if needed
                            console.log(response);
                            completedRequests++;
    
                            if (completedRequests === temp_questionsData.length) {
                                $('#loaderModal').addClass('hidden');
                                window.location.reload();
                            }
                        },
                        error: function (error) {
                            console.log(error);
                            completedRequests++;
    
                            if (completedRequests === temp_questionsData.length) {
                                $('#loaderModal').addClass('hidden');
                                window.location.reload();
                            }
                        }
                    });
                    loopCounter++;
                }
            });
        }
    });
    
    

})