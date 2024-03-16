$(document).ready(function() {
    var baseUrl = window.location.href

    getLearnerSyllabusQuizProgressData()
    getQuizOutputScoreData();
    getLearnerSyllabusQuizContentOutputData();

    function getLearnerSyllabusQuizProgressData() {
        var url = baseUrl + "/quizData"
    
        $.ajax({
            type: "GET",
            url: url,
            success: function (response) {
                // console.log(response);

                var attemptCount = response['attemptCount']
                var learnerQuizProgressData = response['learnerQuizProgressData']
                var averageTimeDifference = response['averageTimeDifference']
                var totalLearnerQuizProgressCount = response['totalLearnerQuizProgressCount']
                var totalLearnerQuizCompleteCount = response['totalLearnerQuizCompleteCount']
                var totalLearnerQuizInProgressCount = response['totalLearnerQuizInProgressCount']
                var totalLearnerQuizLockedCount = response['totalLearnerQuizLockedCount']

                $('#totalLearnerSyllabusCompleteStatus').text(totalLearnerQuizProgressCount)
                $('#totalLearnersCount').text(totalLearnerQuizCompleteCount)
                $('#totalLearnerSyllabusInProgressStatus').text(totalLearnerQuizInProgressCount)
                $('#totalLearnerSyllabusNotYetStatus').text(totalLearnerQuizLockedCount)

                $('#averageLearnerProgress').text(averageTimeDifference)

                displayLearnerSyllabusStatusChart(learnerQuizProgressData)
                displayLearnerSyllabusStatusTimeChart(learnerQuizProgressData)
                displayLearnerSyllabusAttemptNumberChart(attemptCount)
                displayLearnerSyllabusProgressTable(learnerQuizProgressData)
            },
            error: function(error) {
                console.log(error);
            }
        });   
    }

    function displayLearnerSyllabusStatusChart(learnerQuizProgressData) {
        const statusCounts = {};
        learnerQuizProgressData.forEach((learner) => {
            const status = learner.status;
            statusCounts[status] = (statusCounts[status] || 0) + 1;
        });

        const labels = Object.keys(statusCounts);
        const dataValues = Object.values(statusCounts);

        const dartmouthGreenPalette = [
            '#00693e',
            '#005230',
            '#004224',
        ];

        const ctx = $('#learnerSyllabusStatusChart');
        if (ctx.data('chart')) {
            ctx.data('chart').destroy();
        }

        const newChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: dataValues,
                    backgroundColor: dartmouthGreenPalette,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    position: 'top',
                },
            },
        });

        ctx.data('chart', newChart);
    }

    function displayLearnerSyllabusStatusTimeChart(learnerQuizProgressData) {
        const completionRates = learnerQuizProgressData.map((learner) => {
            const startPeriod = new Date(learner.start_period);
            const finishPeriod = new Date(learner.finish_period);


            const completionRate = finishPeriod - startPeriod;

            return completionRate / 1000;
        });


        const labels = learnerQuizProgressData.map((learner) => `Learner ${learner.learner_quiz_progress_id}`);

        const ctx = $('#learnerSyllabusStatusTimeChart');

        if (ctx.data('chart')) {
            ctx.data('chart').destroy();
        }


        const newChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Completion Rate (seconds)',
                    data: completionRates,
                    backgroundColor: '#00693e',
                    borderColor: '#00693e',
                    borderWidth: 1,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        display: false, 
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Completion Rate (seconds)',
                        },
                    },
                },
            },
        });

        ctx.data('chart', newChart);
    }


    function displayLearnerSyllabusAttemptNumberChart(attemptCount) {
        const ctx = $('#learnerSyllabusAttemptNumberChart');

        if (ctx.data('chart')) {
            ctx.data('chart').destroy();
        }

        const oneAttemptCount = attemptCount.OneAttempt || 0;
        const reAttemptsCount = attemptCount.ReAttempts || 0;

        const newChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['One Attempt', 'Reattempts'],
                datasets: [{
                    label: 'Number of Attempts',
                    data: [oneAttemptCount, reAttemptsCount],
                    backgroundColor: '#00693e',
                    borderColor: '#00693e',
                    borderWidth: 1,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        display: true,
                        title: {
                            display: true,
                            text: 'Attempts',
                        },
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Attempts',
                        },
                        stepSize: 1,
                    },
                },
            },
        });

        ctx.data('chart', newChart);
    }


    function getQuizOutputScoreData() {
        var url = baseUrl + "/quizData/outputs"
    
        $.ajax({
            type: "GET",
            url: url,
            success: function (response) {
                // console.log(response);

                var learnerQuizOutputOverallScoreData = response['learnerQuizOutputOverallScoreData']
                displayLearnerSyllabusOverallScoreChart(learnerQuizOutputOverallScoreData)
                displayLearnerSyllabusRemarksChart(learnerQuizOutputOverallScoreData)
                
            },
            error: function(error) {
                console.log(error);
            }
        });
    }

    function displayLearnerSyllabusOverallScoreChart(learnerQuizOutputOverallScoreData) {
        const ctx = $('#learnerSyllabusOverallScoreChart');

        if (ctx.data('chart')) {
            ctx.data('chart').destroy();
        }
    

        const scoresCount = {};
        learnerQuizOutputOverallScoreData.forEach(item => {
            const score = item.score;
            scoresCount[score] = (scoresCount[score] || 0) + 1;
        });
    
        const labels = Object.keys(scoresCount);
        const data = Object.values(scoresCount);
    
        const newChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Number of Outputs',
                    data: data,
                    backgroundColor: '#00693e',
                    borderColor: '#00693e',
                    borderWidth: 1,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        display: true,
                        title: {
                            display: true,
                            text: 'Score',
                        },
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Outputs',
                        },
                        stepSize: 1,
                    },
                },
            },
        });
    
        ctx.data('chart', newChart);
    }


    function displayLearnerSyllabusRemarksChart(learnerQuizOutputOverallScoreData) {
        const ctx = $('#learnerSyllabusRemarksChart');

        if (ctx.data('chart')) {
            ctx.data('chart').destroy();
        }
    
        // Count occurrences of each remark
        const remarksCount = {
            'PASS': 0,
            'FAIL': 0
        };
    
        learnerQuizOutputOverallScoreData.forEach(item => {
            const remark = item.remarks;
            remarksCount[remark]++;
        });
    
        const labels = Object.keys(remarksCount);
        const data = Object.values(remarksCount);
    
        const newChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Number of Outputs',
                    data: data,
                    backgroundColor: '#00693e',
                    borderColor: '#00693e',
                    borderWidth: 1,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        display: true,
                        title: {
                            display: true,
                            text: 'Remarks',
                        },
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Outputs',
                        },
                        stepSize: 1,
                    },
                },
            },
        });
    
        ctx.data('chart', newChart);
    }


    function displayLearnerSyllabusProgressTable(learnerQuizProgressData) {
        var learnerSyllabusProgressTableDisp = ``;

        for (let i = 0; i < learnerQuizProgressData.length; i++) {
            const learner_quiz_progress_id = learnerQuizProgressData[i]['learner_quiz_progress_id'];
            const learner_course_id = learnerQuizProgressData[i]['learner_course_id'];
            const course_id = learnerQuizProgressData[i]['course_id'];
            const syllabus_id = learnerQuizProgressData[i]['syllabus_id'];
            const learner_fname = learnerQuizProgressData[i]['learner_fname'];
            const learner_lname = learnerQuizProgressData[i]['learner_lname'];
            const quiz_id = learnerQuizProgressData[i]['quiz_id'];
            const score = learnerQuizProgressData[i]['score'];
            const status = learnerQuizProgressData[i]['status'];
            const start_period = learnerQuizProgressData[i]['start_period'];
            const finish_period = learnerQuizProgressData[i]['finish_period'];
            const attempt = learnerQuizProgressData[i]['attempt'];
            const topic_id = learnerQuizProgressData[i]['topic_id'];
            const remarks = learnerQuizProgressData[i]['remarks'];
            
            const absoluteUrl = `/instructor/course/content/${course_id}/${syllabus_id}/quiz/${topic_id}/view_learner_output/${learner_quiz_progress_id}`;
            
            learnerSyllabusProgressTableDisp += `
            <tr>
                <td class="mt-5 py-5">${learner_fname} ${learner_lname}</td>
                <td>${status}</td>
                <td>${attempt}</td>
                <td>${score}</td>
                <td>${remarks}</td>
                <td>${start_period}</td>
                <td>${finish_period}</td>
                <td>
                <a href="${absoluteUrl}" class="px-5 py-3 bg-darthmouthgreen hover:bg-green-950 text-white rounded-xl text-xl">view</a>
                </td>
            </tr>
            `
        }

        $('.learnerSyllabusProgressRowData').empty();
        $('.learnerSyllabusProgressRowData').append(learnerSyllabusProgressTableDisp);
    }

    var learnerQuizOutputData;
    var learnerQuizOutputOverallScoreData;
    var quizData;
    
    function getLearnerSyllabusQuizContentOutputData() {
        var url = baseUrl + "/quizData/contentOutputs";
    
        $.ajax({
            type: "GET",
            url: url,
            success: function (response) {
                // console.log(response);
    
                learnerQuizOutputData = response['learnerQuizOutputData'];
                learnerQuizOutputOverallScoreData = response['learnerQuizOutputOverallScoreData'];
                quizData = response['quizData'];
    
                createCharts();
            },
            error: function (error) {
                console.log(error);
            }
        });
    }



    // Function to create charts dynamically
    function createCharts() {
        const uniqueQuizContentIds = [...new Set(quizData.map(item => item.quiz_content_id))];
    
        uniqueQuizContentIds.forEach(quizContentId => {
            const chartData = getChartData(quizContentId);
            const correctChartData = getCorrectChartData(quizContentId);
            const containerId = `learnerSyllabusQuizContentOutputArea_${quizContentId}`;
            const correctContainerId = `learnerSyllabusQuizContentCorrectsArea_${quizContentId}`;
            const canvasId = `learnerSyllabusQuizContentOutputCanvas_${quizContentId}`;
            const correctCanvasId = `learnerSyllabusQuizContentCorrectsCanvas_${quizContentId}`;
            const question = getQuestion(quizContentId);

            // Create a flex container dynamically for each chart
            $('#learnerSyllabusQuizOutputArea').append(`
            <div class="my-10">
                <div class="">
                    <p class="text-lg font-semibold mb-3">${question}</p>
                </div>
                <div class="flex">
                    <div class="w-2/3 h-[400px] ml-5 border-2 border-darthmouthgreen learnerSyllabusQuizContentOutputArea" id="${containerId}">
                        <canvas class="" id="${canvasId}"></canvas>
                    </div>
                    <div class="w-1/3 h-[400px] ml-5 border-2 border-darthmouthgreen learnerSyllabusQuizContentCorrectsArea" id="${correctContainerId}">
                        <canvas class="" id="${correctCanvasId}"></canvas>
                    </div>
                </div>
            </div>
            `);

            
            // Create the chart
            const ctx = $(`#${canvasId}`)[0].getContext('2d');
            const newChart = new Chart(ctx, {
                type: getChartType(quizContentId),
                data: chartData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    // Add other chart options as needed
                },
            });

            // Create the correct/incorrect chart
            const correctCtx = $(`#${correctCanvasId}`)[0].getContext('2d');
            const correctChart = new Chart(correctCtx, {
                type: 'doughnut', // Assuming a bar chart for correct/incorrect counts
                data: correctChartData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    // Add other chart options as needed
                },
            });
        });
        
    }

    // Function to get correct chart data based on quiz content type

    function getCorrectChartData(quizContentId) {
        const correctCount = learnerQuizOutputData
            .filter(output => output.quiz_content_id === quizContentId && output.isCorrect === 1).length;

        const incorrectCount = learnerQuizOutputData
            .filter(output => output.quiz_content_id === quizContentId && output.isCorrect === 0).length;

        return {
            labels: ['Correct', 'Incorrect'],
            datasets: [{
                label: `Quiz Content ${quizContentId} Correct/Incorrect Answers`,
                data: [correctCount, incorrectCount],
                backgroundColor: ['#00693e', '#e74c3c'],
                borderColor: ['#00693e', '#e74c3c'],
                borderWidth: 1,
            }]
        };
    }
    

    
    // Function to get the question based on quiz content id
    function getQuestion(quizContentId) {
        const quizContent = quizData.find(item => item.quiz_content_id === quizContentId);
        return quizContent ? quizContent.question : '';
    }

    
    // Function to get the question based on quiz content id
    function getQuestion(quizContentId) {
        const quizContent = quizData.find(item => item.quiz_content_id === quizContentId);
        return quizContent ? quizContent.question : '';
    }

    // Function to get chart type based on quiz content type
    function getChartType(quizContentId) {
        const quizContent = quizData.find(item => item.quiz_content_id === quizContentId);
        
        if (quizContent.category === "MULTIPLECHOICE" || quizContent.category === "IDENTIFICATION") {
            // Return 'bar' for both MULTIPLECHOICE and IDENTIFICATION
            return 'bar';
        } else {
            // For other categories, return 'line' or handle accordingly
            return 'line';
        }
    }

    // Function to get chart data based on quiz content type
    function getChartData(quizContentId) {
        const quizContent = quizData.find(item => item.quiz_content_id === quizContentId);

        if (quizContent.category === "MULTIPLECHOICE") {
            const labels = quizData.filter(item => item.quiz_content_id === quizContentId).map(item => item.answer);
            const data = labels.map(answer => learnerQuizOutputData.filter(output => output.quiz_content_id === quizContentId && output.answer === answer).length);

            return {
                labels: labels,
                datasets: [{
                    label: `Quiz Content ${quizContentId} Answers`,
                    data: data,
                    backgroundColor: '#00693e',
                    borderColor: '#00693e',
                    borderWidth: 1,
                }]
            };

        } else if (quizContent.category === "IDENTIFICATION") {
            // New code for IDENTIFICATION category
            const learnerAnswers = learnerQuizOutputData
                .filter(output => output.quiz_content_id === quizContentId)
                .map(item => item.answer);
    
            const uniqueAnswers = [...new Set(learnerAnswers)];
            const data = uniqueAnswers.map(answer => learnerAnswers.filter(a => a === answer).length);
    
            return {
                labels: uniqueAnswers,
                datasets: [{
                    label: `Quiz Content ${quizContentId} Answers`,
                    data: data,
                    backgroundColor: '#00693e',
                    borderColor: '#00693e',
                    borderWidth: 1,
                }]
            };
        } else {
            const answers = learnerQuizOutputData.filter(output => output.quiz_content_id === quizContentId).map(item => item.answer);
            return {
                labels: answers,
                datasets: [{
                    label: `Quiz Content ${quizContentId} Answers`,
                    data: answers,
                    backgroundColor: '#00693e',
                    borderColor: '#00693e',
                    borderWidth: 1,
                }]
            };
        }
    }
})